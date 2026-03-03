// Dark/Light theme toggle
(function () {
    var toggle = document.querySelector('.theme-toggle');
    var html = document.documentElement;

    // Load saved preference, default to light
    var saved = localStorage.getItem('theme');
    if (saved) {
        html.setAttribute('data-theme', saved);
    }

    if (toggle) {
        toggle.addEventListener('click', function () {
            var current = html.getAttribute('data-theme');
            var next = current === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', next);
            localStorage.setItem('theme', next);
        });
    }

    // Mobile navigation toggle
    var navToggle = document.querySelector('.nav-toggle');
    var navLinks = document.querySelector('.nav-links');

    if (navToggle && navLinks) {
        navToggle.addEventListener('click', function (e) {
            e.stopPropagation();
            navLinks.classList.toggle('open');
        });

        document.addEventListener('click', function (e) {
            if (!navLinks.contains(e.target) && !navToggle.contains(e.target)) {
                navLinks.classList.remove('open');
            }
        });
    }

    // Rotating hero headings
    var heroEl = document.querySelector('.hero-heading');
    if (heroEl && heroEl.dataset.headings) {
        var headings = JSON.parse(heroEl.dataset.headings);
        var index = 0;
        var switches = 0;

        var interval = setInterval(function () {
            heroEl.classList.add('slide-out');

            setTimeout(function () {
                index = (index + 1) % headings.length;
                heroEl.textContent = headings[index];
                heroEl.classList.remove('slide-out');
                heroEl.classList.add('slide-in');

                requestAnimationFrame(function () {
                    requestAnimationFrame(function () {
                        heroEl.classList.remove('slide-in');
                    });
                });
            }, 400);

            switches++;
            if (switches >= 2) {
                clearInterval(interval);
            }
        }, 5000);
    }
})();
