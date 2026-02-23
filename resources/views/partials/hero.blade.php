    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-grid">
                <div class="hero-content" data-aos="fade-right">
                    <!-- Badge -->
                    <div class="hero-badge">
                        <span class="pulse-dot"></span>
                        <span>Now accepting early access signups</span>
                    </div>
                    
                    <!-- Headline -->
                    <h1 class="hero-title">
                        Run Your Creator Business Like a <span class="gradient-text">Company</span>
                    </h1>
                    
                    <!-- Subheadline -->
                    <p class="hero-subtitle">
                        The complete financial OS for creators, influencers & creative entrepreneurs. 
                        From brand deal to bank — simplified.
                    </p>
                    
                    <!-- Feature Pills -->
                    <div class="feature-pills">
                        <span class="pill"><i data-lucide="check-circle-2"></i> Brand deal tracking</span>
                        <span class="pill"><i data-lucide="check-circle-2"></i> Smart invoicing</span>
                        <span class="pill"><i data-lucide="check-circle-2"></i> Tax-ready reports</span>
                        <span class="pill"><i data-lucide="check-circle-2"></i> Payment automation</span>
                    </div>
                    
                    <!-- CTA Buttons -->
                    <div class="hero-cta">
                        <button class="btn btn-primary btn-lg" onclick="openWaitlistModal()">
                            Join Waitlist Free <i data-lucide="arrow-right"></i>
                        </button>
                        <a href="#how-it-works" class="btn btn-secondary btn-lg">
                            <i data-lucide="play"></i> See How It Works
                        </a>
                    </div>
                    
                    <!-- Social Proof -->
                    <div class="social-proof">
                        <div class="avatar-stack">
                            <div class="avatar">A</div>
                            <div class="avatar">B</div>
                            <div class="avatar">C</div>
                            <div class="avatar">D</div>
                            <div class="avatar">E</div>
                        </div>
                        <div class="proof-text">
                            <div class="stars">
                                <i data-lucide="star" class="star-filled"></i>
                                <i data-lucide="star" class="star-filled"></i>
                                <i data-lucide="star" class="star-filled"></i>
                                <i data-lucide="star" class="star-filled"></i>
                                <i data-lucide="star" class="star-filled"></i>
                            </div>
<p>
        <strong>{{ max(500, $waitlistCount ?? 0) }}+</strong>
        creators already signed up
    </p>
                        </div>
                    </div>
                </div>
                
                <!-- Hero Dashboard Preview -->
                <div class="hero-visual" data-aos="fade-left" data-aos-delay="200">
                    <div class="dashboard-preview">
                        <!-- Dashboard Header -->
                        <div class="dashboard-header">
                            <div>
                                <h3>Revenue Dashboard</h3>
                                <p>March 2024</p>
                            </div>
                            <div class="trend-badge">
                                <i data-lucide="trending-up"></i>
                                <span>+23.5%</span>
                            </div>
                        </div>
                        
                        <!-- Revenue Card -->
                        <div class="revenue-card">
                            <p class="label">Total Revenue</p>
                            <p class="amount">₹4,85,230</p>
                            <div class="revenue-breakdown">
                                <span><span class="dot green"></span> Received: ₹3,20,000</span>
                                <span><span class="dot yellow"></span> Pending: ₹1,65,230</span>
                            </div>
                        </div>
                        
                        <!-- Recent Payments -->
                        <div class="recent-payments">
                            <p class="section-label">Recent Payments</p>
                            <div class="payment-item">
                                <div class="payment-icon"><i data-lucide="credit-card"></i></div>
                                <div class="payment-info">
                                    <p class="brand">Nike India</p>
                                    <p class="amount">₹75,000</p>
                                </div>
                                <span class="status paid">Paid</span>
                            </div>
                            <div class="payment-item">
                                <div class="payment-icon"><i data-lucide="credit-card"></i></div>
                                <div class="payment-info">
                                    <p class="brand">Myntra</p>
                                    <p class="amount">₹45,000</p>
                                </div>
                                <span class="status pending">Pending</span>
                            </div>
                            <div class="payment-item">
                                <div class="payment-icon"><i data-lucide="credit-card"></i></div>
                                <div class="payment-info">
                                    <p class="brand">Boat Audio</p>
                                    <p class="amount">₹25,000</p>
                                </div>
                                <span class="status paid">Paid</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Floating Cards -->
                    <div class="floating-card invoices">
                        <div class="floating-icon purple">
                            <i data-lucide="file-text"></i>
                        </div>
                        <div>
                            <p class="number">127</p>
                            <p class="label">Invoices Sent</p>
                        </div>
                    </div>
                    
                    <div class="floating-card gst">
                        <span class="flag">🇮🇳</span>
                        <div>
                            <p class="title">GST Ready</p>
                            <p class="subtitle">Auto-calculated</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Stats -->
            <div class="stats-grid" data-aos="fade-up">
                <div class="stat-item">
                    <p class="stat-number">₹50Cr+</p>
                    <p class="stat-label">Creator Revenue Tracked</p>
                </div>
                <div class="stat-item">
                    <p class="stat-number">10,000+</p>
                    <p class="stat-label">Invoices Generated</p>
                </div>
                <div class="stat-item">
                    <p class="stat-number">{{ max(500, $waitlistCount ?? 0) }}+</p>
                    <p class="stat-label">Early Access Signups</p>
                </div>
            </div>
        </div>
    </section>
