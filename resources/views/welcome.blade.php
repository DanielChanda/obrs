<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OBRS - Online Bus Reservation System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2c5aa0;
            --secondary: #f8f9fa;
            --accent: #ff6b35;
            --dark: #1a1a2e;
            --light: #ffffff;
            --gradient: linear-gradient(135deg, #2c5aa0 0%, #1e3a8a 100%);
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: #333;
        }

        /* Navbar */
        .navbar {
            background: var(--gradient) !important;
            padding: 1rem 0;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.8rem;
            color: white !important;
        }

        .navbar-brand i {
            color: var(--accent);
        }

        .nav-link {
            color: rgba(255,255,255,0.9) !important;
            font-weight: 500;
            margin: 0 0.5rem;
            transition: all 0.3s ease;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem !important;
        }

        .nav-link:hover {
            color: white !important;
            background: rgba(255,255,255,0.1);
            transform: translateY(-1px);
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), 
                        url('https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1469&q=80') center/cover no-repeat;
            min-height: 100vh;
            display: flex;
            align-items: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            background: linear-gradient(45deg, #fff, #ffd700);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-subtitle {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .btn-hero {
            background: var(--accent);
            border: none;
            padding: 1rem 2.5rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(255, 107, 53, 0.4);
        }

        .btn-hero:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(255, 107, 53, 0.6);
            background: #ff5722;
        }

        /* Stats Section */
        .stats {
            background: var(--gradient);
            color: white;
            padding: 4rem 0;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 1rem;
            opacity: 0.9;
        }

        /* Features Section */
        .features {
            padding: 6rem 0;
            background: var(--secondary);
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 1rem;
            color: var(--dark);
        }

        .section-subtitle {
            text-align: center;
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 4rem;
        }

        .feature-card {
            background: white;
            border: none;
            border-radius: 1rem;
            padding: 2.5rem 2rem;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: var(--gradient);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: white;
        }

        .feature-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--dark);
        }

        .feature-text {
            color: #666;
            line-height: 1.6;
        }

        /* How It Works */
        .how-it-works {
            padding: 6rem 0;
            background: white;
        }

        .step-card {
            text-align: center;
            padding: 2rem;
            position: relative;
        }

        .step-number {
            width: 50px;
            height: 50px;
            background: var(--accent);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            margin: 0 auto 1.5rem;
            font-size: 1.2rem;
        }

        /* Testimonials */
        .testimonials {
            background: var(--secondary);
            padding: 6rem 0;
        }

        .testimonial-card {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            margin: 1rem;
        }

        .testimonial-text {
            font-style: italic;
            margin-bottom: 1.5rem;
            color: #555;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
        }

        .author-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            margin-right: 1rem;
        }

        /* CTA Section */
        .cta-section {
            background: var(--gradient);
            color: white;
            padding: 5rem 0;
            text-align: center;
        }

        .cta-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .btn-cta {
            background: var(--accent);
            border: none;
            padding: 1rem 3rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
            margin-top: 2rem;
        }

        .btn-cta:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(255, 107, 53, 0.6);
        }

        /* Footer */
        footer {
            background: var(--dark);
            color: white;
            padding: 4rem 0 2rem;
        }

        .footer-links h5 {
            color: var(--accent);
            margin-bottom: 1.5rem;
        }

        .footer-links a {
            color: #ccc;
            text-decoration: none;
            transition: color 0.3s ease;
            display: block;
            margin-bottom: 0.5rem;
        }

        .footer-links a:hover {
            color: white;
        }

        .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            margin-right: 1rem;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background: var(--accent);
            transform: translateY(-3px);
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.8s ease-out;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .stat-number {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="/">
            <i class="fas fa-bus me-2"></i>OBRS
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                <li class="nav-item"><a class="nav-link" href="#how-it-works">How It Works</a></li>
                <li class="nav-item"><a class="nav-link" href="#testimonials">Testimonials</a></li>
                @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button class="btn btn-sm btn-outline-light" type="submit">Logout</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt me-1"></i>Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-warning btn-sm ms-2" href="{{ route('register') }}">
                            <i class="fas fa-user-plus me-1"></i>Register
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 fade-in-up">
                <h1 class="hero-title">Travel Smarter, Book Easier</h1>
                <p class="hero-subtitle">Experience the future of bus travel with our seamless online reservation system. Book tickets anytime, anywhere with just a few clicks.</p>
                <div class="hero-buttons">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-hero me-3">
                            <i class="fas fa-tachometer-alt me-2"></i>Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-hero me-3">
                            <i class="fas fa-rocket me-2"></i>Get Started
                        </a>
                        <a href="#features" class="btn btn-outline-light">
                            <i class="fas fa-play-circle me-2"></i>Learn More
                        </a>
                    @endauth
                </div>
            </div>
            <div class="col-lg-6 text-center fade-in-up" style="animation-delay: 0.2s;">
                <div class="hero-image">
                    <i class="fas fa-bus fa-10x text-warning opacity-25"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 col-6 stat-item">
                <div class="stat-number">50K+</div>
                <div class="stat-label">Happy Travelers</div>
            </div>
            <div class="col-md-3 col-6 stat-item">
                <div class="stat-number">500+</div>
                <div class="stat-label">Bus Routes</div>
            </div>
            <div class="col-md-3 col-6 stat-item">
                <div class="stat-number">24/7</div>
                <div class="stat-label">Customer Support</div>
            </div>
            <div class="col-md-3 col-6 stat-item">
                <div class="stat-number">98%</div>
                <div class="stat-label">Satisfaction Rate</div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features" id="features">
    <div class="container">
        <h2 class="section-title">Why Choose OBRS?</h2>
        <p class="section-subtitle">Experience the difference with our comprehensive bus reservation platform</p>
        
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h4 class="feature-title">Lightning Fast Booking</h4>
                    <p class="feature-text">Book your bus tickets in under 2 minutes with our streamlined process. No more waiting in long queues.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h4 class="feature-title">Secure Payments</h4>
                    <p class="feature-text">Your payments are protected with bank-level security. Multiple payment options available for your convenience.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h4 class="feature-title">Mobile Friendly</h4>
                    <p class="feature-text">Access our platform from any device. Perfect for booking on the go with our responsive design.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-qrcode"></i>
                    </div>
                    <h4 class="feature-title">Digital Tickets</h4>
                    <p class="feature-text">Get instant e-tickets with QR codes. No need to print - just show your phone and board.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h4 class="feature-title">Real-time Tracking</h4>
                    <p class="feature-text">Track your bus in real-time and get live updates on arrival times and route changes.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h4 class="feature-title">24/7 Support</h4>
                    <p class="feature-text">Our customer support team is available round the clock to assist you with any queries.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="how-it-works" id="how-it-works">
    <div class="container">
        <h2 class="section-title">How It Works</h2>
        <p class="section-subtitle">Book your bus ticket in 4 simple steps</p>
        
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="step-card">
                    <div class="step-number">1</div>
                    <h5>Search & Select</h5>
                    <p>Enter your route and travel date to find available buses</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="step-card">
                    <div class="step-number">2</div>
                    <h5>Choose Your Seat</h5>
                    <p>Select your preferred seat from the interactive seat map</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="step-card">
                    <div class="step-number">3</div>
                    <h5>Secure Payment</h5>
                    <p>Pay safely using your preferred payment method</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="step-card">
                    <div class="step-number">4</div>
                    <h5>Get E-Ticket</h5>
                    <p>Receive instant confirmation and your digital ticket</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="testimonials" id="testimonials">
    <div class="container">
        <h2 class="section-title">What Our Travelers Say</h2>
        <p class="section-subtitle">Don't just take our word for it</p>
        
        <div class="row">
            <div class="col-lg-4">
                <div class="testimonial-card">
                    <div class="testimonial-text">
                        "OBRS made my travel so much easier! The booking process is incredibly smooth and the customer support is excellent."
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">SJ</div>
                        <div>
                            <strong>Sarah Johnson</strong>
                            <div class="text-warning">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="testimonial-card">
                    <div class="testimonial-text">
                        "I travel frequently for work and OBRS has saved me so much time. The mobile app is fantastic and the real-time updates are a lifesaver."
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">MK</div>
                        <div>
                            <strong>Michael Kim</strong>
                            <div class="text-warning">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="testimonial-card">
                    <div class="testimonial-text">
                        "As a student, I appreciate the affordable prices and easy booking process. The digital tickets make everything so convenient!"
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">ED</div>
                        <div>
                            <strong>Emily Davis</strong>
                            <div class="text-warning">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <h2 class="cta-title">Ready to Start Your Journey?</h2>
        <p class="lead mb-4">Join thousands of satisfied travelers and experience hassle-free bus booking today.</p>
        @auth
            <a href="{{ route('dashboard') }}" class="btn btn-cta">
                <i class="fas fa-tachometer-alt me-2"></i>Go to Dashboard
            </a>
        @else
            <a href="{{ route('register') }}" class="btn btn-cta me-3">
                <i class="fas fa-user-plus me-2"></i>Sign Up Free
            </a>
            <a href="{{ route('login') }}" class="btn btn-outline-light">
                <i class="fas fa-sign-in-alt me-2"></i>Login
            </a>
        @endauth
    </div>
</section>

<!-- Footer -->
<footer>
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <h4><i class="fas fa-bus me-2 text-warning"></i>OBRS</h4>
                <p class="mt-3">Your trusted partner for seamless bus travel experiences. Book smarter, travel better.</p>
                <div class="social-links mt-4">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-md-6 mb-4">
                <div class="footer-links">
                    <h5>Quick Links</h5>
                    <a href="#features">Features</a>
                    <a href="#how-it-works">How It Works</a>
                    <a href="#testimonials">Testimonials</a>
                    <a href="#">Pricing</a>
                </div>
            </div>
            <div class="col-lg-2 col-md-6 mb-4">
                <div class="footer-links">
                    <h5>Support</h5>
                    <a href="#">Help Center</a>
                    <a href="#">Contact Us</a>
                    <a href="#">FAQs</a>
                    <a href="#">Privacy Policy</a>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="footer-links">
                    <h5>Contact Info</h5>
                    <p><i class="fas fa-envelope me-2 text-warning"></i> support@obrs.com</p>
                    <p><i class="fas fa-phone me-2 text-warning"></i> +260 123 456 789</p>
                    <p><i class="fas fa-map-marker-alt me-2 text-warning"></i> Lusaka, Zambia</p>
                </div>
            </div>
        </div>
        <hr class="my-4" style="border-color: #444;">
        <div class="text-center">
            <p>&copy; 2024 Online Bus Reservation System. All rights reserved.</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Navbar background on scroll
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar');
        if (window.scrollY > 100) {
            navbar.style.background = 'var(--gradient)';
            navbar.style.padding = '0.5rem 0';
        } else {
            navbar.style.background = 'var(--gradient)';
            navbar.style.padding = '1rem 0';
        }
    });

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Animation on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe elements for animation
    document.querySelectorAll('.feature-card, .step-card, .testimonial-card').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });
</script>
</body>
</html>