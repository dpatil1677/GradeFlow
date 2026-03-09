<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search Results - GradeFlow</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar scrolled" id="navbar">
    <div class="container">
      <a href="index.php" class="navbar-brand">
        <div class="logo-icon">🎓</div>
        <span>Grade<span class="text-gradient">Flow</span></span>
      </a>
      <div class="navbar-nav" id="navMenu">
        <a href="index.php">Home</a>
        <a href="index.php#features">Features</a>
        <a href="index.php#how-it-works">How it Works</a>
        <a href="result-search.php" class="active">Results</a>
        <a href="index.php#contact">Contact</a>
      </div>
      <div class="navbar-actions">
        <a href="student-login.php" class="btn btn-ghost btn-sm">Student Login</a>
        <a href="admin-login.php" class="btn btn-primary btn-sm">Admin Portal</a>
      </div>
    </div>
  </nav>

  <div class="result-search-page">
    <div class="hero-bg">
      <div class="orb orb-1" style="opacity:0.2;"></div>
      <div class="orb orb-2" style="opacity:0.15;"></div>
    </div>

    <div class="container">
      <!-- Search Section -->
      <div class="search-hero animate-fade-up">
        <div class="section-badge"><i class="fas fa-search"></i> Result Lookup</div>
        <h1>Search Your <span class="text-gradient">Results</span></h1>
        <p>Enter your roll number to instantly access your examination results</p>

        <div class="search-box">
          <span class="search-icon"><i class="fas fa-search"></i></span>
          <input type="text" class="form-input" id="rollInput" placeholder="Enter Roll Number (e.g., CS2025001)" value="CS2025001">
          <button class="btn btn-primary" onclick="showResult()">
            <i class="fas fa-arrow-right"></i> Search
          </button>
        </div>
      </div>

      <!-- Result Display (shown after search) -->
      <div id="resultSection" style="display:none; padding-bottom: 80px;">
        <!-- Student Info Card -->
        <div class="glass-card animate-fade-up" style="margin-bottom:28px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:24px;">
          <div style="display:flex;align-items:center;gap:20px;">
            <div class="profile-avatar" style="width:72px;height:72px;font-size:1.6rem;margin:0;">AK</div>
            <div>
              <h3 style="font-size:1.3rem;margin-bottom:4px;">Ananya Kumari</h3>
              <p style="color:var(--text-muted);font-size:0.9rem;">Roll No: CS2025001 • BSc Computer Science • Semester 6</p>
            </div>
          </div>
          <div style="display:flex;gap:32px;flex-wrap:wrap;">
            <div style="text-align:center;">
              <div style="font-size:0.78rem;color:var(--text-muted);margin-bottom:4px;">CGPA</div>
              <div style="font-size:1.6rem;font-weight:800;color:var(--primary-light);">8.75</div>
            </div>
            <div style="text-align:center;">
              <div style="font-size:0.78rem;color:var(--text-muted);margin-bottom:4px;">Percentage</div>
              <div style="font-size:1.6rem;font-weight:800;color:var(--success);">86.17%</div>
            </div>
            <div style="text-align:center;">
              <div style="font-size:0.78rem;color:var(--text-muted);margin-bottom:4px;">Result</div>
              <div><span class="status-badge pass" style="font-size:0.85rem;padding:6px 16px;">Distinction</span></div>
            </div>
          </div>
        </div>

        <!-- Result Table -->
        <div class="result-card animate-fade-up" style="animation-delay:0.2s;">
          <div class="result-header">
            <div class="result-header-left">
              <h3>Semester 6 — Midterm Examination 2026</h3>
              <p>BSc Computer Science • University of Technology</p>
            </div>
            <button class="btn btn-ghost btn-sm"><i class="fas fa-download"></i> Download PDF</button>
          </div>
          <div class="panel-body" style="padding:0;">
            <div class="data-table-wrapper">
              <table class="data-table">
                <thead>
                  <tr>
                    <th>Code</th>
                    <th>Subject</th>
                    <th>Internal (30)</th>
                    <th>External (70)</th>
                    <th>Total (100)</th>
                    <th>Grade</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td style="font-family:var(--font-mono);color:var(--text-muted);">CS601</td>
                    <td><strong>Data Structures & Algorithms</strong></td>
                    <td>28</td><td>64</td><td><strong>92</strong></td>
                    <td><span class="grade-badge grade-a">A+</span></td>
                    <td><span class="status-badge pass">Pass</span></td>
                  </tr>
                  <tr>
                    <td style="font-family:var(--font-mono);color:var(--text-muted);">CS602</td>
                    <td><strong>Database Management Systems</strong></td>
                    <td>26</td><td>62</td><td><strong>88</strong></td>
                    <td><span class="grade-badge grade-a">A</span></td>
                    <td><span class="status-badge pass">Pass</span></td>
                  </tr>
                  <tr>
                    <td style="font-family:var(--font-mono);color:var(--text-muted);">CS603</td>
                    <td><strong>Operating Systems</strong></td>
                    <td>29</td><td>66</td><td><strong>95</strong></td>
                    <td><span class="grade-badge grade-a">A+</span></td>
                    <td><span class="status-badge pass">Pass</span></td>
                  </tr>
                  <tr>
                    <td style="font-family:var(--font-mono);color:var(--text-muted);">CS604</td>
                    <td><strong>Computer Networks</strong></td>
                    <td>22</td><td>56</td><td><strong>78</strong></td>
                    <td><span class="grade-badge grade-b">B+</span></td>
                    <td><span class="status-badge pass">Pass</span></td>
                  </tr>
                  <tr>
                    <td style="font-family:var(--font-mono);color:var(--text-muted);">CS605</td>
                    <td><strong>Software Engineering</strong></td>
                    <td>25</td><td>59</td><td><strong>84</strong></td>
                    <td><span class="grade-badge grade-a">A</span></td>
                    <td><span class="status-badge pass">Pass</span></td>
                  </tr>
                  <tr>
                    <td style="font-family:var(--font-mono);color:var(--text-muted);">MA604</td>
                    <td><strong>Mathematics IV</strong></td>
                    <td>24</td><td>56</td><td><strong>80</strong></td>
                    <td><span class="grade-badge grade-a">A</span></td>
                    <td><span class="status-badge pass">Pass</span></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div style="padding:20px 24px;border-top:1px solid var(--border-color);display:flex;justify-content:space-between;flex-wrap:wrap;gap:20px;background:var(--bg-glass);">
            <div><span style="font-size:0.78rem;color:var(--text-muted);">Total Marks</span><div style="font-size:1.2rem;font-weight:700;">517 / 600</div></div>
            <div><span style="font-size:0.78rem;color:var(--text-muted);">Percentage</span><div style="font-size:1.2rem;font-weight:700;color:var(--success);">86.17%</div></div>
            <div><span style="font-size:0.78rem;color:var(--text-muted);">SGPA</span><div style="font-size:1.2rem;font-weight:700;color:var(--primary-light);">8.62</div></div>
            <div><span style="font-size:0.78rem;color:var(--text-muted);">CGPA</span><div style="font-size:1.2rem;font-weight:700;color:var(--primary-light);">8.75</div></div>
            <div><span style="font-size:0.78rem;color:var(--text-muted);">Division</span><div style="font-size:1.2rem;font-weight:700;color:var(--success);">First Class - Distinction</div></div>
          </div>
        </div>

        <!-- Disclaimer -->
        <div class="alert alert-info animate-fade-up" style="animation-delay:0.4s;">
          <i class="fas fa-info-circle"></i>
          <span>This is a provisional result. For official result documents, please contact the examination cell or login to your student portal.</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="footer">
    <div class="container">
      <div class="footer-bottom" style="border:none;padding:0;">
        <p>&copy; 2026 GradeFlow. All rights reserved.</p>
        <div class="footer-bottom-links">
          <a href="index.php">Home</a>
          <a href="student-login.php">Student Login</a>
          <a href="admin-login.php">Admin Login</a>
        </div>
      </div>
    </div>
  </footer>

  <script>
    function showResult() {
      const roll = document.getElementById('rollInput').value.trim();
      if (roll) {
        document.getElementById('resultSection').style.display = 'block';
        document.getElementById('resultSection').scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    }

    // Allow Enter key
    document.getElementById('rollInput').addEventListener('keypress', function(e) {
      if (e.key === 'Enter') showResult();
    });
  </script>
</body>
</html>
