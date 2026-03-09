<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Attendance - GradeFlow</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>

  <div class="dashboard-layout">
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
      <div class="sidebar-header">
        <div class="logo-icon">🎓</div>
        <div class="brand-text">GradeFlow<small>Student Portal</small></div>
      </div>
      <nav class="sidebar-nav">
        <div class="sidebar-nav-group">
          <div class="sidebar-nav-label">Main</div>
          <a href="student-dashboard.php" class="sidebar-link"><span class="icon"><i class="fas fa-th-large"></i></span> Dashboard</a>
          <a href="view-result.php" class="sidebar-link"><span class="icon"><i class="fas fa-poll"></i></span> My Results</a>
          <a href="attendance.php" class="sidebar-link active"><span class="icon"><i class="fas fa-calendar-check"></i></span> Attendance</a>
        </div>
        <div class="sidebar-nav-group">
          <div class="sidebar-nav-label">Academic</div>
          <a href="#" class="sidebar-link"><span class="icon"><i class="fas fa-book-open"></i></span> Subjects</a>
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

    <!-- Main Content -->
    <div class="main-content">
      <header class="topbar">
        <div class="topbar-left">
          <button class="sidebar-toggle" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
          <div class="topbar-title"><h2>Attendance</h2><p>Track your attendance across all subjects</p></div>
        </div>
        <div class="topbar-right">
          <button class="btn btn-ghost btn-sm"><i class="fas fa-download"></i> Download Report</button>
        </div>
      </header>

      <div class="dashboard-content">
        <!-- Attendance Stats -->
        <div class="stats-grid">
          <div class="stat-card animate-fade-up delay-1">
            <div class="stat-card-header">
              <div class="stat-icon success"><i class="fas fa-check-circle"></i></div>
              <span class="stat-trend up"><i class="fas fa-arrow-up"></i> 2%</span>
            </div>
            <div class="stat-value" style="color:var(--success);">92%</div>
            <div class="stat-label">Overall Attendance</div>
          </div>
          <div class="stat-card animate-fade-up delay-2">
            <div class="stat-card-header">
              <div class="stat-icon primary"><i class="fas fa-calendar-check"></i></div>
            </div>
            <div class="stat-value">176</div>
            <div class="stat-label">Classes Attended</div>
          </div>
          <div class="stat-card animate-fade-up delay-3">
            <div class="stat-card-header">
              <div class="stat-icon accent"><i class="fas fa-calendar-times"></i></div>
            </div>
            <div class="stat-value">16</div>
            <div class="stat-label">Classes Missed</div>
          </div>
          <div class="stat-card animate-fade-up delay-4">
            <div class="stat-card-header">
              <div class="stat-icon info"><i class="fas fa-calendar-day"></i></div>
            </div>
            <div class="stat-value">192</div>
            <div class="stat-label">Total Classes</div>
          </div>
        </div>

        <!-- Subject-wise Attendance -->
        <div class="dashboard-grid dashboard-grid-equal">
          <div class="panel animate-fade-up">
            <div class="panel-header">
              <h3><i class="fas fa-book" style="margin-right:8px;color:var(--primary-light);"></i> Subject-wise Attendance</h3>
            </div>
            <div class="panel-body">
              <div class="progress-bar-wrapper">
                <div class="progress-label">
                  <span class="subject">Data Structures & Algorithms</span>
                  <span class="score" style="color:var(--success);">95% (38/40)</span>
                </div>
                <div class="progress-bar"><div class="progress-fill progress-success" style="width:95%;"></div></div>
              </div>
              <div class="progress-bar-wrapper">
                <div class="progress-label">
                  <span class="subject">Database Management Systems</span>
                  <span class="score" style="color:var(--success);">92% (35/38)</span>
                </div>
                <div class="progress-bar"><div class="progress-fill progress-primary" style="width:92%;"></div></div>
              </div>
              <div class="progress-bar-wrapper">
                <div class="progress-label">
                  <span class="subject">Operating Systems</span>
                  <span class="score" style="color:var(--success);">97% (29/30)</span>
                </div>
                <div class="progress-bar"><div class="progress-fill progress-secondary" style="width:97%;"></div></div>
              </div>
              <div class="progress-bar-wrapper">
                <div class="progress-label">
                  <span class="subject">Computer Networks</span>
                  <span class="score" style="color:var(--warning);">78% (28/36)</span>
                </div>
                <div class="progress-bar"><div class="progress-fill progress-accent" style="width:78%;"></div></div>
              </div>
              <div class="progress-bar-wrapper">
                <div class="progress-label">
                  <span class="subject">Software Engineering</span>
                  <span class="score" style="color:var(--success);">94% (30/32)</span>
                </div>
                <div class="progress-bar"><div class="progress-fill progress-info" style="width:94%;"></div></div>
              </div>
              <div class="progress-bar-wrapper" style="margin-bottom:0;">
                <div class="progress-label">
                  <span class="subject">Mathematics IV</span>
                  <span class="score" style="color:var(--success);">89% (16/18)</span>
                </div>
                <div class="progress-bar"><div class="progress-fill progress-primary" style="width:89%;"></div></div>
              </div>

              <!-- Warning for low attendance -->
              <div class="alert alert-warning" style="margin-top:24px;margin-bottom:0;">
                <i class="fas fa-exclamation-triangle"></i>
                <span><strong>Computer Networks</strong> attendance is below 80%. Minimum 75% is required to appear for exams.</span>
              </div>
            </div>
          </div>

          <!-- Monthly Calendar -->
          <div class="panel animate-fade-up" style="animation-delay:0.1s;">
            <div class="panel-header">
              <h3><i class="fas fa-calendar" style="margin-right:8px;color:var(--accent);"></i> February 2026</h3>
              <div class="panel-actions">
                <button class="btn btn-ghost btn-sm"><i class="fas fa-chevron-left"></i></button>
                <button class="btn btn-ghost btn-sm"><i class="fas fa-chevron-right"></i></button>
              </div>
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
                <div class="attendance-day present">19</div>
                <div class="attendance-day present">20</div>
                <div class="attendance-day holiday">21</div>

                <div class="attendance-day holiday">22</div>
                <div class="attendance-day today present">23</div>
                <div class="attendance-day empty">24</div>
                <div class="attendance-day empty">25</div>
                <div class="attendance-day empty">26</div>
                <div class="attendance-day empty">27</div>
                <div class="attendance-day empty">28</div>
              </div>

              <div class="attendance-legend">
                <div class="legend-item"><div class="legend-dot present"></div><span>Present (14)</span></div>
                <div class="legend-item"><div class="legend-dot absent"></div><span>Absent (1)</span></div>
                <div class="legend-item"><div class="legend-dot holiday"></div><span>Holiday (4)</span></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Detailed Attendance Log -->
        <div class="panel animate-fade-up" style="animation-delay:0.2s; margin-top:24px;">
          <div class="panel-header">
            <h3><i class="fas fa-list-alt" style="margin-right:8px;color:var(--secondary);"></i> Recent Attendance Log</h3>
            <div class="panel-actions">
              <select class="form-select" style="width:auto;padding:8px 30px 8px 12px;font-size:0.85rem;">
                <option>All Subjects</option>
                <option>DSA</option>
                <option>DBMS</option>
                <option>OS</option>
                <option>CN</option>
                <option>SE</option>
                <option>Math</option>
              </select>
            </div>
          </div>
          <div class="panel-body" style="padding:0;">
            <div class="data-table-wrapper">
              <table class="data-table">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th>Day</th>
                    <th>Subject</th>
                    <th>Time</th>
                    <th>Faculty</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td><strong>19 Feb 2026</strong></td>
                    <td>Thursday</td>
                    <td>Data Structures & Algorithms</td>
                    <td>9:00 - 10:00 AM</td>
                    <td>Prof. R. Kumar</td>
                    <td><span class="status-badge pass">Present</span></td>
                  </tr>
                  <tr>
                    <td><strong>19 Feb 2026</strong></td>
                    <td>Thursday</td>
                    <td>Database Management Systems</td>
                    <td>10:00 - 11:00 AM</td>
                    <td>Dr. S. Rao</td>
                    <td><span class="status-badge pass">Present</span></td>
                  </tr>
                  <tr>
                    <td><strong>18 Feb 2026</strong></td>
                    <td>Wednesday</td>
                    <td>Operating Systems</td>
                    <td>11:00 AM - 12:00 PM</td>
                    <td>Prof. A. Singh</td>
                    <td><span class="status-badge pass">Present</span></td>
                  </tr>
                  <tr>
                    <td><strong>18 Feb 2026</strong></td>
                    <td>Wednesday</td>
                    <td>Computer Networks</td>
                    <td>2:00 - 3:00 PM</td>
                    <td>Dr. M. Gupta</td>
                    <td><span class="status-badge pass">Present</span></td>
                  </tr>
                  <tr>
                    <td><strong>17 Feb 2026</strong></td>
                    <td>Tuesday</td>
                    <td>Software Engineering</td>
                    <td>9:00 - 10:00 AM</td>
                    <td>Prof. V. Patel</td>
                    <td><span class="status-badge pass">Present</span></td>
                  </tr>
                  <tr>
                    <td><strong>10 Feb 2026</strong></td>
                    <td>Tuesday</td>
                    <td>Computer Networks</td>
                    <td>2:00 - 3:00 PM</td>
                    <td>Dr. M. Gupta</td>
                    <td><span class="status-badge fail">Absent</span></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="js/app.js"></script>
</body>
</html>
