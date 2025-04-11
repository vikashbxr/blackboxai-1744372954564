</main>
    <!-- Main Content End -->

    <?php if (isLoggedIn()): ?>
        <!-- Footer -->
        <footer class="bg-white shadow-lg mt-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <!-- Copyright -->
                    <div class="text-center md:text-left mb-4 md:mb-0">
                        <p class="text-gray-600">
                            &copy; <?php echo date('Y'); ?> Success View Academy. All rights reserved.
                        </p>
                    </div>

                    <!-- Quick Links -->
                    <div class="flex space-x-6">
                        <a href="#" class="text-gray-600 hover:text-primary transition duration-150 ease-in-out">
                            Privacy Policy
                        </a>
                        <a href="#" class="text-gray-600 hover:text-primary transition duration-150 ease-in-out">
                            Terms of Service
                        </a>
                        <a href="#" class="text-gray-600 hover:text-primary transition duration-150 ease-in-out">
                            Contact Us
                        </a>
                    </div>
                </div>
            </div>
        </footer>
    <?php endif; ?>

    <!-- JavaScript -->
    <script>
        // Mobile menu toggle
        document.querySelector('.mobile-menu-button')?.addEventListener('click', function() {
            document.querySelector('.mobile-menu')?.classList.toggle('hidden');
        });

        // User dropdown toggle
        document.querySelector('.user-menu-button')?.addEventListener('click', function() {
            document.querySelector('.user-menu')?.classList.toggle('hidden');
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.user-menu-button')) {
                document.querySelector('.user-menu')?.classList.add('hidden');
            }
            if (!event.target.closest('.mobile-menu-button') && !event.target.closest('.mobile-menu')) {
                document.querySelector('.mobile-menu')?.classList.add('hidden');
            }
        });

        // Flash messages auto-hide
        document.querySelectorAll('.alert-dismissible').forEach(function(alert) {
            setTimeout(function() {
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.style.display = 'none';
                }, 300);
            }, 5000);
        });

        // Form validation
        document.querySelectorAll('form').forEach(function(form) {
            form.addEventListener('submit', function(event) {
                let isValid = true;
                form.querySelectorAll('[required]').forEach(function(input) {
                    if (!input.value.trim()) {
                        isValid = false;
                        input.classList.add('border-red-500');
                        let errorDiv = input.nextElementSibling;
                        if (!errorDiv || !errorDiv.classList.contains('error-message')) {
                            errorDiv = document.createElement('div');
                            errorDiv.className = 'error-message text-red-500 text-sm mt-1';
                            input.parentNode.insertBefore(errorDiv, input.nextSibling);
                        }
                        errorDiv.textContent = 'This field is required';
                    }
                });
                if (!isValid) {
                    event.preventDefault();
                }
            });
        });

        // Remove error styling on input
        document.querySelectorAll('input, select, textarea').forEach(function(input) {
            input.addEventListener('input', function() {
                this.classList.remove('border-red-500');
                let errorDiv = this.nextElementSibling;
                if (errorDiv && errorDiv.classList.contains('error-message')) {
                    errorDiv.remove();
                }
            });
        });
    </script>
</body>
</html>
