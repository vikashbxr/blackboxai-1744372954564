<?php
require_once 'config.php';
require_once 'functions.php';

if (!isLoggedIn() && basename($_SERVER['PHP_SELF']) !== 'index.php') {
    redirectTo('index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?></title>
    
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
    
    <!-- Custom Styles -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .nav-link:hover {
            background-color: rgba(255, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-gray-50">
    <?php if (isLoggedIn()): ?>
        <!-- Top Navigation Bar -->
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex justify-between h-16">
                    <!-- Logo and Brand -->
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <img class="h-8 w-auto" src="<?php echo SITE_URL; ?>/assets/images/logo.png" alt="School Logo">
                            <span class="ml-2 text-xl font-bold text-primary">Success View Academy</span>
                        </div>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden md:flex items-center space-x-4">
                        <?php if (isset($_SESSION['role'])): ?>
                            <?php foreach (getModulesByRole($_SESSION['role']) as $module): ?>
                                <a href="<?php echo SITE_URL . '/' . $module['url']; ?>" 
                                   class="nav-link px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-primary transition duration-150 ease-in-out">
                                    <i class="<?php echo $module['icon']; ?> mr-1"></i>
                                    <?php echo $module['name']; ?>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        
                        <!-- User Menu -->
                        <div class="ml-4 flex items-center">
                            <div class="relative">
                                <button class="flex items-center space-x-2 text-gray-700 hover:text-primary focus:outline-none">
                                    <img class="h-8 w-8 rounded-full" 
                                         src="<?php echo SITE_URL; ?>/assets/images/default-avatar.png" 
                                         alt="User avatar">
                                    <span class="text-sm font-medium"><?php echo $_SESSION['username'] ?? 'User'; ?></span>
                                </button>
                                <!-- Dropdown menu -->
                                <div class="hidden absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                                    <div class="py-1">
                                        <a href="<?php echo SITE_URL; ?>/profile.php" 
                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Profile
                                        </a>
                                        <a href="<?php echo SITE_URL; ?>/logout.php" 
                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Logout
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="md:hidden flex items-center">
                        <button class="mobile-menu-button p-2 rounded-md text-gray-700 hover:text-primary focus:outline-none">
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile menu -->
            <div class="hidden md:hidden">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <?php if (isset($_SESSION['role'])): ?>
                        <?php foreach (getModulesByRole($_SESSION['role']) as $module): ?>
                            <a href="<?php echo SITE_URL . '/' . $module['url']; ?>" 
                               class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary hover:bg-gray-50">
                                <i class="<?php echo $module['icon']; ?> mr-2"></i>
                                <?php echo $module['name']; ?>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </nav>

        <!-- Page Container -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Breadcrumb -->
            <div class="mb-4">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="<?php echo SITE_URL; ?>/index.php" 
                               class="text-gray-700 hover:text-primary">
                                <i class="fas fa-home mr-2"></i>
                                Home
                            </a>
                        </li>
                        <?php
                        $current_page = basename($_SERVER['PHP_SELF'], '.php');
                        if ($current_page != 'index'): ?>
                            <li>
                                <div class="flex items-center">
                                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                    <span class="text-gray-500 capitalize">
                                        <?php echo str_replace('_', ' ', $current_page); ?>
                                    </span>
                                </div>
                            </li>
                        <?php endif; ?>
                    </ol>
                </nav>
            </div>
    <?php endif; ?>
    <!-- Main Content Start -->
    <main>
