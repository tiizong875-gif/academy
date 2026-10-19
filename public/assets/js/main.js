/**
 * ក្តឹបកូដ Academy — Main JavaScript
 */

document.addEventListener('DOMContentLoaded', function () {
    // 1. Initialize Bootstrap Tooltips & Popovers
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // 2. Wishlist Toggle Button Handler
    const wishlistButtons = document.querySelectorAll('.btn-wishlist-toggle');
    wishlistButtons.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const courseId = this.getAttribute('data-course-id');
            const icon = this.querySelector('i');

            fetch(window.APP_URL + '/course/' + courseId + '/wishlist', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    if (data.added) {
                        icon.classList.remove('bi-heart');
                        icon.classList.add('bi-heart-fill', 'text-danger');
                    } else {
                        icon.classList.remove('bi-heart-fill', 'text-danger');
                        icon.classList.add('bi-heart');
                    }
                } else {
                    alert(data.message || 'សូមចូលគណនីជាមុនសិន!');
                }
            })
            .catch(err => {
                console.error(err);
            });
        });
    });

    // 3. Confirm Dialog on Dangerous Forms (delete, revoke)
    const confirmForms = document.querySelectorAll('.form-confirm-action');
    confirmForms.forEach(form => {
        form.addEventListener('submit', function (e) {
            const message = this.getAttribute('data-confirm-message') || 'តើអ្នកប្រាកដជាចង់បន្តសកម្មភាពនេះដែរឬទេ?';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });

    // 4. Admin Sidebar Toggle for Mobile
    const sidebarToggle = document.getElementById('sidebarToggleBtn');
    const adminSidebar = document.querySelector('.admin-sidebar');
    if (sidebarToggle && adminSidebar) {
        sidebarToggle.addEventListener('click', function () {
            adminSidebar.classList.toggle('show');
        });
    }
});
