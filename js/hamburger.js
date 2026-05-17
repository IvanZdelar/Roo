const hamburger = document.getElementById('rooHamburger');
const panel = document.getElementById('rooMenuPanel');

hamburger.addEventListener('click', function () {
    hamburger.classList.toggle('active');
    panel.classList.toggle('active');
});

// close when clicking outside
document.addEventListener('click', function (e) {
    if (!hamburger.contains(e.target) && !panel.contains(e.target)) {
        hamburger.classList.remove('active');
        panel.classList.remove('active');
    }
});
