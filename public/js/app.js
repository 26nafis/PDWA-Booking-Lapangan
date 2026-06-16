// ============================================================
//  SportField Booking — app.js
// ============================================================

document.addEventListener('DOMContentLoaded', () => {

    // ---- Auto-dismiss flash message after 5 detik ----
    const alert = document.querySelector('[role="alert"]');
    if (alert) {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    }

    // ---- Drag & drop upload zone ----
    const dropZone = document.getElementById('drop-zone');
    if (dropZone) {
        ['dragenter', 'dragover'].forEach(evt => {
            dropZone.addEventListener(evt, e => {
                e.preventDefault();
                dropZone.classList.add('dragging');
            });
        });
        ['dragleave', 'drop'].forEach(evt => {
            dropZone.addEventListener(evt, e => {
                e.preventDefault();
                dropZone.classList.remove('dragging');
            });
        });
        dropZone.addEventListener('drop', e => {
            const file = e.dataTransfer.files[0];
            const input = dropZone.querySelector('input[type="file"]');
            if (input && file) {
                const dt = new DataTransfer();
                dt.items.add(file);
                input.files = dt.files;
                input.dispatchEvent(new Event('change'));
            }
        });
    }

    // ---- Confirm before delete forms ----
    document.querySelectorAll('[data-confirm]').forEach(el => {
        el.addEventListener('click', e => {
            if (!confirm(el.dataset.confirm)) e.preventDefault();
        });
    });

    // ---- Format angka rupiah pada input price ----
    const priceInputs = document.querySelectorAll('input[data-rupiah]');
    priceInputs.forEach(input => {
        input.addEventListener('input', () => {
            const raw = input.value.replace(/\D/g, '');
            input.value = raw;
        });
    });

    // ---- Mobile menu toggle (jika ada) ----
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }

});
