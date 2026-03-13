<?php
require_once 'includes/db_connect.php';
require_once 'includes/auth.php';

$result = null;
$error = '';
$searchRoll = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['roll'])) {
    $searchRoll = trim($_POST['roll_number'] ?? $_GET['roll'] ?? '');
    
    if (empty($searchRoll)) {
        $error = 'Please enter a roll number.';
    } else {
        // Find student
        $stmt = $pdo->prepare("SELECT s.*, c.course_name, c.short_name FROM students s JOIN courses c ON s.course_id=c.id WHERE s.roll_number = ?");
        $stmt->execute([$searchRoll]);
        $studentData = $stmt->fetch();
        
        if (!$studentData) {
            $error = 'No student found with roll number: ' . $searchRoll;
        } else {
            // Get latest semester marks
            $latestSem = $pdo->prepare("SELECT MAX(sub.semester) FROM marks m JOIN subjects sub ON m.subject_id=sub.id WHERE m.student_id=? AND sub.course_id=?");
            $latestSem->execute([$studentData['id'], $studentData['course_id']]);
            $latestSemester = $latestSem->fetchColumn();
            
            if (!$latestSemester) {
                $error = 'No results published yet for this student.';
            } else {
                $marksStmt = $pdo->prepare("
                    SELECT m.*, sub.subject_name, sub.subject_code, sub.max_internal, sub.max_external
                    FROM marks m
                    JOIN subjects sub ON m.subject_id=sub.id
                    WHERE m.student_id=? AND sub.semester=? AND sub.course_id=?
                    ORDER BY sub.subject_code
                ");
                $marksStmt->execute([$studentData['id'], $latestSemester, $studentData['course_id']]);
                $marks = $marksStmt->fetchAll();
                
                $totalObt = 0;
                $totalMax = 0;
                $subjects = [];
                $passed = true;
                
                foreach ($marks as $m) {
                    $total = $m['internal_marks'] + $m['external_marks'];
                    $max = $m['max_internal'] + $m['max_external'];
                    $pct = $max > 0 ? round($total * 100 / $max, 1) : 0;
                    $grade = calculateGrade($pct);
                    $subPassed = $pct >= 40;
                    if (!$subPassed) $passed = false;
                    
                    $totalObt += $total;
                    $totalMax += $max;
                    $subjects[] = [
                        'code' => $m['subject_code'],
                        'name' => $m['subject_name'],
                        'internal' => intval($m['internal_marks']),
                        'external' => intval($m['external_marks']),
                        'max_internal' => $m['max_internal'],
                        'max_external' => $m['max_external'],
                        'total' => $total,
                        'max' => $max,
                        'pct' => $pct,
                        'grade' => $grade,
                        'passed' => $subPassed,
                        'exam' => $m['exam_type'],
                    ];
                }
                
                $overallPct = $totalMax > 0 ? round($totalObt * 100 / $totalMax, 1) : 0;
                $sgpa = round($overallPct / 9.5, 2);
                
                $result = [
                    'student' => $studentData,
                    'semester' => $latestSemester,
                    'subjects' => $subjects,
                    'total_obtained' => $totalObt,
                    'total_max' => $totalMax,
                    'percentage' => $overallPct,
                    'sgpa' => $sgpa,
                    'grade' => calculateGrade($overallPct),
                    'result_class' => getResultClass($overallPct),
                    'passed' => $passed,
                ];
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search Results - GradeFlow</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>

  <!-- Background Orbs -->
  <div class="hero-bg" style="position:fixed;z-index:0;">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
    <div class="hero-grid"></div>
  </div>

  <!-- ===== NAVBAR (same as index.php) ===== -->
  <nav class="navbar" id="navbar">
    <div class="container">
      <a href="index.php" class="navbar-brand">
        <div class="logo-icon">🎓</div>
        <span>Grade<span class="text-gradient">Flow</span></span>
      </a>

      <div class="navbar-nav" id="navMenu">
        <a href="index.php">Home</a>
        <a href="index.php#features">Features</a>
        <a href="index.php#how-it-works">How it Works</a>
        <a href="result-search.php" class="active">Results</a>
        <a href="index.php#contact">Contact</a>
      </div>

      <div class="navbar-actions">
        <a href="student-login.php" class="btn btn-ghost btn-sm">Student Login</a>
        <a href="admin-login.php" class="btn btn-primary btn-sm">Admin Portal</a>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <div style="position:relative;z-index:2;max-width:900px;margin:0 auto;padding:120px 20px 40px;">

    <!-- Search Section -->
    <div style="text-align:center;margin-bottom:40px;" class="animate-fade-up">
      <div class="hero-badge" style="margin-bottom:24px;">
        <i class="fas fa-search"></i>
        <span>RESULT LOOKUP</span>
      </div>
      <h1 style="font-size:2.8rem;font-weight:800;margin-bottom:16px;font-family:var(--font-heading);">
        Search Your <span class="text-gradient">Results</span>
      </h1>
      <p style="color:var(--text-secondary);font-size:1.05rem;margin-bottom:36px;max-width:500px;margin-left:auto;margin-right:auto;">
        Enter your roll number to instantly access your examination results
      </p>

      <form method="POST" style="max-width:520px;margin:0 auto;">
        <div style="display:flex;gap:12px;background:var(--bg-glass);border:1px solid var(--border-color);border-radius:var(--radius-lg);padding:6px;backdrop-filter:blur(20px);">
          <div style="flex:1;position:relative;display:flex;align-items:center;">
            <i class="fas fa-search" style="position:absolute;left:16px;color:var(--text-muted);font-size:0.95rem;"></i>
            <input type="text" name="roll_number" placeholder="CS2025001" value="<?php echo htmlspecialchars($searchRoll); ?>" required
              style="width:100%;padding:12px 16px 12px 44px;background:transparent;border:none;color:var(--text-primary);font-size:0.95rem;font-family:inherit;outline:none;">
          </div>
          <button type="submit" class="btn btn-primary btn-sm" style="border-radius:var(--radius-md);white-space:nowrap;">
            <i class="fas fa-arrow-right"></i> Search
          </button>
        </div>
      </form>
    </div>

    <?php if ($error): ?>
    <div class="alert alert-danger animate-fade-up" style="margin-top:24px;">
      <i class="fas fa-exclamation-circle"></i> <span><?php echo htmlspecialchars($error); ?></span>
    </div>
    <?php endif; ?>

    <?php if ($result): 
      $stu = $result['student'];
      $stuInitials = getInitials($stu['first_name'] . ' ' . $stu['last_name']);
    ?>
    <!-- Student Info -->
    <div class="panel animate-fade-up" style="margin-top:24px;">
      <div class="panel-body" style="display:flex;align-items:center;gap:20px;flex-wrap:wrap;">
        <div class="table-avatar" style="background:var(--gradient-primary);width:56px;height:56px;font-size:1.2rem;"><?php echo $stuInitials; ?></div>
        <div style="flex:1;">
          <div style="font-size:1.1rem;font-weight:700;"><?php echo htmlspecialchars($stu['first_name'] . ' ' . $stu['last_name']); ?></div>
          <div style="font-size:0.85rem;color:var(--text-muted);">Roll: <?php echo htmlspecialchars($stu['roll_number']); ?> | <?php echo htmlspecialchars($stu['course_name']); ?> | Semester <?php echo $result['semester']; ?></div>
        </div>
        <div style="display:flex;gap:24px;text-align:center;">
          <div>
            <div style="font-size:1.2rem;font-weight:800;color:<?php echo $result['passed'] ? 'var(--success)' : 'var(--danger)'; ?>;"><?php echo $result['percentage']; ?>%</div>
            <div style="font-size:0.72rem;color:var(--text-muted);">Percentage</div>
          </div>
          <div>
            <div style="font-size:1.2rem;font-weight:800;color:var(--primary-light);"><?php echo $result['sgpa']; ?></div>
            <div style="font-size:0.72rem;color:var(--text-muted);">SGPA</div>
          </div>
          <div>
            <span class="grade-badge <?php echo gradeClass($result['grade']); ?>" style="font-size:1rem;"><?php echo $result['grade']; ?></span>
            <div style="font-size:0.72rem;color:var(--text-muted);margin-top:4px;">Grade</div>
          </div>
          <div>
            <span class="status-badge <?php echo $result['passed'] ? 'pass' : 'fail'; ?>"><?php echo $result['passed'] ? 'PASSED' : 'FAILED'; ?></span>
            <div style="font-size:0.72rem;color:var(--text-muted);margin-top:4px;">Result</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Results Table -->
    <div class="panel animate-fade-up" style="margin-top:16px;animation-delay:0.1s;">
      <div class="panel-header">
        <h3><i class="fas fa-file-alt" style="margin-right:8px;color:var(--primary-light);"></i> Semester <?php echo $result['semester']; ?> Results</h3>
      </div>
      <div class="panel-body" style="padding:0;">
        <div class="data-table-wrapper">
          <table class="data-table">
            <thead>
              <tr>
                <th>Code</th>
                <th>Subject</th>
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
              <?php foreach ($result['subjects'] as $s): ?>
              <tr>
                <td style="font-family:var(--font-mono);"><?php echo htmlspecialchars($s['code']); ?></td>
                <td><?php echo htmlspecialchars($s['name']); ?></td>
                <td><?php echo htmlspecialchars($s['exam']); ?></td>
                <td><?php echo $s['internal']; ?>/<?php echo $s['max_internal']; ?></td>
                <td><?php echo $s['external']; ?>/<?php echo $s['max_external']; ?></td>
                <td><strong><?php echo intval($s['total']); ?>/<?php echo $s['max']; ?></strong></td>
                <td><strong style="color:<?php echo $s['passed']?'var(--success)':'var(--danger)'; ?>;"><?php echo $s['pct']; ?>%</strong></td>
                <td><span class="grade-badge <?php echo gradeClass($s['grade']); ?>"><?php echo $s['grade']; ?></span></td>
                <td><span class="status-badge <?php echo $s['passed']?'pass':'fail'; ?>"><?php echo $s['passed']?'Pass':'Fail'; ?></span></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
            <tfoot>
              <tr style="font-weight:700;border-top:2px solid var(--border-color);">
                <td colspan="3">Total</td>
                <td>-</td>
                <td>-</td>
                <td><?php echo intval($result['total_obtained']); ?>/<?php echo $result['total_max']; ?></td>
                <td style="color:<?php echo $result['passed']?'var(--success)':'var(--danger)'; ?>;"><?php echo $result['percentage']; ?>%</td>
                <td><span class="grade-badge <?php echo gradeClass($result['grade']); ?>"><?php echo $result['grade']; ?></span></td>
                <td><span class="status-badge <?php echo $result['passed']?'pass':'fail'; ?>"><?php echo $result['passed']?'Pass':'Fail'; ?></span></td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>

    <div style="text-align:center;margin-top:24px;">
      <a href="student-login.php" class="btn btn-ghost"><i class="fas fa-sign-in-alt"></i> Login for Full Results</a>
    </div>
    <?php endif; ?>

  </div>

  <script>
    // Navbar scroll effect
    window.addEventListener('scroll', () => {
      const navbar = document.getElementById('navbar');
      navbar.classList.toggle('scrolled', window.scrollY > 40);
    });
  </script>
</body>
</html>
