<?php
/**
 * GradeFlow — Authentication & Session Helpers
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Require admin login — redirects if not authenticated as admin
 */
function requireAdmin() {
    if (!isset($_SESSION['admin_id'])) {
        header('Location: admin-login.php');
        exit;
    }
}

/**
 * Require student login — redirects if not authenticated as student
 */
function requireStudent() {
    if (!isset($_SESSION['student_id'])) {
        header('Location: student-login.php');
        exit;
    }
}

/**
 * Check if any user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['admin_id']) || isset($_SESSION['student_id']);
}

/**
 * Get logged-in admin info
 */
function getAdminInfo() {
    return [
        'id'        => $_SESSION['admin_id'] ?? null,
        'username'  => $_SESSION['admin_username'] ?? '',
        'full_name' => $_SESSION['admin_name'] ?? 'Admin',
    ];
}

/**
 * Get logged-in student info
 */
function getStudentInfo() {
    return [
        'id'          => $_SESSION['student_id'] ?? null,
        'roll_number' => $_SESSION['student_roll'] ?? '',
        'full_name'   => $_SESSION['student_name'] ?? 'Student',
        'course'      => $_SESSION['student_course'] ?? '',
        'semester'    => $_SESSION['student_semester'] ?? '',
        'section'     => $_SESSION['student_section'] ?? '',
        'email'       => $_SESSION['student_email'] ?? '',
    ];
}

/**
 * Helper: generate initials from name
 */
function getInitials($name) {
    $parts = explode(' ', trim($name));
    $initials = '';
    foreach ($parts as $part) {
        if (!empty($part)) {
            $initials .= strtoupper($part[0]);
        }
    }
    return substr($initials, 0, 2);
}

/**
 * Helper: calculate grade from total marks (out of 100)
 */
function calculateGrade($total) {
    if ($total >= 90) return 'A+';
    if ($total >= 80) return 'A';
    if ($total >= 70) return 'B+';
    if ($total >= 60) return 'B';
    if ($total >= 50) return 'C';
    if ($total >= 40) return 'D';
    return 'F';
}

/**
 * Helper: get grade CSS class
 */
function gradeClass($grade) {
    if (in_array($grade, ['A+', 'A'])) return 'grade-a';
    if (in_array($grade, ['B+', 'B'])) return 'grade-b';
    if (in_array($grade, ['C']))       return 'grade-c';
    if (in_array($grade, ['D']))       return 'grade-d';
    return 'grade-f';
}

/**
 * Helper: get result classification from percentage
 */
function getResultClass($percentage) {
    if ($percentage >= 75) return 'First Class with Distinction';
    if ($percentage >= 60) return 'First Class';
    if ($percentage >= 50) return 'Second Class';
    if ($percentage >= 40) return 'Pass';
    return 'Fail';
}

/**
 * Mock Notifications Initialization
 */
function initMockNotifications() {
    if (!isset($_SESSION['mock_notifications'])) {
        $_SESSION['mock_notifications'] = [
            [
                'id' => 1,
                'type' => 'academic',
                'title' => 'New Grades Posted',
                'message' => 'Your recent results for the Mid-Semester Examination have been uploaded.',
                'date' => date('Y-m-d H:i:s', strtotime('-2 hours')),
                'read' => false,
                'icon' => 'fas fa-graduation-cap',
                'color' => 'var(--success)'
            ],
            [
                'id' => 2,
                'type' => 'system',
                'title' => 'System Maintenance Scheduled',
                'message' => 'The GradeFlow portal will be offline for maintenance on Saturday from 2:00 AM to 4:00 AM.',
                'date' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'read' => false,
                'icon' => 'fas fa-wrench',
                'color' => 'var(--warning)'
            ],
            [
                'id' => 3,
                'type' => 'event',
                'title' => 'Upcoming Tech Symposium',
                'message' => 'Don\'t miss out on the annual Tech Symposium happening next week! Register before Friday.',
                'date' => date('Y-m-d H:i:s', strtotime('-3 days')),
                'read' => true,
                'icon' => 'fas fa-calendar-alt',
                'color' => 'var(--primary)'
            ],
            [
                'id' => 4,
                'type' => 'academic',
                'title' => 'Attendance Alert',
                'message' => 'Your attendance in Data Structures is falling below 75%. Please ensure you attend the upcoming classes.',
                'date' => date('Y-m-d H:i:s', strtotime('-5 days')),
                'read' => false,
                'icon' => 'fas fa-exclamation-triangle',
                'color' => 'var(--danger)'
            ]
        ];
    }
}

/**
 * Get Unread Notification Count
 */
function getUnreadNotificationCount() {
    initMockNotifications();
    $count = 0;
    foreach ($_SESSION['mock_notifications'] as $n) {
        if (!$n['read']) {
            $count++;
        }
    }
    return $count;
}
