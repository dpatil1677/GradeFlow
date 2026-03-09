<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Student - GradeFlow</title>
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
          <a href="add-student.php" class="sidebar-link active"><span class="icon"><i class="fas fa-user-plus"></i></span> Add Student</a>
          <a href="manage-students.php" class="sidebar-link"><span class="icon"><i class="fas fa-users"></i></span> Manage Students</a>
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
          <div class="topbar-title"><h2>Add New Student</h2><p>Register a new student in the system</p></div>
        </div>
        <div class="topbar-right">
          <a href="manage-students.php" class="btn btn-ghost btn-sm"><i class="fas fa-list"></i> View All Students</a>
        </div>
      </header>

      <div class="dashboard-content">
        <div class="panel animate-fade-up">
          <div class="panel-header">
            <h3><i class="fas fa-user-plus" style="margin-right:8px;color:var(--primary-light);"></i> Student Registration Form</h3>
          </div>
          <div class="panel-body">
            <form>
              <!-- Personal Information -->
              <h4 style="font-size:1rem;margin-bottom:20px;color:var(--primary-light);display:flex;align-items:center;gap:8px;">
                <i class="fas fa-user"></i> Personal Information
              </h4>

              <div class="form-row">
                <div class="form-group">
                  <label class="form-label">First Name *</label>
                  <input type="text" class="form-input form-input-plain" placeholder="Enter first name" required>
                </div>
                <div class="form-group">
                  <label class="form-label">Last Name *</label>
                  <input type="text" class="form-input form-input-plain" placeholder="Enter last name" required>
                </div>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label class="form-label">Date of Birth *</label>
                  <input type="date" class="form-input form-input-plain" required>
                </div>
                <div class="form-group">
                  <label class="form-label">Gender *</label>
                  <select class="form-select" required>
                    <option value="">Select Gender</option>
                    <option>Male</option>
                    <option>Female</option>
                    <option>Other</option>
                  </select>
                </div>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label class="form-label">Email Address *</label>
                  <input type="email" class="form-input form-input-plain" placeholder="student@email.com" required>
                </div>
                <div class="form-group">
                  <label class="form-label">Phone Number *</label>
                  <input type="tel" class="form-input form-input-plain" placeholder="+91 98765 43210" required>
                </div>
              </div>

              <div class="form-group">
                <label class="form-label">Address</label>
                <textarea class="form-input form-input-plain" rows="3" placeholder="Enter complete address" style="resize: vertical; padding-top: 14px;"></textarea>
              </div>

              <div style="height:1px;background:var(--border-color);margin:32px 0;"></div>

              <!-- Academic Information -->
              <h4 style="font-size:1rem;margin-bottom:20px;color:var(--secondary);display:flex;align-items:center;gap:8px;">
                <i class="fas fa-graduation-cap"></i> Academic Information
              </h4>

              <div class="form-row">
                <div class="form-group">
                  <label class="form-label">Roll Number *</label>
                  <input type="text" class="form-input form-input-plain" placeholder="e.g., CS2025050" required>
                </div>
                <div class="form-group">
                  <label class="form-label">Registration Number</label>
                  <input type="text" class="form-input form-input-plain" placeholder="University Registration No.">
                </div>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label class="form-label">Course / Program *</label>
                  <select class="form-select" required>
                    <option value="">Select Course</option>
                    <option>BSc Computer Science</option>
                    <option>BSc Electronics</option>
                    <option>BSc Mechanical Engineering</option>
                    <option>BSc Civil Engineering</option>
                    <option>BBA</option>
                    <option>BCA</option>
                    <option>MSc Computer Science</option>
                  </select>
                </div>
                <div class="form-group">
                  <label class="form-label">Current Semester *</label>
                  <select class="form-select" required>
                    <option value="">Select Semester</option>
                    <option>Semester 1</option>
                    <option>Semester 2</option>
                    <option>Semester 3</option>
                    <option>Semester 4</option>
                    <option>Semester 5</option>
                    <option>Semester 6</option>
                    <option>Semester 7</option>
                    <option>Semester 8</option>
                  </select>
                </div>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label class="form-label">Section</label>
                  <select class="form-select">
                    <option value="">Select Section</option>
                    <option>Section A</option>
                    <option>Section B</option>
                    <option>Section C</option>
                  </select>
                </div>
                <div class="form-group">
                  <label class="form-label">Admission Year *</label>
                  <select class="form-select" required>
                    <option value="">Select Year</option>
                    <option>2026</option>
                    <option>2025</option>
                    <option>2024</option>
                    <option>2023</option>
                    <option>2022</option>
                  </select>
                </div>
              </div>

              <div style="height:1px;background:var(--border-color);margin:32px 0;"></div>

              <!-- Guardian Information -->
              <h4 style="font-size:1rem;margin-bottom:20px;color:var(--accent);display:flex;align-items:center;gap:8px;">
                <i class="fas fa-users"></i> Guardian Information
              </h4>

              <div class="form-row">
                <div class="form-group">
                  <label class="form-label">Father's Name</label>
                  <input type="text" class="form-input form-input-plain" placeholder="Enter father's name">
                </div>
                <div class="form-group">
                  <label class="form-label">Mother's Name</label>
                  <input type="text" class="form-input form-input-plain" placeholder="Enter mother's name">
                </div>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label class="form-label">Guardian Contact</label>
                  <input type="tel" class="form-input form-input-plain" placeholder="Guardian phone number">
                </div>
                <div class="form-group">
                  <label class="form-label">Guardian Email</label>
                  <input type="email" class="form-input form-input-plain" placeholder="guardian@email.com">
                </div>
              </div>

              <div style="height:1px;background:var(--border-color);margin:32px 0;"></div>

              <!-- Login Credentials -->
              <h4 style="font-size:1rem;margin-bottom:20px;color:var(--success);display:flex;align-items:center;gap:8px;">
                <i class="fas fa-key"></i> Login Credentials
              </h4>

              <div class="form-row">
                <div class="form-group">
                  <label class="form-label">Password *</label>
                  <input type="password" class="form-input form-input-plain" placeholder="Set initial password" required>
                </div>
                <div class="form-group">
                  <label class="form-label">Confirm Password *</label>
                  <input type="password" class="form-input form-input-plain" placeholder="Confirm password" required>
                </div>
              </div>

              <div class="alert alert-info" style="margin-top:8px;">
                <i class="fas fa-info-circle"></i>
                <span>Student will use their Roll Number as username and the password set here to login.</span>
              </div>

              <!-- Submit Buttons -->
              <div style="display:flex;gap:16px;margin-top:32px;justify-content:flex-end;">
                <button type="reset" class="btn btn-ghost"><i class="fas fa-redo-alt"></i> Reset Form</button>
                <button type="submit" class="btn btn-success btn-lg"><i class="fas fa-user-plus"></i> Register Student</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="js/app.js"></script>
</body>
</html>
