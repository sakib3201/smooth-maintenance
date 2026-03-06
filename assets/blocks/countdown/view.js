/**
 * Pure Vanilla JavaScript for Frontend Countdown
 * Performance note: Zero heavy dependencies, simple interval.
 */

function setWithTick(el, val) {
    if (el.textContent === val) return;
    el.textContent = val;
    el.classList.remove('sm-tick');
    void el.offsetWidth; // force reflow to restart animation
    el.classList.add('sm-tick');
}

document.addEventListener('DOMContentLoaded', () => {
    const countdownBlocks = document.querySelectorAll('.sm-countdown-wrapper');

    if (!countdownBlocks.length) {
        return;
    }

    countdownBlocks.forEach((block) => {
        const endDateStr = block.getAttribute('data-end-date');
        if (!endDateStr) return;

        const targetDate = new Date(endDateStr).getTime();
        const grid = block.querySelector('.sm-countdown-grid');
        const expired = block.querySelector('.sm-countdown-expired');

        const daysEl = block.querySelector('.sm-days');
        const hoursEl = block.querySelector('.sm-hours');
        const minsEl = block.querySelector('.sm-minutes');
        const secsEl = block.querySelector('.sm-seconds');

        if (!targetDate || !grid || !daysEl) return;

        const updateCountdown = () => {
            const now = new Date().getTime();
            const distance = targetDate - now;

            if (distance < 0) {
                clearInterval(interval);
                if (grid && expired) {
                    grid.style.display = 'none';
                    expired.style.display = 'block';
                }
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            setWithTick(daysEl, String(days).padStart(2, '0'));
            setWithTick(hoursEl, String(hours).padStart(2, '0'));
            setWithTick(minsEl, String(minutes).padStart(2, '0'));
            setWithTick(secsEl, String(seconds).padStart(2, '0'));
        };

        // Run immediately, then interval
        updateCountdown();
        const interval = setInterval(updateCountdown, 1000);
    });
});
