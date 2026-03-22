<?php
require_once 'includes/db_connect.php';
require_once 'includes/auth.php';
requireAdmin();

$admin = getAdminInfo();
$initials = getInitials($admin['full_name']);

$courses = $pdo->query("SELECT * FROM courses ORDER BY course_name")->fetchAll();
$success = '';

// Handle save attendance
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_attendance'])) {
    $studentIds = $_POST['student_id'] ?? [];
    $subjectId  = intval($_POST['subject_id']);
    $attDate    = $_POST['attendance_date'];
    $statuses   = $_POST['status'] ?? [];
    $remarks    = $_POST['remarks'] ?? [];

    $stmt = $pdo->prepare("INSERT INTO attendance (student_id, subject_id, attendance_date, status, remarks) VALUES (?,?,?,?,?) ON DUPLICATE KEY UPDATE status=VALUES(status), remarks=VALUES(remarks)");

    $count = 0;
    foreach ($studentIds as $idx => $sid) {
        $st = $statuses[$idx] ?? 'Present';
        $rem = trim($remarks[$idx] ?? '');
        $stmt->execute([intval($sid), $subjectId, $attDate, $st, $rem ?: null]);
        $count++;
    }
    $success = "Attendance saved for $count students!";
}

// Filters
$selectedCourse = intval($_GET['course_id'] ?? $_POST['filter_course_id'] ?? 0);
$selectedSem = intval($_GET['semester'] ?? $_POST['filter_semester'] ?? 0);
$selectedSubject = intval($_GET['subject_id'] ?? $_POST['filter_subject_id'] ?? 0);
$selectedDate = $_GET['date'] ?? $_POST['filter_date'] ?? date('Y-m-d');

$subjects = [];
if ($selectedCourse && $selectedSem) {
    $stmt = $pdo->prepare("SELECT * FROM subjects WHERE course_id=? AND semester=? ORDER BY subject_code");
    $stmt->execute([$selectedCourse, $selectedSem]);
    $subjects = $stmt->fetchAll();
}

$loadStudents = [];
$existingAtt = [];
if ($selectedCourse && $selectedSem) {
    $stmt = $pdo->prepare("SELECT s.* FROM students s WHERE s.course_id=? AND s.semester=? ORDER BY s.roll_number");
    $stmt->execute([$selectedCourse, $selectedSem]);
    $loadStudents = $stmt->fetchAll();

    if ($selectedSubject && $selectedDate) {
        $aStmt = $pdo->prepare("SELECT * FROM attendance WHERE subject_id=? AND attendance_date=?");
        $aStmt->execute([$selectedSubject, $selectedDate]);
        foreach ($aStmt->fetchAll() as $a) {
            $existingAtt[$a['student_id']] = $a;
        }
    }
}

// Quick stats for loaded students
$presentCount = 0;
$absentCount = 0;
foreach ($loadStudents as $stu) {
    $att = $existingAtt[$stu['id']] ?? null;
    if ($att) {
        if ($att['status'] === 'Present') $presentCount++;
        else $absentCount++;
    }
}

// Overall attendance per student
$overallAtt = [];
if (!empty($loadStudents) && $selectedSubject) {
    $ids = array_column($loadStudents, 'id');
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT student_id, 
        ROUND(SUM(status='Present')*100.0/COUNT(*),0) as pct
        FROM attendance WHERE student_id IN ($placeholders) AND subject_id=?
        GROUP BY student_id");
    $stmt->execute(array_merge($ids, [$selectedSubject]));
    foreach ($stmt->fetchAll() as $r) {
        $overallAtt[$r['student_id']] = $r['pct'];
    }
}

$gradients = [
    'var(--gradient-primary)', 'var(--gradient-secondary)', 'var(--gradient-accent)',
    'linear-gradient(135deg,#fdcb6e,#e17055)', 'linear-gradient(135deg,#a29bfe,#6c5ce7)',
    'linear-gradient(135deg,#55efc4,#00b894)',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Attendance - GradeFlow Admin</title>
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

    <div class="main-content">
      <header class="topbar">
        <div class="topbar-left">
          <button class="sidebar-toggle" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
          <div class="topbar-title"><h2>Manage Attendance</h2><p>Mark and manage student attendance</p></div>
        </div>
      </header>

      <div class="dashboard-content">

        <?php if ($success): ?>
        <div class="alert alert-success animate-fade-down" style="margin-bottom:20px;">
          <i class="fas fa-check-circle"></i> <span><?php echo htmlspecialchars($success); ?></span>
        </div>
        <?php endif; ?>

        <!-- Selection Panel -->
        <div class="panel animate-fade-up" style="margin-bottom:28px; position:relative; z-index:50;">
          <div class="panel-header">
            <h3><i class="fas fa-filter" style="margin-right:8px;color:var(--primary-light);"></i> Select Class</h3>
          </div>
          <div class="panel-body">
            <form method="GET" id="filterForm">
              <div class="form-row" style="grid-template-columns: repeat(4, 1fr);">
                <div class="form-group" style="margin-bottom:0;">
                  <label class="form-label">Date</label>
                  <input type="date" name="date" class="form-input form-input-plain" value="<?php echo htmlspecialchars($selectedDate); ?>">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                  <label class="form-label">Course</label>
                  <select name="course_id" class="form-select" onchange="this.form.submit()">
                    <option value="0">Select Course</option>
                    <?php foreach ($courses as $c): ?>
                    <option value="<?php echo $c['id']; ?>" <?php echo $selectedCourse==$c['id']?'selected':''; ?>><?php echo htmlspecialchars($c['course_name']); ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                  <label class="form-label">Semester</label>
                  <select name="semester" class="form-select" onchange="this.form.submit()">
                    <option value="0">Select Semester</option>
                    <?php for ($s=1;$s<=8;$s++): ?>
                    <option value="<?php echo $s; ?>" <?php echo $selectedSem==$s?'selected':''; ?>>Semester <?php echo $s; ?></option>
                    <?php endfor; ?>
                  </select>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                  <label class="form-label">Subject</label>
                  <select name="subject_id" class="form-select" onchange="this.form.submit()">
                    <option value="0">Select Subject</option>
                    <?php foreach ($subjects as $sub): ?>
                    <option value="<?php echo $sub['id']; ?>" <?php echo $selectedSubject==$sub['id']?'selected':''; ?>><?php echo htmlspecialchars($sub['subject_code'].' - '.$sub['subject_name']); ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div style="margin-top:16px;">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Load Students</button>
              </div>
            </form>
          </div>
        </div>

        <?php if ($selectedSubject && !empty($loadStudents)): ?>
        <!-- Quick stats -->
        <div style="display:flex;gap:16px;margin-bottom:24px;flex-wrap:wrap;">
          <div class="glass-card" style="flex:1;min-width:200px;padding:16px 20px;display:flex;align-items:center;gap:12px;">
            <div class="stat-icon primary" style="width:40px;height:40px;font-size:1rem;"><i class="fas fa-users"></i></div>
            <div><div style="font-size:1.1rem;font-weight:700;"><?php echo count($loadStudents); ?></div><div style="font-size:0.78rem;color:var(--text-muted);">Total Students</div></div>
          </div>
          <div class="glass-card" style="flex:1;min-width:200px;padding:16px 20px;display:flex;align-items:center;gap:12px;">
            <div class="stat-icon success" style="width:40px;height:40px;font-size:1rem;"><i class="fas fa-check"></i></div>
            <div><div style="font-size:1.1rem;font-weight:700;color:var(--success);"><?php echo $presentCount; ?></div><div style="font-size:0.78rem;color:var(--text-muted);">Present</div></div>
          </div>
          <div class="glass-card" style="flex:1;min-width:200px;padding:16px 20px;display:flex;align-items:center;gap:12px;">
            <div class="stat-icon accent" style="width:40px;height:40px;font-size:1rem;"><i class="fas fa-times"></i></div>
            <div><div style="font-size:1.1rem;font-weight:700;color:var(--danger);"><?php echo $absentCount; ?></div><div style="font-size:0.78rem;color:var(--text-muted);">Absent</div></div>
          </div>
        </div>

        <!-- Attendance Marking Table -->
        <form method="POST">
          <input type="hidden" name="save_attendance" value="1">
          <input type="hidden" name="subject_id" value="<?php echo $selectedSubject; ?>">
          <input type="hidden" name="attendance_date" value="<?php echo htmlspecialchars($selectedDate); ?>">
          <input type="hidden" name="filter_course_id" value="<?php echo $selectedCourse; ?>">
          <input type="hidden" name="filter_semester" value="<?php echo $selectedSem; ?>">
          <input type="hidden" name="filter_subject_id" value="<?php echo $selectedSubject; ?>">
          <input type="hidden" name="filter_date" value="<?php echo htmlspecialchars($selectedDate); ?>">

          <div class="panel animate-fade-up" style="animation-delay:0.1s;">
            <div class="panel-header">
              <h3><i class="fas fa-clipboard-check" style="margin-right:8px;color:var(--secondary);"></i> Mark Attendance — <?php echo htmlspecialchars($selectedDate); ?></h3>
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
                    <?php foreach ($loadStudents as $idx => $stu):
                      $stuName = $stu['first_name'].' '.$stu['last_name'];
                      $stuInit = getInitials($stuName);
                      $grad = $gradients[$idx % count($gradients)];
                      $att = $existingAtt[$stu['id']] ?? null;
                      $isPresent = $att ? ($att['status'] === 'Present') : true;
                      $ovPct = $overallAtt[$stu['id']] ?? 0;
                      $attColor = $ovPct >= 75 ? 'var(--success)' : 'var(--danger)';
                    ?>
                    <tr <?php if (!$isPresent): ?>style="background:rgba(214,48,49,0.05);"<?php endif; ?>>
                      <td><?php echo $idx+1; ?></td>
                      <td><div class="table-user"><div class="table-avatar" style="background:<?php echo $grad; ?>;"><?php echo $stuInit; ?></div><div class="table-user-info"><div class="name"><?php echo htmlspecialchars($stuName); ?></div></div></div></td>
                      <td style="font-family:var(--font-mono);"><?php echo htmlspecialchars($stu['roll_number']); ?></td>
                      <td><span style="color:<?php echo $attColor; ?>;font-weight:600;"><?php echo $ovPct; ?>%</span></td>
                      <td style="text-align:center;">
                        <input type="hidden" name="student_id[]" value="<?php echo $stu['id']; ?>">
                        <input type="radio" name="status[<?php echo $idx; ?>]" value="Present" <?php echo $isPresent?'checked':''; ?> style="accent-color:var(--success);width:20px;height:20px;cursor:pointer;">
                      </td>
                      <td style="text-align:center;">
                        <input type="radio" name="status[<?php echo $idx; ?>]" value="Absent" <?php echo !$isPresent?'checked':''; ?> style="accent-color:var(--danger);width:20px;height:20px;cursor:pointer;">
                      </td>
                      <td><input type="text" name="remarks[]" class="form-input form-input-plain" style="padding:6px 10px;font-size:0.82rem;width:140px;" placeholder="Optional..." value="<?php echo htmlspecialchars($att['remarks'] ?? ''); ?>"></td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
            <div style="padding:20px 24px;border-top:1px solid var(--border-color);display:flex;justify-content:flex-end;gap:12px;">
              <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Save Attendance</button>
            </div>
          </div>
        </form>

        <?php
        // Low attendance warnings
        foreach ($loadStudents as $stu) {
            $ovPct = $overallAtt[$stu['id']] ?? 100;
            if ($ovPct < 75 && $ovPct > 0):
        ?>
        <div class="alert alert-danger animate-fade-up" style="margin-top:12px;">
          <i class="fas fa-exclamation-circle"></i>
          <span><strong><?php echo htmlspecialchars($stu['first_name'].' '.$stu['last_name'].' ('.$stu['roll_number'].')'); ?></strong> has only <?php echo $ovPct; ?>% attendance in this subject. Below the 75% minimum requirement.</span>
        </div>
        <?php endif; } ?>

        <?php endif; ?>
      </div>
    </div>
  </div>

  <script src="js/app.js?v=2.3"></script>
</body>
</html>
