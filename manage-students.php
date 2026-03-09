<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Students - GradeFlow Admin</title>
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
        <div class="logo-icon">🛡️</div>
        <div class="brand-text">GradeFlow<small>Admin Panel</small></div>
      </div>
      <nav class="sidebar-nav">
        <div class="sidebar-nav-group">
          <div class="sidebar-nav-label">Overview</div>
          <a href="admin-dashboard.php" class="sidebar-link"><span class="icon"><i class="fas fa-th-large"></i></span> Dashboard</a>
        </div>
        <div class="sidebar-nav-group">
          <div class="sidebar-nav-label">Student Management</div>
          <a href="add-student.php" class="sidebar-link"><span class="icon"><i class="fas fa-user-plus"></i></span> Add Student</a>
          <a href="manage-students.php" class="sidebar-link active"><span class="icon"><i class="fas fa-users"></i></span> Manage Students <span class="badge">523</span></a>
        </div>
        <div class="sidebar-nav-group">
          <div class="sidebar-nav-label">Academics</div>
          <a href="add-marks.php" class="sidebar-link"><span class="icon"><i class="fas fa-pen-alt"></i></span> Add Marks</a>
          <a href="manage-attendance.php" class="sidebar-link"><span class="icon"><i class="fas fa-clipboard-check"></i></span> Attendance</a>
          <a href="#" class="sidebar-link"><span class="icon"><i class="fas fa-poll"></i></span> Results</a>
        </div>
        <div class="sidebar-nav-group">
          <div class="sidebar-nav-label">System</div>
          <a href="#" class="sidebar-link"><span class="icon"><i class="fas fa-cog"></i></span> Settings</a>
          <a href="index.php" class="sidebar-link"><span class="icon"><i class="fas fa-sign-out-alt"></i></span> Logout</a>
        </div>
      </nav>
      <div class="sidebar-footer">
        <div class="sidebar-user">
          <div class="sidebar-user-avatar" style="background:var(--gradient-accent);">AD</div>
          <div class="sidebar-user-info"><div class="name">Dr. Admin</div><div class="role">Super Administrator</div></div>
        </div>
      </div>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
      <header class="topbar">
        <div class="topbar-left">
          <button class="sidebar-toggle" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
          <div class="topbar-title"><h2>Manage Students</h2><p>View, edit and manage all student records</p></div>
        </div>
        <div class="topbar-right">
          <a href="add-student.php" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add Student</a>
        </div>
      </header>

      <div class="dashboard-content">
        <!-- Stats -->
        <div class="stats-grid" style="grid-template-columns: repeat(4, 1fr); margin-bottom: 28px;">
          <div class="stat-card">
            <div class="stat-card-header">
              <div class="stat-icon primary"><i class="fas fa-users"></i></div>
            </div>
            <div class="stat-value">523</div>
            <div class="stat-label">Total Students</div>
          </div>
          <div class="stat-card">
            <div class="stat-card-header">
              <div class="stat-icon success"><i class="fas fa-user-check"></i></div>
            </div>
            <div class="stat-value">498</div>
            <div class="stat-label">Active Students</div>
          </div>
          <div class="stat-card">
            <div class="stat-card-header">
              <div class="stat-icon warning"><i class="fas fa-user-clock"></i></div>
            </div>
            <div class="stat-value">18</div>
            <div class="stat-label">Inactive</div>
          </div>
          <div class="stat-card">
            <div class="stat-card-header">
              <div class="stat-icon accent"><i class="fas fa-user-graduate"></i></div>
            </div>
            <div class="stat-value">7</div>
            <div class="stat-label">Graduated</div>
          </div>
        </div>

        <!-- Filters -->
        <div class="filter-bar">
          <div class="topbar-search" style="flex:1;max-width:320px;">
            <span class="search-icon"><i class="fas fa-search"></i></span>
            <input type="text" placeholder="Search by name or roll number..." style="width:100%;">
          </div>
          <select class="form-select">
            <option>All Courses</option>
            <option>BSc Computer Science</option>
            <option>BSc Electronics</option>
            <option>BSc Mechanical</option>
            <option>BBA</option>
            <option>BCA</option>
          </select>
          <select class="form-select">
            <option>All Semesters</option>
            <option>Semester 1</option>
            <option>Semester 2</option>
            <option>Semester 3</option>
            <option>Semester 4</option>
            <option>Semester 5</option>
            <option>Semester 6</option>
          </select>
          <select class="form-select">
            <option>All Status</option>
            <option>Active</option>
            <option>Inactive</option>
            <option>Graduated</option>
          </select>
          <button class="btn btn-ghost btn-sm"><i class="fas fa-file-export"></i> Export</button>
        </div>

        <!-- Students Table -->
        <div class="panel animate-fade-up">
          <div class="panel-body" style="padding:0;">
            <div class="data-table-wrapper">
              <table class="data-table">
                <thead>
                  <tr>
                    <th><input type="checkbox" style="accent-color:var(--primary);"></th>
                    <th>Student</th>
                    <th>Roll No</th>
                    <th>Course</th>
                    <th>Semester</th>
                    <th>Section</th>
                    <th>Attendance</th>
                    <th>CGPA</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td><input type="checkbox" style="accent-color:var(--primary);"></td>
                    <td>
                      <div class="table-user">
                        <div class="table-avatar" style="background:var(--gradient-primary);">AK</div>
                        <div class="table-user-info"><div class="name">Ananya Kumari</div><div class="email">ananya@GradeFlow.edu</div></div>
                      </div>
                    </td>
                    <td style="font-family:var(--font-mono);">CS2025001</td>
                    <td>BSc CS</td>
                    <td>6</td>
                    <td>A</td>
                    <td><span style="color:var(--success);font-weight:600;">92%</span></td>
                    <td><strong>8.75</strong></td>
                    <td><span class="status-badge active">Active</span></td>
                    <td>
                      <div class="table-actions">
                        <button class="table-action-btn view" title="View"><i class="fas fa-eye"></i></button>
                        <button class="table-action-btn edit" title="Edit"><i class="fas fa-edit"></i></button>
                        <button class="table-action-btn delete" title="Delete"><i class="fas fa-trash-alt"></i></button>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td><input type="checkbox" style="accent-color:var(--primary);"></td>
                    <td>
                      <div class="table-user">
                        <div class="table-avatar" style="background:var(--gradient-secondary);">RS</div>
                        <div class="table-user-info"><div class="name">Rahul Sharma</div><div class="email">rahul@GradeFlow.edu</div></div>
                      </div>
                    </td>
                    <td style="font-family:var(--font-mono);">CS2025002</td>
                    <td>BSc CS</td>
                    <td>6</td>
                    <td>A</td>
                    <td><span style="color:var(--success);font-weight:600;">88%</span></td>
                    <td><strong>8.12</strong></td>
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
                    <td><input type="checkbox" style="accent-color:var(--primary);"></td>
                    <td>
                      <div class="table-user">
                        <div class="table-avatar" style="background:var(--gradient-accent);">PG</div>
                        <div class="table-user-info"><div class="name">Priya Gupta</div><div class="email">priya@GradeFlow.edu</div></div>
                      </div>
                    </td>
                    <td style="font-family:var(--font-mono);">ME2025010</td>
                    <td>BSc Mech</td>
                    <td>4</td>
                    <td>B</td>
                    <td><span style="color:var(--warning);font-weight:600;">76%</span></td>
                    <td><strong>7.65</strong></td>
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
                    <td><input type="checkbox" style="accent-color:var(--primary);"></td>
                    <td>
                      <div class="table-user">
                        <div class="table-avatar" style="background:linear-gradient(135deg,#fdcb6e,#e17055);">VP</div>
                        <div class="table-user-info"><div class="name">Vikash Patel</div><div class="email">vikash@GradeFlow.edu</div></div>
                      </div>
                    </td>
                    <td style="font-family:var(--font-mono);">EC2025008</td>
                    <td>BSc ECE</td>
                    <td>2</td>
                    <td>A</td>
                    <td><span style="color:var(--success);font-weight:600;">91%</span></td>
                    <td><strong>8.40</strong></td>
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
                    <td><input type="checkbox" style="accent-color:var(--primary);"></td>
                    <td>
                      <div class="table-user">
                        <div class="table-avatar" style="background:linear-gradient(135deg,#a29bfe,#6c5ce7);">SK</div>
                        <div class="table-user-info"><div class="name">Sneha Krishnan</div><div class="email">sneha@GradeFlow.edu</div></div>
                      </div>
                    </td>
                    <td style="font-family:var(--font-mono);">CS2024015</td>
                    <td>BSc CS</td>
                    <td>4</td>
                    <td>B</td>
                    <td><span style="color:var(--danger);font-weight:600;">68%</span></td>
                    <td><strong>7.10</strong></td>
                    <td><span class="status-badge pending">Warning</span></td>
                    <td>
                      <div class="table-actions">
                        <button class="table-action-btn view"><i class="fas fa-eye"></i></button>
                        <button class="table-action-btn edit"><i class="fas fa-edit"></i></button>
                        <button class="table-action-btn delete"><i class="fas fa-trash-alt"></i></button>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td><input type="checkbox" style="accent-color:var(--primary);"></td>
                    <td>
                      <div class="table-user">
                        <div class="table-avatar" style="background:linear-gradient(135deg,#55efc4,#00b894);">AM</div>
                        <div class="table-user-info"><div class="name">Arjun Mehta</div><div class="email">arjun@GradeFlow.edu</div></div>
                      </div>
                    </td>
                    <td style="font-family:var(--font-mono);">CS2023042</td>
                    <td>BSc CS</td>
                    <td>6</td>
                    <td>A</td>
                    <td><span style="color:var(--success);font-weight:600;">95%</span></td>
                    <td><strong>9.20</strong></td>
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

          <div class="pagination">
            <div class="pagination-info">Showing 1-6 of 523 students</div>
            <div class="pagination-buttons">
              <button class="pagination-btn"><i class="fas fa-chevron-left"></i></button>
              <button class="pagination-btn active">1</button>
              <button class="pagination-btn">2</button>
              <button class="pagination-btn">3</button>
              <button class="pagination-btn">...</button>
              <button class="pagination-btn">88</button>
              <button class="pagination-btn"><i class="fas fa-chevron-right"></i></button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Delete Confirmation Modal -->
  <div class="modal-overlay" id="deleteModal">
    <div class="modal">
      <div class="modal-header">
        <h3><i class="fas fa-exclamation-triangle" style="color:var(--danger);margin-right:8px;"></i> Confirm Deletion</h3>
        <button class="modal-close" onclick="closeModal('deleteModal')">✕</button>
      </div>
      <div class="modal-body">
        <p style="color:var(--text-secondary);">Are you sure you want to delete this student record? This action cannot be undone and all associated data (marks, attendance, results) will be permanently removed.</p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-ghost" onclick="closeModal('deleteModal')">Cancel</button>
        <button class="btn btn-accent"><i class="fas fa-trash-alt"></i> Delete Student</button>
      </div>
    </div>
  </div>

  <script src="js/app.js"></script>
  <script>
    // Delete buttons open modal
    document.querySelectorAll('.table-action-btn.delete').forEach(btn => {
      btn.addEventListener('click', () => openModal('deleteModal'));
    });
  </script>
</body>
</html>
