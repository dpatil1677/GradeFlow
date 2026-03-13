<?php
require_once 'includes/db_connect.php';
require_once 'includes/auth.php';
requireAdmin();

$admin = getAdminInfo();
$initials = getInitials($admin['full_name']);

// Fetch stats
$totalStudents = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
$activeStudents = $pdo->query("SELECT COUNT(*) FROM students WHERE status='Active'")->fetchColumn();
$totalMarks = $pdo->query("SELECT COUNT(DISTINCT CONCAT(student_id,'-',subject_id)) FROM marks")->fetchColumn();

// Avg attendance
$avgAttendance = $pdo->query("SELECT ROUND(
    (SELECT COUNT(*) FROM attendance WHERE status='Present') * 100.0 / 
    NULLIF((SELECT COUNT(*) FROM attendance), 0), 1
)")->fetchColumn() ?: 0;

// Pass rate
$passRateResult = $pdo->query("
    SELECT ROUND(
        SUM(CASE WHEN total >= 40 THEN 1 ELSE 0 END) * 100.0 / NULLIF(COUNT(*), 0), 1
    ) as pass_rate
    FROM (
        SELECT student_id, subject_id, (internal_marks + external_marks) as total FROM marks
    ) sub
")->fetch();
$passRate = $passRateResult['pass_rate'] ?? 0;

// Recent students
$recentStudents = $pdo->query("
    SELECT s.*, c.short_name as course_short 
    FROM students s 
    JOIN courses c ON s.course_id = c.id 
    ORDER BY s.created_at DESC LIMIT 5
")->fetchAll();

// Recent activity - we'll build from recent marks and attendance
$recentMarks = $pdo->query("
    SELECT m.created_at, s.first_name, s.last_name, sub.subject_name, sub.semester, c.short_name, m.exam_type
    FROM marks m
    JOIN students s ON m.student_id = s.id
    JOIN subjects sub ON m.subject_id = sub.id
    JOIN courses c ON sub.course_id = c.id
    ORDER BY m.created_at DESC LIMIT 5
")->fetchAll();

// Avatar gradients
$gradients = [
    'var(--gradient-primary)',
    'var(--gradient-secondary)',
    'var(--gradient-accent)',
    'linear-gradient(135deg, #fdcb6e, #e17055)',
    'linear-gradient(135deg, #a29bfe, #6c5ce7)',
    'linear-gradient(135deg, #55efc4, #00b894)',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - GradeFlow</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>

  <div class="dashboard-layout">
    <!-- ===== SIDEBAR ===== -->
    <aside class="sidebar" id="sidebar">
      <div class="sidebar-header">
        <div class="logo-icon">🛡️</div>
        <div class="brand-text">
          GradeFlow
          <small>Admin Panel</small>
        </div>
      </div>

      <nav class="sidebar-nav">
        <div class="sidebar-nav-group">
          <div class="sidebar-nav-label">Overview</div>
          <a href="admin-dashboard.php" class="sidebar-link active">
            <span class="icon"><i class="fas fa-th-large"></i></span> Dashboard
          </a>
        </div>

        <div class="sidebar-nav-group">
          <div class="sidebar-nav-label">Student Management</div>
          <a href="add-student.php" class="sidebar-link">
            <span class="icon"><i class="fas fa-user-plus"></i></span> Add Student
          </a>
          <a href="manage-students.php" class="sidebar-link">
            <span class="icon"><i class="fas fa-users"></i></span> Manage Students
            <span class="badge"><?php echo $totalStudents; ?></span>
          </a>
        </div>

        <div class="sidebar-nav-group">
          <div class="sidebar-nav-label">Academics</div>
          <a href="add-marks.php" class="sidebar-link">
            <span class="icon"><i class="fas fa-pen-alt"></i></span> Add Marks
          </a>
          <a href="manage-attendance.php" class="sidebar-link">
            <span class="icon"><i class="fas fa-clipboard-check"></i></span> Attendance
          </a>
        </div>

        <div class="sidebar-nav-group">
          <div class="sidebar-nav-label">System</div>
          <a href="logout.php" class="sidebar-link">
            <span class="icon"><i class="fas fa-sign-out-alt"></i></span> Logout
          </a>
        </div>
      </nav>

      <div class="sidebar-footer">
        <div class="sidebar-user">
          <div class="sidebar-user-avatar" style="background:var(--gradient-accent);"><?php echo $initials; ?></div>
          <div class="sidebar-user-info">
            <div class="name"><?php echo htmlspecialchars($admin['full_name']); ?></div>
            <div class="role">Super Administrator</div>
          </div>
        </div>
      </div>
    </aside>

    <!-- ===== MAIN CONTENT ===== -->
    <div class="main-content">
      <!-- Topbar -->
      <header class="topbar">
        <div class="topbar-left">
          <button class="sidebar-toggle" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
          </button>
          <div class="topbar-title">
            <h2>Admin Dashboard</h2>
            <p>System overview &amp; management</p>
          </div>
        </div>
        <div class="topbar-right">
          <div class="topbar-search">
            <span class="search-icon"><i class="fas fa-search"></i></span>
            <input type="text" placeholder="Search students, results...">
          </div>
        </div>
      </header>

      <!-- Dashboard Content -->
      <div class="dashboard-content">

        <!-- Stat Cards -->
        <div class="stats-grid">
          <div class="stat-card animate-fade-up delay-1">
            <div class="stat-card-header">
              <div class="stat-icon primary"><i class="fas fa-user-graduate"></i></div>
            </div>
            <div class="stat-value"><?php echo number_format($totalStudents); ?></div>
            <div class="stat-label">Total Students</div>
          </div>

          <div class="stat-card animate-fade-up delay-2">
            <div class="stat-card-header">
              <div class="stat-icon secondary"><i class="fas fa-check-double"></i></div>
            </div>
            <div class="stat-value"><?php echo $passRate; ?>%</div>
            <div class="stat-label">Overall Pass Rate</div>
          </div>

          <div class="stat-card animate-fade-up delay-3">
            <div class="stat-card-header">
              <div class="stat-icon accent"><i class="fas fa-file-alt"></i></div>
            </div>
            <div class="stat-value"><?php echo number_format($totalMarks); ?></div>
            <div class="stat-label">Results Published</div>
          </div>

          <div class="stat-card animate-fade-up delay-4">
            <div class="stat-card-header">
              <div class="stat-icon success"><i class="fas fa-calendar-day"></i></div>
            </div>
            <div class="stat-value"><?php echo $avgAttendance; ?>%</div>
            <div class="stat-label">Avg Attendance</div>
          </div>
        </div>

        <!-- Main Grid -->
        <div class="dashboard-grid">
          <!-- Recent Students Table -->
          <div class="panel">
            <div class="panel-header">
              <h3><i class="fas fa-users" style="margin-right:8px;color:var(--primary-light);"></i> Recent Students</h3>
              <div class="panel-actions">
                <a href="manage-students.php" class="btn btn-ghost btn-sm">View All</a>
                <a href="add-student.php" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add</a>
              </div>
            </div>
            <div class="panel-body" style="padding:0;">
              <div class="data-table-wrapper">
                <table class="data-table">
                  <thead>
                    <tr>
                      <th>Student</th>
                      <th>Roll No</th>
                      <th>Course</th>
                      <th>Sem</th>
                      <th>Status</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($recentStudents as $i => $stu): 
                      $stuInitials = getInitials($stu['first_name'] . ' ' . $stu['last_name']);
                      $gradient = $gradients[$i % count($gradients)];
                    ?>
                    <tr>
                      <td>
                        <div class="table-user">
                          <div class="table-avatar" style="background:<?php echo $gradient; ?>;"><?php echo $stuInitials; ?></div>
                          <div class="table-user-info">
                            <div class="name"><?php echo htmlspecialchars($stu['first_name'] . ' ' . $stu['last_name']); ?></div>
                            <div class="email"><?php echo htmlspecialchars($stu['email']); ?></div>
                          </div>
                        </div>
                      </td>
                      <td><?php echo htmlspecialchars($stu['roll_number']); ?></td>
                      <td><?php echo htmlspecialchars($stu['course_short']); ?></td>
                      <td><?php echo $stu['semester']; ?></td>
                      <td><span class="status-badge <?php echo strtolower($stu['status']); ?>"><?php echo $stu['status']; ?></span></td>
                      <td>
                        <div class="table-actions">
                          <a href="manage-students.php?view=<?php echo $stu['id']; ?>" class="table-action-btn view"><i class="fas fa-eye"></i></a>
                          <a href="manage-students.php?edit=<?php echo $stu['id']; ?>" class="table-action-btn edit"><i class="fas fa-edit"></i></a>
                        </div>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($recentStudents)): ?>
                    <tr><td colspan="6" style="text-align:center;padding:40px;color:var(--text-muted);">No students yet. <a href="add-student.php">Add one</a></td></tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Quick Actions & Activity -->
          <div style="display:flex;flex-direction:column;gap:24px;">
            <!-- Quick Actions -->
            <div class="panel">
              <div class="panel-header">
                <h3><i class="fas fa-bolt" style="margin-right:8px;color:var(--warning);"></i> Quick Actions</h3>
              </div>
              <div class="panel-body">
                <div class="quick-actions-grid">
                  <a href="add-student.php" class="quick-action-btn">
                    <div class="icon" style="background:rgba(108,92,231,0.15);color:var(--primary-light);"><i class="fas fa-user-plus"></i></div>
                    <span>Add Student</span>
                  </a>
                  <a href="add-marks.php" class="quick-action-btn">
                    <div class="icon" style="background:rgba(0,206,201,0.15);color:var(--secondary);"><i class="fas fa-pen-alt"></i></div>
                    <span>Enter Marks</span>
                  </a>
                  <a href="manage-attendance.php" class="quick-action-btn">
                    <div class="icon" style="background:rgba(253,121,168,0.15);color:var(--accent);"><i class="fas fa-clipboard-check"></i></div>
                    <span>Attendance</span>
                  </a>
                  <a href="manage-students.php" class="quick-action-btn">
                    <div class="icon" style="background:rgba(0,184,148,0.15);color:var(--success);"><i class="fas fa-users"></i></div>
                    <span>All Students</span>
                  </a>
                </div>
              </div>
            </div>

            <!-- Activity Feed -->
            <div class="panel" style="flex:1;">
              <div class="panel-header">
                <h3><i class="fas fa-history" style="margin-right:8px;color:var(--secondary);"></i> Recent Activity</h3>
              </div>
              <div class="panel-body">
                <div class="activity-feed">
                  <?php if (!empty($recentMarks)): ?>
                  <?php foreach (array_slice($recentMarks, 0, 4) as $m): ?>
                  <div class="activity-item">
                    <div class="activity-icon" style="background:rgba(0,206,201,0.15);color:var(--secondary);">
                      <i class="fas fa-pen-alt"></i>
                    </div>
                    <div class="activity-content">
                      <p><strong><?php echo htmlspecialchars($m['exam_type']); ?> marks</strong> entered for <?php echo htmlspecialchars($m['first_name'] . ' ' . $m['last_name']); ?> — <?php echo htmlspecialchars($m['subject_name']); ?></p>
                      <div class="activity-time"><?php echo date('M j, Y', strtotime($m['created_at'])); ?></div>
                    </div>
                  </div>
                  <?php endforeach; ?>
                  <?php else: ?>
                  <div class="activity-item">
                    <div class="activity-icon" style="background:rgba(108,92,231,0.15);color:var(--primary-light);">
                      <i class="fas fa-info-circle"></i>
                    </div>
                    <div class="activity-content">
                      <p>No recent activity. Start by adding students and entering marks.</p>
                    </div>
                  </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <script src="js/app.js"></script>
</body>
</html>
