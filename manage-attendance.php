<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Attendance - GradeFlow Admin</title>
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
          <a href="manage-students.php" class="sidebar-link"><span class="icon"><i class="fas fa-users"></i></span> Manage Students</a>
        </div>
        <div class="sidebar-nav-group">
          <div class="sidebar-nav-label">Academics</div>
          <a href="add-marks.php" class="sidebar-link"><span class="icon"><i class="fas fa-pen-alt"></i></span> Add Marks</a>
          <a href="manage-attendance.php" class="sidebar-link active"><span class="icon"><i class="fas fa-clipboard-check"></i></span> Attendance</a>
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
          <div class="topbar-title"><h2>Manage Attendance</h2><p>Mark and manage student attendance</p></div>
        </div>
        <div class="topbar-right">
          <button class="btn btn-ghost btn-sm"><i class="fas fa-file-export"></i> Export Report</button>
          <button class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Save Attendance</button>
        </div>
      </header>

      <div class="dashboard-content">
        <!-- Selection Panel -->
        <div class="panel animate-fade-up" style="margin-bottom:28px;">
          <div class="panel-header">
            <h3><i class="fas fa-filter" style="margin-right:8px;color:var(--primary-light);"></i> Select Class</h3>
          </div>
          <div class="panel-body">
            <div class="form-row" style="grid-template-columns: repeat(4, 1fr);">
              <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Date</label>
                <input type="date" class="form-input form-input-plain" value="2026-02-19">
              </div>
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
                  <option>CS601 - DSA</option>
                  <option>CS602 - DBMS</option>
                  <option>CS603 - Operating Systems</option>
                  <option>CS604 - Computer Networks</option>
                </select>
              </div>
            </div>
            <div style="margin-top:16px;">
              <button class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Load Students</button>
            </div>
          </div>
        </div>

        <!-- Quick stats -->
        <div style="display:flex;gap:16px;margin-bottom:24px;flex-wrap:wrap;">
          <div class="glass-card" style="flex:1;min-width:200px;padding:16px 20px;display:flex;align-items:center;gap:12px;">
            <div class="stat-icon primary" style="width:40px;height:40px;font-size:1rem;"><i class="fas fa-users"></i></div>
            <div><div style="font-size:1.1rem;font-weight:700;">6</div><div style="font-size:0.78rem;color:var(--text-muted);">Total Students</div></div>
          </div>
          <div class="glass-card" style="flex:1;min-width:200px;padding:16px 20px;display:flex;align-items:center;gap:12px;">
            <div class="stat-icon success" style="width:40px;height:40px;font-size:1rem;"><i class="fas fa-check"></i></div>
            <div><div style="font-size:1.1rem;font-weight:700;color:var(--success);">5</div><div style="font-size:0.78rem;color:var(--text-muted);">Present</div></div>
          </div>
          <div class="glass-card" style="flex:1;min-width:200px;padding:16px 20px;display:flex;align-items:center;gap:12px;">
            <div class="stat-icon accent" style="width:40px;height:40px;font-size:1rem;"><i class="fas fa-times"></i></div>
            <div><div style="font-size:1.1rem;font-weight:700;color:var(--danger);">1</div><div style="font-size:0.78rem;color:var(--text-muted);">Absent</div></div>
          </div>
          <div class="glass-card" style="flex:1;min-width:200px;padding:16px 20px;display:flex;align-items:center;gap:12px;">
            <div class="stat-icon secondary" style="width:40px;height:40px;font-size:1rem;"><i class="fas fa-percentage"></i></div>
            <div><div style="font-size:1.1rem;font-weight:700;color:var(--secondary);">83.3%</div><div style="font-size:0.78rem;color:var(--text-muted);">Today's Rate</div></div>
          </div>
        </div>

        <!-- Attendance Marking Table -->
        <div class="panel animate-fade-up" style="animation-delay:0.1s;">
          <div class="panel-header">
            <h3><i class="fas fa-clipboard-check" style="margin-right:8px;color:var(--secondary);"></i> Mark Attendance — CS601 DSA | 19 Feb 2026</h3>
            <div class="panel-actions">
              <button class="btn btn-ghost btn-sm" onclick="markAll('present')"><i class="fas fa-check-double"></i> Mark All Present</button>
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
                    <th>Overall Attendance</th>
                    <th style="text-align:center;">Present</th>
                    <th style="text-align:center;">Absent</th>
                    <th>Remarks</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>1</td>
                    <td><div class="table-user"><div class="table-avatar" style="background:var(--gradient-primary);">AK</div><div class="table-user-info"><div class="name">Ananya Kumari</div></div></div></td>
                    <td style="font-family:var(--font-mono);">CS2025001</td>
                    <td><span style="color:var(--success);font-weight:600;">95%</span></td>
                    <td style="text-align:center;"><input type="radio" name="att1" checked style="accent-color:var(--success);width:20px;height:20px;cursor:pointer;"></td>
                    <td style="text-align:center;"><input type="radio" name="att1" style="accent-color:var(--danger);width:20px;height:20px;cursor:pointer;"></td>
                    <td><input type="text" class="form-input form-input-plain" style="padding:6px 10px;font-size:0.82rem;width:140px;" placeholder="Optional..."></td>
                  </tr>
                  <tr>
                    <td>2</td>
                    <td><div class="table-user"><div class="table-avatar" style="background:var(--gradient-secondary);">RS</div><div class="table-user-info"><div class="name">Rahul Sharma</div></div></div></td>
                    <td style="font-family:var(--font-mono);">CS2025002</td>
                    <td><span style="color:var(--success);font-weight:600;">88%</span></td>
                    <td style="text-align:center;"><input type="radio" name="att2" checked style="accent-color:var(--success);width:20px;height:20px;cursor:pointer;"></td>
                    <td style="text-align:center;"><input type="radio" name="att2" style="accent-color:var(--danger);width:20px;height:20px;cursor:pointer;"></td>
                    <td><input type="text" class="form-input form-input-plain" style="padding:6px 10px;font-size:0.82rem;width:140px;" placeholder="Optional..."></td>
                  </tr>
                  <tr>
                    <td>3</td>
                    <td><div class="table-user"><div class="table-avatar" style="background:var(--gradient-accent);">PG</div><div class="table-user-info"><div class="name">Priya Gupta</div></div></div></td>
                    <td style="font-family:var(--font-mono);">CS2025003</td>
                    <td><span style="color:var(--success);font-weight:600;">92%</span></td>
                    <td style="text-align:center;"><input type="radio" name="att3" checked style="accent-color:var(--success);width:20px;height:20px;cursor:pointer;"></td>
                    <td style="text-align:center;"><input type="radio" name="att3" style="accent-color:var(--danger);width:20px;height:20px;cursor:pointer;"></td>
                    <td><input type="text" class="form-input form-input-plain" style="padding:6px 10px;font-size:0.82rem;width:140px;" placeholder="Optional..."></td>
                  </tr>
                  <tr>
                    <td>4</td>
                    <td><div class="table-user"><div class="table-avatar" style="background:linear-gradient(135deg,#fdcb6e,#e17055);">VP</div><div class="table-user-info"><div class="name">Vikash Patel</div></div></div></td>
                    <td style="font-family:var(--font-mono);">CS2025004</td>
                    <td><span style="color:var(--success);font-weight:600;">91%</span></td>
                    <td style="text-align:center;"><input type="radio" name="att4" checked style="accent-color:var(--success);width:20px;height:20px;cursor:pointer;"></td>
                    <td style="text-align:center;"><input type="radio" name="att4" style="accent-color:var(--danger);width:20px;height:20px;cursor:pointer;"></td>
                    <td><input type="text" class="form-input form-input-plain" style="padding:6px 10px;font-size:0.82rem;width:140px;" placeholder="Optional..."></td>
                  </tr>
                  <tr style="background:rgba(214,48,49,0.05);">
                    <td>5</td>
                    <td><div class="table-user"><div class="table-avatar" style="background:linear-gradient(135deg,#a29bfe,#6c5ce7);">SK</div><div class="table-user-info"><div class="name">Sneha Krishnan</div></div></div></td>
                    <td style="font-family:var(--font-mono);">CS2025005</td>
                    <td><span style="color:var(--danger);font-weight:600;">68%</span></td>
                    <td style="text-align:center;"><input type="radio" name="att5" style="accent-color:var(--success);width:20px;height:20px;cursor:pointer;"></td>
                    <td style="text-align:center;"><input type="radio" name="att5" checked style="accent-color:var(--danger);width:20px;height:20px;cursor:pointer;"></td>
                    <td><input type="text" class="form-input form-input-plain" style="padding:6px 10px;font-size:0.82rem;width:140px;" placeholder="Optional..." value="Medical leave"></td>
                  </tr>
                  <tr>
                    <td>6</td>
                    <td><div class="table-user"><div class="table-avatar" style="background:linear-gradient(135deg,#55efc4,#00b894);">AM</div><div class="table-user-info"><div class="name">Arjun Mehta</div></div></div></td>
                    <td style="font-family:var(--font-mono);">CS2025006</td>
                    <td><span style="color:var(--success);font-weight:600;">95%</span></td>
                    <td style="text-align:center;"><input type="radio" name="att6" checked style="accent-color:var(--success);width:20px;height:20px;cursor:pointer;"></td>
                    <td style="text-align:center;"><input type="radio" name="att6" style="accent-color:var(--danger);width:20px;height:20px;cursor:pointer;"></td>
                    <td><input type="text" class="form-input form-input-plain" style="padding:6px 10px;font-size:0.82rem;width:140px;" placeholder="Optional..."></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div style="padding:20px 24px;border-top:1px solid var(--border-color);display:flex;justify-content:flex-end;gap:12px;">
            <button class="btn btn-ghost"><i class="fas fa-redo"></i> Reset</button>
            <button class="btn btn-success"><i class="fas fa-save"></i> Save Attendance</button>
          </div>
        </div>

        <!-- Low Attendance Warning -->
        <div class="alert alert-danger animate-fade-up" style="margin-top:24px;animation-delay:0.3s;">
          <i class="fas fa-exclamation-circle"></i>
          <span><strong>Sneha Krishnan (CS2025005)</strong> has only 68% attendance in this subject. Below the 75% minimum requirement.</span>
        </div>
      </div>
    </div>
  </div>

  <script src="js/app.js"></script>
</body>
</html>
