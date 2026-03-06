/**
 * Vanilla JS logic for the Subscriber Form frontend.
 * Performance: Zero dependencies, ultra-fast.
 */

document.addEventListener('DOMContentLoaded', () => {
    const forms = document.querySelectorAll('.sm-subscriber-form');

    if (!forms.length) {
        return;
    }

    forms.forEach((form) => {
        const emailInput = form.querySelector('.sm-subscriber-email');
        const submitBtn = form.querySelector('.sm-subscriber-submit');
        const statusDiv = form.querySelector('.sm-form-status');
        const formWrapper = form.closest('.sm-subscriber-form-wrapper');
        const successMsg = formWrapper.getAttribute('data-success-message');

        form.addEventListener('submit', (e) => {
            e.preventDefault();

            if (!emailInput.value) return;

            // Add loading state
            submitBtn.disabled = true;
            submitBtn.classList.add('is-loading');
            statusDiv.style.display = 'none';

            // Fetch nonce if defined in global plugin assets
            // For simplicity we create an endpoint that allows public sub for email

            const data = {
                email: emailInput.value,
            };

            fetch('/wp-json/smooth-maintenance/v1/subscribe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data),
            })
                .then((response) => response.json())
                .then((result) => {
                    if (result.success) {
                        form.innerHTML = `<div class="sm-subscriber-success">${successMsg}</div>`;
                    } else {
                        statusDiv.innerText = result.message || 'Error occurred. Please try again.';
                        statusDiv.style.display = 'block';
                        statusDiv.classList.add('has-error');
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('is-loading');
                    }
                })
                .catch(() => {
                    statusDiv.innerText = 'Network error. Please try again.';
                    statusDiv.style.display = 'block';
                    statusDiv.classList.add('has-error');
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('is-loading');
                });
        });
    });
});
