document.addEventListener('DOMContentLoaded', function () {
    // ─── Common elements ──────────────────────────────────────────────────────

    const authContainer = document.getElementById('authContainer');

    // ─── Panel switching: index.php (signin/login) ───────────────────────────

    const showLoginBtn = document.getElementById('showLogin');
    const showSigninBtn = document.getElementById('showSignin');

    showLoginBtn && showLoginBtn.addEventListener('click', function (e) {
        e.preventDefault();
        authContainer && authContainer.classList.add('show-login');
    });

    showSigninBtn && showSigninBtn.addEventListener('click', function (e) {
        e.preventDefault();
        authContainer && authContainer.classList.remove('show-login');
    });

    // ─── Kviz flow: step 1 -> step 2 -> final submit ─────────────────────────

    const bioForm = document.getElementById('bioForm');
    const interestiForm = document.getElementById('interestiForm');
    const preskociBtn = document.getElementById('preskociBtn') || document.getElementById('preskoциBtn');
    const skipInterests = document.getElementById('skipInterests');
    const chips = document.querySelectorAll('.interest-chip');
    const otherInputWrap = document.getElementById('otherInputWrap');
    const otherChip = document.querySelector('.other-chip');

    function showInterests() {
        authContainer && authContainer.classList.add('show-interests');
    }

    // First step submit only opens second step
    if (bioForm && interestiForm) {
        bioForm.addEventListener('submit', function (e) {
            e.preventDefault();
            showInterests();
        });
    }

    // Skip first step
    preskociBtn && preskociBtn.addEventListener('click', function () {
        showInterests();
    });

    // Skip second step -> submit empty interests
    skipInterests && skipInterests.addEventListener('click', function () {
        submitAllData([]);
    });

    // Interest chips toggle
    chips.forEach(function (chip) {
        chip.addEventListener('click', function () {
            chip.classList.toggle('selected');

            if (chip === otherChip) {
                if (chip.classList.contains('selected')) {
                    otherInputWrap && otherInputWrap.classList.add('visible');
                } else {
                    otherInputWrap && otherInputWrap.classList.remove('visible');
                    const otherInput = document.getElementById('ostaloInput');
                    if (otherInput) otherInput.value = '';
                }
            }

            chip.style.transform = 'scale(0.92) translateY(-2px)';
            setTimeout(() => {
                chip.style.transform = chip.classList.contains('selected')
                    ? 'translateY(-2px)'
                    : '';
            }, 120);
        });
    });

    // Final step submit
    interestiForm && interestiForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const selected = [];
        document.querySelectorAll('.interest-chip.selected').forEach(function (chip) {
            if (chip === otherChip) {
                const customVal = document.getElementById('ostaloInput')?.value.trim() || '';
                if (customVal) selected.push(customVal);
            } else {
                selected.push(chip.dataset.value);
            }
        });

        submitAllData(selected);
    });

    function submitAllData(interests) {
        if (!bioForm) return;

        // remove previously generated hidden inputs
        bioForm.querySelectorAll('.generated-hidden').forEach(function (el) {
            el.remove();
        });

        appendHidden('selected_interests', JSON.stringify(interests));

        const ostaloInput = document.getElementById('ostaloInput');
        if (ostaloInput && ostaloInput.value.trim() !== '') {
            appendHidden('ostalo', ostaloInput.value.trim());
        }

        // bypass first-step submit handler and submit for real
        HTMLFormElement.prototype.submit.call(bioForm);
    }

    function appendHidden(name, value) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = value;
        input.classList.add('generated-hidden');
        bioForm.appendChild(input);
    }

    // ─── Ripple effect on buttons ─────────────────────────────────────────────

    document.querySelectorAll('.submit-btn').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            const rect = btn.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;

            const ripple = document.createElement('span');
            ripple.classList.add('ripple');
            ripple.style.cssText = `width:${size}px;height:${size}px;left:${x}px;top:${y}px;`;
            btn.appendChild(ripple);

            ripple.addEventListener('animationend', function () {
                ripple.remove();
            });
        });
    });

    // ─── Password strength meter ──────────────────────────────────────────────

    const lozinkaInput = document.getElementById('lozinka');
    const strengthBar = document.getElementById('strengthBar');

    function getStrength(pw) {
        let score = 0;
        if (!pw) return 0;
        if (pw.length >= 8) score++;
        if (pw.length >= 12) score++;
        if (/[A-Z]/.test(pw)) score++;
        if (/[0-9]/.test(pw)) score++;
        if (/[^A-Za-z0-9]/.test(pw)) score++;
        return score;
    }

    lozinkaInput && lozinkaInput.addEventListener('input', function () {
        const score = getStrength(lozinkaInput.value);
        const pct = (score / 5) * 100;
        let color;

        if (score <= 1) color = '#e74c3c';
        else if (score <= 2) color = '#e67e22';
        else if (score <= 3) color = '#f1c40f';
        else if (score <= 4) color = '#2ecc71';
        else color = '#27ae60';

        if (strengthBar) {
            strengthBar.style.width = pct + '%';
            strengthBar.style.backgroundColor = color;
        }
    });

    // ─── Validation helpers ───────────────────────────────────────────────────

    function markError(input, show) {
        if (!input) return;
        input.classList.toggle('error', show);
    }

    // ─── Register form validation ─────────────────────────────────────────────

    const registerForm = document.getElementById('registerForm');
    const potvrdaInput = document.getElementById('potvrda');
    const potvrdaError = document.getElementById('potvrdaError');

    potvrdaInput && potvrdaInput.addEventListener('input', function () {
        const mismatch = potvrdaInput.value && lozinkaInput && potvrdaInput.value !== lozinkaInput.value;
        markError(potvrdaInput, mismatch);
        potvrdaError && potvrdaError.classList.toggle('visible', mismatch);
    });

    registerForm && registerForm.addEventListener('submit', function (e) {
        let valid = true;

        ['ime', 'prezime', 'mail', 'lozinka', 'potvrda'].forEach(function (id) {
            const input = document.getElementById(id);
            if (!input) return;

            if (!input.value.trim()) {
                markError(input, true);
                valid = false;
            } else {
                markError(input, false);
            }
        });

        if (lozinkaInput && potvrdaInput && lozinkaInput.value !== potvrdaInput.value) {
            markError(potvrdaInput, true);
            potvrdaError && potvrdaError.classList.add('visible');
            valid = false;
        }

        if (!valid) {
            e.preventDefault();
        }
    });

    // ─── Login form validation ────────────────────────────────────────────────

    const loginFormEl = document.getElementById('loginFormEl');

    loginFormEl && loginFormEl.addEventListener('submit', function (e) {
        let valid = true;

        ['login-mail', 'login-lozinka'].forEach(function (id) {
            const input = document.getElementById(id);
            if (!input) return;

            if (!input.value.trim()) {
                markError(input, true);
                valid = false;
            } else {
                markError(input, false);
            }
        });

        if (!valid) {
            e.preventDefault();
        }
    });

    // ─── Select floating labels ───────────────────────────────────────────────

    document.querySelectorAll('.field-group select').forEach(function (select) {
        function checkValue() {
            select.classList.toggle('has-value', !!select.value);
        }

        select.addEventListener('change', checkValue);
        checkValue();
    });

    // ─── Clear errors on input ────────────────────────────────────────────────

    document.querySelectorAll('.field-group input, .field-group textarea').forEach(function (input) {
        input.addEventListener('input', function () {
            if (input.value.trim()) {
                input.classList.remove('error');
            }
        });
    });

    // ─── Reveal on scroll ─────────────────────────────────────────────────────

    const revealEls = document.querySelectorAll('.reveal-up, .reveal-right');

    if (revealEls.length) {
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.15
        });

        revealEls.forEach(el => revealObserver.observe(el));
    }

    // ─── Count up animation ───────────────────────────────────────────────────

    const counters = document.querySelectorAll('.count-up');

    if (counters.length) {
        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;

                const el = entry.target;
                const target = parseInt(el.dataset.target, 10) || 0;
                const duration = 2000;
                const startTime = performance.now();

                function updateCount(now) {
                    const progress = Math.min((now - startTime) / duration, 1);
                    const eased = 1 - Math.pow(1 - progress, 3);
                    el.textContent = Math.floor(eased * target);

                    if (progress < 1) {
                        requestAnimationFrame(updateCount);
                    } else {
                        el.textContent = target;
                    }
                }

                requestAnimationFrame(updateCount);
                counterObserver.unobserve(el);
            });
        }, {
            threshold: 0.6
        });

        counters.forEach(counter => counterObserver.observe(counter));
    }

    // ─── Hero button press effect ─────────────────────────────────────────────

    const heroBtn = document.getElementById('heroStartBtn');
    if (heroBtn) {
        heroBtn.addEventListener('click', function () {
            heroBtn.classList.add('pressed');
            setTimeout(() => heroBtn.classList.remove('pressed'), 180);
        });
    }

    // ─── Hero parallax ────────────────────────────────────────────────────────

    const hero = document.querySelector('.hero-home');
    const heroMascot = document.querySelector('.hero-mascot-placeholder');
    const heroContent = document.querySelector('.hero-content-home');

    if (hero && heroMascot && heroContent && window.innerWidth > 900) {
        hero.addEventListener('mousemove', function (e) {
            const rect = hero.getBoundingClientRect();
            const x = (e.clientX - rect.left) / rect.width - 0.5;
            const y = (e.clientY - rect.top) / rect.height - 0.5;

            heroMascot.style.transform = `translate(${x * 12}px, ${y * 12}px)`;
            heroContent.style.transform = `translate(${x * -8}px, ${y * -8}px)`;
        });

        hero.addEventListener('mouseleave', function () {
            heroMascot.style.transform = '';
            heroContent.style.transform = '';
        });
    }

    // ─── Page transition loader ───────────────────────────────────────────────

    const transition = document.getElementById('pageTransition');
    const links = document.querySelectorAll('.transition-link');
    const dots = document.getElementById('transitionDots');

    let dotsInterval = null;

    function startDots() {
        if (!dots) return;
        const states = ['.', '..', '...'];
        let index = 0;

        dotsInterval = setInterval(() => {
            dots.textContent = states[index];
            index = (index + 1) % states.length;
        }, 350);
    }

    function showTransitionAndGo(url) {
        if (!transition || !url) {
            window.location.href = url;
            return;
        }

        transition.classList.add('active');
        startDots();

        setTimeout(() => {
            window.location.href = url;
        }, 650);
    }

    links.forEach(link => {
        link.addEventListener('click', function (e) {
            const href = link.getAttribute('href');

            if (!href || href === '#' || href.startsWith('javascript:')) {
                return;
            }

            e.preventDefault();
            showTransitionAndGo(href);
        });
    });
});

const parallaxBg = document.getElementById('parallaxBg');

if (parallaxBg) {
    let lastScroll = 0;

    function parallaxLoop() {
        lastScroll += (window.scrollY - lastScroll) * 0.08;
        parallaxBg.style.transform = `translateY(${lastScroll * 0.35}px)`;
        requestAnimationFrame(parallaxLoop);
    }

    parallaxLoop();
}