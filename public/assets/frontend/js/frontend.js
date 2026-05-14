document.addEventListener('DOMContentLoaded', function () {
    const toast = document.getElementById('frontToast');
    const selectedLocation = document.getElementById('selectedLocation');
    const pincodeForm = document.getElementById('pincodeForm');
    const pincodeInput = document.getElementById('pincodeInput');

    function showToast(message) {
        if (!toast) return;

        toast.textContent = message;
        toast.classList.add('show');

        window.clearTimeout(showToast.timer);
        showToast.timer = window.setTimeout(function () {
            toast.classList.remove('show');
        }, 1800);
    }

    document.querySelectorAll('[data-add-bag]').forEach(function (button) {
        button.addEventListener('click', function () {
            showToast('Adding to bag');
        });
    });

    const tryToggle = document.querySelector('[data-try-toggle]');
    const tryMessage = document.querySelector('[data-try-message]');

    function syncTryMessage() {
        if (!tryToggle || !tryMessage) return;

        if (tryToggle.checked) {
            tryMessage.textContent = 'Try Cloth selected: is order par return available nahi hoga.';
            tryMessage.classList.add('locked');
            return;
        }

        tryMessage.textContent = 'Normal buy selected: product return eligible rahega.';
        tryMessage.classList.remove('locked');
    }

    if (tryToggle) {
        tryToggle.addEventListener('change', syncTryMessage);
        syncTryMessage();
    }

    document.querySelectorAll('[data-gender-tab]').forEach(function (button) {
        button.addEventListener('click', function () {
            document.querySelectorAll('[data-gender-tab]').forEach(function (tab) {
                tab.classList.remove('active');
            });

            button.classList.add('active');
            showToast(button.textContent.trim() + ' styles selected');
        });
    });

    document.querySelectorAll('[data-location]').forEach(function (button) {
        button.addEventListener('click', function () {
            document.querySelectorAll('[data-location-label]').forEach(function (label) {
                label.textContent = button.dataset.location;
            });

            showToast('Delivery zone selected');
        });
    });

    if (pincodeForm) {
        pincodeForm.addEventListener('submit', function (event) {
            event.preventDefault();

            const value = pincodeInput ? pincodeInput.value.trim() : '';

            if (!value) {
                showToast('Enter your pincode');
                return;
            }

            document.querySelectorAll('[data-location-label]').forEach(function (label) {
                label.textContent = value;
            });

            showToast('Checking quick delivery availability');
        });
    }

    const slides = Array.from(document.querySelectorAll('.carousel-slide'));
    const dots = Array.from(document.querySelectorAll('[data-carousel-dot]'));
    const prev = document.querySelector('[data-carousel-prev]');
    const next = document.querySelector('[data-carousel-next]');
    let activeSlide = 0;

    function showSlide(index) {
        if (!slides.length) return;

        activeSlide = (index + slides.length) % slides.length;

        slides.forEach(function (slide, slideIndex) {
            slide.classList.toggle('active', slideIndex === activeSlide);
        });

        dots.forEach(function (dot, dotIndex) {
            dot.classList.toggle('active', dotIndex === activeSlide);
        });
    }

    if (prev) {
        prev.addEventListener('click', function () {
            showSlide(activeSlide - 1);
        });
    }

    if (next) {
        next.addEventListener('click', function () {
            showSlide(activeSlide + 1);
        });
    }

    dots.forEach(function (dot) {
        dot.addEventListener('click', function () {
            showSlide(parseInt(dot.dataset.carouselDot, 10));
        });
    });

    if (slides.length > 1) {
        window.setInterval(function () {
            showSlide(activeSlide + 1);
        }, 4500);
    }
});
