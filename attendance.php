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

// Overall stats
$overallStmt = $pdo->prepare("SELECT COUNT(*) as total, SUM(status='Present') as present, SUM(status='Absent') as absent FROM attendance WHERE student_id=?");
$overallStmt->execute([$studentId]);
$overall = $overallStmt->fetch();
$overallPct = $overall['total'] > 0 ? round($overall['present'] * 100 / $overall['total'], 1) : 0;
$totalClasses = $overall['total'] ?? 0;
$presentClasses = $overall['present'] ?? 0;
$absentClasses = $overall['absent'] ?? 0;

// Subject-wise attendance (current semester)
$subjectsStmt = $pdo->prepare("SELECT * FROM subjects WHERE course_id=? AND semester=? ORDER BY subject_code");
$subjectsStmt->execute([$courseId, $semester]);
$subjects = $subjectsStmt->fetchAll();

$subjectAttendance = [];
foreach ($subjects as $sub) {
    $aStmt = $pdo->prepare("SELECT COUNT(*) as total, SUM(status='Present') as present, SUM(status='Absent') as absent FROM attendance WHERE student_id=? AND subject_id=?");
    $aStmt->execute([$studentId, $sub['id']]);
    $aData = $aStmt->fetch();
    $pct = $aData['total'] > 0 ? round($aData['present'] * 100 / $aData['total'], 0) : 0;
    $subjectAttendance[] = [
        'code' => $sub['subject_code'],
        'name' => $sub['subject_name'],
        'total' => $aData['total'],
        'present' => $aData['present'] ?? 0,
        'absent' => $aData['absent'] ?? 0,
        'percentage' => $pct,
    ];
}

// Calendar - current month or selected month
$calMonth = intval($_GET['month'] ?? date('n'));
$calYear  = intval($_GET['year'] ?? date('Y'));
$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $calMonth, $calYear);
$firstDay = date('w', mktime(0, 0, 0, $calMonth, 1, $calYear));
$monthName = date('F', mktime(0, 0, 0, $calMonth, 1, $calYear));

$calStmt = $pdo->prepare("
    SELECT attendance_date, 
        CASE WHEN SUM(status='Absent') > 0 THEN 'Absent' ELSE 'Present' END as day_status
    FROM attendance 
    WHERE student_id=? AND MONTH(attendance_date)=? AND YEAR(attendance_date)=?
    GROUP BY attendance_date ORDER BY attendance_date
");
$calStmt->execute([$studentId, $calMonth, $calYear]);
$calData = [];
foreach ($calStmt->fetchAll() as $cd) {
    $calData[intval(date('j', strtotime($cd['attendance_date'])))] = $cd['day_status'];
}

// Recent attendance log
$logStmt = $pdo->prepare("
    SELECT a.*, sub.subject_name, sub.subject_code 
    FROM attendance a
    JOIN subjects sub ON a.subject_id=sub.id
    WHERE a.student_id=?
    ORDER BY a.attendance_date DESC, sub.subject_code
    LIMIT 20
");
$logStmt->execute([$studentId]);
$recentLog = $logStmt->fetchAll();

// Previous/next month navigation
$prevMonth = $calMonth - 1;
$prevYear = $calYear;
if ($prevMonth < 1) { $prevMonth = 12; $prevYear--; }
$nextMonth = $calMonth + 1;
$nextYear = $calYear;
if ($nextMonth > 12) { $nextMonth = 1; $nextYear++; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Attendance - GradeFlow</title>
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
          <a href="attendance.php" class="sidebar-link active"><span class="icon"><i class="fas fa-calendar-check"></i></span> Attendance</a>
        </div>
        <div class="sidebar-nav-group">
          <div class="sidebar-nav-label">Academic</div>
          <a href="student-subjects.php" class="sidebar-link"><span class="icon"><i class="fas fa-book"></i></span> Subjects</a>
          <a href="student-notifications.php" class="sidebar-link"><span class="icon"><i class="fas fa-bell"></i></span> Notifications <?php $unreadCnt = getUnreadNotificationCount(); if($unreadCnt > 0): ?><span class="badge" id="sidebar-badge" style="margin-left:auto;background:var(--danger);color:#fff;padding:2px 8px;border-radius:10px;font-size:0.7rem;font-weight:700;"><?php echo $unreadCnt; ?></span><?php endif; ?></a>
        </div>
        <div class="sidebar-nav-group">
          <div class="sidebar-nav-label">Account</div>
          <a href="#" class="sidebar-link"><span class="icon"><i class="fas fa-user-circle"></i></span> My Profile</a>
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
          <div class="topbar-title"><h2>My Attendance</h2><p>Track your attendance records</p></div>
        </div>
      </header>

      <div class="dashboard-content">
        <!-- Overall Stats -->
        <div class="stats-grid" style="margin-bottom:28px;">
          <div class="stat-card animate-fade-up delay-1">
            <div class="stat-card-header"><div class="stat-icon <?php echo $overallPct>=75?'success':'accent'; ?>"><i class="fas fa-chart-pie"></i></div></div>
            <div class="stat-value" style="color:<?php echo $overallPct>=75?'var(--success)':'var(--danger)'; ?>;"><?php echo $overallPct; ?>%</div>
            <div class="stat-label">Overall Attendance</div>
          </div>
          <div class="stat-card animate-fade-up delay-2">
            <div class="stat-card-header"><div class="stat-icon primary"><i class="fas fa-calendar-alt"></i></div></div>
            <div class="stat-value"><?php echo $totalClasses; ?></div>
            <div class="stat-label">Total Classes</div>
          </div>
          <div class="stat-card animate-fade-up delay-3">
            <div class="stat-card-header"><div class="stat-icon success"><i class="fas fa-check-circle"></i></div></div>
            <div class="stat-value"><?php echo $presentClasses; ?></div>
            <div class="stat-label">Classes Attended</div>
          </div>
          <div class="stat-card animate-fade-up delay-4">
            <div class="stat-card-header"><div class="stat-icon accent"><i class="fas fa-times-circle"></i></div></div>
            <div class="stat-value"><?php echo $absentClasses; ?></div>
            <div class="stat-label">Classes Missed</div>
          </div>
        </div>

        <?php if ($overallPct < 75 && $overallPct > 0): ?>
        <div class="alert alert-danger animate-fade-up" style="margin-bottom:24px;">
          <i class="fas fa-exclamation-triangle"></i>
          <span>Your attendance (<?php echo $overallPct; ?>%) is below the minimum required 75%. You may be debarred from examinations if attendance is not improved.</span>
        </div>
        <?php endif; ?>

        <div class="dashboard-grid">
          <!-- Subject-wise Attendance -->
          <div class="panel animate-fade-up">
            <div class="panel-header">
              <h3><i class="fas fa-book" style="margin-right:8px;color:var(--primary-light);"></i> Subject-wise Attendance (Semester <?php echo $semester; ?>)</h3>
            </div>
            <div class="panel-body" style="padding:0;">
              <div class="data-table-wrapper">
                <table class="data-table">
                  <thead>
                    <tr>
                      <th>Subject</th>
                      <th>Present</th>
                      <th>Absent</th>
                      <th>Total</th>
                      <th>Percentage</th>
                      <th>Progress</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($subjectAttendance as $sa): 
                      $color = $sa['percentage'] >= 75 ? 'var(--success)' : ($sa['percentage'] >= 50 ? 'var(--warning)' : 'var(--danger)');
                    ?>
                    <tr>
                      <td>
                        <div style="font-weight:500;"><?php echo htmlspecialchars($sa['code']); ?></div>
                        <div style="font-size:0.78rem;color:var(--text-muted);"><?php echo htmlspecialchars($sa['name']); ?></div>
                      </td>
                      <td style="color:var(--success);font-weight:600;"><?php echo $sa['present']; ?></td>
                      <td style="color:var(--danger);font-weight:600;"><?php echo $sa['absent']; ?></td>
                      <td><?php echo $sa['total']; ?></td>
                      <td><strong style="color:<?php echo $color; ?>;"><?php echo $sa['percentage']; ?>%</strong></td>
                      <td style="min-width:120px;">
                        <div style="height:6px;border-radius:3px;background:rgba(255,255,255,0.05);overflow:hidden;">
                          <div style="height:100%;width:<?php echo $sa['percentage']; ?>%;background:<?php echo $color; ?>;border-radius:3px;transition:width 1s ease;"></div>
                        </div>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($subjectAttendance)): ?>
                    <tr><td colspan="6" style="text-align:center;padding:40px;color:var(--text-muted);">No attendance data available.</td></tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Calendar + Recent Log -->
          <div style="display:flex;flex-direction:column;gap:24px;">
            <!-- Calendar -->
            <div class="panel animate-fade-up" style="animation-delay:0.1s;">
              <div class="panel-header">
                <a href="?month=<?php echo $prevMonth; ?>&year=<?php echo $prevYear; ?>" class="btn btn-ghost btn-sm"><i class="fas fa-chevron-left"></i></a>
                <h3><i class="fas fa-calendar-alt" style="margin-right:8px;color:var(--accent);"></i> <?php echo $monthName . ' ' . $calYear; ?></h3>
                <a href="?month=<?php echo $nextMonth; ?>&year=<?php echo $nextYear; ?>" class="btn btn-ghost btn-sm"><i class="fas fa-chevron-right"></i></a>
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
                      if ($d == date('j') && $calMonth == date('n') && $calYear == date('Y')) { $bdr = 'var(--primary)'; $shadow = '0 0 10px rgba(108,92,231,0.3)'; $fw = '700'; }
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
                </div>
              </div>
            </div>

            <!-- Recent Log -->
            <div class="panel animate-fade-up" style="animation-delay:0.2s;">
              <div class="panel-header">
                <h3><i class="fas fa-history" style="margin-right:8px;color:var(--secondary);"></i> Recent Log</h3>
              </div>
              <div class="panel-body" style="max-height:300px;overflow-y:auto;">
                <div class="activity-feed">
                  <?php foreach (array_slice($recentLog, 0, 8) as $log): ?>
                  <div class="activity-item">
                    <div class="activity-icon" style="background:<?php echo $log['status']==='Present'?'rgba(0,184,148,0.15)':'rgba(214,48,49,0.15)'; ?>;color:<?php echo $log['status']==='Present'?'var(--success)':'var(--danger)'; ?>;">
                      <i class="fas fa-<?php echo $log['status']==='Present'?'check':'times'; ?>"></i>
                    </div>
                    <div class="activity-content">
                      <p><strong><?php echo htmlspecialchars($log['subject_code']); ?></strong> — <?php echo htmlspecialchars($log['subject_name']); ?></p>
                      <div class="activity-time"><?php echo date('M j, Y', strtotime($log['attendance_date'])); ?> · <?php echo $log['status']; ?></div>
                    </div>
                  </div>
                  <?php endforeach; ?>
                  <?php if (empty($recentLog)): ?>
                  <p style="text-align:center;color:var(--text-muted);padding:20px;">No attendance records yet.</p>
                  <?php endif; ?>
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
