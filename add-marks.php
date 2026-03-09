<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Marks - GradeFlow Admin</title>
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
          <a href="add-student.phpl" class="sidebar-link"><span class="icon"><i class="fas fa-user-plus"></i></span> Add Student</a>
          <a href="manage-students.php" class="sidebar-link"><span class="icon"><i class="fas fa-users"></i></span> Manage Students</a>
        </div>
        <div class="sidebar-nav-group">
          <div class="sidebar-nav-label">Academics</div>
          <a href="add-marks.php" class="sidebar-link active"><span class="icon"><i class="fas fa-pen-alt"></i></span> Add Marks</a>
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
          <div class="topbar-title"><h2>Add / Edit Marks</h2><p>Enter examination marks for students</p></div>
        </div>
        <div class="topbar-right">
          <button class="btn btn-ghost btn-sm"><i class="fas fa-file-import"></i> Import CSV</button>
          <button class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Save All</button>
        </div>
      </header>

      <div class="dashboard-content">
        <!-- Selection Filters -->
        <div class="panel animate-fade-up" style="margin-bottom:28px;">
          <div class="panel-header">
            <h3><i class="fas fa-filter" style="margin-right:8px;color:var(--primary-light);"></i> Select Class & Subject</h3>
          </div>
          <div class="panel-body">
            <div class="form-row" style="grid-template-columns: repeat(4, 1fr);">
              <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Course</label>
                <select class="form-select">
                  <option>BSc Computer Science</option>
                  <option>BSc Electronics</option>
                  <option>BSc Mechanical</option>
                </select>
              </div>
              <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Semester</label>
                <select class="form-select">
                  <option>Semester 6</option>
                  <option>Semester 5</option>
                  <option>Semester 4</option>
                </select>
              </div>
              <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Subject</label>
                <select class="form-select">
                  <option>CS601 - Data Structures & Algorithms</option>
                  <option>CS602 - Database Management Systems</option>
                  <option>CS603 - Operating Systems</option>
                  <option>CS604 - Computer Networks</option>
                  <option>CS605 - Software Engineering</option>
                  <option>MA604 - Mathematics IV</option>
                </select>
              </div>
              <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Exam Type</label>
                <select class="form-select">
                  <option>Midterm Examination</option>
                  <option>Final Examination</option>
                  <option>Internal Assessment</option>
                  <option>Practical Exam</option>
                </select>
              </div>
            </div>
            <div style="margin-top:16px;display:flex;gap:12px;">
              <button class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Load Students</button>
              <button class="btn btn-ghost btn-sm"><i class="fas fa-redo"></i> Reset</button>
            </div>
          </div>
        </div>

        <!-- Marks Entry Info -->
        <div class="alert alert-info animate-fade-up" style="animation-delay:0.1s;">
          <i class="fas fa-info-circle"></i>
          <span><strong>CS601 - Data Structures & Algorithms</strong> | BSc Computer Science - Semester 6 | Midterm Examination | Max Internal: 30, Max External: 70</span>
        </div>

        <!-- Marks Entry Table -->
        <div class="panel animate-fade-up" style="animation-delay:0.2s;">
          <div class="panel-header">
            <h3><i class="fas fa-pen-alt" style="margin-right:8px;color:var(--secondary);"></i> Enter Marks</h3>
            <div class="panel-actions">
              <span style="font-size:0.85rem;color:var(--text-muted);">6 students loaded</span>
            </div>
          </div>
          <div class="panel-body" style="padding:0;">
            <div class="data-table-wrapper">
              <table class="data-table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Student</th>
                    <th>Roll No</th>
                    <th>Internal (Max: 30)</th>
                    <th>External (Max: 70)</th>
                    <th>Total</th>
                    <th>Grade</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>1</td>
                    <td>
                      <div class="table-user">
                        <div class="table-avatar" style="background:var(--gradient-primary);">AK</div>
                        <div class="table-user-info"><div class="name">Ananya Kumari</div></div>
                      </div>
                    </td>
                    <td style="font-family:var(--font-mono);">CS2025001</td>
                    <td><input type="number" class="form-input form-input-plain" style="width:80px;padding:8px 12px;text-align:center;" value="28" min="0" max="30"></td>
                    <td><input type="number" class="form-input form-input-plain" style="width:80px;padding:8px 12px;text-align:center;" value="64" min="0" max="70"></td>
                    <td><strong style="color:var(--success);">92</strong></td>
                    <td><span class="grade-badge grade-a">A+</span></td>
                    <td><span class="status-badge pass">Pass</span></td>
                  </tr>
                  <tr>
                    <td>2</td>
                    <td>
                      <div class="table-user">
                        <div class="table-avatar" style="background:var(--gradient-secondary);">RS</div>
                        <div class="table-user-info"><div class="name">Rahul Sharma</div></div>
                      </div>
                    </td>
                    <td style="font-family:var(--font-mono);">CS2025002</td>
                    <td><input type="number" class="form-input form-input-plain" style="width:80px;padding:8px 12px;text-align:center;" value="24" min="0" max="30"></td>
                    <td><input type="number" class="form-input form-input-plain" style="width:80px;padding:8px 12px;text-align:center;" value="58" min="0" max="70"></td>
                    <td><strong style="color:var(--success);">82</strong></td>
                    <td><span class="grade-badge grade-a">A</span></td>
                    <td><span class="status-badge pass">Pass</span></td>
                  </tr>
                  <tr>
                    <td>3</td>
                    <td>
                      <div class="table-user">
                        <div class="table-avatar" style="background:var(--gradient-accent);">PG</div>
                        <div class="table-user-info"><div class="name">Priya Gupta</div></div>
                      </div>
                    </td>
                    <td style="font-family:var(--font-mono);">CS2025003</td>
                    <td><input type="number" class="form-input form-input-plain" style="width:80px;padding:8px 12px;text-align:center;" value="20" min="0" max="30"></td>
                    <td><input type="number" class="form-input form-input-plain" style="width:80px;padding:8px 12px;text-align:center;" value="52" min="0" max="70"></td>
                    <td><strong style="color:var(--success);">72</strong></td>
                    <td><span class="grade-badge grade-b">B+</span></td>
                    <td><span class="status-badge pass">Pass</span></td>
                  </tr>
                  <tr>
                    <td>4</td>
                    <td>
                      <div class="table-user">
                        <div class="table-avatar" style="background:linear-gradient(135deg,#fdcb6e,#e17055);">VP</div>
                        <div class="table-user-info"><div class="name">Vikash Patel</div></div>
                      </div>
                    </td>
                    <td style="font-family:var(--font-mono);">CS2025004</td>
                    <td><input type="number" class="form-input form-input-plain" style="width:80px;padding:8px 12px;text-align:center;" value="18" min="0" max="30"></td>
                    <td><input type="number" class="form-input form-input-plain" style="width:80px;padding:8px 12px;text-align:center;" value="47" min="0" max="70"></td>
                    <td><strong style="color:var(--success);">65</strong></td>
                    <td><span class="grade-badge grade-b">B</span></td>
                    <td><span class="status-badge pass">Pass</span></td>
                  </tr>
                  <tr>
                    <td>5</td>
                    <td>
                      <div class="table-user">
                        <div class="table-avatar" style="background:linear-gradient(135deg,#a29bfe,#6c5ce7);">SK</div>
                        <div class="table-user-info"><div class="name">Sneha Krishnan</div></div>
                      </div>
                    </td>
                    <td style="font-family:var(--font-mono);">CS2025005</td>
                    <td><input type="number" class="form-input form-input-plain" style="width:80px;padding:8px 12px;text-align:center;" value="10" min="0" max="30"></td>
                    <td><input type="number" class="form-input form-input-plain" style="width:80px;padding:8px 12px;text-align:center;" value="22" min="0" max="70"></td>
                    <td><strong style="color:var(--danger);">32</strong></td>
                    <td><span class="grade-badge grade-f">F</span></td>
                    <td><span class="status-badge fail">Fail</span></td>
                  </tr>
                  <tr>
                    <td>6</td>
                    <td>
                      <div class="table-user">
                        <div class="table-avatar" style="background:linear-gradient(135deg,#55efc4,#00b894);">AM</div>
                        <div class="table-user-info"><div class="name">Arjun Mehta</div></div>
                      </div>
                    </td>
                    <td style="font-family:var(--font-mono);">CS2025006</td>
                    <td><input type="number" class="form-input form-input-plain" style="width:80px;padding:8px 12px;text-align:center;" value="29" min="0" max="30"></td>
                    <td><input type="number" class="form-input form-input-plain" style="width:80px;padding:8px 12px;text-align:center;" value="67" min="0" max="70"></td>
                    <td><strong style="color:var(--success);">96</strong></td>
                    <td><span class="grade-badge grade-a">A+</span></td>
                    <td><span class="status-badge pass">Pass</span></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Save Actions -->
          <div style="padding:20px 24px;border-top:1px solid var(--border-color);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;">
            <div style="display:flex;gap:24px;font-size:0.88rem;">
              <span>Total Students: <strong>6</strong></span>
              <span>Passed: <strong style="color:var(--success);">5</strong></span>
              <span>Failed: <strong style="color:var(--danger);">1</strong></span>
              <span>Class Average: <strong>73.17</strong></span>
            </div>
            <div style="display:flex;gap:12px;">
              <button class="btn btn-ghost"><i class="fas fa-save"></i> Save as Draft</button>
              <button class="btn btn-success"><i class="fas fa-check-circle"></i> Submit Marks</button>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <script src="js/app.js"></script>
</body>
</html>
