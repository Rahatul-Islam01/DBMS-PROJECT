
document.addEventListener('DOMContentLoaded', function() {
    const alertMessages = document.querySelectorAll('.alert-message, .error-message, .success-message');
    alertMessages.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });

   
    const deleteButtons = document.querySelectorAll('.btn-danger');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to delete this item?')) {
                e.preventDefault();
            }
        });
    });

    // File upload preview
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const fileName = this.files[0].name;
                const nextElement = this.nextElementSibling;
                if (nextElement && nextElement.classList.contains('file-label')) {
                    nextElement.innerHTML = `<span>📎 ${fileName}</span>`;
                }
            }
        });
    });

    // Form 
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = this.querySelectorAll('[required]');
            let valid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    valid = false;
                    field.style.borderColor = '#dc3545';
                    
                   
                    if (!field.nextElementSibling || !field.nextElementSibling.classList.contains('error-text')) {
                        const error = document.createElement('small');
                        error.className = 'error-text';
                        error.style.color = '#dc3545';
                        error.style.display = 'block';
                        error.style.marginTop = '5px';
                        error.textContent = 'This field is required';
                        field.parentNode.insertBefore(error, field.nextSibling);
                    }
                } else {
                    field.style.borderColor = '#28a745';
                    const error = field.nextElementSibling;
                    if (error && error.classList.contains('error-text')) {
                        error.remove();
                    }
                }
            });
            
            if (!valid) {
                e.preventDefault();
                alert('Please fill in all required fields.');
            }
        });
    });

    // Task status 
    const statusSelects = document.querySelectorAll('.status-select');
    statusSelects.forEach(select => {
        select.addEventListener('change', function() {
            const form = this.closest('form');
            if (form) {
                if (confirm('Update task status?')) {
                    form.submit();
                } else {
                   
                    this.form.reset();
                }
            }
        });
    });

   
    const mobileMenuToggle = document.createElement('button');
    mobileMenuToggle.className = 'mobile-menu-toggle';
    mobileMenuToggle.innerHTML = '☰';
    mobileMenuToggle.style.display = 'none';
    
    const nav = document.querySelector('.main-nav');
    if (nav && window.innerWidth <= 768) {
        nav.parentNode.insertBefore(mobileMenuToggle, nav);
        mobileMenuToggle.style.display = 'block';
        
        mobileMenuToggle.addEventListener('click', function() {
            nav.style.display = nav.style.display === 'none' ? 'flex' : 'none';
        });
    }

   
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 80,
                    behavior: 'smooth'
                });
            }
        });
    });

    //  footer
    const yearSpans = document.querySelectorAll('.current-year');
    yearSpans.forEach(span => {
        span.textContent = new Date().getFullYear();
    });
});

//  messages
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.textContent = message;
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        background: ${type === 'success' ? '#28a745' : '#dc3545'};
        color: white;
        border-radius: 5px;
        z-index: 1000;
        animation: slideIn 0.3s ease;
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
`;
document.head.appendChild(style);

// Export utility functions
window.AcademicPortal = {
    showToast,
    confirmAction: function(message) {
        return confirm(message);
    }
};