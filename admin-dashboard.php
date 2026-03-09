<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - GradeFlow</title>
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
        <div class="logo-icon">🛡️</div>
        <div class="brand-text">
          GradeFlow
          <small>Admin Panel</small>
        </div>
      </div>

      <nav class="sidebar-nav">
        <div class="sidebar-nav-group">
          <div class="sidebar-nav-label">Overview</div>
          <a href="admin-dashboard.php" class="sidebar-link active">
            <span class="icon"><i class="fas fa-th-large"></i></span> Dashboard
          </a>
        </div>

        <div class="sidebar-nav-group">
          <div class="sidebar-nav-label">Student Management</div>
          <a href="add-student.php" class="sidebar-link">
            <span class="icon"><i class="fas fa-user-plus"></i></span> Add Student
          </a>
          <a href="manage-students.php" class="sidebar-link">
            <span class="icon"><i class="fas fa-users"></i></span> Manage Students
            <span class="badge">523</span>
          </a>
        </div>

        <div class="sidebar-nav-group">
          <div class="sidebar-nav-label">Academics</div>
          <a href="add-marks.php" class="sidebar-link">
            <span class="icon"><i class="fas fa-pen-alt"></i></span> Add Marks
          </a>
          <a href="manage-attendance.php" class="sidebar-link">
            <span class="icon"><i class="fas fa-clipboard-check"></i></span> Attendance
          </a>
          <a href="#" class="sidebar-link">
            <span class="icon"><i class="fas fa-poll"></i></span> Results
            <span class="badge">New</span>
          </a>
        </div>

        <div class="sidebar-nav-group">
          <div class="sidebar-nav-label">Reports</div>
          <a href="#" class="sidebar-link">
            <span class="icon"><i class="fas fa-chart-pie"></i></span> Analytics
          </a>
          <a href="#" class="sidebar-link">
            <span class="icon"><i class="fas fa-file-export"></i></span> Export Data
          </a>
        </div>

        <div class="sidebar-nav-group">
          <div class="sidebar-nav-label">System</div>
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
          <div class="sidebar-user-avatar" style="background:var(--gradient-accent);">AD</div>
          <div class="sidebar-user-info">
            <div class="name">Dr. Admin</div>
            <div class="role">Super Administrator</div>
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
            <h2>Admin Dashboard</h2>
            <p>System overview &amp; management</p>
          </div>
        </div>
        <div class="topbar-right">
          <div class="topbar-search">
            <span class="search-icon"><i class="fas fa-search"></i></span>
            <input type="text" placeholder="Search students, results...">
          </div>
          <button class="topbar-icon-btn">
            <i class="fas fa-bell"></i>
            <span class="notification-dot"></span>
          </button>
          <button class="topbar-icon-btn">
            <i class="fas fa-moon"></i>
          </button>
        </div>
      </header>

      <!-- Dashboard Content -->
      <div class="dashboard-content">

        <!-- Alert -->
        <div class="alert alert-info animate-fade-down">
          <i class="fas fa-info-circle"></i>
          <span><strong>Semester 6 results</strong> are ready for review. <a href="#" style="color:var(--info);text-decoration:underline;">Publish Now →</a></span>
        </div>

        <!-- Stat Cards -->
        <div class="stats-grid">
          <div class="stat-card animate-fade-up delay-1">
            <div class="stat-card-header">
              <div class="stat-icon primary"><i class="fas fa-user-graduate"></i></div>
              <span class="stat-trend up"><i class="fas fa-arrow-up"></i> 12%</span>
            </div>
            <div class="stat-value">5,234</div>
            <div class="stat-label">Total Students</div>
          </div>

          <div class="stat-card animate-fade-up delay-2">
            <div class="stat-card-header">
              <div class="stat-icon secondary"><i class="fas fa-check-double"></i></div>
              <span class="stat-trend up"><i class="fas fa-arrow-up"></i> 3.1%</span>
            </div>
            <div class="stat-value">98.2%</div>
            <div class="stat-label">Overall Pass Rate</div>
          </div>

          <div class="stat-card animate-fade-up delay-3">
            <div class="stat-card-header">
              <div class="stat-icon accent"><i class="fas fa-file-alt"></i></div>
            </div>
            <div class="stat-value">1,847</div>
            <div class="stat-label">Results Published</div>
          </div>

          <div class="stat-card animate-fade-up delay-4">
            <div class="stat-card-header">
              <div class="stat-icon success"><i class="fas fa-calendar-day"></i></div>
              <span class="stat-trend down"><i class="fas fa-arrow-down"></i> 1.2%</span>
            </div>
            <div class="stat-value">89.7%</div>
            <div class="stat-label">Avg Attendance</div>
          </div>
        </div>

        <!-- Main Grid -->
        <div class="dashboard-grid">
          <!-- Recent Students Table -->
          <div class="panel">
            <div class="panel-header">
              <h3><i class="fas fa-users" style="margin-right:8px;color:var(--primary-light);"></i> Recent Students</h3>
              <div class="panel-actions">
                <a href="manage-students.php" class="btn btn-ghost btn-sm">View All</a>
                <a href="add-student.php" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add</a>
              </div>
            </div>
            <div class="panel-body" style="padding:0;">
              <div class="data-table-wrapper">
                <table class="data-table">
                  <thead>
                    <tr>
                      <th>Student</th>
                      <th>Roll No</th>
                      <th>Course</th>
                      <th>Sem</th>
                      <th>Status</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <div class="table-user">
                          <div class="table-avatar" style="background:var(--gradient-primary);">AK</div>
                          <div class="table-user-info">
                            <div class="name">Ananya Kumari</div>
                            <div class="email">ananya@GradeFlow.edu</div>
                          </div>
                        </div>
                      </td>
                      <td>CS2025001</td>
                      <td>BSc CS</td>
                      <td>6</td>
                      <td><span class="status-badge active">Active</span></td>
                      <td>
                        <div class="table-actions">
                          <button class="table-action-btn view"><i class="fas fa-eye"></i></button>
                          <button class="table-action-btn edit"><i class="fas fa-edit"></i></button>
                          <button class="table-action-btn delete"><i class="fas fa-trash-alt"></i></button>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <div class="table-user">
                          <div class="table-avatar" style="background:var(--gradient-secondary);">RS</div>
                          <div class="table-user-info">
                            <div class="name">Rahul Sharma</div>
                            <div class="email">rahul@GradeFlow.edu</div>
                          </div>
                        </div>
                      </td>
                      <td>CS2025002</td>
                      <td>BSc CS</td>
                      <td>6</td>
                      <td><span class="status-badge active">Active</span></td>
                      <td>
                        <div class="table-actions">
                          <button class="table-action-btn view"><i class="fas fa-eye"></i></button>
                          <button class="table-action-btn edit"><i class="fas fa-edit"></i></button>
                          <button class="table-action-btn delete"><i class="fas fa-trash-alt"></i></button>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <div class="table-user">
                          <div class="table-avatar" style="background:var(--gradient-accent);">PG</div>
                          <div class="table-user-info">
                            <div class="name">Priya Gupta</div>
                            <div class="email">priya@GradeFlow.edu</div>
                          </div>
                        </div>
                      </td>
                      <td>ME2025010</td>
                      <td>BSc Mech</td>
                      <td>4</td>
                      <td><span class="status-badge active">Active</span></td>
                      <td>
                        <div class="table-actions">
                          <button class="table-action-btn view"><i class="fas fa-eye"></i></button>
                          <button class="table-action-btn edit"><i class="fas fa-edit"></i></button>
                          <button class="table-action-btn delete"><i class="fas fa-trash-alt"></i></button>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <div class="table-user">
                          <div class="table-avatar" style="background:linear-gradient(135deg,#fdcb6e,#e17055);">VP</div>
                          <div class="table-user-info">
                            <div class="name">Vikash Patel</div>
                            <div class="email">vikash@GradeFlow.edu</div>
                          </div>
                        </div>
                      </td>
                      <td>EC2025008</td>
                      <td>BSc ECE</td>
                      <td>2</td>
                      <td><span class="status-badge active">Active</span></td>
                      <td>
                        <div class="table-actions">
                          <button class="table-action-btn view"><i class="fas fa-eye"></i></button>
                          <button class="table-action-btn edit"><i class="fas fa-edit"></i></button>
                          <button class="table-action-btn delete"><i class="fas fa-trash-alt"></i></button>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Quick Actions & Activity -->
          <div style="display:flex;flex-direction:column;gap:24px;">
            <!-- Quick Actions -->
            <div class="panel">
              <div class="panel-header">
                <h3><i class="fas fa-bolt" style="margin-right:8px;color:var(--warning);"></i> Quick Actions</h3>
              </div>
              <div class="panel-body">
                <div class="quick-actions-grid">
                  <a href="add-student.php" class="quick-action-btn">
                    <div class="icon" style="background:rgba(108,92,231,0.15);color:var(--primary-light);"><i class="fas fa-user-plus"></i></div>
                    <span>Add Student</span>
                  </a>
                  <a href="add-marks.php" class="quick-action-btn">
                    <div class="icon" style="background:rgba(0,206,201,0.15);color:var(--secondary);"><i class="fas fa-pen-alt"></i></div>
                    <span>Enter Marks</span>
                  </a>
                  <a href="manage-attendance.php" class="quick-action-btn">
                    <div class="icon" style="background:rgba(253,121,168,0.15);color:var(--accent);"><i class="fas fa-clipboard-check"></i></div>
                    <span>Attendance</span>
                  </a>
                  <a href="#" class="quick-action-btn">
                    <div class="icon" style="background:rgba(0,184,148,0.15);color:var(--success);"><i class="fas fa-file-export"></i></div>
                    <span>Export Data</span>
                  </a>
                </div>
              </div>
            </div>

            <!-- Activity Feed -->
            <div class="panel" style="flex:1;">
              <div class="panel-header">
                <h3><i class="fas fa-history" style="margin-right:8px;color:var(--secondary);"></i> Recent Activity</h3>
              </div>
              <div class="panel-body">
                <div class="activity-feed">
                  <div class="activity-item">
                    <div class="activity-icon" style="background:rgba(0,184,148,0.15);color:var(--success);">
                      <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="activity-content">
                      <p><strong>Semester 5 results</strong> published for BSc CS batch 2023</p>
                      <div class="activity-time">2 hours ago</div>
                    </div>
                  </div>
                  <div class="activity-item">
                    <div class="activity-icon" style="background:rgba(108,92,231,0.15);color:var(--primary-light);">
                      <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="activity-content">
                      <p><strong>42 new students</strong> enrolled in BSc CS program</p>
                      <div class="activity-time">5 hours ago</div>
                    </div>
                  </div>
                  <div class="activity-item">
                    <div class="activity-icon" style="background:rgba(253,121,168,0.15);color:var(--accent);">
                      <i class="fas fa-pen-alt"></i>
                    </div>
                    <div class="activity-content">
                      <p><strong>Midterm marks</strong> entered for Sem 6 DBMS subject</p>
                      <div class="activity-time">1 day ago</div>
                    </div>
                  </div>
                  <div class="activity-item">
                    <div class="activity-icon" style="background:rgba(253,203,110,0.15);color:var(--warning);">
                      <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="activity-content">
                      <p><strong>15 students</strong> below minimum attendance in Sem 4</p>
                      <div class="activity-time">2 days ago</div>
                    </div>
                  </div>
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
