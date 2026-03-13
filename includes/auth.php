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
