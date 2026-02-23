// ===== Global Variables =====
let formData = {
    name: '',
    email: '',
    phone: '',
    creator_type: '',
    followers: '',
    monthly_invoices: ''
};

// ===== Navbar Scroll Effect =====
window.addEventListener('scroll', function() {
    const navbar = document.getElementById('navbar');
    if (navbar && window.scrollY > 50) {
        navbar.classList.add('scrolled');
    } else if (navbar) {
        navbar.classList.remove('scrolled');
    }
});

// ===== Mobile Menu =====
function toggleMobileMenu() {
    const mobileMenu = document.getElementById('mobileMenu');
    if (mobileMenu) {
        mobileMenu.classList.toggle('active');
        document.body.style.overflow = mobileMenu.classList.contains('active') ? 'hidden' : '';
    }
}

// ===== FAQ Accordion =====
document.querySelectorAll('.faq-question').forEach(function(question) {
    question.addEventListener('click', function() {
        const item = this.parentElement;
        const isActive = item.classList.contains('active');
        
        // Close all items
        document.querySelectorAll('.faq-item').forEach(function(faq) {
            faq.classList.remove('active');
        });
        
        // Open clicked item if it wasn't active
        if (!isActive) {
            item.classList.add('active');
        }
        
        // Reinitialize icons
        lucide.createIcons();
    });
});

// ===== Waitlist Modal =====
function openWaitlistModal() {
    const modal = document.getElementById('waitlistModal');
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

function closeWaitlistModal() {
    const modal = document.getElementById('waitlistModal');
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
        
        // Reset form after close
        setTimeout(() => {
            resetWaitlistForm();
        }, 300);
    }
}

function resetWaitlistForm() {
    const form = document.getElementById('waitlistForm');
    if (form) {
        form.reset();
    }
    
    // Reset step visibility
    document.querySelectorAll('.modal-step').forEach(step => {
        step.classList.add('hidden');
    });
    
    const step1 = document.getElementById('step1');
    if (step1) {
        step1.classList.remove('hidden');
    }
    
    // Reset form data
    formData = {
        name: '',
        email: '',
        phone: '',
        creator_type: '',
        followers: '',
        monthly_invoices: ''
    };
}

function goToStep2() {
    const name = document.querySelector('input[name="name"]');
    const email = document.querySelector('input[name="email"]');
    const phone = document.querySelector('input[name="phone"]');
    
    if (name && email && phone) {
        if (!name.value.trim()) {
            showToast('Please enter your name', 'error');
            return;
        }
        
        if (!email.value.trim() || !isValidEmail(email.value)) {
            showToast('Please enter a valid email', 'error');
            return;
        }
        
        if (!phone.value.trim() || phone.value.length < 10) {
            showToast('Please enter a valid phone number', 'error');
            return;
        }
        
        // Store data
        formData.name = name.value;
        formData.email = email.value;
        formData.phone = phone.value;
        
        // Switch to step 2
        document.getElementById('step1').classList.add('hidden');
        document.getElementById('step2').classList.remove('hidden');
    }
}

function goToStep1() {
    document.getElementById('step2').classList.add('hidden');
    document.getElementById('step1').classList.remove('hidden');
}

function selectOption(btn, field) {
    // Remove selection from siblings
    const parent = btn.parentElement;
    parent.querySelectorAll('.option-btn').forEach(b => {
        b.classList.remove('selected');
    });
    
    // Add selection
    btn.classList.add('selected');
    formData[field] = btn.dataset.value;
}

function selectPill(btn, field) {
    // Remove selection from siblings
    const parent = btn.parentElement;
    parent.querySelectorAll('.pill-btn').forEach(b => {
        b.classList.remove('selected');
    });
    
    // Add selection
    btn.classList.add('selected');
    
    if (field === 'followers') {
        formData.followers = btn.textContent;
    } else if (field === 'invoices') {
        formData.monthly_invoices = btn.textContent;
    }
}

function submitWaitlist() {
    const submitBtn = document.getElementById('submitBtn');
    if (!submitBtn) return;
    
    const btnText = submitBtn.querySelector('.btn-text');
    const btnLoader = submitBtn.querySelector('.btn-loader');
    
    // Show loading
    if (btnText) btnText.classList.add('hidden');
    if (btnLoader) btnLoader.classList.remove('hidden');
    submitBtn.disabled = true;
    
    // Prepare data
    const data = {
        name: formData.name,
        email: formData.email,
        phone: formData.phone,
        creator_type: formData.creator_type || 'Not specified',
        followers: formData.followers || 'Not specified',
        monthly_invoices: formData.monthly_invoices || 'Not specified',
        source: 'waitlist_modal'
    };
    
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    // Submit to API
    fetch('/waitlist', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        // Hide loading
        if (btnText) btnText.classList.remove('hidden');
        if (btnLoader) btnLoader.classList.add('hidden');
        submitBtn.disabled = false;
        
        if (result.success) {
            // Show success
            document.getElementById('step2').classList.add('hidden');
            document.getElementById('successStep').classList.remove('hidden');
            
            const positionEl = document.getElementById('waitlistPosition');
            if (positionEl) {
                positionEl.textContent = '#' + result.data.position;
            }
            
            showToast('Successfully joined the waitlist!', 'success');
        } else {
            showToast(result.message || 'Something went wrong', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        
        // Hide loading
        if (btnText) btnText.classList.remove('hidden');
        if (btnLoader) btnLoader.classList.add('hidden');
        submitBtn.disabled = false;
        
        showToast('Network error. Please try again.', 'error');
    });
}

// ===== Toast Notifications =====
function showToast(message, type = 'info') {
    const container = document.getElementById('toastContainer') || createToastContainer();
    
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerHTML = `
        <div class="toast-icon">
            <i data-lucide="${type === 'success' ? 'check' : 'x'}"></i>
        </div>
        <span class="toast-message">${message}</span>
        <button class="toast-close" onclick="this.parentElement.remove()">
            <i data-lucide="x"></i>
        </button>
    `;
    
    container.appendChild(toast);
    
    // Initialize Lucide icons
    if (window.lucide) {
        window.lucide.createIcons();
    }
    
    // Auto remove after 4 seconds
    setTimeout(() => {
        if (toast.parentElement) {
            toast.remove();
        }
    }, 4000);
}

function createToastContainer() {
    const container = document.createElement('div');
    container.id = 'toastContainer';
    container.className = 'toast-container';
    document.body.appendChild(container);
    return container;
}

// ===== WhatsApp Popup =====
function initWhatsApp() {
    // Show WhatsApp popup after 5 seconds
    setTimeout(() => {
        const popup = document.getElementById('whatsappPopup');
        if (popup) {
            popup.style.display = 'block';
        }
    }, 5000);
}

function closeWhatsAppPopup() {
    const popup = document.getElementById('whatsappPopup');
    if (popup) {
        popup.style.display = 'none';
    }
}

function toggleWhatsAppChat() {
    const chat = document.getElementById('whatsappChat');
    if (chat) {
        chat.classList.toggle('hidden');
    }
}

function sendWhatsApp(message) {
    const phoneNumber = '919876543210'; // Replace with your actual number
    const encodedMessage = encodeURIComponent(message);
    window.open(`https://wa.me/${phoneNumber}?text=${encodedMessage}`, '_blank');
}

// ===== Utility Functions =====
function isValidEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

// ===== Initialize =====
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Lucide icons
    if (window.lucide) {
        window.lucide.createIcons();
    }
    
    // Initialize WhatsApp
    initWhatsApp();
    
    // Initialize AOS (if present)
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });
    }
});