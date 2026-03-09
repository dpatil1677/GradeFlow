<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Dashboard - GradeFlow</title>
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
        <div class="brand-text">
          GradeFlow
          <small>Student Portal</small>
        </div>
      </div>

      <nav class="sidebar-nav">
        <div class="sidebar-nav-group">
          <div class="sidebar-nav-label">Main</div>
          <a href="student-dashboard.php" class="sidebar-link active">
            <span class="icon"><i class="fas fa-th-large"></i></span> Dashboard
          </a>
          <a href="view-result.php" class="sidebar-link">
            <span class="icon"><i class="fas fa-poll"></i></span> My Results
            <span class="badge">New</span>
          </a>
          <a href="attendance.php" class="sidebar-link">
            <span class="icon"><i class="fas fa-calendar-check"></i></span> Attendance
          </a>
        </div>

        <div class="sidebar-nav-group">
          <div class="sidebar-nav-label">Academic</div>
          <a href="#" class="sidebar-link">
            <span class="icon"><i class="fas fa-book-open"></i></span> Subjects
          </a>
          <a href="#" class="sidebar-link">
            <span class="icon"><i class="fas fa-file-download"></i></span> Downloads
          </a>
          <a href="#" class="sidebar-link">
            <span class="icon"><i class="fas fa-bell"></i></span> Notifications
            <span class="badge">3</span>
          </a>
        </div>

        <div class="sidebar-nav-group">
          <div class="sidebar-nav-label">Account</div>
          <a href="#" class="sidebar-link">
            <span class="icon"><i class="fas fa-user-circle"></i></span> My Profile
          </a>
          <a href="#" class="sidebar-link">
            <span class="icon"><i class="fas fa-cog"></i></span> Settings
          </a>
          <a href="index.php" class="sidebar-link">
            <span class="icon"><i class="fas fa-sign-out-alt"></i></span> Logout
          </a>
        </div>
      </nav>

      <div class="sidebar-footer">
        <div class="sidebar-user">
          <div class="sidebar-user-avatar">AK</div>
          <div class="sidebar-user-info">
            <div class="name">Ananya Kumari</div>
            <div class="role">Roll No: CS2025001</div>
          </div>
        </div>
      </div>
    </aside>

    <!-- ===== MAIN CONTENT ===== -->
    <div class="main-content">
      <!-- Topbar -->
      <header class="topbar">
        <div class="topbar-left">
          <button class="sidebar-toggle" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
          </button>
          <div class="topbar-title">
            <h2>Dashboard</h2>
            <p>Welcome back, Ananya! 👋</p>
          </div>
        </div>
        <div class="topbar-right">
          <div class="topbar-search">
            <span class="search-icon"><i class="fas fa-search"></i></span>
            <input type="text" placeholder="Search...">
          </div>
          <button class="topbar-icon-btn">
            <i class="fas fa-bell"></i>
            <span class="notification-dot"></span>
          </button>
          <button class="topbar-icon-btn">
            <i class="fas fa-expand"></i>
          </button>
        </div>
      </header>

      <!-- Dashboard Content -->
      <div class="dashboard-content">
        <!-- Stat Cards -->
        <div class="stats-grid">
          <div class="stat-card animate-fade-up delay-1">
            <div class="stat-card-header">
              <div class="stat-icon primary"><i class="fas fa-percentage"></i></div>
              <span class="stat-trend up"><i class="fas fa-arrow-up"></i> 5.2%</span>
            </div>
            <div class="stat-value">87.5%</div>
            <div class="stat-label">Overall Percentage</div>
          </div>

          <div class="stat-card animate-fade-up delay-2">
            <div class="stat-card-header">
              <div class="stat-icon secondary"><i class="fas fa-star"></i></div>
              <span class="stat-trend up"><i class="fas fa-arrow-up"></i> 0.3</span>
            </div>
            <div class="stat-value">8.75</div>
            <div class="stat-label">Current CGPA</div>
          </div>

          <div class="stat-card animate-fade-up delay-3">
            <div class="stat-card-header">
              <div class="stat-icon accent"><i class="fas fa-calendar-check"></i></div>
              <span class="stat-trend up"><i class="fas fa-arrow-up"></i> 2%</span>
            </div>
            <div class="stat-value">92%</div>
            <div class="stat-label">Attendance Rate</div>
          </div>

          <div class="stat-card animate-fade-up delay-4">
            <div class="stat-card-header">
              <div class="stat-icon success"><i class="fas fa-trophy"></i></div>
            </div>
            <div class="stat-value">12<span style="font-size:1rem;color:var(--text-muted);font-weight:400;">/180</span></div>
            <div class="stat-label">Class Rank</div>
          </div>
        </div>

        <!-- Main Grid -->
        <div class="dashboard-grid">
          <!-- Subject Performance -->
          <div class="panel">
            <div class="panel-header">
              <h3><i class="fas fa-chart-bar" style="margin-right:8px;color:var(--primary-light);"></i> Subject Performance</h3>
              <div class="panel-actions">
                <button class="btn btn-ghost btn-sm">Semester 6</button>
              </div>
            </div>
            <div class="panel-body">
              <div class="progress-bar-wrapper">
                <div class="progress-label">
                  <span class="subject">Data Structures & Algorithms</span>
                  <span class="score">92/100</span>
                </div>
                <div class="progress-bar">
                  <div class="progress-fill progress-primary" style="width: 92%;"></div>
                </div>
              </div>

              <div class="progress-bar-wrapper">
                <div class="progress-label">
                  <span class="subject">Database Management Systems</span>
                  <span class="score">88/100</span>
                </div>
                <div class="progress-bar">
                  <div class="progress-fill progress-secondary" style="width: 88%;"></div>
                </div>
              </div>

              <div class="progress-bar-wrapper">
                <div class="progress-label">
                  <span class="subject">Operating Systems</span>
                  <span class="score">95/100</span>
                </div>
                <div class="progress-bar">
                  <div class="progress-fill progress-success" style="width: 95%;"></div>
                </div>
              </div>
              <div class="progress-bar-wrapper">
                <div class="progress-label">
                  <span class="subject">WDT-I</span>
                  <span class="score">91/100</span>
                </div>
                <div class="progress-bar">
                  <div class="progress-fill progress-primary" style="width :91" ></div>
                </div>
              </div>

              <div class="progress-bar-wrapper">
                <div class="progress-label">
                  <span class="subject">Computer Networks</span>
                  <span class="score">78/100</span>
                </div>
                <div class="progress-bar">
                  <div class="progress-fill progress-accent" style="width: 78%;"></div>
                </div>
              </div>

              <div class="progress-bar-wrapper">
                <div class="progress-label">
                  <span class="subject">Software Engineering</span>
                  <span class="score">84/100</span>
                </div>
                <div class="progress-bar">
                  <div class="progress-fill progress-info" style="width: 84%;"></div>
                </div>
              </div>

              <div class="progress-bar-wrapper" style="margin-bottom:0;">
                <div class="progress-label">
                  <span class="subject">Mathematics IV</span>
                  <span class="score">80/100</span>
                </div>
                <div class="progress-bar">
                  <div class="progress-fill progress-primary" style="width: 80%;"></div>
                </div>
              </div>
            </div>
          </div>

          <!-- Profile & Quick Info -->
          <div class="panel">
            <div class="panel-header">
              <h3><i class="fas fa-user" style="margin-right:8px;color:var(--primary-light);"></i> My Profile</h3>
              <button class="btn btn-ghost btn-sm"><i class="fas fa-edit"></i></button>
            </div>
            <div class="panel-body">
              <div class="profile-card">
                <div class="profile-avatar">AK</div>
                <div class="name">Ananya Kumari</div>
                <span class="role-badge">BSc Computer Science</span>

                <div class="profile-details">
                  <div class="profile-detail-row">
                    <span class="label">Roll Number</span>
                    <span class="value">CS2025001</span>
                  </div>
                  <div class="profile-detail-row">
                    <span class="label">Semester</span>
                    <span class="value">6th Semester</span>
                  </div>
                  <div class="profile-detail-row">
                    <span class="label">Section</span>
                    <span class="value">Section A</span>
                  </div>
                  <div class="profile-detail-row">
                    <span class="label">Batch</span>
                    <span class="value">2023 - 2026</span>
                  </div>
                  <div class="profile-detail-row">
                    <span class="label">Email</span>
                    <span class="value">ananya@GradeFlow.edu</span>
                  </div>
                  <div class="profile-detail-row">
                    <span class="label">Phone</span>
                    <span class="value">+91 98765 43210</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Second Row -->
        <div class="dashboard-grid dashboard-grid-equal">
          <!-- Recent Results -->
          <div class="panel">
            <div class="panel-header">
              <h3><i class="fas fa-clipboard-list" style="margin-right:8px;color:var(--secondary);"></i> Recent Results</h3>
              <a href="view-result.php" class="btn btn-ghost btn-sm">View All</a>
            </div>
            <div class="panel-body" style="padding:0;">
              <div class="data-table-wrapper">
                <table class="data-table">
                  <thead>
                    <tr>
                      <th>Exam</th>
                      <th>Semester</th>
                      <th>Percentage</th>
                      <th>Grade</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Final Exam</td>
                      <td>Sem 5</td>
                      <td><strong>87.5%</strong></td>
                      <td><span class="grade-badge grade-a">A</span></td>
                      <td><span class="status-badge pass">Passed</span></td>
                    </tr>
                    <tr>
                      <td>Midterm</td>
                      <td>Sem 6</td>
                      <td><strong>82.3%</strong></td>
                      <td><span class="grade-badge grade-a">A</span></td>
                      <td><span class="status-badge pass">Passed</span></td>
                    </tr>
                    <tr>
                      <td>Final Exam</td>
                      <td>Sem 4</td>
                      <td><strong>85.1%</strong></td>
                      <td><span class="grade-badge grade-a">A</span></td>
                      <td><span class="status-badge pass">Passed</span></td>
                    </tr>
                    <tr>
                      <td>Final Exam</td>
                      <td>Sem 3</td>
                      <td><strong>79.6%</strong></td>
                      <td><span class="grade-badge grade-b">B+</span></td>
                      <td><span class="status-badge pass">Passed</span></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Attendance Overview -->
          <div class="panel">
            <div class="panel-header">
              <h3><i class="fas fa-calendar-alt" style="margin-right:8px;color:var(--accent);"></i> Attendance - Feb 2026</h3>
              <a href="attendance.php" class="btn btn-ghost btn-sm">Full Calendar</a>
            </div>
            <div class="panel-body">
              <div class="attendance-grid">
                <div class="attendance-day-header">Sun</div>
                <div class="attendance-day-header">Mon</div>
                <div class="attendance-day-header">Tue</div>
                <div class="attendance-day-header">Wed</div>
                <div class="attendance-day-header">Thu</div>
                <div class="attendance-day-header">Fri</div>
                <div class="attendance-day-header">Sat</div>

                <div class="attendance-day holiday">1</div>
                <div class="attendance-day present">2</div>
                <div class="attendance-day present">3</div>
                <div class="attendance-day present">4</div>
                <div class="attendance-day present">5</div>
                <div class="attendance-day present">6</div>
                <div class="attendance-day holiday">7</div>

                <div class="attendance-day holiday">8</div>
                <div class="attendance-day present">9</div>
                <div class="attendance-day absent">10</div>
                <div class="attendance-day present">11</div>
                <div class="attendance-day present">12</div>
                <div class="attendance-day present">13</div>
                <div class="attendance-day holiday">14</div>

                <div class="attendance-day holiday">15</div>
                <div class="attendance-day present">16</div>
                <div class="attendance-day present">17</div>
                <div class="attendance-day present">18</div>
                <div class="attendance-day today present">19</div>
                <div class="attendance-day empty">20</div>
                <div class="attendance-day empty">21</div>

                <div class="attendance-day empty">22</div>
                <div class="attendance-day empty">23</div>
                <div class="attendance-day empty">24</div>
                <div class="attendance-day empty">25</div>
                <div class="attendance-day empty">26</div>
                <div class="attendance-day empty">27</div>
                <div class="attendance-day empty">28</div>
              </div>

              <div class="attendance-legend">
                <div class="legend-item">
                  <div class="legend-dot present"></div>
                  <span>Present</span>
                </div>
                <div class="legend-item">
                  <div class="legend-dot absent"></div>
                  <span>Absent</span>
                </div>
                <div class="legend-item">
                  <div class="legend-dot holiday"></div>
                  <span>Holiday</span>
                </div>
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
