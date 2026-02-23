<nav class="navbar" id="navbar">
    <div class="container">
        <a href="#" class="logo">
            <div class="logo-icon">
                <i data-lucide="zap"></i>
            </div>
            <span>Adivo<span class="text-green">Q</span></span>
        </a>
        
        <div class="nav-links">
            <a href="#features">Features</a>
            <a href="#how-it-works">How It Works</a>
            <a href="#pricing">Pricing</a>
            <a href="#faq">FAQ</a>
        </div>
        
        <button class="btn btn-primary nav-cta" onclick="openWaitlistModal()">
            Join Waitlist <span class="badge">Free</span>
        </button>
        
        <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
            <i data-lucide="menu"></i>
        </button>
    </div>
</nav>

<!-- Mobile Menu -->
<div class="mobile-menu" id="mobileMenu">
    <button class="mobile-menu-close" onclick="toggleMobileMenu()">
        <i data-lucide="x"></i>
    </button>
    <div class="mobile-menu-links">
        <a href="#features" onclick="toggleMobileMenu()">Features</a>
        <a href="#how-it-works" onclick="toggleMobileMenu()">How It Works</a>
        <a href="#pricing" onclick="toggleMobileMenu()">Pricing</a>
        <a href="#faq" onclick="toggleMobileMenu()">FAQ</a>
        <button class="btn btn-primary" onclick="toggleMobileMenu(); openWaitlistModal();">
            Join Waitlist – It's Free
        </button>
    </div>
</div>