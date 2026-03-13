<?php
require_once 'includes/db_connect.php';
require_once 'includes/auth.php';
requireAdmin();

$admin = getAdminInfo();
$initials = getInitials($admin['full_name']);

// Handle delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delId = intval($_POST['delete_id']);
    $pdo->prepare("DELETE FROM students WHERE id = ?")->execute([$delId]);
    header('Location: manage-students.php?deleted=1');
    exit;
}

// Filters
$search   = trim($_GET['search'] ?? '');
$courseF   = intval($_GET['course'] ?? 0);
$semF     = intval($_GET['semester'] ?? 0);
$statusF  = $_GET['status'] ?? '';
$page     = max(1, intval($_GET['page'] ?? 1));
$perPage  = 6;
$offset   = ($page - 1) * $perPage;

// Build query
$where = [];
$params = [];

if ($search) {
    $where[] = "(s.first_name LIKE ? OR s.last_name LIKE ? OR s.roll_number LIKE ? OR s.email LIKE ?)";
    $params = array_merge($params, ["%$search%", "%$search%", "%$search%", "%$search%"]);
}
if ($courseF) {
    $where[] = "s.course_id = ?";
    $params[] = $courseF;
}
if ($semF) {
    $where[] = "s.semester = ?";
    $params[] = $semF;
}
if ($statusF) {
    $where[] = "s.status = ?";
    $params[] = $statusF;
}

$whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// Count total
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM students s $whereClause");
$countStmt->execute($params);
$totalFiltered = $countStmt->fetchColumn();
$totalPages = max(1, ceil($totalFiltered / $perPage));

// Fetch students
$sql = "SELECT s.*, c.short_name as course_short FROM students s JOIN courses c ON s.course_id = c.id $whereClause ORDER BY s.created_at DESC LIMIT $perPage OFFSET $offset";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$students = $stmt->fetchAll();

// Stats
$totalStudents  = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
$activeStudents = $pdo->query("SELECT COUNT(*) FROM students WHERE status='Active'")->fetchColumn();
$inactiveStudents = $pdo->query("SELECT COUNT(*) FROM students WHERE status='Inactive'")->fetchColumn();
$graduatedStudents = $pdo->query("SELECT COUNT(*) FROM students WHERE status='Graduated'")->fetchColumn();

// Courses for filter
$courses = $pdo->query("SELECT * FROM courses ORDER BY course_name")->fetchAll();

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
  <title>Manage Students - GradeFlow Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/dashboard.css">
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
          <a href="add-student.php" class="sidebar-link"><span class="icon"><i class="fas fa-user-plus"></i></span> Add Student</a>
          <a href="manage-students.php" class="sidebar-link active"><span class="icon"><i class="fas fa-users"></i></span> Manage Students <span class="badge"><?php echo $totalStudents; ?></span></a>
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
          <div class="topbar-title"><h2>Manage Students</h2><p>View, edit and manage all student records</p></div>
        </div>
        <div class="topbar-right">
          <a href="add-student.php" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add Student</a>
        </div>
      </header>

      <div class="dashboard-content">

        <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-success animate-fade-down" style="margin-bottom:20px;">
          <i class="fas fa-check-circle"></i> <span>Student deleted successfully.</span>
        </div>
        <?php endif; ?>

        <!-- Stats -->
        <div class="stats-grid" style="grid-template-columns: repeat(4, 1fr); margin-bottom: 28px;">
          <div class="stat-card">
            <div class="stat-card-header"><div class="stat-icon primary"><i class="fas fa-users"></i></div></div>
            <div class="stat-value"><?php echo $totalStudents; ?></div>
            <div class="stat-label">Total Students</div>
          </div>
          <div class="stat-card">
            <div class="stat-card-header"><div class="stat-icon success"><i class="fas fa-user-check"></i></div></div>
            <div class="stat-value"><?php echo $activeStudents; ?></div>
            <div class="stat-label">Active Students</div>
          </div>
          <div class="stat-card">
            <div class="stat-card-header"><div class="stat-icon warning"><i class="fas fa-user-clock"></i></div></div>
            <div class="stat-value"><?php echo $inactiveStudents; ?></div>
            <div class="stat-label">Inactive</div>
          </div>
          <div class="stat-card">
            <div class="stat-card-header"><div class="stat-icon accent"><i class="fas fa-user-graduate"></i></div></div>
            <div class="stat-value"><?php echo $graduatedStudents; ?></div>
            <div class="stat-label">Graduated</div>
          </div>
        </div>

        <!-- Filters -->
        <form method="GET" class="filter-bar">
          <div class="topbar-search" style="flex:1;max-width:320px;">
            <span class="search-icon"><i class="fas fa-search"></i></span>
            <input type="text" name="search" placeholder="Search by name or roll number..." style="width:100%;" value="<?php echo htmlspecialchars($search); ?>">
          </div>
          <select name="course" class="form-select">
            <option value="0">All Courses</option>
            <?php foreach ($courses as $c): ?>
            <option value="<?php echo $c['id']; ?>" <?php echo $courseF == $c['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($c['course_name']); ?></option>
            <?php endforeach; ?>
          </select>
          <select name="semester" class="form-select">
            <option value="0">All Semesters</option>
            <?php for ($s=1;$s<=8;$s++): ?>
            <option value="<?php echo $s; ?>" <?php echo $semF == $s ? 'selected' : ''; ?>>Semester <?php echo $s; ?></option>
            <?php endfor; ?>
          </select>
          <select name="status" class="form-select">
            <option value="">All Status</option>
            <option value="Active" <?php echo $statusF==='Active'?'selected':''; ?>>Active</option>
            <option value="Inactive" <?php echo $statusF==='Inactive'?'selected':''; ?>>Inactive</option>
            <option value="Graduated" <?php echo $statusF==='Graduated'?'selected':''; ?>>Graduated</option>
          </select>
          <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Filter</button>
        </form>

        <!-- Students Table -->
        <div class="panel animate-fade-up">
          <div class="panel-body" style="padding:0;">
            <div class="data-table-wrapper">
              <table class="data-table">
                <thead>
                  <tr>
                    <th>Student</th>
                    <th>Roll No</th>
                    <th>Course</th>
                    <th>Semester</th>
                    <th>Section</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($students as $i => $stu):
                    $stuName = $stu['first_name'] . ' ' . $stu['last_name'];
                    $stuInit = getInitials($stuName);
                    $grad = $gradients[$i % count($gradients)];
                  ?>
                  <tr>
                    <td>
                      <div class="table-user">
                        <div class="table-avatar" style="background:<?php echo $grad; ?>;"><?php echo $stuInit; ?></div>
                        <div class="table-user-info">
                          <div class="name"><?php echo htmlspecialchars($stuName); ?></div>
                          <div class="email"><?php echo htmlspecialchars($stu['email']); ?></div>
                        </div>
                      </div>
                    </td>
                    <td style="font-family:var(--font-mono);"><?php echo htmlspecialchars($stu['roll_number']); ?></td>
                    <td><?php echo htmlspecialchars($stu['course_short']); ?></td>
                    <td><?php echo $stu['semester']; ?></td>
                    <td><?php echo htmlspecialchars($stu['section']); ?></td>
                    <td><span class="status-badge <?php echo strtolower($stu['status']); ?>"><?php echo $stu['status']; ?></span></td>
                    <td>
                      <div class="table-actions">
                        <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this student? This action cannot be undone.');">
                          <input type="hidden" name="delete_id" value="<?php echo $stu['id']; ?>">
                          <button type="submit" class="table-action-btn delete" title="Delete"><i class="fas fa-trash-alt"></i></button>
                        </form>
                      </div>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                  <?php if (empty($students)): ?>
                  <tr><td colspan="7" style="text-align:center;padding:40px;color:var(--text-muted);">No students found matching your criteria.</td></tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Pagination -->
          <?php if ($totalPages > 1): ?>
          <div class="pagination">
            <div class="pagination-info">Showing <?php echo $offset+1; ?>-<?php echo min($offset+$perPage, $totalFiltered); ?> of <?php echo $totalFiltered; ?> students</div>
            <div class="pagination-buttons">
              <?php
              $qp = $_GET;
              if ($page > 1): $qp['page'] = $page - 1; ?>
              <a href="?<?php echo http_build_query($qp); ?>" class="pagination-btn"><i class="fas fa-chevron-left"></i></a>
              <?php endif; ?>
              <?php for ($p = max(1, $page-2); $p <= min($totalPages, $page+2); $p++):
                $qp['page'] = $p; ?>
              <a href="?<?php echo http_build_query($qp); ?>" class="pagination-btn <?php echo $p==$page?'active':''; ?>"><?php echo $p; ?></a>
              <?php endfor; ?>
              <?php if ($page < $totalPages): $qp['page'] = $page + 1; ?>
              <a href="?<?php echo http_build_query($qp); ?>" class="pagination-btn"><i class="fas fa-chevron-right"></i></a>
              <?php endif; ?>
            </div>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <script src="js/app.js"></script>
</body>
</html>
