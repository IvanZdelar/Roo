<?php
session_start();
require_once 'bootstrap.php';
$pdo = require 'db.php';
require_once 'auth_helpers.php';

$user_id = $_SESSION['user_id'];

if (!isset($_SESSION['user_id']) && !try_remember_login($pdo)) {
    header('Location: index.php');
    exit;
}

$userId = (int)$_SESSION['user_id'];
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH'])
        && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

    $tripTitle = trim($_POST['trip_title'] ?? '');

    $tripTypes = json_decode($_POST['trip_type'] ?? '[]', true);
    if (!is_array($tripTypes)) $tripTypes = [];

    $tripTypes = array_values(array_filter(array_map('trim', $tripTypes)));
    $tripType = implode(', ', $tripTypes);

    $travelWith = trim($_POST['travel_with'] ?? '');
    $buddySlots = (int)($_POST['buddy_slots'] ?? 0);

    $budgetPerDay = (int)($_POST['budget_range'] ?? 0);

    if ($budgetPerDay < 100) {
        $budgetType = 'Low budget';
    } elseif ($budgetPerDay < 200) {
        $budgetType = 'Srednji';
    } else {
        $budgetType = 'Luksuz';
    }

    $transportMode = trim($_POST['transport_mode'] ?? '');
    $accommodationType = trim($_POST['smjestaj_tip'] ?? '');
    $selectedStayOption = trim($_POST['selected_stay_option'] ?? '');
    $startDate = trim($_POST['start_date'] ?? '');
    $endDate = trim($_POST['end_date'] ?? '');

    $routeLocations = json_decode($_POST['route_locations'] ?? '[]', true);
    $activityTags = json_decode($_POST['activity_tags'] ?? '[]', true);
    $locationActivityChoices = json_decode($_POST['location_activity_choices'] ?? '{}', true);

    if (!is_array($routeLocations)) $routeLocations = [];
    if (!is_array($activityTags)) $activityTags = [];
    if (!is_array($locationActivityChoices)) $locationActivityChoices = [];

    $cleanLocations = [];

    foreach ($routeLocations as $loc) {
        $name = trim((string)($loc['name'] ?? ''));
        $days = (int)($loc['days'] ?? 0);

        if ($name !== '') {
            $cleanLocations[] = [
                'name' => $name,
                'days' => $days > 0 ? $days : 1
            ];
        }
    }

    $destination = count($cleanLocations)
        ? implode(' → ', array_column($cleanLocations, 'name'))
        : '';

    if ($tripTitle === '') {
        $error = 'Unesi naslov putovanja.';
    } elseif (count($cleanLocations) < 2) {
        $error = 'Unesi barem početnu i završnu lokaciju.';
    } elseif ($startDate === '' || $endDate === '') {
        $error = 'Odaberi početni i završni datum.';
    } elseif (empty($tripTypes)) {
        $error = 'Odaberi barem jedan tip putovanja.';
    } elseif ($travelWith === '') {
        $error = 'Odaberi s kime putuješ.';
    } elseif ($budgetPerDay < 20 || $budgetPerDay > 300) {
        $error = 'Odaberi budžet između 20€ i 300€.';
    } elseif ($transportMode === '') {
        $error = 'Odaberi način putovanja.';
    } elseif ($accommodationType === '') {
        $error = 'Odaberi tip smještaja.';
    }

    if ($error !== '') {
        if ($isAjax) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => false, 'error' => $error]);
            exit;
        }
    } else {
        try {
            $pdo->beginTransaction();

            $dailyPlan = '';

            foreach ($cleanLocations as $loc) {
                $dailyPlan .= $loc['name'] . ': ' . $loc['days'] . " dana\n";
            }

            $adventureImage = null;

            if (!empty($_FILES['adventure_image']['name'])) {
                $uploadDir = 'uploads/adventures/';

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $allowed = ['jpg', 'jpeg', 'png', 'webp'];
                $ext = strtolower(pathinfo($_FILES['adventure_image']['name'], PATHINFO_EXTENSION));

                if (in_array($ext, $allowed)) {
                    $newName = 'adventure_' . $userId . '_' . time() . '.' . $ext;
                    $targetPath = $uploadDir . $newName;

                    if (move_uploaded_file($_FILES['adventure_image']['tmp_name'], $targetPath)) {
                        $adventureImage = $targetPath;
                    }
                }
            }

            $stmt = $pdo->prepare("
                INSERT INTO adventures (
                    user_id, naziv, trip_type, travel_with, budget_type, budget_per_day,
                    destination, daily_plan, transport_mode, accommodation_type, host_languages, adventure_image
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $userId,
                $tripTitle,
                $tripType !== '' ? $tripType : null,
                $travelWith !== '' ? $travelWith : null,
                $budgetType,
                $budgetPerDay,
                $destination,
                $dailyPlan !== '' ? $dailyPlan : null,
                $transportMode,
                $accommodationType,
                null,
                $adventureImage
            ]);

            $adventureId = (int)$pdo->lastInsertId();

            $insertTag = $pdo->prepare("
                INSERT INTO adventure_tags (adventure_id, tag_type, tag_value)
                VALUES (?, ?, ?)
            ");

            if ($travelWith === 'Korisnik') {
                $insertTag->execute([$adventureId, 'buddy_slots', $buddySlots > 0 ? (string)$buddySlots : '1']);
                $insertTag->execute([$adventureId, 'travel_buddy_open', '1']);
            }

            foreach ($cleanLocations as $loc) {
                $insertTag->execute([$adventureId, 'location', $loc['name']]);
                $insertTag->execute([$adventureId, 'location_days', $loc['name'] . '|' . $loc['days']]);
            }

            foreach ($tripTypes as $type) {
                $insertTag->execute([$adventureId, 'trip_type', $type]);
            }

            foreach ($activityTags as $tag) {
                $tag = trim((string)$tag);

                if ($tag !== '') {
                    $insertTag->execute([$adventureId, 'activity', $tag]);
                }
            }

            foreach ($locationActivityChoices as $locationName => $choices) {
                if (!is_array($choices)) continue;

                $locationName = trim((string)$locationName);

                foreach ($choices as $choice) {
                    $choice = trim((string)$choice);

                    if ($locationName !== '' && $choice !== '') {
                        $insertTag->execute([
                            $adventureId,
                            'location_activity',
                            $locationName . '|' . $choice
                        ]);
                    }
                }
            }

            if ($selectedStayOption !== '') {
                $insertTag->execute([$adventureId, 'stay_option', $selectedStayOption]);
            }

            $insertTag->execute([$adventureId, 'start_date', $startDate]);
            $insertTag->execute([$adventureId, 'end_date', $endDate]);

            $pdo->commit();

            $success = 'Avantura je uspješno spremljena!';

            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => true]);
                exit;
            }
        } catch (Exception $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            $error = 'Došlo je do greške pri spremanju avanture.';

            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'error' => $error]);
                exit;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roo - Osmisli putovanje</title>
    <link rel="stylesheet" href="css/main.css" type="text/css">
    <link rel="stylesheet" href="css/hamburger.css" type="text/css">
    <link rel="stylesheet" href="css/adventure.css" type="text/css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="media/svg/LOGO.svg">
</head>
<body class="adventure-wizard-body">
    <div class="create-loader" id="adventureCreateLoader">
        <img src="media/svg/roo-create.svg">
        <p>Roo slaže tvoju avanturu<span id="loaderDots">...</span></p>
    </div>
    
    <div class="page-transition" id="pageTransition">
        <img src="media/svg/roo-happy.svg" alt="Roo loading">
        <div class="page-transition-text">
            Roo te vodi dalje<span class="page-transition-dots" id="transitionDots">...</span>
        </div>
    </div>

<?php include 'nav.php'; ?>
    <main class="adventure-wizard-wrap">
        <form action="create-adventure.php" method="POST" id="adventureWizardForm" enctype="multipart/form-data">
    <input type="hidden" name="route_locations" id="route_locations_input">
    <input type="hidden" name="trip_type" id="trip_type_input">
    <input type="hidden" name="travel_with" id="travel_with_input">
    <input type="hidden" name="transport_mode" id="transport_mode_input">
    <input type="hidden" name="activity_tags" id="activity_tags_input">
    <input type="hidden" name="selected_stay_option" id="selected_stay_option_input">
    <input type="hidden" name="location_activity_choices" id="location_activity_choices_input">
    <input type="hidden" name="buddy_slots" id="buddy_slots_input">
    <?php if ($error): ?>
        <div class="roo-toast roo-toast-error" id="rooToast">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="roo-toast roo-toast-success" id="rooToast">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <div class="wizard-progress-wrap">
        <div class="wizard-progress-top">
            <span id="wizardProgressText">Korak 1 / 6</span>
            <span id="wizardProgressLabel">Lokacije</span>
        </div>
        <div class="wizard-progress-bar">
            <div class="wizard-progress-fill" id="wizardProgressFill"></div>
        </div>
    </div>
    <section class="adventure-wizard-stage reveal-up" id="adventureWizardStage">
        <div class="adventure-wizard-bg"></div>

       <div class="adventure-wizard-roo">
            <div class="roo-wizard-bubble" id="rooWizardBubble">
                Prvo mi reci odakle krećemo i gdje završavamo avanturu.
            </div>

            <div class="roo-outfit-stage">
                <img src="media/svg/maskot.svg" alt="Roo" class="roo-base-img" id="rooMoodImg">

                <div class="roo-budget-item roo-budget-low" id="rooBudgetLow">
                    ŠEŠIR + KARTA
                </div>

                <div class="roo-budget-item roo-budget-mid" id="rooBudgetMid">
                    NAOČALE + FOTOAPARAT
                </div>

                <div class="roo-budget-item roo-budget-high" id="rooBudgetHigh">
                    MAŠNA + ŠAMPANJAC
                </div>
            </div>
        </div>

        <div class="adventure-wizard-panel">
            
            <div class="wizard-slide active" data-step="1">
                <div class="wizard-slide-scroll">

                    <p class="wizard-small-title">PRVO MI RECI,</p>
                    <h2>Kamo bježimo i koliko dugo ćemo tamo guštati?</h2>

                    <div class="wizard-route-layout">
                        <div class="wizard-route-main">
                            <div id="locationsContainer" class="wizard-location-list">

                                <div class="wizard-location-row fixed-start">
                                    <div class="field-group wizard-field-group">
                                        <input type="text" class="wizard-input-text location-input" data-role="start" placeholder=" ">
                                        <label>Polazna lokacija</label>
                                    </div>

                                    <div class="field-group wizard-field-group wizard-days-group">
                                        <input type="number" class="wizard-input-text location-days-input" min="1" placeholder=" ">
                                        <label>Dani</label>
                                    </div>
                                </div>

                                <div class="wizard-location-row fixed-end">
                                    <div class="field-group wizard-field-group">
                                        <input type="text" class="wizard-input-text location-input" data-role="end" placeholder=" ">
                                        <label>Završna lokacija</label>
                                    </div>

                                    <div class="field-group wizard-field-group wizard-days-group">
                                        <input type="number" class="wizard-input-text location-days-input" min="1" placeholder=" ">
                                        <label>Dani</label>
                                    </div>
                                </div>

                            </div>

                            <button type="button" class="wizard-add-location" id="addLocationBtn">＋ Dodaj lokaciju</button>
                        </div>

                        <div class="wizard-side-box">
                            <h3>Datumi putovanja</h3>

                            <div class="wizard-date-grid">

                            <div class="calendar-box">
                                <div id="tripCalendar"></div>
                            </div>

                            <input type="hidden" id="start_date" name="start_date">
                            <input type="hidden" id="end_date" name="end_date">

                        </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="wizard-slide" data-step="2">
                <div class="wizard-slide-scroll">
                    <p class="wizard-small-title">NAZIV I BUDŽET</p>
                    <h2>Kako zovemo ovu avanturu?</h2>

                    <div class="wizard-two-col">
                        <div class="field-group wizard-field-group full-width">
                            <input type="text" id="trip_title" name="trip_title" class="wizard-input-text" placeholder=" ">
                            <label for="trip_title">Naslov putovanja</label>
                        </div>

                        <div class="field-group wizard-field-group full-width">
                            <input type="file" id="adventure_image" name="adventure_image" class="wizard-input-text adventure-image-input" accept="image/*">
                            <label for="adventure_image">Slika avanture</label>
                        </div>

                        <div class="adventure-image-preview" id="adventureImagePreview">
                            <span>Pregled slike</span>
                        </div>

                        <div class="wizard-budget-box full-width">
                            <label for="budget_range">Koliko okvirno misliš potrošiti dnevno: <span id="budgetValue">120€</span></label>
                            <input type="range" id="budget_range" name="budget_range" min="20" max="300" step="10" value="120">
                        </div>
                    </div>
                </div>
            </div>

            <div class="wizard-slide" data-step="3">
                <p class="wizard-small-title">KAD SUNCE ZAĐE TREBA NAM DOBAR KREVET.</p>
                <h2>Voliš li više svoj mir ili želiš osjetiti kako žive pravi mještani?</h2>
                <div class="wizard-chip-grid stay-type-grid" id="stayTypeGrid">
                    <button type="button" class="adventure-chip stay-type-btn" data-value="Hotel"><img src="media/slike/hotel.png" alt=""> <p>Hotel/motel</p></button>
                    <button type="button" class="adventure-chip stay-type-btn" data-value="Motel"><img src="media/slike/hostel.png" alt=""> <p>Hostel</p></button>
                    <button type="button" class="adventure-chip stay-type-btn" data-value="Kod lokalca"><img src="media/slike/home.png" alt=""> <p>Kod drugih korisnika</p></button>
                </div>
                <input type="hidden" name="smjestaj_tip" id="smjestaj_tip_hidden">
            </div>

            <div class="wizard-slide" data-step="4">
                <div class="wizard-slide-scroll">
                    <p class="wizard-small-title">ODABERI PONUDU</p>
                    <h2 id="stayChoiceTitle">Što ti se najviše sviđa?</h2>

                    <div class="stay-filter-row" id="stayFilterRow"></div>
                    <div class="stay-price-slider-box">
                        <label for="stayPriceRange">
                            Maksimalna cijena po noći: <span id="stayPriceValue">150€</span>
                        </label>
                        <input type="range" id="stayPriceRange" min="30" max="350" step="10" value="150">
                    </div>
                    <div class="stay-options-grid" id="stayOptionsGrid"></div>
                </div>
            </div>

            <div class="wizard-slide" data-step="5">
                <div class="wizard-slide-scroll">
                    <p class="wizard-small-title">DRUŠTVO I PRIJEVOZ</p>
                    <h2>Tko ide s tobom i kako putujemo?</h2>

                    <h4 class="wizard-subheading">S kim planiraš putovati:</h4>
                    <div class="wizard-chip-grid single-choice" data-target="travel_with_input">
                        <button type="button" class="adventure-chip" data-value="Solo">Solo</button>
                        <button type="button" class="adventure-chip" data-value="Par">Par</button>
                        <button type="button" class="adventure-chip" data-value="Prijatelji">Prijatelji</button>
                        <button type="button" class="adventure-chip" data-value="Obitelj">Obitelj</button>
                        <button type="button" class="adventure-chip" data-value="Korisnik">Drugi korisnici</button>
                    </div>
                    <div class="buddy-slots-box" id="buddySlotsBox">
                        <div class="field-group wizard-field-group">
                            <input type="number" id="buddy_slots_visible" class="wizard-input-text" min="1" max="10" placeholder=" ">
                            <label for="buddy_slots_visible">Koliko korisnika se može pridružiti?</label>
                        </div>
                    </div>

                    <h4 class="wizard-subheading">Način putovanja</h4>
                    <div class="wizard-chip-grid single-choice" data-target="transport_mode_input">
                        <button type="button" class="adventure-chip" data-value="Osobni auto">🚗 Osobni auto</button>
                        <button type="button" class="adventure-chip" data-value="Avion">✈ Avion</button>
                        <button type="button" class="adventure-chip" data-value="Rent-a-car">🚐 Iznajmiti auto</button>
                        <button type="button" class="adventure-chip" data-value="Vlak">🚆 Vlak</button>
                        <button type="button" class="adventure-chip" data-value="Bus">🚌 Bus</button>
                    </div>
                </div>
            </div>

            <div class="wizard-slide" data-step="6">
                <div class="wizard-slide-scroll">
                    <p class="wizard-small-title">TIP I AKTIVNOSTI</p>
                    <h2>Kakav vibe želiš?</h2>

                    <h4 class="wizard-subheading">Tip putovanja</h4>
                    <div class="wizard-chip-grid multi-choice" data-target="trip_type_input">
                        <button type="button" class="adventure-chip" data-value="Opuštanje">🏖️ Opuštanje</button>
                        <button type="button" class="adventure-chip" data-value="Avantura">🗺️ Avantura</button>
                        <button type="button" class="adventure-chip" data-value="Istraživanje gradova">🧭 Istraživanje gradova</button>
                        <button type="button" class="adventure-chip" data-value="Gastro putovanje">🍝 Gastro putovanje</button>
                        <button type="button" class="adventure-chip" data-value="Kultura i povijest">🎭 Kultura i povijest</button>
                        <button type="button" class="adventure-chip" data-value="Noćni život">🎉 Noćni život</button>
                    </div>

                    <h4 class="wizard-subheading">Aktivnosti</h4>
                    <div class="wizard-chip-grid" id="activityChoiceGrid">
                        <span class="text-muted">Prvo odaberi tip putovanja.</span>
                    </div>

                    <div class="other-activity-wrap" id="otherActivityWrap">
                        <div class="field-group wizard-field-group">
                            <input type="text" id="otherActivityInput" class="wizard-input-text" placeholder=" ">
                            <label for="otherActivityInput">Upiši svoju aktivnost</label>
                        </div>
                    </div>
                </div>
            </div>

             <div class="wizard-slide" data-step="7">
                <div class="wizard-slide-scroll">
                    <p class="wizard-small-title">AKTIVNOSTI PO LOKACIJI</p>
                    <h2>Što želiš raditi na svakoj lokaciji?</h2>

                    <div class="location-activity-list" id="locationActivityList"></div>
                </div>
            </div>

            <div class="wizard-slide" data-step="8">
                <div class="wizard-slide-scroll">
                    <p class="wizard-small-title">SAŽETAK</p>
                    <h2>Tvoja avantura je spremna!</h2>

                    <div class="wizard-summary-box" id="summaryBox"></div>

                    <div class="wizard-final-actions">
                        <button type="submit" class="submit-btn">💾 Spremi avanturu</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="wizard-nav">
            <button type="button" id="wizardPrev" class="wizard-arrow wizard-prev"><img src="media/svg/prevBtn.svg" alt="Previous"></button>
            <button type="button" id="wizardNext" class="wizard-arrow wizard-next"><img src="media/svg/nextBtn.svg" alt="Next"></button>
        </div>
    </section>
</form>
    </main>
    <?php include 'chatbot.php'; ?>
    <script src="js/main.js"></script>
    <script src="js/hamburger.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="js/adventure.js"></script>
</body>
</html>