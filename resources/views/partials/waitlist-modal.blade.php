    <!-- Waitlist Modal -->
    <div class="modal-overlay" id="waitlistModal">
        <div class="modal">
            <button class="modal-close" onclick="closeWaitlistModal()">
                <i data-lucide="x"></i>
            </button>
            
            <!-- Step 1 -->
            <div class="modal-step" id="step1">
                <div class="modal-header">
                    <div class="modal-badge">
                        <i data-lucide="sparkles"></i>
                        <span>Free Early Access</span>
                    </div>
                    <h2>Join the Waitlist</h2>
                    <p>Be among the first to experience AdivoQ</p>
                    <div class="progress-bar">
                        <div class="progress" style="width: 50%"></div>
                    </div>
                </div>
                
                <form id="waitlistForm" class="modal-form">
                    <div class="form-group">
                        <label>Full Name *</label>
                        <input type="text" name="name" placeholder="Enter your name" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Email Address *</label>
                        <input type="email" name="email" placeholder="you@example.com" required>
                    </div>
                    
                    <div class="form-group">
                        <label>WhatsApp Number *</label>
                        <div class="input-group">
                            <span class="input-prefix">+91</span>
                            <input type="tel" name="phone" placeholder="9876543210" required>
                        </div>
                    </div>
                    
                    <button type="button" class="btn btn-primary btn-block" onclick="goToStep2()">
                        Continue <i data-lucide="arrow-right"></i>
                    </button>
                </form>
            </div>
            
            <!-- Step 2 -->
            <div class="modal-step hidden" id="step2">
                <div class="modal-header">
                    <div class="modal-badge">
                        <i data-lucide="sparkles"></i>
                        <span>Free Early Access</span>
                    </div>
                    <h2>Tell Us More</h2>
                    <p>Help us personalize your experience</p>
                    <div class="progress-bar">
                        <div class="progress" style="width: 100%"></div>
                    </div>
                </div>
                
                <div class="modal-form">
                    <div class="form-group">
                        <label>What best describes you?</label>
                        <div class="option-grid">
                            <button type="button" class="option-btn" data-value="influencer" onclick="selectOption(this, 'creatorType')">
                                <span class="option-emoji">📸</span>
                                <span class="option-label">Influencer</span>
                                <span class="option-desc">Instagram, YouTube, etc.</span>
                            </button>
                            <button type="button" class="option-btn" data-value="freelancer" onclick="selectOption(this, 'creatorType')">
                                <span class="option-emoji">💼</span>
                                <span class="option-label">Freelancer</span>
                                <span class="option-desc">Designer, Editor, etc.</span>
                            </button>
                            <button type="button" class="option-btn" data-value="agency" onclick="selectOption(this, 'creatorType')">
                                <span class="option-emoji">🏢</span>
                                <span class="option-label">Agency</span>
                                <span class="option-desc">Managing creators</span>
                            </button>
                            <button type="button" class="option-btn" data-value="other" onclick="selectOption(this, 'creatorType')">
                                <span class="option-emoji">✨</span>
                                <span class="option-label">Other</span>
                                <span class="option-desc">Something else</span>
                            </button>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Follower Count</label>
                        <div class="pill-group">
                            <button type="button" class="pill-btn" onclick="selectPill(this, 'followers')">1K - 10K</button>
                            <button type="button" class="pill-btn" onclick="selectPill(this, 'followers')">10K - 50K</button>
                            <button type="button" class="pill-btn" onclick="selectPill(this, 'followers')">50K - 100K</button>
                            <button type="button" class="pill-btn" onclick="selectPill(this, 'followers')">100K - 500K</button>
                            <button type="button" class="pill-btn" onclick="selectPill(this, 'followers')">500K+</button>
                            <button type="button" class="pill-btn" onclick="selectPill(this, 'followers')">N/A</button>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Monthly Invoices</label>
                        <div class="pill-group">
                            <button type="button" class="pill-btn" onclick="selectPill(this, 'invoices')">1 - 5</button>
                            <button type="button" class="pill-btn" onclick="selectPill(this, 'invoices')">5 - 15</button>
                            <button type="button" class="pill-btn" onclick="selectPill(this, 'invoices')">15 - 30</button>
                            <button type="button" class="pill-btn" onclick="selectPill(this, 'invoices')">30+</button>
                        </div>
                    </div>
                    
                    <div class="form-buttons">
                        <button type="button" class="btn btn-outline" onclick="goToStep1()">Back</button>
                        <button type="button" class="btn btn-primary" onclick="submitWaitlist()" id="submitBtn">
                            <span class="btn-text">Join Waitlist</span>
                            <span class="btn-loader hidden">
                                <i data-lucide="loader-2" class="spin"></i>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Success -->
            <div class="modal-step hidden" id="successStep">
                <div class="success-content">
                    <div class="success-icon">
                        <i data-lucide="check-circle-2"></i>
                    </div>
                    <h2>You're In! 🎉</h2>
                    <p>Welcome to the AdivoQ family!</p>
                    
                    <div class="position-box">
                        <p class="label">Your waitlist position</p>
                        <p class="position" id="waitlistPosition">#1</p>
                    </div>
                    
                    <p class="success-note">We'll notify you via email & WhatsApp when we launch. Share with friends to move up the list!</p>
                    
                    <div class="share-buttons">
                        <button class="btn btn-outline" onclick="shareTwitter()">Share on Twitter</button>
                        <button class="btn btn-whatsapp" onclick="shareWhatsApp()">Share on WhatsApp</button>
                    </div>
                </div>
            </div>
        </div>
    </div>