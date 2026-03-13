<?php
/**
 * GradeFlow — Add Missing Subjects for All Branches
 * Run this once: http://localhost/college/includes/add_subjects.php
 */

require_once 'db_connect.php';

echo "<h2>🎓 Adding Subjects for All Branches</h2>";
echo "<pre style='background:#1a1a2e;color:#00ff88;padding:20px;border-radius:10px;font-family:monospace;'>";

// All subjects by course_id and semester
$allSubjects = [

    // ========== BSc Computer Science (course_id = 1) ==========
    // Semester 1
    ['CS101','Programming Fundamentals',1,1,30,70],
    ['CS102','Computer Organization',1,1,30,70],
    ['MA101','Mathematics I',1,1,30,70],
    ['PH101','Applied Physics',1,1,30,70],
    ['EN101','Technical English',1,1,30,70],
    ['CS103','Computer Lab I',1,1,30,70],
    // Semester 2
    ['CS201','Data Structures',1,2,30,70],
    ['CS202','Digital Logic Design',1,2,30,70],
    ['MA201','Mathematics II',1,2,30,70],
    ['CS203','Object Oriented Programming',1,2,30,70],
    ['CS204','Web Development',1,2,30,70],
    ['CS205','Computer Lab II',1,2,30,70],
    // Semester 3
    ['CS301','Database Systems',1,3,30,70],
    ['CS302','Computer Architecture',1,3,30,70],
    ['MA301','Probability & Statistics',1,3,30,70],
    ['CS303','Python Programming',1,3,30,70],
    ['CS304','Operating Systems Basics',1,3,30,70],
    ['CS305','Computer Lab III',1,3,30,70],
    // Semester 4 (already has some, add missing)
    // Semester 5 (already has some, add missing)
    // Semester 6 (already has some, add missing)
    // Semester 7
    ['CS701','Cloud Computing',1,7,30,70],
    ['CS702','Big Data Analytics',1,7,30,70],
    ['CS703','Information Security',1,7,30,70],
    ['CS704','Deep Learning',1,7,30,70],
    ['CS705','Project I',1,7,30,70],
    ['CS706','Seminar',1,7,30,70],
    // Semester 8
    ['CS801','Internet of Things',1,8,30,70],
    ['CS802','Blockchain Technology',1,8,30,70],
    ['CS803','Natural Language Processing',1,8,30,70],
    ['CS804','Project II',1,8,30,70],
    ['CS805','Internship',1,8,30,70],
    ['CS806','Comprehensive Viva',1,8,30,70],

    // ========== BSc Electronics (course_id = 2) ==========
    // Semester 1
    ['EC101','Basic Electronics',2,1,30,70],
    ['EC102','Engineering Mathematics I',2,1,30,70],
    ['EC103','Engineering Physics',2,1,30,70],
    ['EC104','Engineering Chemistry',2,1,30,70],
    ['EC105','Technical Communication',2,1,30,70],
    ['EC106','Electronics Lab I',2,1,30,70],
    // Semester 2 (already has some)
    // Semester 3
    ['EC301','Analog Electronics',2,3,30,70],
    ['EC302','Electromagnetic Theory',2,3,30,70],
    ['EC303','Linear Integrated Circuits',2,3,30,70],
    ['EC304','Engineering Mathematics III',2,3,30,70],
    ['EC305','C Programming',2,3,30,70],
    ['EC306','Electronics Lab III',2,3,30,70],
    // Semester 4
    ['EC401','Microprocessors & Microcontrollers',2,4,30,70],
    ['EC402','Communication Systems',2,4,30,70],
    ['EC403','Control Systems',2,4,30,70],
    ['EC404','VLSI Design',2,4,30,70],
    ['EC405','Probability & Random Processes',2,4,30,70],
    ['EC406','Electronics Lab IV',2,4,30,70],
    // Semester 5
    ['EC501','Digital Signal Processing',2,5,30,70],
    ['EC502','Antenna & Wave Propagation',2,5,30,70],
    ['EC503','Embedded Systems',2,5,30,70],
    ['EC504','Computer Networks',2,5,30,70],
    ['EC505','Power Electronics',2,5,30,70],
    ['EC506','Electronics Lab V',2,5,30,70],
    // Semester 6
    ['EC601','Wireless Communication',2,6,30,70],
    ['EC602','Optical Communication',2,6,30,70],
    ['EC603','Robotics',2,6,30,70],
    ['EC604','Satellite Communication',2,6,30,70],
    ['EC605','Project I',2,6,30,70],
    ['EC606','Electronics Lab VI',2,6,30,70],
    // Semester 7
    ['EC701','Biomedical Instrumentation',2,7,30,70],
    ['EC702','Radar Engineering',2,7,30,70],
    ['EC703','IoT Applications',2,7,30,70],
    ['EC704','Nanoelectronics',2,7,30,70],
    ['EC705','Project II',2,7,30,70],
    ['EC706','Seminar',2,7,30,70],
    // Semester 8
    ['EC801','Advanced Communication',2,8,30,70],
    ['EC802','AI in Electronics',2,8,30,70],
    ['EC803','Project III',2,8,30,70],
    ['EC804','Internship',2,8,30,70],
    ['EC805','Comprehensive Viva',2,8,30,70],
    ['EC806','Industry Training',2,8,30,70],

    // ========== BSc Mechanical Engineering (course_id = 3) ==========
    // Semester 1
    ['ME101','Engineering Mechanics',3,1,30,70],
    ['ME102','Engineering Drawing',3,1,30,70],
    ['ME103','Engineering Mathematics I',3,1,30,70],
    ['ME104','Engineering Physics',3,1,30,70],
    ['ME105','Workshop Practice',3,1,30,70],
    ['ME106','Technical Communication',3,1,30,70],
    // Semester 2
    ['ME201','Engineering Materials',3,2,30,70],
    ['ME202','Engineering Mathematics II',3,2,30,70],
    ['ME203','Engineering Chemistry',3,2,30,70],
    ['ME204','Basic Electrical Engineering',3,2,30,70],
    ['ME205','Computer Aided Drawing',3,2,30,70],
    ['ME206','Mechanical Lab I',3,2,30,70],
    // Semester 3
    ['ME301','Kinematics of Machines',3,3,30,70],
    ['ME302','Material Science',3,3,30,70],
    ['ME303','Engineering Mathematics III',3,3,30,70],
    ['ME304','Basic Electronics',3,3,30,70],
    ['ME305','Machine Drawing',3,3,30,70],
    ['ME306','Mechanical Lab II',3,3,30,70],
    // Semester 4 (already has some)
    // Semester 5
    ['ME501','Heat Transfer',3,5,30,70],
    ['ME502','Dynamics of Machines',3,5,30,70],
    ['ME503','Design of Machine Elements',3,5,30,70],
    ['ME504','Industrial Engineering',3,5,30,70],
    ['ME505','Automobile Engineering',3,5,30,70],
    ['ME506','Mechanical Lab III',3,5,30,70],
    // Semester 6
    ['ME601','Refrigeration & Air Conditioning',3,6,30,70],
    ['ME602','CAD/CAM',3,6,30,70],
    ['ME603','Finite Element Analysis',3,6,30,70],
    ['ME604','Power Plant Engineering',3,6,30,70],
    ['ME605','Project I',3,6,30,70],
    ['ME606','Mechanical Lab IV',3,6,30,70],
    // Semester 7
    ['ME701','Mechatronics',3,7,30,70],
    ['ME702','Robotics',3,7,30,70],
    ['ME703','Advanced Manufacturing',3,7,30,70],
    ['ME704','Renewable Energy Systems',3,7,30,70],
    ['ME705','Project II',3,7,30,70],
    ['ME706','Seminar',3,7,30,70],
    // Semester 8
    ['ME801','Quality Engineering',3,8,30,70],
    ['ME802','Supply Chain Management',3,8,30,70],
    ['ME803','Project III',3,8,30,70],
    ['ME804','Internship',3,8,30,70],
    ['ME805','Comprehensive Viva',3,8,30,70],
    ['ME806','Industry Training',3,8,30,70],

    // ========== BSc Civil Engineering (course_id = 4) ==========
    // Semester 1
    ['CE101','Engineering Mechanics',4,1,30,70],
    ['CE102','Engineering Drawing',4,1,30,70],
    ['CE103','Engineering Mathematics I',4,1,30,70],
    ['CE104','Engineering Geology',4,1,30,70],
    ['CE105','Environmental Science',4,1,30,70],
    ['CE106','Surveying Lab I',4,1,30,70],
    // Semester 2
    ['CE201','Surveying',4,2,30,70],
    ['CE202','Building Materials',4,2,30,70],
    ['CE203','Engineering Mathematics II',4,2,30,70],
    ['CE204','Fluid Mechanics',4,2,30,70],
    ['CE205','Solid Mechanics',4,2,30,70],
    ['CE206','Civil Lab I',4,2,30,70],
    // Semester 3
    ['CE301','Structural Analysis I',4,3,30,70],
    ['CE302','Concrete Technology',4,3,30,70],
    ['CE303','Geotechnical Engineering I',4,3,30,70],
    ['CE304','Engineering Mathematics III',4,3,30,70],
    ['CE305','Hydraulics',4,3,30,70],
    ['CE306','Civil Lab II',4,3,30,70],
    // Semester 4
    ['CE401','Structural Analysis II',4,4,30,70],
    ['CE402','Reinforced Concrete Design',4,4,30,70],
    ['CE403','Geotechnical Engineering II',4,4,30,70],
    ['CE404','Transportation Engineering I',4,4,30,70],
    ['CE405','Water Supply Engineering',4,4,30,70],
    ['CE406','Civil Lab III',4,4,30,70],
    // Semester 5
    ['CE501','Steel Structures',4,5,30,70],
    ['CE502','Transportation Engineering II',4,5,30,70],
    ['CE503','Hydrology',4,5,30,70],
    ['CE504','Environmental Engineering',4,5,30,70],
    ['CE505','Estimation & Costing',4,5,30,70],
    ['CE506','Civil Lab IV',4,5,30,70],
    // Semester 6
    ['CE601','Foundation Engineering',4,6,30,70],
    ['CE602','Water Resources Engineering',4,6,30,70],
    ['CE603','Construction Management',4,6,30,70],
    ['CE604','Earthquake Engineering',4,6,30,70],
    ['CE605','Project I',4,6,30,70],
    ['CE606','Civil Lab V',4,6,30,70],
    // Semester 7
    ['CE701','Advanced Structural Design',4,7,30,70],
    ['CE702','Remote Sensing & GIS',4,7,30,70],
    ['CE703','Green Building Technology',4,7,30,70],
    ['CE704','Bridge Engineering',4,7,30,70],
    ['CE705','Project II',4,7,30,70],
    ['CE706','Seminar',4,7,30,70],
    // Semester 8
    ['CE801','Smart City Planning',4,8,30,70],
    ['CE802','Disaster Management',4,8,30,70],
    ['CE803','Project III',4,8,30,70],
    ['CE804','Internship',4,8,30,70],
    ['CE805','Comprehensive Viva',4,8,30,70],
    ['CE806','Industry Training',4,8,30,70],

    // ========== BBA (course_id = 5) ==========
    // Semester 1
    ['BB101','Principles of Management',5,1,30,70],
    ['BB102','Business Economics',5,1,30,70],
    ['BB103','Financial Accounting',5,1,30,70],
    ['BB104','Business Communication',5,1,30,70],
    ['BB105','Business Mathematics',5,1,30,70],
    ['BB106','Computer Applications',5,1,30,70],
    // Semester 2
    ['BB201','Organizational Behavior',5,2,30,70],
    ['BB202','Business Statistics',5,2,30,70],
    ['BB203','Cost Accounting',5,2,30,70],
    ['BB204','Business Law',5,2,30,70],
    ['BB205','Marketing Management',5,2,30,70],
    ['BB206','Environmental Studies',5,2,30,70],
    // Semester 3
    ['BB301','Human Resource Management',5,3,30,70],
    ['BB302','Financial Management',5,3,30,70],
    ['BB303','Business Research Methods',5,3,30,70],
    ['BB304','Production & Operations Management',5,3,30,70],
    ['BB305','Indian Economy',5,3,30,70],
    ['BB306','Consumer Behavior',5,3,30,70],
    // Semester 4
    ['BB401','Strategic Management',5,4,30,70],
    ['BB402','International Business',5,4,30,70],
    ['BB403','Management Information Systems',5,4,30,70],
    ['BB404','Taxation',5,4,30,70],
    ['BB405','Entrepreneurship Development',5,4,30,70],
    ['BB406','Supply Chain Management',5,4,30,70],
    // Semester 5
    ['BB501','Digital Marketing',5,5,30,70],
    ['BB502','Corporate Governance',5,5,30,70],
    ['BB503','Investment Analysis',5,5,30,70],
    ['BB504','E-Commerce',5,5,30,70],
    ['BB505','Project Management',5,5,30,70],
    ['BB506','Business Ethics',5,5,30,70],
    // Semester 6
    ['BB601','Brand Management',5,6,30,70],
    ['BB602','Banking & Insurance',5,6,30,70],
    ['BB603','Event Management',5,6,30,70],
    ['BB604','Project & Internship',5,6,30,70],
    ['BB605','Comprehensive Viva',5,6,30,70],
    ['BB606','Seminar',5,6,30,70],

    // ========== BCA (course_id = 6) ==========
    // Semester 1
    ['CA101','Introduction to Programming',6,1,30,70],
    ['CA102','Mathematical Foundations',6,1,30,70],
    ['CA103','Digital Electronics',6,1,30,70],
    ['CA104','English Communication',6,1,30,70],
    ['CA105','PC Software & Office Tools',6,1,30,70],
    ['CA106','Programming Lab I',6,1,30,70],
    // Semester 2
    ['CA201','Data Structures using C',6,2,30,70],
    ['CA202','Computer Organization',6,2,30,70],
    ['CA203','Discrete Mathematics',6,2,30,70],
    ['CA204','Operating Systems',6,2,30,70],
    ['CA205','Internet & Web Technologies',6,2,30,70],
    ['CA206','Programming Lab II',6,2,30,70],
    // Semester 3
    ['CA301','Object Oriented Programming (Java)',6,3,30,70],
    ['CA302','Database Management Systems',6,3,30,70],
    ['CA303','Computer Networks',6,3,30,70],
    ['CA304','Software Engineering',6,3,30,70],
    ['CA305','Numerical Methods',6,3,30,70],
    ['CA306','Java Lab',6,3,30,70],
    // Semester 4
    ['CA401','Python Programming',6,4,30,70],
    ['CA402','Web Application Development',6,4,30,70],
    ['CA403','Computer Graphics',6,4,30,70],
    ['CA404','Theory of Computation',6,4,30,70],
    ['CA405','Management Principles',6,4,30,70],
    ['CA406','Python Lab',6,4,30,70],
    // Semester 5
    ['CA501','Machine Learning',6,5,30,70],
    ['CA502','Cloud Computing',6,5,30,70],
    ['CA503','Mobile Application Development',6,5,30,70],
    ['CA504','Cyber Security',6,5,30,70],
    ['CA505','Data Science',6,5,30,70],
    ['CA506','ML Lab',6,5,30,70],
    // Semester 6
    ['CA601','Artificial Intelligence',6,6,30,70],
    ['CA602','Blockchain Fundamentals',6,6,30,70],
    ['CA603','DevOps & CI/CD',6,6,30,70],
    ['CA604','Major Project',6,6,30,70],
    ['CA605','Internship',6,6,30,70],
    ['CA606','Comprehensive Viva',6,6,30,70],

    // ========== MSc Computer Science (course_id = 7) ==========
    // Semester 1
    ['MC101','Advanced Data Structures & Algorithms',7,1,30,70],
    ['MC102','Advanced Database Systems',7,1,30,70],
    ['MC103','Mathematical Foundations of CS',7,1,30,70],
    ['MC104','Advanced Computer Architecture',7,1,30,70],
    ['MC105','Research Methodology',7,1,30,70],
    ['MC106','Advanced Programming Lab',7,1,30,70],
    // Semester 2
    ['MC201','Machine Learning & AI',7,2,30,70],
    ['MC202','Distributed Computing',7,2,30,70],
    ['MC203','Advanced Operating Systems',7,2,30,70],
    ['MC204','Data Mining & Warehousing',7,2,30,70],
    ['MC205','Soft Computing',7,2,30,70],
    ['MC206','AI Lab',7,2,30,70],
    // Semester 3
    ['MC301','Deep Learning',7,3,30,70],
    ['MC302','Cloud & Edge Computing',7,3,30,70],
    ['MC303','Information Security & Cryptography',7,3,30,70],
    ['MC304','Natural Language Processing',7,3,30,70],
    ['MC305','Dissertation I',7,3,30,70],
    ['MC306','Seminar',7,3,30,70],
    // Semester 4
    ['MC401','Advanced Topics in AI',7,4,30,70],
    ['MC402','Quantum Computing',7,4,30,70],
    ['MC403','Dissertation II',7,4,30,70],
    ['MC404','Internship / Industry Project',7,4,30,70],
    ['MC405','Comprehensive Viva',7,4,30,70],
    ['MC406','Research Publication',7,4,30,70],
];

$stmt = $pdo->prepare("INSERT IGNORE INTO subjects (subject_code, subject_name, course_id, semester, max_internal, max_external) VALUES (?,?,?,?,?,?)");

$inserted = 0;
foreach ($allSubjects as $s) {
    $stmt->execute($s);
    if ($stmt->rowCount() > 0) $inserted++;
}

echo "✅ Inserted $inserted new subjects (skipped existing ones)\n\n";

// Show summary
$summary = $pdo->query("SELECT c.short_name, c.course_name, COUNT(s.id) as subject_count, 
    GROUP_CONCAT(DISTINCT s.semester ORDER BY s.semester) as semesters
    FROM courses c LEFT JOIN subjects s ON c.id = s.course_id 
    GROUP BY c.id ORDER BY c.id")->fetchAll();

echo "📊 Subjects Summary:\n";
echo str_repeat('-', 70) . "\n";
printf("%-20s %-8s %s\n", "Course", "Count", "Semesters");
echo str_repeat('-', 70) . "\n";
foreach ($summary as $row) {
    printf("%-20s %-8d %s\n", $row['short_name'], $row['subject_count'], $row['semesters']);
}

$total = $pdo->query("SELECT COUNT(*) FROM subjects")->fetchColumn();
echo str_repeat('-', 70) . "\n";
echo "Total subjects in database: $total\n";

echo "\n🎉 All branches now have subjects!\n";
echo "</pre>";
echo "<p><a href='../add-marks.php' style='color:#6c5ce7;font-size:18px;'>→ Go to Add Marks</a></p>";
echo "<p><a href='../manage-attendance.php' style='color:#6c5ce7;font-size:18px;'>→ Go to Manage Attendance</a></p>";
