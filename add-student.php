<?php
require_once 'includes/db_connect.php';
require_once 'includes/auth.php';
requireAdmin();

$admin = getAdminInfo();
$initials = getInitials($admin['full_name']);

$success = '';
$error = '';

// Fetch courses for dropdown
$courses = $pdo->query("SELECT * FROM courses ORDER BY course_name")->fetchAll();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName  = trim($_POST['last_name'] ?? '');
    $dob       = $_POST['dob'] ?? '';
    $gender    = $_POST['gender'] ?? '';
    $email     = trim($_POST['email'] ?? '');
    $phone     = trim($_POST['phone'] ?? '');
    $address   = trim($_POST['address'] ?? '');
    $rollNo    = trim($_POST['roll_number'] ?? '');
    $regNo     = trim($_POST['reg_number'] ?? '');
    $courseId   = intval($_POST['course_id'] ?? 0);
    $semester   = intval($_POST['semester'] ?? 0);
    $section    = $_POST['section'] ?? 'A';
    $admYear    = intval($_POST['admission_year'] ?? 0);
    $fatherName = trim($_POST['father_name'] ?? '');
    $motherName = trim($_POST['mother_name'] ?? '');
    $guardianContact = trim($_POST['guardian_contact'] ?? '');
    $guardianEmail   = trim($_POST['guardian_email'] ?? '');
    $password  = $_POST['password'] ?? '';
    $confirmPw = $_POST['confirm_password'] ?? '';

    // Validation
    if (empty($firstName) || empty($lastName) || empty($rollNo) || empty($email) || $courseId === 0 || $semester === 0 || $admYear === 0 || empty($password)) {
        $error = 'Please fill in all required fields.';
    } elseif ($password !== $confirmPw) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } else {
        // Check if roll number already exists
        $check = $pdo->prepare("SELECT id FROM students WHERE roll_number = ?");
        $check->execute([$rollNo]);
        if ($check->fetch()) {
            $error = 'A student with this roll number already exists.';
        } else {
            $hashedPw = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO students (roll_number, first_name, last_name, email, phone, dob, gender, address, course_id, semester, section, admission_year, father_name, mother_name, guardian_contact, guardian_email, password, status) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,'Active')");
            $stmt->execute([$rollNo, $firstName, $lastName, $email, $phone, $dob ?: null, $gender ?: null, $address, $courseId, $semester, $section, $admYear, $fatherName, $motherName, $guardianContact, $guardianEmail, $hashedPw]);
            
            // Send Email to the student with their credentials
            $to = $email;
            $subject = "Welcome to GradeFlow - Your Account Details";
            
            // Get course name for the email
            $courseName = '';
            foreach ($courses as $c) {
                if ($c['id'] == $courseId) {
                    $courseName = $c['course_name'];
                    break;
                }
            }
            
            $message = <<<HTML
            <html>
            <head>
            <title>Welcome to GradeFlow</title>
            </head>
            <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
            <h2>Welcome to GradeFlow, $firstName $lastName!</h2>
            <p>Your student account has been successfully created by the administrator.</p>
            <p>Here are your account credentials and details:</p>
            <table style="border-collapse: collapse; width: 100%; max-width: 600px; margin-bottom: 20px;">
                <tr><td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>Name:</strong></td><td style="padding: 8px; border-bottom: 1px solid #ddd;">$firstName $lastName</td></tr>
                <tr><td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>Roll Number (Username):</strong></td><td style="padding: 8px; border-bottom: 1px solid #ddd;">$rollNo</td></tr>
                <tr><td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>Email:</strong></td><td style="padding: 8px; border-bottom: 1px solid #ddd;">$email</td></tr>
                <tr><td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>Password:</strong></td><td style="padding: 8px; border-bottom: 1px solid #ddd;">$password</td></tr>
                <tr><td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>Course:</strong></td><td style="padding: 8px; border-bottom: 1px solid #ddd;">$courseName</td></tr>
                <tr><td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>Semester:</strong></td><td style="padding: 8px; border-bottom: 1px solid #ddd;">$semester</td></tr>
                <tr><td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>Section:</strong></td><td style="padding: 8px; border-bottom: 1px solid #ddd;">$section</td></tr>
            </table>
            <p>Please log in using your Roll Number and Password. We strongly recommend changing your password after your first login.</p>
            <p> Using this link <a href="https://gradeflow.gt.tc">GradeFlow</a></p>
            <p>Best Regards,<br><strong>GradeFlow Administrator</strong></p>
            </body>
            </html>
HTML;

            // Use PHPMailer for reliable SMTP sending via Gmail
            require_once 'includes/PHPMailer/src/Exception.php';
            require_once 'includes/PHPMailer/src/PHPMailer.php';
            require_once 'includes/PHPMailer/src/SMTP.php';
            
            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            
            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                
                // IMPORTANT: Replace these two variables with your completely real Gmail details
                $mail->Username   = 'dhruvpatil1677@gmail.com'; 
                $mail->Password   = 'edelfiqwsoyhckwc'; // Removed spaces from App Password
                
                $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port       = 465;

                // Recipients
                $mail->setFrom('dhruvpatil1677@gmail.com', 'GradeFlow Administrator'); 
                $mail->addAddress($to);


                // Content
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body    = $message;

                $mail->send();
                $success = "Student $firstName $lastName (Roll: $rollNo) registered successfully! An email with credentials has been sent via Gmail SMTP.";
            } catch (Exception $e) {
                // If it fails, report that the student was saved but mail failed
                $success = "Student $firstName $lastName registered successfully, BUT email could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }
    }
}

$totalStudents = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Student - GradeFlow</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700;800&family=Nunito:wght@400;600;700;800&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="css/style.css?v=2.3">
  <link rel="stylesheet" href="css/dashboard.css?v=2.4">
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
          <a href="manage-students.php" class="sidebar-link"><span class="icon"><i class="fas fa-users"></i></span> Manage Students <span class="badge"><?php echo $totalStudents; ?></span></a>
        </div>
        <div class="sidebar-nav-group">
          <div class="sidebar-nav-label">Academics</div>
          <a href="add-marks.php" class="sidebar-link"><span class="icon"><i class="fas fa-pen-alt"></i></span> Add Marks</a>
          <a href="manage-attendance.php" class="sidebar-link"><span class="icon"><i class="fas fa-clipboard-check"></i></span> Attendance</a>
        </div>
        <div class="sidebar-nav-group">
          <div class="sidebar-nav-label">System</div>
          <a href="logout.php" class="sidebar-link"><span class="icon"><i class="fas fa-sign-out-alt"></i></span> Logout</a>
        </div>
      </nav>
      <div class="sidebar-footer">
        <div class="sidebar-user">
          <div class="sidebar-user-avatar" style="background:var(--gradient-accent);"><?php echo $initials; ?></div>
          <div class="sidebar-user-info"><div class="name"><?php echo htmlspecialchars($admin['full_name']); ?></div><div class="role">Super Administrator</div></div>
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

        <?php if ($success): ?>
        <div class="alert alert-success animate-fade-down" style="margin-bottom:20px;">
          <i class="fas fa-check-circle"></i>
          <span><?php echo htmlspecialchars($success); ?></span>
        </div>
        <?php endif; ?>

        <?php if ($error): ?>
        <div class="alert alert-danger animate-fade-down" style="margin-bottom:20px;">
          <i class="fas fa-exclamation-circle"></i>
          <span><?php echo htmlspecialchars($error); ?></span>
        </div>
        <?php endif; ?>

        <div class="panel animate-fade-up">
          <div class="panel-header">
            <h3><i class="fas fa-user-plus" style="margin-right:8px;color:var(--primary-light);"></i> Student Registration Form</h3>
          </div>
          <div class="panel-body">
            <form method="POST" action="">
              <!-- Personal Information -->
              <h4 style="font-size:1rem;margin-bottom:20px;color:var(--primary-light);display:flex;align-items:center;gap:8px;">
                <i class="fas fa-user"></i> Personal Information
              </h4>

              <div class="form-row">
                <div class="form-group">
                  <label class="form-label">First Name *</label>
                  <input type="text" name="first_name" class="form-input form-input-plain" placeholder="Enter first name" required value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>">
                </div>
                <div class="form-group">
                  <label class="form-label">Last Name *</label>
                  <input type="text" name="last_name" class="form-input form-input-plain" placeholder="Enter last name" required value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>">
                </div>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label class="form-label">Date of Birth</label>
                  <input type="date" name="dob" class="form-input form-input-plain" value="<?php echo htmlspecialchars($_POST['dob'] ?? ''); ?>">
                </div>
                <div class="form-group">
                  <label class="form-label">Gender</label>
                  <select name="gender" class="form-select">
                    <option value="">Select Gender</option>
                    <option <?php echo ($_POST['gender'] ?? '') === 'Male' ? 'selected' : ''; ?>>Male</option>
                    <option <?php echo ($_POST['gender'] ?? '') === 'Female' ? 'selected' : ''; ?>>Female</option>
                    <option <?php echo ($_POST['gender'] ?? '') === 'Other' ? 'selected' : ''; ?>>Other</option>
                  </select>
                </div>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label class="form-label">Email Address *</label>
                  <input type="email" name="email" class="form-input form-input-plain" placeholder="student@email.com" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                </div>
                <div class="form-group">
                  <label class="form-label">Phone Number</label>
                  <input type="tel" name="phone" class="form-input form-input-plain" placeholder="+91 98765 43210" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                </div>
              </div>

              <div class="form-group">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-input form-input-plain" rows="3" placeholder="Enter complete address" style="resize: vertical; padding-top: 14px;"><?php echo htmlspecialchars($_POST['address'] ?? ''); ?></textarea>
              </div>

              <div style="height:1px;background:var(--border-color);margin:32px 0;"></div>

              <!-- Academic Information -->
              <h4 style="font-size:1rem;margin-bottom:20px;color:var(--secondary);display:flex;align-items:center;gap:8px;">
                <i class="fas fa-graduation-cap"></i> Academic Information
              </h4>

              <div class="form-row">
                <div class="form-group">
                  <label class="form-label">Roll Number *</label>
                  <input type="text" name="roll_number" class="form-input form-input-plain" placeholder="e.g., CS2025050" required value="<?php echo htmlspecialchars($_POST['roll_number'] ?? ''); ?>">
                </div>
                <div class="form-group">
                  <label class="form-label">Registration Number</label>
                  <input type="text" name="reg_number" class="form-input form-input-plain" placeholder="University Registration No." value="<?php echo htmlspecialchars($_POST['reg_number'] ?? ''); ?>">
                </div>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label class="form-label">Course / Program *</label>
                  <select name="course_id" class="form-select" required>
                    <option value="">Select Course</option>
                    <?php foreach ($courses as $c): ?>
                    <option value="<?php echo $c['id']; ?>" <?php echo (intval($_POST['course_id'] ?? 0) === $c['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($c['course_name']); ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="form-group">
                  <label class="form-label">Current Semester *</label>
                  <select name="semester" class="form-select" required>
                    <option value="">Select Semester</option>
                    <?php for ($s = 1; $s <= 8; $s++): ?>
                    <option value="<?php echo $s; ?>" <?php echo (intval($_POST['semester'] ?? 0) === $s) ? 'selected' : ''; ?>>Semester <?php echo $s; ?></option>
                    <?php endfor; ?>
                  </select>
                </div>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label class="form-label">Section</label>
                  <select name="section" class="form-select">
                    <option value="">Select Section</option>
                    <?php foreach (['A','B','C'] as $sec): ?>
                    <option value="<?php echo $sec; ?>" <?php echo ($_POST['section'] ?? '') === $sec ? 'selected' : ''; ?>>Section <?php echo $sec; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="form-group">
                  <label class="form-label">Admission Year *</label>
                  <select name="admission_year" class="form-select" required>
                    <option value="">Select Year</option>
                    <?php for ($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                    <option value="<?php echo $y; ?>" <?php echo (intval($_POST['admission_year'] ?? 0) === $y) ? 'selected' : ''; ?>><?php echo $y; ?></option>
                    <?php endfor; ?>
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
                  <input type="text" name="father_name" class="form-input form-input-plain" placeholder="Enter father's name" value="<?php echo htmlspecialchars($_POST['father_name'] ?? ''); ?>">
                </div>
                <div class="form-group">
                  <label class="form-label">Mother's Name</label>
                  <input type="text" name="mother_name" class="form-input form-input-plain" placeholder="Enter mother's name" value="<?php echo htmlspecialchars($_POST['mother_name'] ?? ''); ?>">
                </div>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label class="form-label">Guardian Contact</label>
                  <input type="tel" name="guardian_contact" class="form-input form-input-plain" placeholder="Guardian phone number" value="<?php echo htmlspecialchars($_POST['guardian_contact'] ?? ''); ?>">
                </div>
                <div class="form-group">
                  <label class="form-label">Guardian Email</label>
                  <input type="email" name="guardian_email" class="form-input form-input-plain" placeholder="guardian@email.com" value="<?php echo htmlspecialchars($_POST['guardian_email'] ?? ''); ?>">
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
                  <input type="password" name="password" class="form-input form-input-plain" placeholder="Set initial password" required>
                </div>
                <div class="form-group">
                  <label class="form-label">Confirm Password *</label>
                  <input type="password" name="confirm_password" class="form-input form-input-plain" placeholder="Confirm password" required>
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

  <script src="js/app.js?v=2.3"></script>
</body>
</html>
