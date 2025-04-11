<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Redirect if already logged in
if (isLoggedIn()) {
    $role = $_SESSION['role'];
    redirectTo("$role/dashboard.php");
}

// Handle login form submission
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username']);
    $password = $_POST['password'];
    $role = sanitizeInput($_POST['role']);

    try {
        $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ? AND role = ?");
        $stmt->execute([$username, $role]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            redirectTo("$role/dashboard.php");
        } else {
            $error = 'Invalid credentials. Please try again.';
        }
    } catch (PDOException $e) {
        $error = 'Login failed. Please try again later.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo SITE_NAME; ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#FF0000',    // Red
                        secondary: '#FFD700',  // Yellow
                    }
                }
            }
        }
    </script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #FF0000 0%, #FFD700 100%);
            min-height: 100vh;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-6">
    <div class="w-full max-w-md">
        <!-- Logo and School Name -->
        <div class="text-center mb-8">
            <img src="assets/images/logo.png" alt="School Logo" class="mx-auto h-20 w-auto mb-4">
            <h1 class="text-3xl font-bold text-white">Success View Academy</h1>
            <p class="text-white text-opacity-90">School Management System</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-lg shadow-xl p-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Login to Your Account</h2>

            <?php if ($error): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p><?php echo $error; ?></p>
                </div>
            <?php endif; ?>

            <form method="POST" action="" class="space-y-6">
                <!-- Role Selection -->
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Select Role</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="relative">
                            <input type="radio" name="role" value="admin" class="peer sr-only" required>
                            <div class="peer-checked:border-primary peer-checked:text-primary hover:border-gray-300 cursor-pointer rounded-lg border-2 border-gray-200 p-4 text-center">
                                <i class="fas fa-user-shield text-2xl mb-2"></i>
                                <p class="text-sm font-medium">Admin</p>
                            </div>
                        </label>
                        <label class="relative">
                            <input type="radio" name="role" value="teacher" class="peer sr-only">
                            <div class="peer-checked:border-primary peer-checked:text-primary hover:border-gray-300 cursor-pointer rounded-lg border-2 border-gray-200 p-4 text-center">
                                <i class="fas fa-chalkboard-teacher text-2xl mb-2"></i>
                                <p class="text-sm font-medium">Teacher</p>
                            </div>
                        </label>
                        <label class="relative">
                            <input type="radio" name="role" value="student" class="peer sr-only">
                            <div class="peer-checked:border-primary peer-checked:text-primary hover:border-gray-300 cursor-pointer rounded-lg border-2 border-gray-200 p-4 text-center">
                                <i class="fas fa-user-graduate text-2xl mb-2"></i>
                                <p class="text-sm font-medium">Student</p>
                            </div>
                        </label>
                        <label class="relative">
                            <input type="radio" name="role" value="parent" class="peer sr-only">
                            <div class="peer-checked:border-primary peer-checked:text-primary hover:border-gray-300 cursor-pointer rounded-lg border-2 border-gray-200 p-4 text-center">
                                <i class="fas fa-users text-2xl mb-2"></i>
                                <p class="text-sm font-medium">Parent</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Username -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                            <i class="fas fa-user"></i>
                        </span>
                        <input type="text" name="username" id="username" required
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm"
                               placeholder="Enter your username">
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" name="password" id="password" required
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm"
                               placeholder="Enter your password">
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" name="remember" id="remember"
                               class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-700">
                            Remember me
                        </label>
                    </div>
                    <a href="#" class="text-sm font-medium text-primary hover:text-red-700">
                        Forgot password?
                    </a>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    <i class="fas fa-sign-in-alt mr-2"></i> Sign in
                </button>
            </form>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center text-white">
            <p>&copy; <?php echo date('Y'); ?> Success View Academy. All rights reserved.</p>
        </div>
    </div>

    <script>
        // Show/hide password toggle
        document.querySelectorAll('.toggle-password').forEach(function(button) {
            button.addEventListener('click', function() {
                const input = this.previousElementSibling;
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });
        });
    </script>
</body>
</html>
