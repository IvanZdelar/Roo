<?php
session_start();
require_once 'db.php';
require_once 'auth_helpers.php';

if (!isset($_SESSION['user_id']) && !try_remember_login($pdo)) {
    redirect('index.php');
}

$userId = (int) $_SESSION['user_id'];
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $korisnik = trim($_POST['korisnik'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    $idealno = trim($_POST['idealno'] ?? '');
    $budget = trim($_POST['budget'] ?? '');
    $tko = trim($_POST['tko'] ?? '');
    $selectedInterestsJson = $_POST['selected_interests'] ?? '[]';

    $allowedIdealno = ['plaza', 'avantura', 'grad', 'kruz', 'safari', 'sve', ''];
    $allowedBudget = ['mali', 'srednji', 'veliki', ''];
    $allowedTko = ['sam', 'prijatelji', 'partner', 'obitelj', 'svi', ''];

    if (!in_array($idealno, $allowedIdealno, true)) {
        $idealno = '';
    }

    if (!in_array($budget, $allowedBudget, true)) {
        $budget = '';
    }

    if (!in_array($tko, $allowedTko, true)) {
        $tko = '';
    }

    if ($korisnik !== '') {
        if (mb_strlen($korisnik) < 3) {
            $error = 'Korisničko ime mora imati barem 3 znaka.';
        } else {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE korisnicko_ime = ? AND id != ?");
            $stmt->execute([$korisnik, $userId]);

            if ($stmt->fetch()) {
                $error = 'To korisničko ime je zauzeto.';
            }
        }
    }

    $profileImagePath = null;

    if ($error === '' && isset($_FILES['slika']) && $_FILES['slika']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['slika']['error'] === UPLOAD_ERR_OK) {
            $tmpName = $_FILES['slika']['tmp_name'];
            $fileSize = $_FILES['slika']['size'];

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $tmpName);
            finfo_close($finfo);

            $allowedMimeTypes = [
                'image/jpeg' => 'jpg',
                'image/png'  => 'png',
                'image/webp' => 'webp'
            ];

            if (!array_key_exists($mimeType, $allowedMimeTypes)) {
                $error = 'Profilna slika mora biti JPG, PNG ili WEBP.';
            } elseif ($fileSize > 5 * 1024 * 1024) {
                $error = 'Profilna slika ne smije biti veća od 5 MB.';
            } else {
                $extension = $allowedMimeTypes[$mimeType];
                $newFileName = 'user_' . $userId . '_' . time() . '.' . $extension;
                $uploadDir = __DIR__ . '/uploads/';

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $destination = $uploadDir . $newFileName;

                if (move_uploaded_file($tmpName, $destination)) {
                    $profileImagePath = 'uploads/' . $newFileName;
                } else {
                    $error = 'Greška prilikom spremanja slike.';
                }
            }
        } else {
            $error = 'Greška pri uploadu slike.';
        }
    }

    if ($error === '') {
        if ($profileImagePath !== null) {
            $stmt = $pdo->prepare("
                UPDATE users
                SET korisnicko_ime = ?, bio = ?, idealno_putovanje = ?, budget = ?, putuje_s_kim = ?, profilna_slika = ?
                WHERE id = ?
            ");
            $stmt->execute([
                $korisnik !== '' ? $korisnik : null,
                $bio !== '' ? $bio : null,
                $idealno !== '' ? $idealno : null,
                $budget !== '' ? $budget : null,
                $tko !== '' ? $tko : null,
                $profileImagePath,
                $userId
            ]);
        } else {
            $stmt = $pdo->prepare("
                UPDATE users
                SET korisnicko_ime = ?, bio = ?, idealno_putovanje = ?, budget = ?, putuje_s_kim = ?
                WHERE id = ?
            ");
            $stmt->execute([
                $korisnik !== '' ? $korisnik : null,
                $bio !== '' ? $bio : null,
                $idealno !== '' ? $idealno : null,
                $budget !== '' ? $budget : null,
                $tko !== '' ? $tko : null,
                $userId
            ]);
        }

        $interests = json_decode($selectedInterestsJson, true);
        if (!is_array($interests)) {
            $interests = [];
        }

        $cleanInterests = [];
        foreach ($interests as $interest) {
            $interest = trim((string)$interest);
            if ($interest !== '' && mb_strlen($interest) <= 100) {
                $cleanInterests[] = $interest;
            }
        }

        $cleanInterests = array_values(array_unique($cleanInterests));

        $pdo->prepare("DELETE FROM user_interests WHERE user_id = ?")->execute([$userId]);

        if (!empty($cleanInterests)) {
            $stmtInterest = $pdo->prepare("INSERT INTO user_interests (user_id, interest_name) VALUES (?, ?)");
            foreach ($cleanInterests as $interest) {
                $stmtInterest->execute([$userId, $interest]);
            }
        }

        $success = 'Podaci su uspješno spremljeni.';
        redirect('dashboard.php');
    }
}
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roo - Registracija - kostumizacija</title>
    <link rel="stylesheet" href="css/main.css" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="icon" type="image/x-icon" href="media/svg/LOGO.svg">
</head>
<body>
    <div class="page-transition" id="pageTransition">
        <img src="media/svg/roo-happy.svg" alt="Roo loading">
        <div class="page-transition-text">
            Roo te vodi dalje<span class="page-transition-dots" id="transitionDots">...</span>
        </div>
    </div>
    <div id="authContainer" class="container-fluid signin-content">
        <div class="row h-100">

            <div class="col-md-6 signin-bg-container warm-bg" id="warmBg">
                <svg width="80%" height="80%" xmlns="http://www.w3.org/2000/svg">
                    <image width="100%" height="100%" href="media/svg/roo-bg.svg" />
                    <image x="15%" y="20%" width="70%" height="70%" href="media/svg/roo-eyebrow.svg"/>
                </svg>
            </div>

            <div class="col-md-6 signin-bg-container cool-bg" id="coolBg">
                <svg width="70%" height="70%" xmlns="http://www.w3.org/2000/svg">
                    <image width="100%" height="100%" href="media/svg/roo-bg2.svg" />
                    <image x="10%" y="15%" width="82.5%" height="82.5%" href="media/svg/roo-eyebrow.svg"/>
                </svg>
            </div>

            <div id="signinForm" class="col-md-6 signin-form-container">
                <div class="form-content">
                    <h1>Roo želi znati više o tebi!</h1>
                    <h3>Kostimiziraj svoj profil.</h3>

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                    <?php endif; ?>

                    <form id="bioForm" novalidate action="kviz.php" method="POST" enctype="multipart/form-data">
                        <div class="profile-upload-wrap">
                            <label for="slika" class="profile-label">
                                <img src="media/svg/roo-wink.png" id="previewSlika" class="profile-preview" alt="Profilna slika">
                                <span class="upload-text">Dodaj sliku</span>
                            </label>
                            <input type="file" id="slika" name="slika" accept="image/*">
                        </div>

                        <div class="field-group">
                            <input type="text" id="korisnik" name="korisnik" placeholder=" " autocomplete="username">
                            <label for="korisnik">Korisničko ime</label>
                        </div>

                        <div class="field-group">
                            <textarea id="bio" name="bio" placeholder=" " rows="3"></textarea>
                            <label for="bio">Recite nešto o sebi</label>
                        </div>

                        <div class="field-group select-group">
                            <select name="idealno" id="idealno">
                                <option value="" disabled selected hidden></option>
                                <option value="plaza">Plaže i relaksacija</option>
                                <option value="avantura">Avanture na otvorenom</option>
                                <option value="grad">Istraživanje gradova</option>
                                <option value="kruz">Krstarenje</option>
                                <option value="safari">Safari i divljine</option>
                                <option value="sve">Bitno mi je samo putovati</option>
                            </select>
                            <label for="idealno">Kakav je vaš idealni put?</label>
                            <span class="select-arrow">▾</span>
                        </div>

                        <div class="field-group select-group">
                            <select name="budget" id="budget">
                                <option value="" disabled selected hidden></option>
                                <option value="mali">Mali budžet</option>
                                <option value="srednji">Srednji budžet</option>
                                <option value="veliki">Velik budžet</option>
                            </select>
                            <label for="budget">Koliki vam je budžet?</label>
                            <span class="select-arrow">▾</span>
                        </div>

                        <div class="field-group select-group">
                            <select name="tko" id="tko">
                                <option value="" disabled selected hidden></option>
                                <option value="sam">Solo putovanja</option>
                                <option value="prijatelji">Sa prijateljima</option>
                                <option value="partner">Sa svojim voljenim/om</option>
                                <option value="obitelj">Sa obitelji</option>
                                <option value="svi">Sa bilo kim</option>
                            </select>
                            <label for="tko">S kime planirate putovati?</label>
                            <span class="select-arrow">▾</span>
                        </div>

                        <div class="btn-row">
                            <button type="submit" class="submit-btn" id="daljeBtn">Dalje</button>
                            <button type="button" class="submit-btn skip-btn" id="preskociBtn">Preskoči</button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="loginForm" class="col-md-6 login-form-container">
                <div class="form-content">
                    <h1>Što te zanima?</h1>
                    <h3>Odaberi interese koji opisuju tebe kao putnika.</h3>

                    <form id="interestiForm" novalidate>
                        <div class="interests-grid" id="interestsGrid">
                            <button type="button" class="interest-chip" data-value="plaze">🏖️ Plaže</button>
                            <button type="button" class="interest-chip" data-value="planine">🏔️ Planine</button>
                            <button type="button" class="interest-chip" data-value="hrana">🍜 Gastronomija</button>
                            <button type="button" class="interest-chip" data-value="kultura">🏛️ Kultura i povijest</button>
                            <button type="button" class="interest-chip" data-value="muzika">🎵 Glazba i festivali</button>
                            <button type="button" class="interest-chip" data-value="sport">⚽ Sport</button>
                            <button type="button" class="interest-chip" data-value="hiking">🥾 Planinarenje</button>
                            <button type="button" class="interest-chip" data-value="fotografija">📷 Fotografija</button>
                            <button type="button" class="interest-chip" data-value="ronilastvo">🤿 Ronjenje</button>
                            <button type="button" class="interest-chip" data-value="shopping">🛍️ Shopping</button>
                            <button type="button" class="interest-chip" data-value="wellness">🧘 Wellness i spa</button>
                            <button type="button" class="interest-chip" data-value="priroda">🌿 Priroda i divljina</button>
                            <button type="button" class="interest-chip" data-value="arhitektura">🏗️ Arhitektura</button>
                            <button type="button" class="interest-chip" data-value="nocni">🌃 Noćni život</button>
                            <button type="button" class="interest-chip" data-value="vino">🍷 Vino i pivnice</button>
                            <button type="button" class="interest-chip" data-value="umjetnost">🎨 Umjetnost i galerije</button>
                            <button type="button" class="interest-chip" data-value="kampiranje">⛺ Kampiranje</button>
                            <button type="button" class="interest-chip" data-value="biciklizam">🚴 Biciklizam</button>
                            <button type="button" class="interest-chip" data-value="jedrenje">⛵ Jedrenje</button>
                            <button type="button" class="interest-chip" data-value="eko">♻️ Eko turizam</button>
                            <button type="button" class="interest-chip other-chip" data-value="ostalo">➕ Ostalo</button>
                        </div>

                        <div class="other-input-wrap" id="otherInputWrap">
                            <div class="field-group">
                                <input type="text" id="ostaloInput" name="ostalo" placeholder=" ">
                                <label for="ostaloInput">Upiši svoj interes</label>
                            </div>
                        </div>

                        <div class="btn-row">
                            <button type="submit" class="submit-btn">Završi</button>
                            <button type="button" class="submit-btn skip-btn" id="skipInterests">Preskoči</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script src="js/main.js"></script>
    <script>
    document.getElementById('slika').addEventListener('change', function (e) {
        const file = e.target.files[0];
        const preview = document.getElementById('previewSlika');

        if (file) {
            const reader = new FileReader();

            reader.onload = function (event) {
                preview.src = event.target.result;
            };

            reader.readAsDataURL(file);
        }
    });
    </script>
</body>
</html>