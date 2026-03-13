<?php
/**
 * GradeFlow — Database Setup Script
 * Run this once to create the database and seed data
 * Visit: http://localhost/college/includes/setup.php
 */

// DB connection without database selected first
try {
    $pdo = new PDO("mysql:host=localhost;charset=utf8mb4", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
} catch (PDOException $e) {
    die("MySQL connection failed: " . $e->getMessage());
}

echo "<h2>🎓 GradeFlow Database Setup</h2>";
echo "<pre style='background:#1a1a2e;color:#00ff88;padding:20px;border-radius:10px;font-family:monospace;'>";

// Create database
$pdo->exec("CREATE DATABASE IF NOT EXISTS gradeflow");
$pdo->exec("USE gradeflow");
echo "✅ Database 'gradeflow' created/selected\n";

// Create tables
$pdo->exec("CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    security_code VARCHAR(10) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB");
echo "✅ Table 'admins' ready\n";

$pdo->exec("CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_name VARCHAR(100) NOT NULL,
    short_name VARCHAR(20) NOT NULL
) ENGINE=InnoDB");
echo "✅ Table 'courses' ready\n";

$pdo->exec("CREATE TABLE IF NOT EXISTS subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subject_code VARCHAR(10) NOT NULL,
    subject_name VARCHAR(100) NOT NULL,
    course_id INT NOT NULL,
    semester INT NOT NULL,
    max_internal INT DEFAULT 30,
    max_external INT DEFAULT 70,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
) ENGINE=InnoDB");
echo "✅ Table 'subjects' ready\n";

$pdo->exec("CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    roll_number VARCHAR(20) NOT NULL UNIQUE,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(20),
    dob DATE,
    gender ENUM('Male','Female','Other'),
    address TEXT,
    course_id INT NOT NULL,
    semester INT NOT NULL DEFAULT 1,
    section VARCHAR(5) DEFAULT 'A',
    admission_year INT NOT NULL,
    father_name VARCHAR(100),
    mother_name VARCHAR(100),
    guardian_contact VARCHAR(20),
    guardian_email VARCHAR(100),
    password VARCHAR(255) NOT NULL,
    status ENUM('Active','Inactive','Graduated') DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
) ENGINE=InnoDB");
echo "✅ Table 'students' ready\n";

$pdo->exec("CREATE TABLE IF NOT EXISTS marks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    subject_id INT NOT NULL,
    exam_type ENUM('Midterm','Final','Internal','Practical') NOT NULL,
    internal_marks DECIMAL(5,2) DEFAULT 0,
    external_marks DECIMAL(5,2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_mark (student_id, subject_id, exam_type),
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE
) ENGINE=InnoDB");
echo "✅ Table 'marks' ready\n";

$pdo->exec("CREATE TABLE IF NOT EXISTS attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    subject_id INT NOT NULL,
    attendance_date DATE NOT NULL,
    status ENUM('Present','Absent') NOT NULL DEFAULT 'Present',
    remarks VARCHAR(255),
    UNIQUE KEY unique_attendance (student_id, subject_id, attendance_date),
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE
) ENGINE=InnoDB");
echo "✅ Table 'attendance' ready\n\n";

// ---- SEED DATA ----
echo "--- Seeding Data ---\n";

// Admin
$adminPass = password_hash('admin123', PASSWORD_DEFAULT);
$stmt = $pdo->prepare("INSERT IGNORE INTO admins (username, password, full_name, security_code) VALUES (?, ?, ?, ?)");
$stmt->execute(['admin', $adminPass, 'Dr. Admin', '123456']);
echo "✅ Admin user created (admin / admin123 / code: 123456)\n";

// Courses
$courses = [
    [1, 'BSc Computer Science', 'BSc CS'],
    [2, 'BSc Electronics', 'BSc ECE'],
    [3, 'BSc Mechanical Engineering', 'BSc Mech'],
    [4, 'BSc Civil Engineering', 'BSc Civil'],
    [5, 'BBA', 'BBA'],
    [6, 'BCA', 'BCA'],
    [7, 'MSc Computer Science', 'MSc CS'],
];
$stmt = $pdo->prepare("INSERT IGNORE INTO courses (id, course_name, short_name) VALUES (?, ?, ?)");
foreach ($courses as $c) { $stmt->execute($c); }
echo "✅ " . count($courses) . " courses inserted\n";

// Subjects
$subjects = [
    // BSc CS Sem 6
    [1,'CS601','Data Structures & Algorithms',1,6,30,70],
    [2,'CS602','Database Management Systems',1,6,30,70],
    [3,'CS603','Operating Systems',1,6,30,70],
    [4,'CS604','Computer Networks',1,6,30,70],
    [5,'CS605','Software Engineering',1,6,30,70],
    [6,'MA604','Mathematics IV',1,6,30,70],
    // BSc CS Sem 5
    [7,'CS501','Theory of Computation',1,5,30,70],
    [8,'CS502','Compiler Design',1,5,30,70],
    [9,'CS503','Artificial Intelligence',1,5,30,70],
    [10,'CS504','Web Technologies',1,5,30,70],
    [11,'CS505','Machine Learning',1,5,30,70],
    [12,'MA503','Discrete Mathematics',1,5,30,70],
    // BSc CS Sem 4
    [13,'CS401','Design & Analysis of Algorithms',1,4,30,70],
    [14,'CS402','Microprocessors',1,4,30,70],
    [15,'CS403','Object Oriented Programming',1,4,30,70],
    [16,'CS404','Digital Electronics',1,4,30,70],
    [17,'CS405','Linear Algebra',1,4,30,70],
    [18,'MA404','Mathematics III',1,4,30,70],
    // BSc ECE Sem 2
    [19,'EC201','Circuit Theory',2,2,30,70],
    [20,'EC202','Electronic Devices',2,2,30,70],
    [21,'EC203','Signals & Systems',2,2,30,70],
    [22,'EC204','Digital Logic Design',2,2,30,70],
    [23,'EC205','Network Analysis',2,2,30,70],
    [24,'MA202','Engineering Mathematics II',2,2,30,70],
    // BSc Mech Sem 4
    [25,'ME401','Thermodynamics',3,4,30,70],
    [26,'ME402','Fluid Mechanics',3,4,30,70],
    [27,'ME403','Strength of Materials',3,4,30,70],
    [28,'ME404','Manufacturing Processes',3,4,30,70],
    [29,'ME405','Machine Drawing',3,4,30,70],
    [30,'MA403','Applied Mathematics',3,4,30,70],
];
$stmt = $pdo->prepare("INSERT IGNORE INTO subjects (id, subject_code, subject_name, course_id, semester, max_internal, max_external) VALUES (?,?,?,?,?,?,?)");
foreach ($subjects as $s) { $stmt->execute($s); }
echo "✅ " . count($subjects) . " subjects inserted\n";

// Students
$studentPass = password_hash('password123', PASSWORD_DEFAULT);
$students = [
    ['CS2025001','Ananya','Kumari','ananya@GradeFlow.edu','+91 98765 43210','2004-05-15','Female',1,6,'A',2023,'Rajesh Kumari','Sunita Kumari'],
    ['CS2025002','Rahul','Sharma','rahul@GradeFlow.edu','+91 98765 43211','2004-03-22','Male',1,6,'A',2023,'Suresh Sharma','Meera Sharma'],
    ['CS2025003','Priya','Gupta','priya@GradeFlow.edu','+91 98765 43212','2004-07-10','Female',1,6,'A',2023,'Anil Gupta','Kavita Gupta'],
    ['ME2025010','Priya','Gupta','priyag@GradeFlow.edu','+91 98765 43213','2004-01-20','Female',3,4,'B',2023,'Ramesh Gupta','Neeta Gupta'],
    ['EC2025008','Vikash','Patel','vikash@GradeFlow.edu','+91 98765 43214','2005-09-08','Male',2,2,'A',2024,'Dinesh Patel','Sarita Patel'],
    ['CS2024015','Sneha','Krishnan','sneha@GradeFlow.edu','+91 98765 43215','2004-11-30','Female',1,4,'B',2024,'Krishnan R','Lakshmi K'],
    ['CS2023042','Arjun','Mehta','arjun@GradeFlow.edu','+91 98765 43216','2003-06-14','Male',1,6,'A',2023,'Vijay Mehta','Nirmala Mehta'],
];
$stmt = $pdo->prepare("INSERT IGNORE INTO students (roll_number, first_name, last_name, email, phone, dob, gender, course_id, semester, section, admission_year, father_name, mother_name, password, status) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,'Active')");
foreach ($students as $s) {
    $data = array_merge($s, [$studentPass]);
    $stmt->execute($data);
}
echo "✅ " . count($students) . " students inserted (password: password123)\n";

// Marks - Sem 6 Midterm for Ananya (student_id=1)
$marksData = [
    // student_id, subject_id, exam_type, internal, external
    [1, 1, 'Midterm', 28, 64], // DSA - 92
    [1, 2, 'Midterm', 26, 62], // DBMS - 88
    [1, 3, 'Midterm', 29, 66], // OS - 95
    [1, 4, 'Midterm', 22, 56], // CN - 78
    [1, 5, 'Midterm', 25, 59], // SE - 84
    [1, 6, 'Midterm', 24, 56], // Math IV - 80
    // Sem 5 Final for Ananya
    [1, 7, 'Final', 27, 63],   // TOC - 90
    [1, 8, 'Final', 25, 60],   // Compiler - 85
    [1, 9, 'Final', 28, 65],   // AI - 93
    [1, 10, 'Final', 26, 58],  // Web Tech - 84
    [1, 11, 'Final', 24, 62],  // ML - 86
    [1, 12, 'Final', 26, 61],  // Discrete Math - 87
    // Sem 6 Midterm for Rahul (student_id=2)
    [2, 1, 'Midterm', 24, 58], // DSA - 82
    [2, 2, 'Midterm', 22, 55], // DBMS - 77
    [2, 3, 'Midterm', 26, 60], // OS - 86
    [2, 4, 'Midterm', 20, 50], // CN - 70
    [2, 5, 'Midterm', 23, 56], // SE - 79
    [2, 6, 'Midterm', 21, 53], // Math IV - 74
    // Sem 6 Midterm for Priya CS (student_id=3)
    [3, 1, 'Midterm', 20, 52], // DSA - 72
    [3, 2, 'Midterm', 18, 47], // DBMS - 65
    [3, 3, 'Midterm', 22, 53], // OS - 75
    [3, 4, 'Midterm', 19, 49], // CN - 68
    [3, 5, 'Midterm', 21, 50], // SE - 71
    [3, 6, 'Midterm', 17, 45], // Math IV - 62
    // Sneha (student_id=6) - Sem 6 Midterm (low performer)
    [6, 1, 'Midterm', 10, 22], // DSA - 32 Fail
    [6, 2, 'Midterm', 12, 28], // DBMS - 40
    [6, 3, 'Midterm', 14, 30], // OS - 44
    [6, 4, 'Midterm', 11, 25], // CN - 36 Fail
    [6, 5, 'Midterm', 13, 29], // SE - 42
    [6, 6, 'Midterm', 10, 24], // Math IV - 34 Fail
    // Arjun (student_id=7) - Sem 6 Midterm (top performer)
    [7, 1, 'Midterm', 29, 67], // DSA - 96
    [7, 2, 'Midterm', 28, 65], // DBMS - 93
    [7, 3, 'Midterm', 30, 68], // OS - 98
    [7, 4, 'Midterm', 27, 63], // CN - 90
    [7, 5, 'Midterm', 29, 66], // SE - 95
    [7, 6, 'Midterm', 28, 64], // Math IV - 92
];
$stmt = $pdo->prepare("INSERT IGNORE INTO marks (student_id, subject_id, exam_type, internal_marks, external_marks) VALUES (?,?,?,?,?)");
foreach ($marksData as $m) { $stmt->execute($m); }
echo "✅ " . count($marksData) . " marks records inserted\n";

// Attendance seed data - February 2026 for Ananya (student 1) across subjects
$attendanceDates = [
    '2026-02-02','2026-02-03','2026-02-04','2026-02-05','2026-02-06',
    '2026-02-09','2026-02-10','2026-02-11','2026-02-12','2026-02-13',
    '2026-02-16','2026-02-17','2026-02-18','2026-02-19',
];
$stmt = $pdo->prepare("INSERT IGNORE INTO attendance (student_id, subject_id, attendance_date, status, remarks) VALUES (?,?,?,?,?)");

// Ananya - mostly present
foreach ([1,2,3,4,5,6] as $subId) {
    foreach ($attendanceDates as $date) {
        // Mark absent on Feb 10 for CN (subject 4)
        if ($date === '2026-02-10' && $subId === 4) {
            $stmt->execute([1, $subId, $date, 'Absent', null]);
        } else {
            $stmt->execute([1, $subId, $date, 'Present', null]);
        }
    }
}

// Other students - attendance for today/few days
foreach ([2,3,5,7] as $stuId) {
    foreach ([1,2,3,4,5,6] as $subId) {
        foreach ($attendanceDates as $date) {
            $status = (rand(1,10) <= 9) ? 'Present' : 'Absent';
            $stmt->execute([$stuId, $subId, $date, $status, null]);
        }
    }
}

// Sneha (6) - low attendance
foreach ([1,2,3,4,5,6] as $subId) {
    foreach ($attendanceDates as $date) {
        $status = (rand(1,10) <= 7) ? 'Present' : 'Absent'; // ~70% attendance
        $remark = ($status === 'Absent' && rand(1,3) === 1) ? 'Medical leave' : null;
        $stmt->execute([6, $subId, $date, $status, $remark]);
    }
}

echo "✅ Attendance records inserted\n";

echo "\n🎉 Setup complete! You can now use GradeFlow.\n";
echo "   Admin: admin / admin123 / code: 123456\n";
echo "   Student: any roll number / password123\n";
echo "</pre>";
echo "<p><a href='../admin-login.php' style='color:#6c5ce7;font-size:18px;'>→ Go to Admin Login</a></p>";
echo "<p><a href='../student-login.php' style='color:#6c5ce7;font-size:18px;'>→ Go to Student Login</a></p>";
