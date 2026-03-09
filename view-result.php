<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Results - GradeFlow</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>

  <div class="dashboard-layout">
    <!-- ===== SIDEBAR ===== -->
    <aside class="sidebar" id="sidebar">
      <div class="sidebar-header">
        <div class="logo-icon">🎓</div>
        <div class="brand-text">GradeFlow<small>Student Portal</small></div>
      </div>
      <nav class="sidebar-nav">
        <div class="sidebar-nav-group">
          <div class="sidebar-nav-label">Main</div>
          <a href="student-dashboard.php" class="sidebar-link"><span class="icon"><i class="fas fa-th-large"></i></span> Dashboard</a>
          <a href="view-result.php" class="sidebar-link active"><span class="icon"><i class="fas fa-poll"></i></span> My Results <span class="badge">New</span></a>
          <a href="attendance.php" class="sidebar-link"><span class="icon"><i class="fas fa-calendar-check"></i></span> Attendance</a>
        </div>
        <div class="sidebar-nav-group">
          <div class="sidebar-nav-label">Academic</div>
          <a href="#" class="sidebar-link"><span class="icon"><i class="fas fa-book-open"></i></span> Subjects</a>
          <a href="#" class="sidebar-link"><span class="icon"><i class="fas fa-file-download"></i></span> Downloads</a>
          <a href="#" class="sidebar-link"><span class="icon"><i class="fas fa-bell"></i></span> Notifications <span class="badge">3</span></a>
        </div>
        <div class="sidebar-nav-group">
          <div class="sidebar-nav-label">Account</div>
          <a href="#" class="sidebar-link"><span class="icon"><i class="fas fa-user-circle"></i></span> My Profile</a>
          <a href="index.php" class="sidebar-link"><span class="icon"><i class="fas fa-sign-out-alt"></i></span> Logout</a>
        </div>
      </nav>
      <div class="sidebar-footer">
        <div class="sidebar-user">
          <div class="sidebar-user-avatar">AK</div>
          <div class="sidebar-user-info"><div class="name">Ananya Kumari</div><div class="role">CS2025001</div></div>
        </div>
      </div>
    </aside>

    <!-- ===== MAIN CONTENT ===== -->
    <div class="main-content">
      <header class="topbar">
        <div class="topbar-left">
          <button class="sidebar-toggle" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
          <div class="topbar-title"><h2>My Results</h2><p>View all your semester results</p></div>
        </div>
        <div class="topbar-right">
          <button class="btn btn-ghost btn-sm"><i class="fas fa-download"></i> Download All</button>
          <button class="btn btn-primary btn-sm"><i class="fas fa-print"></i> Print</button>
        </div>
      </header>

      <div class="dashboard-content">
        <!-- Tabs for semester selection -->
        <div class="tabs">
          <button class="tab-btn active" onclick="switchTab(event, 'sem6')">Semester 6</button>
          <button class="tab-btn" onclick="switchTab(event, 'sem5')">Semester 5</button>
          <button class="tab-btn" onclick="switchTab(event, 'sem4')">Semester 4</button>
          <button class="tab-btn" onclick="switchTab(event, 'all')">All Semesters</button>
        </div>

        <!-- Semester 6 Result -->
        <div id="sem6" class="tab-content active">
          <div class="result-card">
            <div class="result-header">
              <div class="result-header-left">
                <h3>Semester 6 — Midterm Examination</h3>
                <p>BSc Computer Science • February 2026</p>
              </div>
              <div class="result-summary">
                <div class="result-percentage">
                  <div class="ring"></div>
                  <div class="ring-fill"></div>
                  <span class="value">86.2%</span>
                  <span class="label">Overall</span>
                </div>
                <div class="result-meta">
                  <span class="status-badge pass">Passed</span>
                  <div class="rank" style="margin-top:8px;">Rank: <strong>12</strong> / 180</div>
                </div>
              </div>
            </div>
            <div class="panel-body" style="padding:0;">
              <div class="data-table-wrapper">
                <table class="data-table">
                  <thead>
                    <tr>
                      <th>Subject Code</th>
                      <th>Subject Name</th>
                      <th>Internal</th>
                      <th>External</th>
                      <th>Total</th>
                      <th>Grade</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td style="font-family:var(--font-mono);color:var(--text-muted);">CS601</td>
                      <td><strong>Data Structures & Algorithms</strong></td>
                      <td>28/30</td>
                      <td>64/70</td>
                      <td><strong>92/100</strong></td>
                      <td><span class="grade-badge grade-a">A+</span></td>
                      <td><span class="status-badge pass">Pass</span></td>
                    </tr>
                    <tr>
                      <td style="font-family:var(--font-mono);color:var(--text-muted);">CS602</td>
                      <td><strong>Database Management Systems</strong></td>
                      <td>26/30</td>
                      <td>62/70</td>
                      <td><strong>88/100</strong></td>
                      <td><span class="grade-badge grade-a">A</span></td>
                      <td><span class="status-badge pass">Pass</span></td>
                    </tr>
                    <tr>
                      <td style="font-family:var(--font-mono);color:var(--text-muted);">CS603</td>
                      <td><strong>Operating Systems</strong></td>
                      <td>29/30</td>
                      <td>66/70</td>
                      <td><strong>95/100</strong></td>
                      <td><span class="grade-badge grade-a">A+</span></td>
                      <td><span class="status-badge pass">Pass</span></td>
                    </tr>
                    <tr>
                      <td style="font-family:var(--font-mono);color:var(--text-muted);">CS604</td>
                      <td><strong>Computer Networks</strong></td>
                      <td>22/30</td>
                      <td>56/70</td>
                      <td><strong>78/100</strong></td>
                      <td><span class="grade-badge grade-b">B+</span></td>
                      <td><span class="status-badge pass">Pass</span></td>
                    </tr>
                    <tr>
                      <td style="font-family:var(--font-mono);color:var(--text-muted);">CS605</td>
                      <td><strong>Software Engineering</strong></td>
                      <td>25/30</td>
                      <td>59/70</td>
                      <td><strong>84/100</strong></td>
                      <td><span class="grade-badge grade-a">A</span></td>
                      <td><span class="status-badge pass">Pass</span></td>
                    </tr>
                    <tr>
                      <td style="font-family:var(--font-mono);color:var(--text-muted);">MA604</td>
                      <td><strong>Mathematics IV</strong></td>
                      <td>24/30</td>
                      <td>56/70</td>
                      <td><strong>80/100</strong></td>
                      <td><span class="grade-badge grade-a">A</span></td>
                      <td><span class="status-badge pass">Pass</span></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Result Summary Footer -->
            <div style="padding:20px 24px;border-top:1px solid var(--border-color);display:flex;justify-content:space-between;flex-wrap:wrap;gap:20px;">
              <div>
                <span style="font-size:0.82rem;color:var(--text-muted);">Total Marks</span>
                <div style="font-size:1.3rem;font-weight:700;">517 <span style="color:var(--text-muted);font-weight:400;">/ 600</span></div>
              </div>
              <div>
                <span style="font-size:0.82rem;color:var(--text-muted);">Percentage</span>
                <div style="font-size:1.3rem;font-weight:700;color:var(--success);">86.17%</div>
              </div>
              <div>
                <span style="font-size:0.82rem;color:var(--text-muted);">SGPA</span>
                <div style="font-size:1.3rem;font-weight:700;color:var(--primary-light);">8.62</div>
              </div>
              <div>
                <span style="font-size:0.82rem;color:var(--text-muted);">CGPA</span>
                <div style="font-size:1.3rem;font-weight:700;color:var(--primary-light);">8.75</div>
              </div>
              <div>
                <span style="font-size:0.82rem;color:var(--text-muted);">Result</span>
                <div><span class="status-badge pass" style="font-size:0.88rem;padding:6px 18px;">First Class with Distinction</span></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Semester 5 Result -->
        <div id="sem5" class="tab-content">
          <div class="result-card">
            <div class="result-header">
              <div class="result-header-left">
                <h3>Semester 5 — Final Examination</h3>
                <p>BSc Computer Science • November 2025</p>
              </div>
              <div class="result-summary">
                <div class="result-percentage">
                  <div class="ring"></div>
                  <span class="value">87.5%</span>
                  <span class="label">Overall</span>
                </div>
                <div class="result-meta">
                  <span class="status-badge pass">Passed</span>
                  <div class="rank" style="margin-top:8px;">Rank: <strong>10</strong> / 180</div>
                </div>
              </div>
            </div>
            <div class="panel-body" style="padding:0;">
              <div class="data-table-wrapper">
                <table class="data-table">
                  <thead>
                    <tr><th>Subject Code</th><th>Subject Name</th><th>Internal</th><th>External</th><th>Total</th><th>Grade</th><th>Status</th></tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td style="font-family:var(--font-mono);color:var(--text-muted);">CS501</td>
                      <td><strong>Theory of Computation</strong></td>
                      <td>27/30</td><td>63/70</td><td><strong>90/100</strong></td>
                      <td><span class="grade-badge grade-a">A+</span></td>
                      <td><span class="status-badge pass">Pass</span></td>
                    </tr>
                    <tr>
                      <td style="font-family:var(--font-mono);color:var(--text-muted);">CS502</td>
                      <td><strong>Compiler Design</strong></td>
                      <td>25/30</td><td>60/70</td><td><strong>85/100</strong></td>
                      <td><span class="grade-badge grade-a">A</span></td>
                      <td><span class="status-badge pass">Pass</span></td>
                    </tr>
                    <tr>
                      <td style="font-family:var(--font-mono);color:var(--text-muted);">CS503</td>
                      <td><strong>Artificial Intelligence</strong></td>
                      <td>28/30</td><td>65/70</td><td><strong>93/100</strong></td>
                      <td><span class="grade-badge grade-a">A+</span></td>
                      <td><span class="status-badge pass">Pass</span></td>
                    </tr>
                    <tr>
                      <td style="font-family:var(--font-mono);color:var(--text-muted);">CS504</td>
                      <td><strong>Web Technologies</strong></td>
                      <td>26/30</td><td>58/70</td><td><strong>84/100</strong></td>
                      <td><span class="grade-badge grade-a">A</span></td>
                      <td><span class="status-badge pass">Pass</span></td>
                    </tr>
                    <tr>
                      <td style="font-family:var(--font-mono);color:var(--text-muted);">CS505</td>
                      <td><strong>Machine Learning</strong></td>
                      <td>24/30</td><td>62/70</td><td><strong>86/100</strong></td>
                      <td><span class="grade-badge grade-a">A</span></td>
                      <td><span class="status-badge pass">Pass</span></td>
                    </tr>
                    <tr>
                      <td style="font-family:var(--font-mono);color:var(--text-muted);">MA503</td>
                      <td><strong>Discrete Mathematics</strong></td>
                      <td>26/30</td><td>61/70</td><td><strong>87/100</strong></td>
                      <td><span class="grade-badge grade-a">A</span></td>
                      <td><span class="status-badge pass">Pass</span></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div style="padding:20px 24px;border-top:1px solid var(--border-color);display:flex;justify-content:space-between;flex-wrap:wrap;gap:20px;">
              <div><span style="font-size:0.82rem;color:var(--text-muted);">Total Marks</span><div style="font-size:1.3rem;font-weight:700;">525 <span style="color:var(--text-muted);font-weight:400;">/ 600</span></div></div>
              <div><span style="font-size:0.82rem;color:var(--text-muted);">Percentage</span><div style="font-size:1.3rem;font-weight:700;color:var(--success);">87.50%</div></div>
              <div><span style="font-size:0.82rem;color:var(--text-muted);">SGPA</span><div style="font-size:1.3rem;font-weight:700;color:var(--primary-light);">8.75</div></div>
              <div><span style="font-size:0.82rem;color:var(--text-muted);">Result</span><div><span class="status-badge pass" style="font-size:0.88rem;padding:6px 18px;">Distinction</span></div></div>
            </div>
          </div>
        </div>

        <!-- Semester 4 -->
        <div id="sem4" class="tab-content">
          <div class="result-card">
            <div class="result-header">
              <div class="result-header-left">
                <h3>Semester 4 — Final Examination</h3>
                <p>BSc Computer Science • May 2025</p>
              </div>
              <div class="result-summary">
                <div class="result-percentage">
                  <div class="ring"></div>
                  <span class="value">85.1%</span>
                  <span class="label">Overall</span>
                </div>
                <div class="result-meta">
                  <span class="status-badge pass">Passed</span>
                </div>
              </div>
            </div>
            <div class="panel-body" style="padding: 40px; text-align: center;">
              <div class="empty-state">
                <div class="icon">📄</div>
                <h3>Detailed Result Available</h3>
                <p>Click below to view the full subject-wise breakdown for Semester 4</p>
                <button class="btn btn-primary"><i class="fas fa-eye"></i> View Full Result</button>
              </div>
            </div>
          </div>
        </div>

        <!-- All Semesters Overview -->
        <div id="all" class="tab-content">
          <div class="panel" style="margin-bottom:24px;">
            <div class="panel-header">
              <h3><i class="fas fa-chart-line" style="margin-right:8px;color:var(--secondary);"></i> Semester-wise Performance</h3>
            </div>
            <div class="panel-body" style="padding:0;">
              <div class="data-table-wrapper">
                <table class="data-table">
                  <thead>
                    <tr><th>Semester</th><th>Examination</th><th>Total Marks</th><th>Percentage</th><th>SGPA</th><th>CGPA</th><th>Result</th></tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td><strong>Semester 6</strong></td><td>Midterm 2026</td><td>517/600</td><td><strong>86.17%</strong></td><td>8.62</td><td>8.75</td><td><span class="status-badge pass">Distinction</span></td>
                    </tr>
                    <tr>
                      <td><strong>Semester 5</strong></td><td>Final 2025</td><td>525/600</td><td><strong>87.50%</strong></td><td>8.75</td><td>8.71</td><td><span class="status-badge pass">Distinction</span></td>
                    </tr>
                    <tr>
                      <td><strong>Semester 4</strong></td><td>Final 2025</td><td>511/600</td><td><strong>85.17%</strong></td><td>8.52</td><td>8.55</td><td><span class="status-badge pass">Distinction</span></td>
                    </tr>
                    <tr>
                      <td><strong>Semester 3</strong></td><td>Final 2024</td><td>478/600</td><td><strong>79.67%</strong></td><td>7.97</td><td>8.22</td><td><span class="status-badge pass">First Class</span></td>
                    </tr>
                    <tr>
                      <td><strong>Semester 2</strong></td><td>Final 2024</td><td>504/600</td><td><strong>84.00%</strong></td><td>8.40</td><td>8.45</td><td><span class="status-badge pass">Distinction</span></td>
                    </tr>
                    <tr>
                      <td><strong>Semester 1</strong></td><td>Final 2023</td><td>509/600</td><td><strong>84.83%</strong></td><td>8.48</td><td>8.48</td><td><span class="status-badge pass">Distinction</span></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <script src="js/app.js"></script>
</body>
</html>
