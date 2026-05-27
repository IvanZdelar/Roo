<?php
session_start();
require_once 'bootstrap.php';
$pdo = require 'db.php';
require_once 'auth_helpers.php';

if (!isset($_SESSION['user_id']) && !try_remember_login($pdo)) {
    header('Location: index.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// dohvati korisnika
$stmt = $pdo->prepare("SELECT korisnicko_ime, ime, prezime, profilna_slika FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// fallback vrijednosti
$korisnicko_ime = trim($user['korisnicko_ime'] ?? '');
$ime = $user['ime'] ?? '';
$prezime = $user['prezime'] ?? '';

// fallback logika
if (!empty($korisnicko_ime)) {
    $display_name = $korisnicko_ime;
} else {
    $display_name = trim($ime . ' ' . $prezime);
}

// ako ni to ne postoji
if (empty($display_name)) {
    $display_name = 'Korisnik';
}

$profilna_slika = $user['profilna_slika'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roo - Početna</title>
    <link rel="stylesheet" href="css/main.css" type="text/css">
    <link rel="stylesheet" href="css/hamburger.css" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="icon" type="image/x-icon" href="media/svg/LOGO.svg">
</head>
<body class="homepage-body">
<div class="page-transition" id="pageTransition">
    <img src="media/svg/roo-happy.svg" alt="Roo loading">
    <div class="page-transition-text">
        Roo te vodi dalje<span class="page-transition-dots" id="transitionDots">...</span>
    </div>
</div>
    <?php
    include 'nav.php';
    ?>

    <section class="putovanja-main">
            <h2 class="section-title-gold reveal-up">Aktivna putovanja</h2>

            <div class="travel-cards-grid-putovanja">
                <article class="travel-card reveal-up">
                    <img src="media/slike/more.jpg" class="travel-card-img" alt="">
                    <div class="travel-card-body">
                        <h3>ZAGREB >>> DUBROVNIK</h3>
                        <p>Ana ide u Dubrovnik!<br>Traži još 2 osobe.</p>
                        <div class="travel-card-tags">Istraživanje</div>
                        <div class="travel-card-footer">15.5.2026. - 20.5.2026.</div>
                    </div>
                </article>
                <article class="travel-card reveal-up">
                    <img src="media/slike/more.jpg" class="travel-card-img" alt="">
                    <div class="travel-card-body">
                        <h3>ZAGREB >>> DUBROVNIK</h3>
                        <p>Ana ide u Dubrovnik!<br>Traži još 2 osobe.</p>
                        <div class="travel-card-tags">Istraživanje</div>
                        <div class="travel-card-footer">15.5.2026. - 20.5.2026.</div>
                    </div>
                </article>
                <article class="travel-card reveal-up">
                    <img src="media/slike/more.jpg" class="travel-card-img" alt="">
                    <div class="travel-card-body">
                        <h3>ZAGREB >>> DUBROVNIK</h3>
                        <p>Ana ide u Dubrovnik!<br>Traži još 2 osobe.</p>
                        <div class="travel-card-tags">Istraživanje</div>
                        <div class="travel-card-footer">15.5.2026. - 20.5.2026.</div>
                    </div>
                </article>
                <article class="travel-card reveal-up">
                    <img src="media/slike/more.jpg" class="travel-card-img" alt="">
                    <div class="travel-card-body">
                        <h3>ZAGREB >>> DUBROVNIK</h3>
                        <p>Ana ide u Dubrovnik!<br>Traži još 2 osobe.</p>
                        <div class="travel-card-tags">Istraživanje</div>
                        <div class="travel-card-footer">15.5.2026. - 20.5.2026.</div>
                    </div>
                </article>
                <article class="travel-card reveal-up">
                    <img src="media/slike/more.jpg" class="travel-card-img" alt="">
                    <div class="travel-card-body">
                        <h3>ZAGREB >>> DUBROVNIK</h3>
                        <p>Ana ide u Dubrovnik!<br>Traži još 2 osobe.</p>
                        <div class="travel-card-tags">Istraživanje</div>
                        <div class="travel-card-footer">15.5.2026. - 20.5.2026.</div>
                    </div>
                </article>

                <article class="travel-card reveal-up">
                    <img src="media/slike/more.jpg" class="travel-card-img" alt="">
                    <div class="travel-card-body">
                        <h3>ZAGREB >>> DUBROVNIK</h3>
                        <p>Ana ide u Dubrovnik!<br>Traži još 2 osobe.</p>
                        <div class="travel-card-tags">Istraživanje</div>
                        <div class="travel-card-footer">15.5.2026. - 20.5.2026.</div>
                    </div>
                </article>
                </div>
            <a href="#" class="discover-more-link discover-more-link-putovanja">OTKRIJ VIŠE>>></a>
    </section>
    <section class="putovanja-second">
        <h2 class="section-title-white reveal-up">Pronadi inspiraciju</h2>
        <div class="travel-cards-grid-putovanja">
                <article class="travel-card reveal-up">
                    <img src="media/slike/more.jpg" class="travel-card-img" alt="">
                    <div class="travel-card-body">
                        <h3>ZAGREB >>> DUBROVNIK</h3>
                        <p>Ana ide u Dubrovnik!<br>Traži još 2 osobe.</p>
                        <div class="travel-card-tags">Istraživanje</div>
                        <div class="travel-card-footer">15.5.2026. - 20.5.2026.</div>
                    </div>
                </article>
                <article class="travel-card reveal-up">
                    <img src="media/slike/more.jpg" class="travel-card-img" alt="">
                    <div class="travel-card-body">
                        <h3>ZAGREB >>> DUBROVNIK</h3>
                        <p>Ana ide u Dubrovnik!<br>Traži još 2 osobe.</p>
                        <div class="travel-card-tags">Istraživanje</div>
                        <div class="travel-card-footer">15.5.2026. - 20.5.2026.</div>
                    </div>
                </article>
                <article class="travel-card reveal-up">
                    <img src="media/slike/more.jpg" class="travel-card-img" alt="">
                    <div class="travel-card-body">
                        <h3>ZAGREB >>> DUBROVNIK</h3>
                        <p>Ana ide u Dubrovnik!<br>Traži još 2 osobe.</p>
                        <div class="travel-card-tags">Istraživanje</div>
                        <div class="travel-card-footer">15.5.2026. - 20.5.2026.</div>
                    </div>
                </article>
                <article class="travel-card reveal-up">
                    <img src="media/slike/more.jpg" class="travel-card-img" alt="">
                    <div class="travel-card-body">
                        <h3>ZAGREB >>> DUBROVNIK</h3>
                        <p>Ana ide u Dubrovnik!<br>Traži još 2 osobe.</p>
                        <div class="travel-card-tags">Istraživanje</div>
                        <div class="travel-card-footer">15.5.2026. - 20.5.2026.</div>
                    </div>
                </article>
                <article class="travel-card reveal-up">
                    <img src="media/slike/more.jpg" class="travel-card-img" alt="">
                    <div class="travel-card-body">
                        <h3>ZAGREB >>> DUBROVNIK</h3>
                        <p>Ana ide u Dubrovnik!<br>Traži još 2 osobe.</p>
                        <div class="travel-card-tags">Istraživanje</div>
                        <div class="travel-card-footer">15.5.2026. - 20.5.2026.</div>
                    </div>
                </article>

                <article class="travel-card reveal-up">
                    <img src="media/slike/more.jpg" class="travel-card-img" alt="">
                    <div class="travel-card-body">
                        <h3>ZAGREB >>> DUBROVNIK</h3>
                        <p>Ana ide u Dubrovnik!<br>Traži još 2 osobe.</p>
                        <div class="travel-card-tags">Istraživanje</div>
                        <div class="travel-card-footer">15.5.2026. - 20.5.2026.</div>
                    </div>
                </article>
                </div>
                <a href="#" class="discover-more-link discover-more-link-putovanja discover-more-link-nar">OTKRIJ VIŠE>>></a>
    </section>
        </div>   
    <script src="js/main.js"></script>
    <script src="js/hamburger.js"></script>
</body>
</html>