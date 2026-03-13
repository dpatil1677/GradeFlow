<?php
require_once 'includes/db_connect.php';
require_once 'includes/auth.php';
requireAdmin();

$admin = getAdminInfo();
$initials = getInitials($admin['full_name']);
$totalStudents = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();

$courses = $pdo->query("SELECT * FROM courses ORDER BY course_name")->fetchAll();
$success = '';
$error = '';

// Handle save marks
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_marks'])) {
    $studentIds = $_POST['student_id'] ?? [];
    $subjectId  = intval($_POST['subject_id']);
    $examType   = $_POST['exam_type'];
    $internals  = $_POST['internal'] ?? [];
    $externals  = $_POST['external'] ?? [];

    $stmt = $pdo->prepare("INSERT INTO marks (student_id, subject_id, exam_type, internal_marks, external_marks) VALUES (?,?,?,?,?) ON DUPLICATE KEY UPDATE internal_marks=VALUES(internal_marks), external_marks=VALUES(external_marks)");

    $count = 0;
    foreach ($studentIds as $idx => $sid) {
        $int = floatval($internals[$idx] ?? 0);
        $ext = floatval($externals[$idx] ?? 0);
        $stmt->execute([intval($sid), $subjectId, $examType, $int, $ext]);
        $count++;
    }
    $success = "Marks saved for $count students successfully!";
}

// Load students based on filter
$loadStudents = [];
$selectedCourse = intval($_GET['course_id'] ?? $_POST['filter_course_id'] ?? 0);
$selectedSem = intval($_GET['semester'] ?? $_POST['filter_semester'] ?? 0);
$selectedSubject = intval($_GET['subject_id'] ?? $_POST['filter_subject_id'] ?? 0);
$selectedExam = $_GET['exam_type'] ?? $_POST['filter_exam_type'] ?? 'Midterm';
$subjects = [];

if ($selectedCourse && $selectedSem) {
    $subjects = $pdo->prepare("SELECT * FROM subjects WHERE course_id = ? AND semester = ? ORDER BY subject_code");
    $subjects->execute([$selectedCourse, $selectedSem]);
    $subjects = $subjects->fetchAll();
}

if ($selectedCourse && $selectedSem) {
    $stmt = $pdo->prepare("SELECT s.*, c.short_name as course_short FROM students s JOIN courses c ON s.course_id=c.id WHERE s.course_id=? AND s.semester=? ORDER BY s.roll_number");
    $stmt->execute([$selectedCourse, $selectedSem]);
    $loadStudents = $stmt->fetchAll();

    // Get existing marks
    if ($selectedSubject) {
        $existingMarks = [];
        $mStmt = $pdo->prepare("SELECT * FROM marks WHERE subject_id=? AND exam_type=?");
        $mStmt->execute([$selectedSubject, $selectedExam]);
        foreach ($mStmt->fetchAll() as $m) {
            $existingMarks[$m['student_id']] = $m;
        }
    }
}

// Get subject info
$subjectInfo = null;
if ($selectedSubject) {
    $subjectInfo = $pdo->prepare("SELECT sub.*, c.short_name FROM subjects sub JOIN courses c ON sub.course_id=c.id WHERE sub.id=?");
    $subjectInfo->execute([$selectedSubject]);
    $subjectInfo = $subjectInfo->fetch();
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
  <title>Add Marks - GradeFlow Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/dashboard.css">
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
          <a href="add-marks.php" class="sidebar-link active"><span class="icon"><i class="fas fa-pen-alt"></i></span> Add Marks</a>
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

    <div class="main-content">
      <header class="topbar">
        <div class="topbar-left">
          <button class="sidebar-toggle" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
          <div class="topbar-title"><h2>Add / Edit Marks</h2><p>Enter examination marks for students</p></div>
        </div>
      </header>

      <div class="dashboard-content">

        <?php if ($success): ?>
        <div class="alert alert-success animate-fade-down" style="margin-bottom:20px;">
          <i class="fas fa-check-circle"></i> <span><?php echo htmlspecialchars($success); ?></span>
        </div>
        <?php endif; ?>

        <!-- Selection Filters -->
        <div class="panel animate-fade-up" style="margin-bottom:28px;">
          <div class="panel-header">
            <h3><i class="fas fa-filter" style="margin-right:8px;color:var(--primary-light);"></i> Select Class & Subject</h3>
          </div>
          <div class="panel-body">
            <form method="GET" id="filterForm">
              <div class="form-row" style="grid-template-columns: repeat(4, 1fr);">
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
                <div class="form-group" style="margin-bottom:0;">
                  <label class="form-label">Exam Type</label>
                  <select name="exam_type" class="form-select" onchange="this.form.submit()">
                    <?php foreach (['Midterm','Final','Internal','Practical'] as $et): ?>
                    <option value="<?php echo $et; ?>" <?php echo $selectedExam===$et?'selected':''; ?>><?php echo $et; ?> Examination</option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
            </form>
          </div>
        </div>

        <?php if ($subjectInfo && !empty($loadStudents)): ?>
        <!-- Marks Info -->
        <div class="alert alert-info animate-fade-up" style="animation-delay:0.1s;">
          <i class="fas fa-info-circle"></i>
          <span><strong><?php echo htmlspecialchars($subjectInfo['subject_code'].' - '.$subjectInfo['subject_name']); ?></strong> | <?php echo htmlspecialchars($subjectInfo['short_name']); ?> - Semester <?php echo $subjectInfo['semester']; ?> | <?php echo $selectedExam; ?> Examination | Max Internal: <?php echo $subjectInfo['max_internal']; ?>, Max External: <?php echo $subjectInfo['max_external']; ?></span>
        </div>

        <!-- Marks Entry Table -->
        <form method="POST">
          <input type="hidden" name="save_marks" value="1">
          <input type="hidden" name="subject_id" value="<?php echo $selectedSubject; ?>">
          <input type="hidden" name="exam_type" value="<?php echo htmlspecialchars($selectedExam); ?>">
          <input type="hidden" name="filter_course_id" value="<?php echo $selectedCourse; ?>">
          <input type="hidden" name="filter_semester" value="<?php echo $selectedSem; ?>">
          <input type="hidden" name="filter_subject_id" value="<?php echo $selectedSubject; ?>">
          <input type="hidden" name="filter_exam_type" value="<?php echo htmlspecialchars($selectedExam); ?>">

          <div class="panel animate-fade-up" style="animation-delay:0.2s;">
            <div class="panel-header">
              <h3><i class="fas fa-pen-alt" style="margin-right:8px;color:var(--secondary);"></i> Enter Marks</h3>
              <div class="panel-actions">
                <span style="font-size:0.85rem;color:var(--text-muted);"><?php echo count($loadStudents); ?> students loaded</span>
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
                      <th>Internal (Max: <?php echo $subjectInfo['max_internal']; ?>)</th>
                      <th>External (Max: <?php echo $subjectInfo['max_external']; ?>)</th>
                      <th>Total</th>
                      <th>Grade</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($loadStudents as $idx => $stu):
                      $stuName = $stu['first_name'].' '.$stu['last_name'];
                      $stuInit = getInitials($stuName);
                      $grad = $gradients[$idx % count($gradients)];
                      $existing = $existingMarks[$stu['id']] ?? null;
                      $intVal = $existing ? intval($existing['internal_marks']) : '';
                      $extVal = $existing ? intval($existing['external_marks']) : '';
                      $total = ($intVal !== '' && $extVal !== '') ? $intVal + $extVal : 0;
                      $grade = $total > 0 ? calculateGrade($total) : '-';
                      $pass = $total >= 40;
                    ?>
                    <tr>
                      <td><?php echo $idx+1; ?></td>
                      <td>
                        <div class="table-user">
                          <div class="table-avatar" style="background:<?php echo $grad; ?>;"><?php echo $stuInit; ?></div>
                          <div class="table-user-info"><div class="name"><?php echo htmlspecialchars($stuName); ?></div></div>
                        </div>
                      </td>
                      <td style="font-family:var(--font-mono);"><?php echo htmlspecialchars($stu['roll_number']); ?></td>
                      <td>
                        <input type="hidden" name="student_id[]" value="<?php echo $stu['id']; ?>">
                        <input type="number" name="internal[]" class="form-input form-input-plain" style="width:80px;padding:8px 12px;text-align:center;" value="<?php echo $intVal; ?>" min="0" max="<?php echo $subjectInfo['max_internal']; ?>">
                      </td>
                      <td>
                        <input type="number" name="external[]" class="form-input form-input-plain" style="width:80px;padding:8px 12px;text-align:center;" value="<?php echo $extVal; ?>" min="0" max="<?php echo $subjectInfo['max_external']; ?>">
                      </td>
                      <td><strong style="color:<?php echo $pass||$total==0?'var(--success)':'var(--danger)'; ?>;"><?php echo $total ?: '-'; ?></strong></td>
                      <td><?php if ($total > 0): ?><span class="grade-badge <?php echo gradeClass($grade); ?>"><?php echo $grade; ?></span><?php else: ?>-<?php endif; ?></td>
                      <td><?php if ($total > 0): ?><span class="status-badge <?php echo $pass?'pass':'fail'; ?>"><?php echo $pass?'Pass':'Fail'; ?></span><?php else: ?>-<?php endif; ?></td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>

            <div style="padding:20px 24px;border-top:1px solid var(--border-color);display:flex;justify-content:flex-end;gap:12px;">
              <button type="submit" class="btn btn-success"><i class="fas fa-check-circle"></i> Submit Marks</button>
            </div>
          </div>
        </form>
        <?php elseif ($selectedCourse && $selectedSem && $selectedSubject && empty($loadStudents)): ?>
        <div class="alert alert-warning">
          <i class="fas fa-exclamation-triangle"></i>
          <span>No students found for the selected course and semester.</span>
        </div>
        <?php endif; ?>

      </div>
    </div>
  </div>

  <script src="js/app.js"></script>
</body>
</html>
