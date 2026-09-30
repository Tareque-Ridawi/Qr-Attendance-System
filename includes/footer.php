<?php $footer_logo_path = strpos($_SERVER['SCRIPT_NAME'] ?? '', '/client-pages/') !== false ? '../assets/logo.png' : './assets/logo.png'; ?>
<footer>
            <div class="logo-container">
                <img src="<?php echo $footer_logo_path; ?>" alt="">
            </div>
            <div class="foot-info">
                <p class="foot-title">QR Code Based Attendance System</p>
                <p class="foot-abt">&copy; 2026 . All Rights Reserved . Developed by <span>The Red Hat Community</span></p>
            </div>
        </footer>