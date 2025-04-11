<?php
require_once 'config.php';

// Authentication Functions
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function checkRole($allowed_roles) {
    if (!isset($_SESSION['role'])) {
        return false;
    }
    return in_array($_SESSION['role'], (array)$allowed_roles);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function isTeacher() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'teacher';
}

function isStudent() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'student';
}

function isParent() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'parent';
}

// Security Functions
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCSRFToken($token) {
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        return false;
    }
    return true;
}

// File Upload Functions
function uploadFile($file, $allowed_types = ['jpg', 'jpeg', 'png'], $max_size = 5242880) {
    try {
        if (!isset($file['error']) || is_array($file['error'])) {
            throw new RuntimeException('Invalid parameters.');
        }

        switch ($file['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new RuntimeException('No file sent.');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new RuntimeException('Exceeded filesize limit.');
            default:
                throw new RuntimeException('Unknown errors.');
        }

        if ($file['size'] > $max_size) {
            throw new RuntimeException('Exceeded filesize limit.');
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $ext = array_search(
            $finfo->file($file['tmp_name']),
            array(
                'jpg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
            ),
            true
        );

        if (false === $ext) {
            throw new RuntimeException('Invalid file format.');
        }

        // Create uploads directory if it doesn't exist
        if (!file_exists('./uploads')) {
            mkdir('./uploads', 0777, true);
        }

        $filename = sprintf(
            '%s.%s',
            sha1_file($file['tmp_name']),
            $ext
        );

        if (!move_uploaded_file(
            $file['tmp_name'],
            sprintf('./uploads/%s',
                $filename
            )
        )) {
            throw new RuntimeException('Failed to move uploaded file.');
        }

        return $filename;
    } catch (RuntimeException $e) {
        return false;
    }
}

// Utility Functions
function redirectTo($path) {
    header("Location: " . SITE_URL . "/" . $path);
    exit();
}

function displayAlert($message, $type = 'info') {
    $bgColor = [
        'success' => 'bg-green-100 border-green-500 text-green-700',
        'error' => 'bg-red-100 border-red-500 text-red-700',
        'warning' => 'bg-yellow-100 border-yellow-500 text-yellow-700',
        'info' => 'bg-blue-100 border-blue-500 text-blue-700'
    ][$type];

    return "
        <div class='border-l-4 p-4 mb-4 {$bgColor}' role='alert'>
            <p>{$message}</p>
        </div>
    ";
}

function formatDate($date) {
    return date('M d, Y', strtotime($date));
}

function formatDateTime($datetime) {
    return date('M d, Y H:i', strtotime($datetime));
}

// Database Helper Functions
function getStudentCount() {
    global $conn;
    try {
        $stmt = $conn->query("SELECT COUNT(*) FROM students");
        return $stmt->fetchColumn();
    } catch (PDOException $e) {
        return 0;
    }
}

function getTeacherCount() {
    global $conn;
    try {
        $stmt = $conn->query("SELECT COUNT(*) FROM teachers");
        return $stmt->fetchColumn();
    } catch (PDOException $e) {
        return 0;
    }
}

function getTotalFeesCollected() {
    global $conn;
    try {
        $stmt = $conn->query("SELECT SUM(amount) FROM fee_collections WHERE status = 'paid'");
        return $stmt->fetchColumn() ?: 0;
    } catch (PDOException $e) {
        return 0;
    }
}

function getAttendancePercentage() {
    global $conn;
    try {
        $stmt = $conn->query("
            SELECT 
                (COUNT(CASE WHEN status = 'present' THEN 1 END) * 100.0 / COUNT(*)) as percentage 
            FROM attendance
        ");
        return round($stmt->fetchColumn() ?: 0, 2);
    } catch (PDOException $e) {
        return 0;
    }
}

// Navigation Functions
function getModulesByRole($role) {
    $modules = [
        'admin' => [
            ['name' => 'Dashboard', 'icon' => 'fas fa-tachometer-alt', 'url' => 'admin/dashboard.php'],
            ['name' => 'Students', 'icon' => 'fas fa-user-graduate', 'url' => 'admin/student_list.php'],
            ['name' => 'Teachers', 'icon' => 'fas fa-chalkboard-teacher', 'url' => 'admin/teacher_list.php'],
            ['name' => 'Attendance', 'icon' => 'fas fa-calendar-check', 'url' => 'admin/attendance_report.php'],
            ['name' => 'Fees', 'icon' => 'fas fa-money-bill-wave', 'url' => 'admin/fees.php'],
            ['name' => 'Timetable', 'icon' => 'fas fa-calendar-alt', 'url' => 'admin/timetable.php'],
            ['name' => 'Notice Board', 'icon' => 'fas fa-bullhorn', 'url' => 'admin/noticeboard.php'],
            ['name' => 'Messages', 'icon' => 'fas fa-envelope', 'url' => 'admin/messaging.php'],
            ['name' => 'Settings', 'icon' => 'fas fa-cog', 'url' => 'admin/settings.php']
        ],
        'teacher' => [
            ['name' => 'Dashboard', 'icon' => 'fas fa-tachometer-alt', 'url' => 'teacher/dashboard.php'],
            ['name' => 'Attendance', 'icon' => 'fas fa-calendar-check', 'url' => 'teacher/attendance.php'],
            ['name' => 'Exam Marks', 'icon' => 'fas fa-graduation-cap', 'url' => 'teacher/exam_marks.php'],
            ['name' => 'Timetable', 'icon' => 'fas fa-calendar-alt', 'url' => 'teacher/timetable_view.php'],
            ['name' => 'Messages', 'icon' => 'fas fa-envelope', 'url' => 'teacher/messaging.php']
        ],
        'student' => [
            ['name' => 'Dashboard', 'icon' => 'fas fa-tachometer-alt', 'url' => 'student/dashboard.php'],
            ['name' => 'Attendance', 'icon' => 'fas fa-calendar-check', 'url' => 'student/attendance_view.php'],
            ['name' => 'Exam Results', 'icon' => 'fas fa-graduation-cap', 'url' => 'student/exam_results.php'],
            ['name' => 'Timetable', 'icon' => 'fas fa-calendar-alt', 'url' => 'student/timetable_view.php'],
            ['name' => 'Messages', 'icon' => 'fas fa-envelope', 'url' => 'student/messaging.php']
        ],
        'parent' => [
            ['name' => 'Dashboard', 'icon' => 'fas fa-tachometer-alt', 'url' => 'parent/dashboard.php'],
            ['name' => 'Student Progress', 'icon' => 'fas fa-chart-line', 'url' => 'parent/student_progress.php'],
            ['name' => 'Attendance', 'icon' => 'fas fa-calendar-check', 'url' => 'parent/attendance_view.php'],
            ['name' => 'Messages', 'icon' => 'fas fa-envelope', 'url' => 'parent/messaging.php']
        ]
    ];
    
    return isset($modules[$role]) ? $modules[$role] : [];
}
