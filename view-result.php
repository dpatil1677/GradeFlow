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

$courseId = $stuData['course_id'];
$currentSemester = $stuData['semester'];

// Get all semesters that have marks data for this student
$semStmt = $pdo->prepare("
    SELECT DISTINCT sub.semester 
    FROM marks m 
    JOIN subjects sub ON m.subject_id=sub.id 
    WHERE m.student_id=? AND sub.course_id=?
    ORDER BY sub.semester DESC
");
$semStmt->execute([$studentId, $courseId]);
$semesters = $semStmt->fetchAll(PDO::FETCH_COLUMN);

// Get results for each semester
$semesterResults = [];
$allSemestorStats = [];

foreach ($semesters as $sem) {
    $stmt = $pdo->prepare("
        SELECT m.*, sub.subject_name, sub.subject_code, sub.max_internal, sub.max_external
        FROM marks m
        JOIN subjects sub ON m.subject_id=sub.id
        WHERE m.student_id=? AND sub.semester=? AND sub.course_id=?
        ORDER BY sub.subject_code
    ");
    $stmt->execute([$studentId, $sem, $courseId]);
    $results = $stmt->fetchAll();
    
    $semTotal = 0;
    $semMax = 0;
    $subjectsPassed = 0;
    $subjectsCount = 0;
    
    $formattedResults = [];
    foreach ($results as $r) {
        $total = $r['internal_marks'] + $r['external_marks'];
        $max = $r['max_internal'] + $r['max_external'];
        $pct = $max > 0 ? round($total * 100 / $max, 1) : 0;
        $grade = calculateGrade($pct);
        $pass = $pct >= 40;
        
        if ($pass) $subjectsPassed++;
        $subjectsCount++;
        $semTotal += $total;
        $semMax += $max;
        
        $formattedResults[] = [
            'subject_code' => $r['subject_code'],
            'subject_name' => $r['subject_name'],
            'internal' => intval($r['internal_marks']),
            'external' => intval($r['external_marks']),
            'max_internal' => $r['max_internal'],
            'max_external' => $r['max_external'],
            'total' => $total,
            'max_total' => $max,
            'percentage' => $pct,
            'grade' => $grade,
            'pass' => $pass,
            'exam_type' => $r['exam_type'],
        ];
    }
    
    $semPct = $semMax > 0 ? round($semTotal * 100 / $semMax, 1) : 0;
    $sgpa = round($semPct / 9.5, 2);
    
    $semesterResults[$sem] = $formattedResults;
    $allSemestorStats[$sem] = [
        'percentage' => $semPct,
        'sgpa' => $sgpa,
        'total_obtained' => $semTotal,
        'total_max' => $semMax,
        'subjects_passed' => $subjectsPassed,
        'subjects_total' => $subjectsCount,
        'result_class' => getResultClass($semPct),
        'overall_pass' => ($subjectsPassed === $subjectsCount && $subjectsCount > 0),
    ];
}

// Calculate cumulative CGPA
$cumTotal = 0;
$cumMax = 0;
foreach ($allSemestorStats as $ss) {
    $cumTotal += $ss['total_obtained'];
    $cumMax += $ss['total_max'];
}
$cumulativePercentage = $cumMax > 0 ? round($cumTotal * 100 / $cumMax, 1) : 0;
$cumulativeCGPA = round($cumulativePercentage / 9.5, 2);

// Active tab
$activeSem = intval($_GET['sem'] ?? ($semesters[0] ?? 0));
if (!in_array($activeSem, $semesters) && !empty($semesters)) $activeSem = $semesters[0];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Results - GradeFlow</title>
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
          <a href="view-result.php" class="sidebar-link active"><span class="icon"><i class="fas fa-file-alt"></i></span> My Results</a>
          <a href="attendance.php" class="sidebar-link"><span class="icon"><i class="fas fa-calendar-check"></i></span> Attendance</a>
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
            <div class="role"><?php echo htmlspecialchars($stuData['short_name']); ?> — Sem <?php echo $currentSemester; ?></div>
          </div>
        </div>
      </div>
    </aside>

    <div class="main-content">
      <header class="topbar">
        <div class="topbar-left">
          <button class="sidebar-toggle" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
          <div class="topbar-title"><h2>My Results</h2><p>View your semester-wise academic results</p></div>
        </div>
        <div class="topbar-right">
          <span style="font-size:0.85rem;color:var(--text-muted);"><?php echo htmlspecialchars($stuData['roll_number']); ?> | <?php echo htmlspecialchars($stuData['course_name']); ?></span>
        </div>
      </header>

      <div class="dashboard-content">

        <?php if (empty($semesters)): ?>
        <div class="alert alert-info"><i class="fas fa-info-circle"></i> <span>No results available yet. Check back after your examinations are processed.</span></div>
        <?php else: ?>

        <!-- Overall Summary -->
        <div class="stats-grid" style="margin-bottom:28px;">
          <div class="stat-card animate-fade-up delay-1">
            <div class="stat-card-header"><div class="stat-icon primary"><i class="fas fa-percentage"></i></div></div>
            <div class="stat-value"><?php echo $cumulativePercentage; ?>%</div>
            <div class="stat-label">Cumulative Percentage</div>
          </div>
          <div class="stat-card animate-fade-up delay-2">
            <div class="stat-card-header"><div class="stat-icon secondary"><i class="fas fa-star"></i></div></div>
            <div class="stat-value"><?php echo $cumulativeCGPA; ?></div>
            <div class="stat-label">CGPA</div>
          </div>
          <div class="stat-card animate-fade-up delay-3">
            <div class="stat-card-header"><div class="stat-icon accent"><i class="fas fa-layer-group"></i></div></div>
            <div class="stat-value"><?php echo count($semesters); ?></div>
            <div class="stat-label">Semesters</div>
          </div>
          <div class="stat-card animate-fade-up delay-4">
            <div class="stat-card-header"><div class="stat-icon success"><i class="fas fa-award"></i></div></div>
            <div class="stat-value"><?php echo calculateGrade($cumulativePercentage); ?></div>
            <div class="stat-label">Overall Grade</div>
          </div>
        </div>

        <!-- Semester Tabs -->
        <div class="panel animate-fade-up" style="animation-delay:0.15s;">
          <div class="panel-header">
            <div class="tab-buttons">
              <?php foreach ($semesters as $sem): ?>
              <a href="?sem=<?php echo $sem; ?>" class="tab-btn <?php echo $sem==$activeSem?'active':''; ?>">Semester <?php echo $sem; ?></a>
              <?php endforeach; ?>
            </div>
          </div>

          <?php if (isset($semesterResults[$activeSem])): 
            $semStats = $allSemestorStats[$activeSem];
            $semResults = $semesterResults[$activeSem];
          ?>
          <!-- Semester Summary -->
          <div style="padding:20px 24px;display:flex;gap:24px;flex-wrap:wrap;border-bottom:1px solid var(--border-color);">
            <div style="flex:1;min-width:120px;">
              <div style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.5px;">Percentage</div>
              <div style="font-size:1.6rem;font-weight:800;color:<?php echo $semStats['percentage']>=50?'var(--success)':'var(--danger)'; ?>;"><?php echo $semStats['percentage']; ?>%</div>
            </div>
            <div style="flex:1;min-width:120px;">
              <div style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.5px;">SGPA</div>
              <div style="font-size:1.6rem;font-weight:800;color:var(--primary-light);"><?php echo $semStats['sgpa']; ?></div>
            </div>
            <div style="flex:1;min-width:120px;">
              <div style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.5px;">Grade</div>
              <div style="font-size:1.6rem;font-weight:800;"><span class="grade-badge <?php echo gradeClass(calculateGrade($semStats['percentage'])); ?>"><?php echo calculateGrade($semStats['percentage']); ?></span></div>
            </div>
            <div style="flex:1;min-width:120px;">
              <div style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.5px;">Result</div>
              <div style="font-size:1rem;font-weight:700;margin-top:6px;"><span class="status-badge <?php echo $semStats['overall_pass']?'pass':'fail'; ?>"><?php echo $semStats['overall_pass']?'PASSED':'FAILED'; ?></span></div>
            </div>
          </div>

          <!-- Results Table -->
          <div class="panel-body" style="padding:0;">
            <div class="data-table-wrapper">
              <table class="data-table">
                <thead>
                  <tr>
                    <th>Subject Code</th>
                    <th>Subject Name</th>
                    <th>Exam</th>
                    <th>Internal</th>
                    <th>External</th>
                    <th>Total</th>
                    <th>%</th>
                    <th>Grade</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($semResults as $r): ?>
                  <tr>
                    <td style="font-family:var(--font-mono);"><?php echo htmlspecialchars($r['subject_code']); ?></td>
                    <td><?php echo htmlspecialchars($r['subject_name']); ?></td>
                    <td><?php echo htmlspecialchars($r['exam_type']); ?></td>
                    <td><?php echo $r['internal']; ?> / <?php echo $r['max_internal']; ?></td>
                    <td><?php echo $r['external']; ?> / <?php echo $r['max_external']; ?></td>
                    <td><strong><?php echo intval($r['total']); ?> / <?php echo $r['max_total']; ?></strong></td>
                    <td><strong style="color:<?php echo $r['pass']?'var(--success)':'var(--danger)'; ?>;"><?php echo $r['percentage']; ?>%</strong></td>
                    <td><span class="grade-badge <?php echo gradeClass($r['grade']); ?>"><?php echo $r['grade']; ?></span></td>
                    <td><span class="status-badge <?php echo $r['pass']?'pass':'fail'; ?>"><?php echo $r['pass']?'Pass':'Fail'; ?></span></td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
                <tfoot>
                  <tr style="font-weight:700;border-top:2px solid var(--border-color);">
                    <td colspan="3">Total</td>
                    <td>-</td>
                    <td>-</td>
                    <td><?php echo intval($semStats['total_obtained']); ?> / <?php echo $semStats['total_max']; ?></td>
                    <td style="color:<?php echo $semStats['percentage']>=50?'var(--success)':'var(--danger)'; ?>;"><?php echo $semStats['percentage']; ?>%</td>
                    <td><span class="grade-badge <?php echo gradeClass(calculateGrade($semStats['percentage'])); ?>"><?php echo calculateGrade($semStats['percentage']); ?></span></td>
                    <td><span class="status-badge <?php echo $semStats['overall_pass']?'pass':'fail'; ?>"><?php echo $semStats['overall_pass']?'Pass':'Fail'; ?></span></td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
          <?php endif; ?>
        </div>

        <!-- All Semesters Overview -->
        <?php if (count($semesters) > 1): ?>
        <div class="panel animate-fade-up" style="animation-delay:0.2s;margin-top:28px;">
          <div class="panel-header">
            <h3><i class="fas fa-chart-line" style="margin-right:8px;color:var(--secondary);"></i> All Semesters Overview</h3>
          </div>
          <div class="panel-body" style="padding:0;">
            <div class="data-table-wrapper">
              <table class="data-table">
                <thead>
                  <tr>
                    <th>Semester</th>
                    <th>Subjects</th>
                    <th>Marks Obtained</th>
                    <th>Total Marks</th>
                    <th>Percentage</th>
                    <th>SGPA</th>
                    <th>Grade</th>
                    <th>Result</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($allSemestorStats as $sem => $ss): ?>
                  <tr>
                    <td><strong>Semester <?php echo $sem; ?></strong></td>
                    <td><?php echo $ss['subjects_passed']; ?>/<?php echo $ss['subjects_total']; ?></td>
                    <td><?php echo intval($ss['total_obtained']); ?></td>
                    <td><?php echo $ss['total_max']; ?></td>
                    <td><strong style="color:<?php echo $ss['percentage']>=50?'var(--success)':'var(--danger)'; ?>;"><?php echo $ss['percentage']; ?>%</strong></td>
                    <td><?php echo $ss['sgpa']; ?></td>
                    <td><span class="grade-badge <?php echo gradeClass(calculateGrade($ss['percentage'])); ?>"><?php echo calculateGrade($ss['percentage']); ?></span></td>
                    <td><span class="status-badge <?php echo $ss['overall_pass']?'pass':'fail'; ?>"><?php echo $ss['overall_pass']?'Pass':'Fail'; ?></span></td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
                <tfoot>
                  <tr style="font-weight:700;border-top:2px solid var(--border-color);">
                    <td>Cumulative</td>
                    <td>-</td>
                    <td><?php echo intval($cumTotal); ?></td>
                    <td><?php echo $cumMax; ?></td>
                    <td style="color:<?php echo $cumulativePercentage>=50?'var(--success)':'var(--danger)'; ?>;"><?php echo $cumulativePercentage; ?>%</td>
                    <td><?php echo $cumulativeCGPA; ?></td>
                    <td><span class="grade-badge <?php echo gradeClass(calculateGrade($cumulativePercentage)); ?>"><?php echo calculateGrade($cumulativePercentage); ?></span></td>
                    <td>-</td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
        <?php endif; ?>

        <?php endif; ?>
      </div>
    </div>
  </div>

  <script src="js/app.js?v=2.3"></script>
</body>
</html>
