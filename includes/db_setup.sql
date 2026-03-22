-- GradeFlow Database Setup
-- Database already selected in phpMyAdmin

-- ============================================
-- ADMINS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    security_code VARCHAR(10) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM;

-- ============================================
-- COURSES TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_name VARCHAR(100) NOT NULL,
    short_name VARCHAR(20) NOT NULL
) ENGINE=MyISAM;

-- ============================================
-- SUBJECTS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subject_code VARCHAR(10) NOT NULL,
    subject_name VARCHAR(100) NOT NULL,
    course_id INT NOT NULL,
    semester INT NOT NULL,
    max_internal INT DEFAULT 30,
    max_external INT DEFAULT 70,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
) ENGINE=MyISAM;

-- ============================================
-- STUDENTS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS students (
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
) ENGINE=MyISAM;

-- ============================================
-- MARKS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS marks (
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
) ENGINE=MyISAM;

-- ============================================
-- ATTENDANCE TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    subject_id INT NOT NULL,
    attendance_date DATE NOT NULL,
    status ENUM('Present','Absent') NOT NULL DEFAULT 'Present',
    remarks VARCHAR(255),
    UNIQUE KEY unique_attendance (student_id, subject_id, attendance_date),
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE
) ENGINE=MyISAM;

-- ============================================
-- SEED DATA
-- ============================================

INSERT INTO admins (username, password, full_name, security_code) VALUES
('admin', 'admin123', 'Dr. Admin', '123456');

INSERT INTO courses (id, course_name, short_name) VALUES
(1, 'BSc Computer Science', 'BSc CS'),
(2, 'BSc Electronics', 'BSc ECE'),
(3, 'BSc Mechanical Engineering', 'BSc Mech'),
(4, 'BSc Civil Engineering', 'BSc Civil'),
(5, 'BBA', 'BBA'),
(6, 'BCA', 'BCA'),
(7, 'MSc Computer Science', 'MSc CS');

INSERT INTO subjects (id, subject_code, subject_name, course_id, semester, max_internal, max_external) VALUES
(1,'CS601','Data Structures & Algorithms',1,6,30,70),
(2,'CS602','Database Management Systems',1,6,30,70),
(3,'CS603','Operating Systems',1,6,30,70),
(4,'CS604','Computer Networks',1,6,30,70),
(5,'CS605','Software Engineering',1,6,30,70),
(6,'MA604','Mathematics IV',1,6,30,70),
(7,'CS501','Theory of Computation',1,5,30,70),
(8,'CS502','Compiler Design',1,5,30,70),
(9,'CS503','Artificial Intelligence',1,5,30,70),
(10,'CS504','Web Technologies',1,5,30,70),
(11,'CS505','Machine Learning',1,5,30,70),
(12,'MA503','Discrete Mathematics',1,5,30,70),
(13,'CS401','Design & Analysis of Algorithms',1,4,30,70),
(14,'CS402','Microprocessors',1,4,30,70),
(15,'CS403','Object Oriented Programming',1,4,30,70),
(16,'CS404','Digital Electronics',1,4,30,70),
(17,'CS405','Linear Algebra',1,4,30,70),
(18,'MA404','Mathematics III',1,4,30,70),

(19,'EC201','Circuit Theory',2,2,30,70),
(20,'EC202','Electronic Devices',2,2,30,70),
(21,'EC203','Signals & Systems',2,2,30,70),
(22,'EC204','Digital Logic Design',2,2,30,70),
(23,'EC205','Network Analysis',2,2,30,70),

(24,'MA202','Engineering Mathematics II',2,2,30,70),
(25,'ME401','Thermodynamics',3,4,30,70),
(26,'ME402','Fluid Mechanics',3,4,30,70),
(27,'ME403','Strength of Materials',3,4,30,70),
(28,'ME404','Manufacturing Processes',3,4,30,70),
(29,'ME405','Machine Drawing',3,4,30,70),
(30,'MA403','Applied Mathematics',3,4,30,70);