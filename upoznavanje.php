<?php
session_start();
require_once 'bootstrap.php';
$pdo = require 'db.php';
require_once 'auth_helpers.php';

if (!isset($_SESSION['user_id']) && !try_remember_login($pdo)) {
    redirect('index.php');
}
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roo - Upoznavanje</title>
    <link rel="stylesheet" href="css/main.css" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="media/svg/LOGO.svg">
</head>
<body class="roo-intro-page">
    <div class="roo-intro-wrap">
        <div class="roo-intro-character">
            <img src="media/svg/roo-happy.svg" alt="Roo mascot" class="roo-intro-img">
        </div>

        <div class="roo-bubble-area">
            <div class="roo-bubble active" data-step="0">
                <p><strong>Bok! 👋</strong></p>
                <p>Ja sam Roo, tvoj vodič za avanture 🌍</p>
                <p>Tu sam da ti pomognem osmisliti savršena putovanja – baš po tvojoj mjeri.</p>
                <p>Bez obzira voliš li opuštanje na plaži, istraživanje gradova ili adrenalinske avanture, zajedno možemo stvoriti iskustva koja ćeš pamtiti.</p>
            </div>

            <div class="roo-bubble" data-step="1">
                <p><strong>Evo kako ti mogu pomoći:</strong></p>
                <p>✨ Predlagati avanture prema tvojim interesima</p>
                <p>🗺 Pomoći ti osmisliti vlastita putovanja</p>
                <p>💡 Dati ideje koje možda ne bi sam otkrio</p>
                <p>📌 Spremati tvoje omiljene destinacije i planove</p>
                <p>Što me bolje upoznaš, to ću bolje znati što voliš!</p>
            </div>

            <div class="roo-bubble" data-step="2">
                <p>Usput… volim skrivene lokacije, dobru hranu i avanture koje nisu u turističkim vodičima 😄</p>
                <p>Možda otkrijemo nešto zanimljivo zajedno!</p>
            </div>

            <div class="roo-bubble" data-step="3">
                <p><strong>Za početak, reci mi malo o sebi.</strong></p>
                <p>Za nekoliko trenutaka prebacit ću te dalje ✨</p>
            </div>
        </div>

        <div class="roo-intro-controls">
            <button type="button" class="submit-btn skip-btn" id="rooBackBtn" disabled>Natrag</button>
            <button type="button" class="submit-btn" id="rooNextBtn">Dalje</button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const bubbles = document.querySelectorAll('.roo-bubble');
            const backBtn = document.getElementById('rooBackBtn');
            const nextBtn = document.getElementById('rooNextBtn');

            let currentStep = 0;
            let autoRedirectTimer = null;

            function clearRedirectTimer() {
                if (autoRedirectTimer) {
                    clearTimeout(autoRedirectTimer);
                    autoRedirectTimer = null;
                }
            }

            function showStep(step) {
                if (step < 0 || step >= bubbles.length) return;

                bubbles.forEach((bubble, index) => {
                    bubble.classList.toggle('active', index === step);
                });

                currentStep = step;

                backBtn.disabled = step === 0;

                if (step === bubbles.length - 1) {
                    nextBtn.disabled = true;
                    nextBtn.classList.add('disabled');

                    clearRedirectTimer();
                    autoRedirectTimer = setTimeout(function () {
                        window.location.href = 'kviz.php';
                    }, 3000);
                } else {
                    nextBtn.disabled = false;
                    nextBtn.classList.remove('disabled');
                    clearRedirectTimer();
                }
            }

            nextBtn.addEventListener('click', function () {
                if (currentStep < bubbles.length - 1) {
                    showStep(currentStep + 1);
                }
            });

            backBtn.addEventListener('click', function () {
                if (currentStep > 0) {
                    showStep(currentStep - 1);
                }
            });

            showStep(0);
        });
    </script>
</body>
</html>