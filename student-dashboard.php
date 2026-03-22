<?php
require_once 'includes/db_connect.php';
require_once 'includes/auth.php';
requireStudent();

$student = getStudentInfo();
$stuInitials = getInitials($student['full_name']);
$studentId = $student['id'];

// Full student data
$stuData = $pdo->prepare("SELECT s.*, c.course_name, c.short_name FROM students s JOIN courses c ON s.course_id=c.id WHERE s.id=?");
$stuData->execute([$studentId]);
$stuData = $stuData->fetch();

$semester = $stuData['semester'];
$courseId = $stuData['course_id'];

// Get marks for current semester
$marksStmt = $pdo->prepare("
    SELECT m.*, sub.subject_name, sub.subject_code, sub.max_internal, sub.max_external 
    FROM marks m 
    JOIN subjects sub ON m.subject_id=sub.id 
    WHERE m.student_id=? AND sub.semester=? AND sub.course_id=?
    ORDER BY sub.subject_code
");
$marksStmt->execute([$studentId, $semester, $courseId]);
$marks = $marksStmt->fetchAll();

// Calculate stats
$totalMarksObtained = 0;
$totalMaxMarks = 0;
$subjectCount = 0;
foreach ($marks as $m) {
    $totalMarksObtained += ($m['internal_marks'] + $m['external_marks']);
    $totalMaxMarks += ($m['max_internal'] + $m['max_external']);
    $subjectCount++;
}
$overallPercentage = $totalMaxMarks > 0 ? round($totalMarksObtained * 100 / $totalMaxMarks, 1) : 0;
$cgpa = round($overallPercentage / 9.5, 2); // Approximate CGPA

// Overall attendance
$attStmt = $pdo->prepare("
    SELECT 
        COUNT(*) as total,
        SUM(status='Present') as present
    FROM attendance WHERE student_id=?
");
$attStmt->execute([$studentId]);
$attData = $attStmt->fetch();
$attendanceRate = ($attData['total'] > 0) ? round($attData['present'] * 100 / $attData['total'], 1) : 0;
$totalClasses = $attData['total'] ?? 0;
$classesPresent = $attData['present'] ?? 0;

// Subject-wise stats (current semester)
$subjStats = [];
$subjectsStmt = $pdo->prepare("SELECT * FROM subjects WHERE course_id=? AND semester=? ORDER BY subject_code");
$subjectsStmt->execute([$courseId, $semester]);
$currentSubjects = $subjectsStmt->fetchAll();

foreach ($currentSubjects as $sub) {
    // Marks
    $markRow = null;
    foreach ($marks as $m) {
        if ($m['subject_id'] == $sub['id']) { $markRow = $m; break; }
    }
    $total = $markRow ? ($markRow['internal_marks'] + $markRow['external_marks']) : 0;
    $pct = ($sub['max_internal'] + $sub['max_external']) > 0 ? round($total * 100 / ($sub['max_internal'] + $sub['max_external']), 0) : 0;
    
    // Attendance
    $aStmt = $pdo->prepare("SELECT COUNT(*) as total, SUM(status='Present') as present FROM attendance WHERE student_id=? AND subject_id=?");
    $aStmt->execute([$studentId, $sub['id']]);
    $aData = $aStmt->fetch();
    $attPct = $aData['total'] > 0 ? round($aData['present'] * 100 / $aData['total'], 0) : 0;
    
    $subjStats[] = [
        'name' => $sub['subject_name'],
        'code' => $sub['subject_code'],
        'marks_pct' => $pct,
        'att_pct' => $attPct,
    ];
}

// Attendance calendar (current month)
$currentMonth = date('n');
$currentYear  = date('Y');
$daysInMonth  = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);
$firstDay     = date('w', mktime(0, 0, 0, $currentMonth, 1, $currentYear));

$calStmt = $pdo->prepare("SELECT attendance_date, status FROM attendance WHERE student_id=? AND MONTH(attendance_date)=? AND YEAR(attendance_date)=? GROUP BY attendance_date ORDER BY attendance_date");
$calStmt->execute([$studentId, $currentMonth, $currentYear]);
$calData = [];
foreach ($calStmt->fetchAll() as $cd) {
    $calData[intval(date('j', strtotime($cd['attendance_date'])))] = $cd['status'];
}

$resultClass = getResultClass($overallPercentage);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Dashboard - GradeFlow</title>
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
        <div class="logo-icon">🎓</div>
        <div class="brand-text">GradeFlow<small>Student Portal</small></div>
      </div>
      <nav class="sidebar-nav">
        <div class="sidebar-nav-group">
          <div class="sidebar-nav-label">Main</div>
          <a href="student-dashboard.php" class="sidebar-link active"><span class="icon"><i class="fas fa-th-large"></i></span> Dashboard</a>
          <a href="view-result.php" class="sidebar-link"><span class="icon"><i class="fas fa-file-alt"></i></span> My Results</a>
          <a href="attendance.php" class="sidebar-link"><span class="icon"><i class="fas fa-calendar-check"></i></span> Attendance</a>
        </div>
        <div class="sidebar-nav-group">
          <div class="sidebar-nav-label">Academic</div>
          <a href="#" class="sidebar-link"><span class="icon"><i class="fas fa-book"></i></span> Subjects</a>
          <a href="#" class="sidebar-link"><span class="icon"><i class="fas fa-bell"></i></span> Notifications <span class="badge" style="margin-left:auto;background:var(--danger);color:#fff;padding:2px 8px;border-radius:10px;font-size:0.7rem;font-weight:700;">3</span></a>
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

    <!-- Main Content -->
    <div class="main-content">
      <header class="topbar">
        <div class="topbar-left">
          <button class="sidebar-toggle" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
          <div class="topbar-title">
            <h2>Welcome, <?php echo htmlspecialchars($stuData['first_name']); ?>! 👋</h2>
            <p>Your academic overview</p>
          </div>
        </div>
        <div class="topbar-right">
          <span style="font-size:0.85rem;color:var(--text-muted);">Roll: <?php echo htmlspecialchars($stuData['roll_number']); ?></span>
        </div>
      </header>

      <div class="dashboard-content">
        <!-- Stat Cards -->
        <div class="stats-grid">
          <div class="stat-card animate-fade-up delay-1">
            <div class="stat-card-header"><div class="stat-icon primary"><i class="fas fa-percentage"></i></div></div>
            <div class="stat-value"><?php echo $overallPercentage; ?>%</div>
            <div class="stat-label">Overall Percentage</div>
          </div>
          <div class="stat-card animate-fade-up delay-2">
            <div class="stat-card-header"><div class="stat-icon secondary"><i class="fas fa-star"></i></div></div>
            <div class="stat-value"><?php echo $cgpa; ?></div>
            <div class="stat-label">CGPA</div>
          </div>
          <div class="stat-card animate-fade-up delay-3">
            <div class="stat-card-header"><div class="stat-icon accent"><i class="fas fa-calendar-check"></i></div></div>
            <div class="stat-value"><?php echo $attendanceRate; ?>%</div>
            <div class="stat-label">Attendance Rate</div>
          </div>
          <div class="stat-card animate-fade-up delay-4">
            <div class="stat-card-header"><div class="stat-icon success"><i class="fas fa-book"></i></div></div>
            <div class="stat-value"><?php echo count($currentSubjects); ?></div>
            <div class="stat-label">Subjects</div>
          </div>
        </div>

        <!-- Grid: Subject Performance + Calendar -->
        <div class="dashboard-grid">
          <!-- Subject Performance -->
          <div class="panel">
            <div class="panel-header">
              <h3><i class="fas fa-chart-bar" style="margin-right:8px;color:var(--primary-light);"></i> Subject Performance (Semester <?php echo $semester; ?>)</h3>
              <a href="view-result.php" class="btn btn-ghost btn-sm">Details</a>
            </div>
            <div class="panel-body">
              <?php foreach ($subjStats as $sub): 
                $barColor = $sub['marks_pct'] >= 75 ? 'var(--success)' : ($sub['marks_pct'] >= 50 ? 'var(--warning)' : 'var(--danger)');
              ?>
              <div style="margin-bottom:20px;">
                <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
                  <span style="font-size:0.85rem;font-weight:500;"><?php echo htmlspecialchars($sub['code'].' - '.$sub['name']); ?></span>
                  <span style="font-size:0.85rem;font-weight:700;color:<?php echo $barColor; ?>;"><?php echo $sub['marks_pct']; ?>%</span>
                </div>
                <div style="height:8px;border-radius:4px;background:rgba(255,255,255,0.05);overflow:hidden;">
                  <div style="height:100%;width:<?php echo $sub['marks_pct']; ?>%;background:<?php echo $barColor; ?>;border-radius:4px;transition:width 0.8s ease;"></div>
                </div>
              </div>
              <?php endforeach; ?>
              <?php if (empty($subjStats)): ?>
              <p style="text-align:center;color:var(--text-muted);padding:20px;">No marks available yet for this semester.</p>
              <?php endif; ?>
            </div>
          </div>

          <!-- Sidebar: Attendance Calendar + Quick Info -->
          <div style="display:flex;flex-direction:column;gap:24px;">
            <!-- Attendance Calendar -->
            <div class="panel">
              <div class="panel-header">
                <h3><i class="fas fa-calendar-alt" style="margin-right:8px;color:var(--accent);"></i> <?php echo date('F Y'); ?></h3>
                <a href="attendance.php" class="btn btn-ghost btn-sm">View All</a>
              </div>
              <div class="panel-body">
                <div style="width:100%;">
                  <div style="display:grid;grid-template-columns:repeat(7,1fr);gap:4px;margin-bottom:8px;">
                    <?php foreach (['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $d): ?>
                    <div style="text-align:center;font-size:0.72rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;padding:6px 0;"><?php echo $d; ?></div>
                    <?php endforeach; ?>
                  </div>
                  <div style="display:grid;grid-template-columns:repeat(7,1fr);gap:4px;">
                    <?php for ($blank = 0; $blank < $firstDay; $blank++): ?>
                    <div style="aspect-ratio:1;"></div>
                    <?php endfor; ?>
                    <?php for ($d = 1; $d <= $daysInMonth; $d++):
                      $bg = 'rgba(255,255,255,0.03)'; $clr = 'inherit'; $bdr = 'transparent'; $shadow = 'none'; $fw = '500';
                      if ($d == date('j')) { $bdr = 'var(--primary)'; $shadow = '0 0 10px rgba(108,92,231,0.3)'; $fw = '700'; }
                      if (isset($calData[$d])) {
                          if ($calData[$d] === 'Present') { $bg = 'rgba(0,184,148,0.15)'; $clr = 'var(--success)'; $bdr = 'rgba(0,184,148,0.3)'; }
                          else { $bg = 'rgba(214,48,49,0.15)'; $clr = 'var(--danger)'; $bdr = 'rgba(214,48,49,0.3)'; }
                      }
                    ?>
                    <div style="aspect-ratio:1;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:0.8rem;font-weight:<?php echo $fw;?>;background:<?php echo $bg;?>;color:<?php echo $clr;?>;border:1px solid <?php echo $bdr;?>;box-shadow:<?php echo $shadow;?>;"><?php echo $d; ?></div>
                    <?php endfor; ?>
                  </div>
                </div>
                <div style="display:flex;gap:16px;margin-top:16px;justify-content:center;">
                  <div style="display:flex;align-items:center;gap:6px;"><div style="width:12px;height:12px;border-radius:50%;background:var(--success);"></div><span style="font-size:0.75rem;color:var(--text-muted);">Present</span></div>
                  <div style="display:flex;align-items:center;gap:6px;"><div style="width:12px;height:12px;border-radius:50%;background:var(--danger);"></div><span style="font-size:0.75rem;color:var(--text-muted);">Absent</span></div>
                  <div style="display:flex;align-items:center;gap:6px;"><div style="width:12px;height:12px;border-radius:50%;background:var(--primary-light);border:1px solid var(--primary-light);"></div><span style="font-size:0.75rem;color:var(--text-muted);">Today</span></div>
                </div>
              </div>
            </div>

            <!-- Result Summary -->
            <div class="panel">
              <div class="panel-header">
                <h3><i class="fas fa-award" style="margin-right:8px;color:var(--secondary);"></i> Result Summary</h3>
              </div>
              <div class="panel-body">
                <div style="text-align:center;padding:12px 0;">
                  <div style="font-size:2rem;font-weight:800;background:var(--gradient-primary);-webkit-background-clip:text;-webkit-text-fill-color:transparent;"><?php echo $overallPercentage > 0 ? calculateGrade($overallPercentage) : '-'; ?></div>
                  <div style="font-size:0.85rem;color:var(--text-muted);margin-top:4px;"><?php echo $resultClass; ?></div>
                  <div style="margin-top:16px;display:flex;justify-content:space-around;">
                    <div>
                      <div style="font-size:1.1rem;font-weight:700;"><?php echo $classesPresent; ?>/<?php echo $totalClasses; ?></div>
                      <div style="font-size:0.75rem;color:var(--text-muted);">Classes Attended</div>
                    </div>
                    <div>
                      <div style="font-size:1.1rem;font-weight:700;"><?php echo $subjectCount; ?></div>
                      <div style="font-size:0.75rem;color:var(--text-muted);">Subjects</div>
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

  <script src="js/app.js?v=2.3"></script>
</body>
</html>
