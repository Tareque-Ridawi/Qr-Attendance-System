    <nav>
        <li class="mbl-nav-head">
            <p>Qr Code Based Attendance System</p>
            <div>
                <button class="expand-nav">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                        <path d="M0 96C0 78.3 14.3 64 32 64l384 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 128C14.3 128 0 113.7 0 96zM0 256c0-17.7 14.3-32 32-32l384 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 288c-17.7 0-32-14.3-32-32zM448 416c0 17.7-14.3 32-32 32L32 448c-17.7 0-32-14.3-32-32s14.3-32 32-32l384 0c17.7 0 32 14.3 32 32z" />
                    </svg>
                </button>
            </div>
        </li>
        <li class="mbl-nav">
            <ul class="active"><a href="index.php">Home</a></ul>
            <ul><a href="docs.php">Docs</a></ul>
            <ul><a href="faq.php">Faq</a></ul>
            <ul><a href="about-us.php">About Us</a></ul>
        </li>
        <li class="nav-btn">
            <ul><button id="tr-s-btn"><a href="access-pages/sign-in-te.php">Instructor Login</a></button></ul>
            <ul><button id="std-s-btn"><a href="access-pages/sign-in-st.php">Student Login</a></button></ul>
        </li>
    </nav>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const expandBtn = document.querySelector('.expand-nav');
            const mblNav = document.querySelector('.mbl-nav');
            const navBtn = document.querySelector('.nav-btn');
            let isExpanded = false;

            // SVG templates
            const hamburgerSVG = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M0 96C0 78.3 14.3 64 32 64l384 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 128C14.3 128 0 113.7 0 96zM0 256c0-17.7 14.3-32 32-32l384 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 288c-17.7 0-32-14.3-32-32zM448 416c0 17.7-14.3 32-32 32L32 448c-17.7 0-32-14.3-32-32s14.3-32 32-32l384 0c17.7 0 32 14.3 32 32z"/></svg>`;

            const closeSVG = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>`;

            expandBtn.addEventListener('click', function() {
                // Toggle navigation visibility
                mblNav.style.display = isExpanded ? 'none' : 'flex';
                navBtn.style.display = isExpanded ? 'none' : 'flex';

                // Update button icon
                this.innerHTML = isExpanded ? hamburgerSVG : closeSVG;

                // Toggle state
                isExpanded = !isExpanded;
            });
        });
    </script>