<?php
require_once 'includes/db_connect.php';
require_once 'includes/auth.php';
requireStudent();

$student = getStudentInfo();
$stuInitials = getInitials($student['full_name']);
$studentId = $student['id'];

// Full student data with course info
$stuData = $pdo->prepare("SELECT s.*, c.course_name, c.short_name FROM students s JOIN courses c ON s.course_id=c.id WHERE s.id=?");
$stuData->execute([$studentId]);
$stuData = $stuData->fetch();

$semester = $stuData['semester'];
$courseId = $stuData['course_id'];

// Get academic stats
$marksStmt = $pdo->prepare("
    SELECT m.*, sub.subject_name, sub.subject_code, sub.max_internal, sub.max_external 
    FROM marks m 
    JOIN subjects sub ON m.subject_id=sub.id 
    WHERE m.student_id=? AND sub.semester=? AND sub.course_id=?
    ORDER BY sub.subject_code
");
$marksStmt->execute([$studentId, $semester, $courseId]);
$marks = $marksStmt->fetchAll();

$totalMarksObtained = 0;
$totalMaxMarks = 0;
$subjectCount = 0;
foreach ($marks as $m) {
    $totalMarksObtained += ($m['internal_marks'] + $m['external_marks']);
    $totalMaxMarks += ($m['max_internal'] + $m['max_external']);
    $subjectCount++;
}
$overallPercentage = $totalMaxMarks > 0 ? round($totalMarksObtained * 100 / $totalMaxMarks, 1) : 0;
$cgpa = round($overallPercentage / 9.5, 2);

// Overall attendance
$attStmt = $pdo->prepare("SELECT COUNT(*) as total, SUM(status='Present') as present FROM attendance WHERE student_id=?");
$attStmt->execute([$studentId]);
$attData = $attStmt->fetch();
$attendanceRate = ($attData['total'] > 0) ? round($attData['present'] * 100 / $attData['total'], 1) : 0;

// Total subjects in current semester
$subjectsStmt = $pdo->prepare("SELECT COUNT(*) FROM subjects WHERE course_id=? AND semester=?");
$subjectsStmt->execute([$courseId, $semester]);
$totalSubjects = $subjectsStmt->fetchColumn();

// Format DOB
$dobFormatted = $stuData['dob'] ? date('d M Y', strtotime($stuData['dob'])) : 'Not set';
$age = $stuData['dob'] ? (new DateTime($stuData['dob']))->diff(new DateTime())->y : '—';

// Admission info
$admissionYear = $stuData['admission_year'] ?? '—';

// Account age
$createdDate = $stuData['created_at'] ? date('d M Y', strtotime($stuData['created_at'])) : '—';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Profile - GradeFlow</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700;800&family=Nunito:wght@400;600;700;800&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="css/style.css?v=2.3">
  <link rel="stylesheet" href="css/dashboard.css?v=2.4">
  <style>
    /* ===== Profile Page Styles ===== */
    .profile-hero {
      position: relative;
      border-radius: var(--radius-lg);
      overflow: hidden;
      margin-bottom: 28px;
    }

    .profile-banner {
      height: 180px;
      background: var(--gradient-primary);
      position: relative;
      overflow: hidden;
    }

    .profile-banner::before {
      content: '';
      position: absolute;
      inset: 0;
      background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.06'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    .profile-banner::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      height: 60px;
      background: linear-gradient(transparent, var(--bg-card));
    }

    .profile-banner .floating-shapes {
      position: absolute;
      inset: 0;
    }

    .profile-banner .shape {
      position: absolute;
      border-radius: 50%;
      opacity: 0.1;
      animation: floatShape 6s ease-in-out infinite;
    }

    .profile-banner .shape:nth-child(1) { width: 80px; height: 80px; background: #fff; top: 20%; left: 10%; animation-delay: 0s; }
    .profile-banner .shape:nth-child(2) { width: 60px; height: 60px; background: var(--accent); top: 40%; right: 15%; animation-delay: 1.5s; }
    .profile-banner .shape:nth-child(3) { width: 40px; height: 40px; background: var(--secondary); bottom: 20%; left: 40%; animation-delay: 3s; }

    @keyframes floatShape {
      0%, 100% { transform: translateY(0) rotate(0deg); }
      50% { transform: translateY(-15px) rotate(10deg); }
    }

    .profile-info-bar {
      display: flex;
      align-items: flex-end;
      gap: 24px;
      padding: 0 32px 24px;
      margin-top: -60px;
      position: relative;
      z-index: 2;
    }

    .profile-avatar-wrapper {
      position: relative;
      flex-shrink: 0;
    }

    .profile-avatar {
      width: 110px;
      height: 110px;
      border-radius: 50%;
      background: var(--gradient-accent);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2.4rem;
      font-weight: 800;
      color: #fff;
      border: 4px solid var(--bg-card);
      box-shadow: 0 8px 32px rgba(0,0,0,0.3);
      letter-spacing: 1px;
    }

    .profile-avatar-badge {
      position: absolute;
      bottom: 4px;
      right: 4px;
      width: 28px;
      height: 28px;
      border-radius: 50%;
      background: var(--success);
      border: 3px solid var(--bg-card);
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .profile-avatar-badge i {
      font-size: 0.6rem;
      color: #fff;
    }

    .profile-meta {
      flex: 1;
      padding-bottom: 4px;
    }

    .profile-meta h1 {
      font-family: var(--font-heading);
      font-size: 1.8rem;
      font-weight: 800;
      margin: 0;
      background: linear-gradient(135deg, #fff 0%, rgba(255,255,255,0.7) 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .profile-meta .subtitle {
      font-size: 0.9rem;
      color: var(--text-muted);
      margin-top: 4px;
    }

    .profile-meta .subtitle span {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      margin-right: 16px;
    }

    .profile-meta .subtitle i {
      font-size: 0.75rem;
      color: var(--primary-light);
    }

    .profile-quick-stats {
      display: flex;
      gap: 12px;
      margin-left: auto;
      flex-shrink: 0;
    }

    .profile-quick-stat {
      background: var(--bg-glass);
      border: 1px solid var(--border-color);
      border-radius: var(--radius-md);
      padding: 12px 20px;
      text-align: center;
      min-width: 90px;
      transition: all var(--transition-fast);
    }

    .profile-quick-stat:hover {
      border-color: var(--primary-light);
      transform: translateY(-2px);
      box-shadow: 0 4px 20px rgba(108,92,231,0.15);
    }

    .profile-quick-stat .value {
      font-size: 1.3rem;
      font-weight: 800;
      background: var(--gradient-primary);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .profile-quick-stat .label {
      font-size: 0.7rem;
      color: var(--text-muted);
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-top: 2px;
    }

    /* Profile Details Grid */
    .profile-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 24px;
    }

    .detail-card {
      background: var(--bg-card);
      border: 1px solid var(--border-color);
      border-radius: var(--radius-lg);
      overflow: hidden;
      transition: all var(--transition-normal);
    }

    .detail-card:hover {
      border-color: rgba(108,92,231,0.3);
      box-shadow: 0 8px 32px rgba(0,0,0,0.15);
      transform: translateY(-2px);
    }

    .detail-card-header {
      padding: 20px 24px 16px;
      border-bottom: 1px solid var(--border-color);
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .detail-card-header .card-icon {
      width: 40px;
      height: 40px;
      border-radius: var(--radius-sm);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1rem;
    }

    .detail-card-header .card-icon.primary { background: rgba(108,92,231,0.15); color: var(--primary-light); }
    .detail-card-header .card-icon.accent { background: rgba(0,206,209,0.15); color: var(--accent); }
    .detail-card-header .card-icon.secondary { background: rgba(253,121,168,0.15); color: var(--secondary); }
    .detail-card-header .card-icon.success { background: rgba(0,184,148,0.15); color: var(--success); }

    .detail-card-header h3 {
      font-size: 1rem;
      font-weight: 700;
      color: var(--text-primary);
      margin: 0;
    }

    .detail-card-body {
      padding: 20px 24px;
    }

    .detail-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 12px 0;
      border-bottom: 1px solid rgba(255,255,255,0.04);
      transition: all var(--transition-fast);
    }

    .detail-row:last-child {
      border-bottom: none;
    }

    .detail-row:hover {
      padding-left: 8px;
    }

    .detail-row .label {
      font-size: 0.85rem;
      color: var(--text-muted);
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .detail-row .label i {
      width: 16px;
      text-align: center;
      font-size: 0.8rem;
      color: var(--primary-light);
    }

    .detail-row .value {
      font-size: 0.9rem;
      font-weight: 600;
      color: var(--text-primary);
    }

    .status-pill {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 4px 14px;
      border-radius: 20px;
      font-size: 0.78rem;
      font-weight: 600;
    }

    .status-pill.active {
      background: rgba(0,184,148,0.15);
      color: var(--success);
    }

    .status-pill.active::before {
      content: '';
      width: 7px;
      height: 7px;
      border-radius: 50%;
      background: var(--success);
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0%, 100% { opacity: 1; transform: scale(1); }
      50% { opacity: 0.5; transform: scale(0.8); }
    }

    /* Full-width card */
    .detail-card.full-width {
      grid-column: 1 / -1;
    }

    @media (max-width: 768px) {
      .profile-info-bar {
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 0 20px 24px;
      }

      .profile-meta .subtitle span {
        display: block;
        margin-right: 0;
        margin-bottom: 4px;
      }

      .profile-quick-stats {
        margin-left: 0;
        flex-wrap: wrap;
        justify-content: center;
      }

      .profile-grid {
        grid-template-columns: 1fr;
      }

      .profile-avatar {
        width: 90px;
        height: 90px;
        font-size: 2rem;
      }

      .profile-meta h1 {
        font-size: 1.4rem;
      }
    }
  </style>
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
          <a href="view-result.php" class="sidebar-link"><span class="icon"><i class="fas fa-file-alt"></i></span> My Results</a>
          <a href="attendance.php" class="sidebar-link"><span class="icon"><i class="fas fa-calendar-check"></i></span> Attendance</a>
        </div>
        <div class="sidebar-nav-group">
          <div class="sidebar-nav-label">Academic</div>
          <a href="student-subjects.php" class="sidebar-link"><span class="icon"><i class="fas fa-book"></i></span> Subjects</a>
          <a href="student-notifications.php" class="sidebar-link"><span class="icon"><i class="fas fa-bell"></i></span> Notifications <?php $unreadCnt = getUnreadNotificationCount(); if($unreadCnt > 0): ?><span class="badge" id="sidebar-badge" style="margin-left:auto;background:var(--danger);color:#fff;padding:2px 8px;border-radius:10px;font-size:0.7rem;font-weight:700;"><?php echo $unreadCnt; ?></span><?php endif; ?></a>
        </div>
        <div class="sidebar-nav-group">
          <div class="sidebar-nav-label">Account</div>
          <a href="student-profile.php" class="sidebar-link active"><span class="icon"><i class="fas fa-user-circle"></i></span> My Profile</a>
          <a href="logout.php" class="sidebar-link"><span class="icon"><i class="fas fa-sign-out-alt"></i></span> Logout</a>
        </div>
      </nav>
      <div class="sidebar-footer">
        <div class="sidebar-user">
          <div class="sidebar-user-avatar" style="background:var(--gradient-primary);"><?php echo $stuInitials; ?></div>
          <div class="sidebar-user-info">
            <div class="name"><?php echo htmlspecialchars($student['full_name']); ?></div>
            <div class="role"><?php echo htmlspecialchars($stuData['short_name']); ?> — Sem <?php echo $semester; ?></div>
          </div>
        </div>
      </div>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
      <header class="topbar">
        <div class="topbar-left">
          <button class="sidebar-toggle" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
          <div class="topbar-title">
            <h2>My Profile</h2>
            <p>View your personal & academic information</p>
          </div>
        </div>
        <div class="topbar-right">
          <span style="font-size:0.85rem;color:var(--text-muted);">Roll: <?php echo htmlspecialchars($stuData['roll_number']); ?></span>
        </div>
      </header>

      <div class="dashboard-content">

        <!-- Profile Hero Card -->
        <div class="profile-hero animate-fade-up">
          <div class="profile-banner">
            <div class="floating-shapes">
              <div class="shape"></div>
              <div class="shape"></div>
              <div class="shape"></div>
            </div>
          </div>
          <div class="profile-info-bar">
            <div class="profile-avatar-wrapper">
              <div class="profile-avatar"><?php echo $stuInitials; ?></div>
              <div class="profile-avatar-badge"><i class="fas fa-check"></i></div>
            </div>
            <div class="profile-meta">
              <h1><?php echo htmlspecialchars($stuData['first_name'] . ' ' . $stuData['last_name']); ?></h1>
              <div class="subtitle">
                <span><i class="fas fa-graduation-cap"></i> <?php echo htmlspecialchars($stuData['course_name']); ?></span>
                <span><i class="fas fa-layer-group"></i> Semester <?php echo $semester; ?> — Section <?php echo htmlspecialchars($stuData['section'] ?? 'A'); ?></span>
                <span><i class="fas fa-id-badge"></i> <?php echo htmlspecialchars($stuData['roll_number']); ?></span>
              </div>
            </div>
            <div class="profile-quick-stats">
              <div class="profile-quick-stat">
                <div class="value"><?php echo $overallPercentage; ?>%</div>
                <div class="label">Score</div>
              </div>
              <div class="profile-quick-stat">
                <div class="value"><?php echo $cgpa; ?></div>
                <div class="label">CGPA</div>
              </div>
              <div class="profile-quick-stat">
                <div class="value"><?php echo $attendanceRate; ?>%</div>
                <div class="label">Attendance</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Details Grid -->
        <div class="profile-grid">

          <!-- Personal Information -->
          <div class="detail-card animate-fade-up" style="animation-delay: 0.1s;">
            <div class="detail-card-header">
              <div class="card-icon primary"><i class="fas fa-user"></i></div>
              <h3>Personal Information</h3>
            </div>
            <div class="detail-card-body">
              <div class="detail-row">
                <span class="label"><i class="fas fa-user"></i> Full Name</span>
                <span class="value"><?php echo htmlspecialchars($stuData['first_name'] . ' ' . $stuData['last_name']); ?></span>
              </div>
              <div class="detail-row">
                <span class="label"><i class="fas fa-birthday-cake"></i> Date of Birth</span>
                <span class="value"><?php echo $dobFormatted; ?></span>
              </div>
              <div class="detail-row">
                <span class="label"><i class="fas fa-hourglass-half"></i> Age</span>
                <span class="value"><?php echo $age; ?> years</span>
              </div>
              <div class="detail-row">
                <span class="label"><i class="fas fa-venus-mars"></i> Gender</span>
                <span class="value"><?php echo htmlspecialchars($stuData['gender'] ?? '—'); ?></span>
              </div>
              <div class="detail-row">
                <span class="label"><i class="fas fa-toggle-on"></i> Status</span>
                <span class="value"><span class="status-pill active"><?php echo htmlspecialchars($stuData['status'] ?? 'Active'); ?></span></span>
              </div>
            </div>
          </div>

          <!-- Contact Details -->
          <div class="detail-card animate-fade-up" style="animation-delay: 0.15s;">
            <div class="detail-card-header">
              <div class="card-icon accent"><i class="fas fa-address-book"></i></div>
              <h3>Contact Details</h3>
            </div>
            <div class="detail-card-body">
              <div class="detail-row">
                <span class="label"><i class="fas fa-envelope"></i> Email</span>
                <span class="value"><?php echo htmlspecialchars($stuData['email'] ?? '—'); ?></span>
              </div>
              <div class="detail-row">
                <span class="label"><i class="fas fa-phone"></i> Phone</span>
                <span class="value"><?php echo htmlspecialchars($stuData['phone'] ?? '—'); ?></span>
              </div>
              <div class="detail-row">
                <span class="label"><i class="fas fa-map-marker-alt"></i> Address</span>
                <span class="value"><?php echo htmlspecialchars($stuData['address'] ?? 'Not provided'); ?></span>
              </div>
            </div>
          </div>

          <!-- Academic Details -->
          <div class="detail-card animate-fade-up" style="animation-delay: 0.2s;">
            <div class="detail-card-header">
              <div class="card-icon secondary"><i class="fas fa-graduation-cap"></i></div>
              <h3>Academic Details</h3>
            </div>
            <div class="detail-card-body">
              <div class="detail-row">
                <span class="label"><i class="fas fa-university"></i> Course</span>
                <span class="value"><?php echo htmlspecialchars($stuData['course_name']); ?></span>
              </div>
              <div class="detail-row">
                <span class="label"><i class="fas fa-id-badge"></i> Roll Number</span>
                <span class="value" style="font-family:var(--font-mono);"><?php echo htmlspecialchars($stuData['roll_number']); ?></span>
              </div>
              <div class="detail-row">
                <span class="label"><i class="fas fa-layer-group"></i> Semester</span>
                <span class="value">Semester <?php echo $semester; ?></span>
              </div>
              <div class="detail-row">
                <span class="label"><i class="fas fa-th"></i> Section</span>
                <span class="value"><?php echo htmlspecialchars($stuData['section'] ?? 'A'); ?></span>
              </div>
              <div class="detail-row">
                <span class="label"><i class="fas fa-calendar-plus"></i> Admission Year</span>
                <span class="value"><?php echo $admissionYear; ?></span>
              </div>
              <div class="detail-row">
                <span class="label"><i class="fas fa-book-open"></i> Total Subjects</span>
                <span class="value"><?php echo $totalSubjects; ?></span>
              </div>
            </div>
          </div>

          <!-- Guardian / Family Information -->
          <div class="detail-card animate-fade-up" style="animation-delay: 0.25s;">
            <div class="detail-card-header">
              <div class="card-icon success"><i class="fas fa-users"></i></div>
              <h3>Guardian / Family</h3>
            </div>
            <div class="detail-card-body">
              <div class="detail-row">
                <span class="label"><i class="fas fa-male"></i> Father's Name</span>
                <span class="value"><?php echo htmlspecialchars($stuData['father_name'] ?? '—'); ?></span>
              </div>
              <div class="detail-row">
                <span class="label"><i class="fas fa-female"></i> Mother's Name</span>
                <span class="value"><?php echo htmlspecialchars($stuData['mother_name'] ?? '—'); ?></span>
              </div>
              <div class="detail-row">
                <span class="label"><i class="fas fa-phone-alt"></i> Guardian Contact</span>
                <span class="value"><?php echo htmlspecialchars($stuData['guardian_contact'] ?? '—'); ?></span>
              </div>
              <div class="detail-row">
                <span class="label"><i class="fas fa-at"></i> Guardian Email</span>
                <span class="value"><?php echo htmlspecialchars($stuData['guardian_email'] ?? '—'); ?></span>
              </div>
            </div>
          </div>

          <!-- Account Info — Full Width -->
          <div class="detail-card full-width animate-fade-up" style="animation-delay: 0.3s;">
            <div class="detail-card-header">
              <div class="card-icon primary"><i class="fas fa-shield-alt"></i></div>
              <h3>Account Information</h3>
            </div>
            <div class="detail-card-body" style="display:grid;grid-template-columns:repeat(auto-fit, minmax(250px, 1fr));gap:0 40px;">
              <div class="detail-row">
                <span class="label"><i class="fas fa-clock"></i> Account Created</span>
                <span class="value"><?php echo $createdDate; ?></span>
              </div>
              <div class="detail-row">
                <span class="label"><i class="fas fa-key"></i> Student ID</span>
                <span class="value" style="font-family:var(--font-mono);">#<?php echo $studentId; ?></span>
              </div>
              <div class="detail-row">
                <span class="label"><i class="fas fa-hashtag"></i> Course Code</span>
                <span class="value" style="font-family:var(--font-mono);"><?php echo htmlspecialchars($stuData['short_name']); ?></span>
              </div>
              <div class="detail-row">
                <span class="label"><i class="fas fa-check-circle"></i> Account Status</span>
                <span class="value"><span class="status-pill active"><?php echo htmlspecialchars($stuData['status'] ?? 'Active'); ?></span></span>
              </div>
            </div>
          </div>

        </div>

      </div>
    </div>
  </div>

  <script src="js/app.js?v=2.3"></script>
</body>
</html>
