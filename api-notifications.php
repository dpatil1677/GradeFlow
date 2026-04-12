<?php
require_once 'includes/db_connect.php';
require_once 'includes/auth.php';
requireStudent();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $action = $input['action'] ?? '';
    $id = $input['id'] ?? null;

    initMockNotifications();

    if ($action === 'mark_all_read') {
        foreach ($_SESSION['mock_notifications'] as &$n) {
            $n['read'] = true;
        }
        echo json_encode(['success' => true, 'unread_count' => 0]);
        exit;
    } elseif ($action === 'mark_read' && $id) {
        $count = 0;
        foreach ($_SESSION['mock_notifications'] as &$n) {
            if ($n['id'] == $id) {
                $n['read'] = true;
            }
            if (!$n['read']) {
                $count++;
            }
        }
        echo json_encode(['success' => true, 'unread_count' => $count]);
        exit;
    }
}

echo json_encode(['success' => false]);
exit;
