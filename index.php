<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GradeFlow - Student Academic & Result Management System</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="icon" href="images/logo.png" sizes="32*32">
</head>
<body>

  <!-- ===== NAVBAR ===== -->
  <nav class="navbar" id="navbar">
    <div class="container">
      <a href="index.php" class="navbar-brand">
        <div class="logo-icon">🎓</div>
        <span>Grade<span class="text-gradient">Flow</span></span>
      </a>

      <div class="navbar-nav" id="navMenu">
        <a href="#home" class="active">Home</a>
        <a href="#features">Features</a>
        <a href="#how-it-works">How it Works</a>
        <a href="result-search.php">Results</a>
        <a href="#contact">Contact</a>
      </div>

      <div class="navbar-actions">
        <a href="student-login.php" class="btn btn-ghost btn-sm" id="studentLoginBtn">Student Login</a>
        <a href="admin-login.php" class="btn btn-primary btn-sm" id="adminPortalBtn">Admin Portal</a>
      </div>
    </div>
  </nav>

  <!-- ===== HERO SECTION ===== -->
  <section class="hero" id="home">
    <div class="hero-bg">
      <div class="orb orb-1"></div>
      <div class="orb orb-2"></div>
      <div class="orb orb-3"></div>
    </div>
    <div class="hero-grid"></div>

    <div class="container">
      <div class="hero-content">
        <div class="hero-text animate-fade-up">
          <div class="hero-badge">
            <span class="dot"></span>
            Academic Year 2025-26 Results Live
          </div>
          <h1 class="hero-title">
            Student Academic &amp;
            <span class=" text-gradient">Result Management</span>
            System
          </h1>
          <p class="hero-description">
            A comprehensive platform to manage student records, track academic performance,
            monitor attendance, and instantly access examination results — all in one place.
          </p>
          <div class="hero-buttons">
            <a href="result-search.php" class="btn btn-primary btn-lg">
              <i class="fas fa-search"></i> Check Results
            </a>
            <a href="#features" class="btn btn-secondary btn-lg">
              <i class="fas fa-play-circle"></i> Learn More
            </a>
          </div>
          <div class="hero-stats">
            <div class="hero-stat">
              <div class="number text-gradient">5,000+</div>
              <div class="label">Students Enrolled</div>
            </div>
            <div class="hero-stat">
              <div class="number text-gradient">98%</div>
              <div class="label">Pass Rate</div>
            </div>
            <div class="hero-stat">
              <div class="number text-gradient">150+</div>
              <div class="label">Faculty Members</div>
            </div>
          </div>
        </div>

        <div class="hero-visual animate-fade-right delay-2">
          <div class="hero-card-stack">
            <!-- Floating Card 1: Student Profile -->
            <div class="hero-floating-card card-1">
              <div class="mini-profile">
                <div class="mini-avatar" style="background: var(--gradient-primary);">AK</div>
                <div class="mini-profile-info">
                  <div class="name">Ananya Kumari</div>
                  <div class="role">BSc Computer Science • Sem 6</div>
                </div>
              </div>
              <div class="mini-grades">
                <div class="mini-grade">
                  <div class="subject">DSA</div>
                  <div class="score">92</div>
                </div>
                <div class="mini-grade">
                  <div class="subject">DBMS</div>
                  <div class="score">88</div>
                </div>
                <div class="mini-grade">
                  <div class="subject">OS</div>
                  <div class="score">95</div>
                </div>
              </div>
            </div>

            <!-- Floating Card 2: Result Status -->
            <div class="hero-floating-card card-2">
              <div style="margin-bottom: 12px; font-weight: 600; font-size: 0.9rem;">Semester Result</div>
              <div class="mini-result-badge badge-pass">
                <i class="fas fa-check-circle"></i> Passed with Distinction
              </div>
              <div class="mini-chart-bars">
                <div class="mini-chart-bar" style="height: 80%; background: var(--gradient-primary);"></div>
                <div class="mini-chart-bar" style="height: 65%; background: var(--gradient-secondary);"></div>
                <div class="mini-chart-bar" style="height: 90%; background: var(--gradient-accent);"></div>
                <div class="mini-chart-bar" style="height: 75%; background: var(--gradient-primary);"></div>
                <div class="mini-chart-bar" style="height: 85%; background: var(--gradient-secondary);"></div>
              </div>
            </div>

            <!-- Floating Card 3: Attendance -->
            <div class="hero-floating-card card-3">
              <div style="display: flex; align-items: center; gap: 20px;">
                <div class="mini-attendance-ring">
                  <span class="percentage">92%</span>
                </div>
                <div>
                  <div style="font-weight: 600; font-size: 0.95rem; margin-bottom: 4px;">Attendance</div>
                  <div style="font-size: 0.8rem; color: var(--text-muted);">176 of 192 classes</div>
                  <div class="mini-result-badge badge-pass" style="margin-top: 8px;">
                    <i class="fas fa-check"></i> Above minimum
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ===== FEATURES SECTION ===== -->
  <section class="features section" id="features">
    <div class="container">
      <div class="section-header animate-fade-up">
        <div class="section-badge">
          <i class="fas fa-sparkles"></i> Features
        </div>
        <h2 class="section-title">
          Everything You Need to <span class="text-gradient">Manage Academics</span>
        </h2>
        <p class="section-subtitle">
          Powerful tools designed to streamline student management, result processing,
          and academic tracking for institutions of all sizes.
        </p>
      </div>

      <div class="features-grid">
        <div class="feature-card animate-fade-up delay-1">
          <div class="feature-icon icon-primary">
            <i class="fas fa-user-graduate"></i>
          </div>
          <h3>Student Management</h3>
          <p>Complete student profiles with enrollment details, course information, and academic history in a centralized database.</p>
        </div>

        <div class="feature-card animate-fade-up delay-2">
          <div class="feature-icon icon-secondary">
            <i class="fas fa-chart-line"></i>
          </div>
          <h3>Result Processing</h3>
          <p>Automated grade calculation, GPA computation, and instant result publication with detailed subject-wise analysis.</p>
        </div>

        <div class="feature-card animate-fade-up delay-3">
          <div class="feature-icon icon-accent">
            <i class="fas fa-calendar-check"></i>
          </div>
          <h3>Attendance Tracking</h3>
          <p>Real-time attendance monitoring with visual calendars, shortage alerts, and detailed subject-wise attendance reports.</p>
        </div>

        <div class="feature-card animate-fade-up delay-4">
          <div class="feature-icon icon-success">
            <i class="fas fa-file-alt"></i>
          </div>
          <h3>Marks Entry</h3>
          <p>Streamlined marks entry system for internal, midterm, and final examinations with validation and auto-save features.</p>
        </div>

        <div class="feature-card animate-fade-up delay-5">
          <div class="feature-icon icon-info">
            <i class="fas fa-search"></i>
          </div>
          <h3>Instant Result Lookup</h3>
          <p>Students can instantly check their results online using roll number — no login required for basic result viewing.</p>
        </div>

        <div class="feature-card animate-fade-up delay-6">
          <div class="feature-icon icon-warning">
            <i class="fas fa-shield-alt"></i>
          </div>
          <h3>Secure & Reliable</h3>
          <p>Role-based access control, encrypted data storage, and automated backups ensure data integrity and security.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ===== HOW IT WORKS ===== -->
  <section class="how-it-works section" id="how-it-works">
    <div class="container">
      <div class="section-header animate-fade-up">
        <div class="section-badge">
          <i class="fas fa-route"></i> Process
        </div>
        <h2 class="section-title">
          How It <span class="text-gradient">Works</span>
        </h2>
        <p class="section-subtitle">
          A simple 4-step process from enrollment to result access.
        </p>
      </div>

      <div class="steps-container">
        <div class="step-card step-1 animate-fade-up delay-1">
          <div class="step-number">01</div>
          <h3>Student Registration</h3>
          <p>Admin registers students with their personal and academic details into the system.</p>
        </div>
        <div class="step-card step-2 animate-fade-up delay-2">
          <div class="step-number">02</div>
          <h3>Marks & Attendance</h3>
          <p>Faculty enters marks and attendance records for each student across all subjects.</p>
        </div>
        <div class="step-card step-3 animate-fade-up delay-3">
          <div class="step-number">03</div>
          <h3>Result Processing</h3>
          <p>System automatically calculates grades, percentages, and generates result sheets.</p>
        </div>
        <div class="step-card step-4 animate-fade-up delay-4">
          <div class="step-number">04</div>
          <h3>View Results Online</h3>
          <p>Students can check their results online using roll number from anywhere, anytime.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ===== CTA SECTION ===== -->
  <section class="cta-section section">
    <div class="container">
      <div class="cta-box animate-scale">
        <h2>Ready to Check Your <span class="text-gradient">Results?</span></h2>
        <p>Access your academic results instantly by entering your roll number. No login required for result checking.</p>
        <div class="cta-buttons">
          <a href="result-search.php" class="btn btn-primary btn-lg">
            <i class="fas fa-search"></i> Search Results
          </a>
          <a href="student-login.php" class="btn btn-secondary btn-lg">
            <i class="fas fa-sign-in-alt"></i> Student Login
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- ===== FOOTER ===== -->
  <footer class="footer" id="contact">
    <div class="container">
      <div class="footer-grid">
        <div class="footer-brand">
          <a href="index.php" class="navbar-brand">
            <div class="logo-icon">🎓</div>
            <span>Grade<span class="text-gradient">Flow</span></span>
          </a>
          <p>Student Academic & Result Management System — your complete solution for managing academic records, results, and student data efficiently.</p>
          <div class="footer-social">
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-linkedin-in"></i></a>
            <a href="#"><i class="fab fa-github"></i></a>
          </div>
        </div>

        <div class="footer-col">
          <h4>Quick Links</h4>
          <a href="index.php">Home</a>
          <a href="result-search.php">Check Results</a>
          <a href="student-login.php">Student Login</a>
          <a href="admin-login.php">Admin Login</a>
        </div>

        <div class="footer-col">
          <h4>Resources</h4>
          <a href="#">Academic Calendar</a>
          <a href="#">Examination Schedule</a>
          <a href="#">Syllabus</a>
          <a href="#">Notifications</a>
        </div>

        <div class="footer-col">
          <h4>Contact</h4>
          <a href="#"><i class="fas fa-envelope" style="margin-right:8px;"></i>support@GradeFlow.edu</a>
          <a href="#"><i class="fas fa-phone" style="margin-right:8px;"></i>+91 98765 43210</a>
          <a href="#"><i class="fas fa-map-marker-alt" style="margin-right:8px;"></i>University Campus, India</a>
        </div>
      </div>

      <div class="footer-bottom">
        <p>&copy; 2026 GradeFlow. All rights reserved.</p>
        <div class="footer-bottom-links">
          <a href="#">Privacy Policy</a>
          <a href="#">Terms of Service</a>
          <a href="#">Help Center</a>
        </div>
      </div>
    </div>
  </footer>

  <script>
    // Navbar scroll effect
    window.addEventListener('scroll', () => {
      const navbar = document.getElementById('navbar');
      navbar.classList.toggle('scrolled', window.scrollY > 40);
    });

    // Mobile responsive navbar
    function handleResize() {
      const navMenu = document.getElementById('navMenu');
      const studentBtn = document.getElementById('studentLoginBtn');
      const adminBtn = document.getElementById('adminPortalBtn');

      if (window.innerWidth <= 768) {
        // Mobile: hide nav links and admin button, show only student login
        navMenu.style.display = 'none';
        adminBtn.style.display = 'none';
        studentBtn.style.display = 'inline-flex';
        studentBtn.className = 'btn btn-primary btn-sm';
      } else {
        // Desktop: show everything as normal
        navMenu.style.display = '';
        adminBtn.style.display = '';
        studentBtn.style.display = '';
        studentBtn.className = 'btn btn-ghost btn-sm';
      }
    }

    window.addEventListener('resize', handleResize);
    window.addEventListener('DOMContentLoaded', handleResize);
    handleResize();

    // Intersection Observer for animations
    const observerOptions = { threshold: 0.1, rootMargin: '0px 0px -50px 0px' };
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.style.animationPlayState = 'running';
        }
      });
    }, observerOptions);

    document.querySelectorAll('[class*="animate-"]').forEach(el => {
      el.style.animationPlayState = 'paused';
      observer.observe(el);
    });
  </script>
</body>
</html>
