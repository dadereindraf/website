<!-- Navbar Header -->
<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- Logo -->
            <div class="navbar-brand-box">
                <a href="index.php" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="assets/images/logoUBL.png" height="30">
                    </span>
                    <span class="logo-lg">
                        <img src="assets/images/logoUBL.png">
                    </span>
                </a>
                <a href="index.php" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="assets/images/logoUBL.png" height="30">
                    </span>
                    <span class="logo-lg">
                        <img src="assets/images/logoUBL.png">
                    </span>
                </a>
            </div>
            <!-- End of Logo -->
            <!-- Hamburger Menu -->
            <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect" id="vertical-menu-btn">
                <i class="mdi mdi-menu"></i>
            </button>
            <div class="d-none d-sm-block ml-2">
                <!-- Show current page name as title -->
                <?php
                    // Get current file name
                    $currentPage = basename($_SERVER['PHP_SELF']);
                    // Set default page title
                    $pageTitle = "Dashboard";
                    // If not index.php, construct title from file name
                    if($currentPage !== "index.php") {
                        // Remove .php extension
                        $pageTitle = str_replace('.php', '', $currentPage);
                        // Add space before capital letters (except first letter)
                        $pageTitle = preg_replace('/(?<!\A)[A-Z]/', ' $0', $pageTitle);
                        // Capitalize the first letter
                        $pageTitle = ucfirst($pageTitle);
                    }
                    // Display page title
                    echo '<h4 class="page-title">' . $pageTitle . '</h4>';
                ?>
            </div>
            <!-- End of Hamburger Menu -->
        </div>
        <div class="d-flex">
            <!-- Avatar -->
            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user" src="assets/images/users/avatar-1.jpg" alt="Header Avatar">
                </button>
            </div>
            <!-- End Of Avatar -->
            <!-- Settings -->
            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon right-bar-toggle waves-effect">
                    <i class="mdi mdi-spin mdi-settings"></i>
                </button>
            </div>
            <!-- End of Settings -->
        </div>
    </div>
    <!-- End of Navbar Header -->
</header>
