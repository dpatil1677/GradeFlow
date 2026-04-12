<?php
require_once 'includes/db_connect.php';
require_once 'includes/auth.php';
requireStudent();

$student = getStudentInfo();
$stuInitials = getInitials($student['full_name']);
$studentId = $student['id'];

$stuData = $pdo->prepare("SELECT s.*, c.course_name, c.short_name FROM students s JOIN courses c ON s.course_id=c.id WHERE s.id=?");
$stuData->execute([$studentId]);
$stuData = $stuData->fetch();

$courseId = $stuData['course_id'];
$semester = $stuData['semester'];

// Fetch subjects for the current semester
$subjectsStmt = $pdo->prepare("SELECT * FROM subjects WHERE course_id=? AND semester=? ORDER BY subject_code");
$subjectsStmt->execute([$courseId, $semester]);
$subjects = $subjectsStmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Subjects - GradeFlow</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700;800&family=Nunito:wght@400;600;700;800&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="css/style.css?v=2.3">
  <link rel="stylesheet" href="css/dashboard.css?v=2.4">
</head>
<body>

  <div class="dashboard-layout">
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
          <a href="student-subjects.php" class="sidebar-link active"><span class="icon"><i class="fas fa-book"></i></span> Subjects</a>
          <a href="student-notifications.php" class="sidebar-link"><span class="icon"><i class="fas fa-bell"></i></span> Notifications <?php $unreadCnt = getUnreadNotificationCount(); if($unreadCnt > 0): ?><span class="badge" id="sidebar-badge" style="margin-left:auto;background:var(--danger);color:#fff;padding:2px 8px;border-radius:10px;font-size:0.7rem;font-weight:700;"><?php echo $unreadCnt; ?></span><?php endif; ?></a>
        </div>
        <div class="sidebar-nav-group">
          <div class="sidebar-nav-label">Account</div>
          <a href="student-profile.php" class="sidebar-link"><span class="icon"><i class="fas fa-user-circle"></i></span> My Profile</a>
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

    <div class="main-content">
      <header class="topbar">
        <div class="topbar-left">
          <button class="sidebar-toggle" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
          <div class="topbar-title"><h2>My Subjects</h2><p>Overview of subjects for Semester <?php echo $semester; ?></p></div>
        </div>
      </header>

      <div class="dashboard-content">
        <div class="dashboard-grid" style="grid-template-columns: 1fr;">
          <div class="panel animate-fade-up">
            <div class="panel-header">
              <h3><i class="fas fa-book" style="margin-right:8px;color:var(--primary-light);"></i> Subjects Enrolled</h3>
            </div>
            <div class="panel-body" style="padding:0;">
              <div class="data-table-wrapper">
                <table class="data-table">
                  <thead>
                    <tr>
                      <th>Subject Code</th>
                      <th>Subject Name</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($subjects as $sub): ?>
                    <tr>
                      <td><strong style="color:var(--primary-light);"><?php echo htmlspecialchars($sub['subject_code']); ?></strong></td>
                      <td>
                        <div style="font-weight:500; font-size:1.1rem;"><?php echo htmlspecialchars($sub['subject_name']); ?></div>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($subjects)): ?>
                    <tr><td colspan="2" style="text-align:center;padding:40px;color:var(--text-muted);">No subjects available for this semester.</td></tr>
                    <?php endif; ?>
                  </tbody>
                </table>
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
