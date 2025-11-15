<nav class="sb-topnav navbar navbar-expand-lg navbar-dark topnav-custom">
    <div class="container-fluid px-4 d-flex align-items-center justify-content-between">

        <!-- Left | Toggle + Logo -->
        <div class="d-flex align-items-center">
            <button class="btn text-white me-3 sidebar-toggle-btn" id="sidebarToggle">
                <i class="fas fa-bars fs-4"></i>
            </button>
            <a href="dashboard.php" class="d-flex align-items-center text-decoration-none brand-link">
                <div class="logo-wrapper">
                    <img src="assets/img/logonganjuk.png" height="50" class="logo-img">
                </div>
                <div class="brand-text ms-2">
                    <span class="fw-bold fs-4 text-white d-block lh-1">Desa Kuncir</span>
                    <span class="brand-subtitle">Sistem Administrasi Desa</span>
                </div>
            </a>
        </div>

        <!-- Right | Menu -->
        <ul class="navbar-nav align-items-center">

            <!-- Notifikasi -->
            <li class="nav-item dropdown me-3">
                <a class="nav-link dropdown-toggle position-relative notification-btn"
                    id="notificationDropdown"
                    href="#"
                    role="button"
                    data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="fas fa-bell fa-lg"></i>
                    <span class="badge rounded-pill bg-danger notification-count pulse">0</span>
                </a>
                <div class="dropdown-menu dropdown-menu-end shadow-lg notification-menu">
                    <div class="notification-header">
                        <h6 class="mb-0"><i class="fas fa-envelope me-2"></i>Notifikasi Surat</h6>
                    </div>
                    <div class="notifications-container"></div>
                    <div class="notification-footer">
                        <a href="suratmasuk.php" class="text-decoration-none">
                            <i class="fas fa-arrow-right me-1"></i> Lihat Semua Surat
                        </a>
                    </div>
                </div>
            </li>

            <!-- Profil -->
            <li class="nav-item dropdown">
                <a class="nav-link text-white profile-btn dropdown-toggle d-flex align-items-center"
                    id="navbarDropdown"
                    href="#"
                    role="button"
                    data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <div class="profile-avatar me-2">
                        <i class="fas fa-user"></i>
                    </div>
                    <span class="profile-name me-1">Admin</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-lg profile-menu">
                    <li class="dropdown-header">
                        <div class="text-center py-2">
                            <div class="profile-avatar-large mb-2">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            <div class="fw-bold">Administrator</div>
                            <small class="text-grey">admin.desakuncir@gmail.com</small>
                        </div>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item" href="crud/gantipasswordadmin.php">
                            <i class="fas fa-key me-2 text-primary"></i> Ganti Password
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                            <i class="fas fa-sign-out-alt me-2"></i> Keluar
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutModalLabel">Konfirmasi Keluar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin keluar dari akun Anda?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <a href="utility/logout.php" class="btn btn-primary">Keluar</a>
            </div>
        </div>
    </div>
</div>

<style>
    /* ===== TOP NAVBAR ===== */
    .topnav-custom {
        background: linear-gradient(135deg, #3629B7 0%, #3629B7 50%, #B36CFF 100%);
        height: 75px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1030;
        backdrop-filter: blur(10px);
    }

    /* ===== SIDEBAR TOGGLE BUTTON ===== */
    .sidebar-toggle-btn {
        background: rgba(255, 255, 255, 0.15);
        border: none;
        border-radius: 10px;
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .sidebar-toggle-btn:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: scale(1.05);
        box-shadow: 0 4px 15px rgba(255, 255, 255, 0.2);
    }

    .sidebar-toggle-btn:active {
        transform: scale(0.95);
    }

    .sidebar-toggle-btn i {
        transition: transform 0.3s ease;
    }

    .sidebar-toggle-btn:hover i {
        transform: rotate(90deg);
    }

    /* ===== BRAND / LOGO ===== */
    .brand-link {
        transition: all 0.3s ease;
    }

    .brand-link:hover {
        transform: translateX(5px);
    }

    .logo-wrapper {
        background: rgba(255, 255, 255, 0.15);
        border-radius: 12px;
        padding: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .brand-link:hover .logo-wrapper {
        background: rgba(255, 255, 255, 0.25);
        box-shadow: 0 4px 15px rgba(255, 255, 255, 0.3);
    }

    .logo-img {
        transition: transform 0.3s ease;
    }

    .brand-link:hover .logo-img {
        transform: scale(1.05);
    }

    .brand-text {
        line-height: 1.2;
    }

    .brand-subtitle {
        font-size: 0.7rem;
        color: rgba(255, 255, 255, 0.8);
        font-weight: 400;
        display: block;
        margin-top: 2px;
    }

    /* ===== NOTIFICATION BUTTON ===== */
    .notification-btn {
        background: rgba(255, 255, 255, 0.12);
        border-radius: 12px;
        padding: 10px 14px !important;
        transition: all 0.3s ease;
        position: relative;
    }

    .notification-btn:hover {
        background: rgba(255, 255, 255, 0.22);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(255, 255, 255, 0.2);
    }

    .notification-btn i {
        transition: all 0.3s ease;
    }

    .notification-btn:hover i {
        transform: scale(1.1);
        animation: swing 0.5s ease;
    }

    @keyframes swing {

        0%,
        100% {
            transform: rotate(0deg);
        }

        25% {
            transform: rotate(15deg);
        }

        75% {
            transform: rotate(-15deg);
        }
    }

    /* Badge Notification */
    .notification-count {
        font-size: 0.65rem;
        padding: 3px 6px;
        position: absolute;
        top: 3px;
        right: 8px;
        min-width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        box-shadow: 0 2px 8px rgba(220, 53, 69, 0.4);
    }

    /* Pulse Animation */
    .pulse {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7);
        }

        70% {
            box-shadow: 0 0 0 10px rgba(220, 53, 69, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
        }
    }

    /* ===== NOTIFICATION DROPDOWN ===== */
    .notification-menu {
        width: 380px;
        max-height: 500px;
        border-radius: 16px;
        border: none;
        padding: 0;
        margin-top: 12px;
        overflow: hidden;
    }

    .notification-header {
        background: linear-gradient(135deg, #0d47a1, #1976d2);
        color: white;
        padding: 16px 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .notification-header h6 {
        font-weight: 600;
        font-size: 0.95rem;
    }

    .notifications-container {
        max-height: 350px;
        overflow-y: auto;
        padding: 8px 0;
    }

    /* Custom Scrollbar */
    .notifications-container::-webkit-scrollbar {
        width: 6px;
    }

    .notifications-container::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .notifications-container::-webkit-scrollbar-thumb {
        background: #bbb;
        border-radius: 10px;
    }

    .notifications-container::-webkit-scrollbar-thumb:hover {
        background: #999;
    }

    .notification-item {
        padding: 14px 20px;
        border-left: 3px solid transparent;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .notification-item:hover {
        background: #f8f9fa;
        border-left-color: #1976d2;
        transform: translateX(3px);
    }

    .notification-item .small {
        font-size: 0.8rem;
    }

    .notification-footer {
        background: #f8f9fa;
        padding: 12px 20px;
        text-align: center;
        border-top: 1px solid #dee2e6;
    }

    .notification-footer a {
        color: #1976d2;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .notification-footer a:hover {
        color: #0d47a1;
        transform: translateX(3px);
        display: inline-block;
    }

    /* ===== PROFILE BUTTON ===== */
    .profile-btn {
        background: rgba(255, 255, 255, 0.12);
        border-radius: 50px;
        padding: 6px 16px 6px 8px !important;
        transition: all 0.3s ease;
    }

    .profile-btn:hover {
        background: rgba(255, 255, 255, 0.22);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(255, 255, 255, 0.2);
    }

    .profile-avatar {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, #ffd54f, #ffb300);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        color: #0d47a1;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
    }

    .profile-btn:hover .profile-avatar {
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 4px 12px rgba(255, 213, 79, 0.5);
    }

    .profile-name {
        font-weight: 600;
        font-size: 0.95rem;
    }

    /* ===== PROFILE DROPDOWN ===== */
    .profile-menu {
        width: 280px;
        border-radius: 16px;
        border: none;
        padding: 0;
        margin-top: 12px;
        overflow: hidden;
    }

    .profile-menu .dropdown-header {
        background: linear-gradient(135deg, #0d47a1, #1976d2);
        color: white;
        padding: 0;
        border: none;
    }

    .profile-avatar-large {
        width: 70px;
        height: 70px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        font-size: 2.5rem;
        color: white;
    }

    .profile-menu .dropdown-item {
        padding: 12px 20px;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }

    .profile-menu .dropdown-item:hover {
        background: #1b89f7ff;
        transform: translateX(5px);
        padding-left: 25px;
    }

    .profile-menu .dropdown-item i {
        width: 20px;
        text-align: center;
    }

    .profile-menu .dropdown-item.text-danger:hover {
        background: rgba(204, 45, 61, 0.47);
        color: #f80019ff !important;
    }

    /* ===== GENERAL DROPDOWN ===== */
    .dropdown-menu {
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 992px) {
        .topnav-custom {
            height: 70px;
        }

        .brand-text .fs-4 {
            font-size: 1.1rem !important;
        }

        .brand-subtitle {
            font-size: 0.65rem;
        }

        .logo-img {
            height: 45px !important;
        }

        .notification-menu {
            width: 340px;
        }

        .profile-menu {
            width: 260px;
        }
    }

    @media (max-width: 768px) {
        .topnav-custom {
            height: 65px;
            padding: 0 15px !important;
        }

        .sidebar-toggle-btn {
            width: 40px;
            height: 40px;
            margin-right: 10px !important;
        }

        .brand-text .fs-4 {
            font-size: 1rem !important;
        }

        .brand-subtitle {
            display: none;
        }

        .logo-img {
            height: 40px !important;
        }

        .profile-name {
            display: none;
        }

        .profile-btn {
            padding: 6px 10px !important;
        }

        .notification-menu {
            width: 320px;
            left: auto !important;
            right: 0 !important;
        }

        .profile-menu {
            width: 250px;
        }
    }

    @media (max-width: 576px) {
        .topnav-custom {
            height: 60px;
        }

        .logo-wrapper {
            padding: 6px;
        }

        .logo-img {
            height: 35px !important;
        }

        .brand-text .fs-4 {
            font-size: 0.9rem !important;
        }

        .notification-btn,
        .profile-btn {
            padding: 8px 10px !important;
        }

        .notification-menu {
            width: calc(100vw - 30px);
            max-width: 300px;
        }

        .notification-header h6 {
            font-size: 0.85rem;
        }

        .profile-menu {
            width: 240px;
        }
    }

    /* ===== EMPTY STATE ===== */
    .notification-empty {
        padding: 40px 20px;
        text-align: center;
        color: #6c757d;
    }

    .notification-empty i {
        font-size: 3rem;
        opacity: 0.3;
        margin-bottom: 15px;
    }

    .notification-empty p {
        margin: 0;
        font-size: 0.9rem;
    }
</style>

<!-- JavaScript Section -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function updateNotifications() {
        // Update notification count
        $.post('notification_count.php', {
            action: 'get_count'
        }, function(count) {
            const badge = $('.notification-count');
            const newCount = parseInt(count) || 0;

            badge.text(newCount);

            // Hide badge if count is 0
            if (newCount === 0) {
                badge.hide();
            } else {
                badge.show();
            }
        }).fail(function() {
            console.error('Failed to update notification count');
        });

        // Update notification list
        $.post('notifications.php', {
            action: 'get_notifications'
        }, function(data) {
            try {
                const notifications = JSON.parse(data);
                const container = $('.notifications-container');
                container.empty();

                if (notifications.length === 0) {
                    container.append(`
                        <div class="notification-empty">
                            <i class="fas fa-inbox"></i>
                            <p>Tidak ada surat masuk baru</p>
                        </div>
                    `);
                } else {
                    notifications.forEach(function(notif) {
                        const notifHtml = `
                            <a class="dropdown-item notification-item" 
                               href="suratmasuk_detail.php?no_pengajuan=${notif.no_pengajuan}&kode_surat=${notif.kode_surat}&id=${notif.id}">
                                <div class="d-flex align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="fw-bold mb-1">${notif.nama}</div>
                                        <div class="small text-muted mb-1">
                                            <i class="fas fa-file-alt me-1"></i>Surat ${notif.kode_surat}
                                        </div>
                                        <div class="small text-muted">
                                            <i class="far fa-clock me-1"></i>${notif.tanggal}
                                        </div>
                                    </div>
                                    <div class="ms-2">
                                        <span class="badge bg-primary rounded-pill">Baru</span>
                                    </div>
                                </div>
                            </a>
                        `;
                        container.append(notifHtml);
                    });
                }
            } catch (error) {
                console.error('Error parsing notifications:', error);
                $('.notifications-container').html(`
                    <div class="notification-empty">
                        <i class="fas fa-exclamation-triangle"></i>
                        <p>Gagal memuat notifikasi</p>
                    </div>
                `);
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error('Ajax request failed:', textStatus, errorThrown);
            $('.notifications-container').html(`
                <div class="notification-empty">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p>Gagal memuat notifikasi</p>
                </div>
            `);
        });
    }

    // Initialize when document is ready
    $(document).ready(function() {
        // Update notifications immediately
        updateNotifications();

        // Update every 30 seconds
        setInterval(updateNotifications, 30000);

        // Update when notification dropdown is opened
        $('#notificationDropdown').on('show.bs.dropdown', function() {
            updateNotifications();
        });

        // Sidebar toggle functionality
        $('#sidebarToggle').on('click', function() {
            $('.sidebar, .sb-sidenav-custom').toggleClass('collapsed');
            $('#layoutSidenav_content, .content').toggleClass('collapsed');

            // Save state to localStorage
            const isCollapsed = $('.sidebar').hasClass('collapsed');
            localStorage.setItem('sidebarCollapsed', isCollapsed);
        });

        // Restore sidebar state from localStorage
        const savedState = localStorage.getItem('sidebarCollapsed');
        if (savedState === 'true') {
            $('.sidebar, .sb-sidenav-custom').addClass('collapsed');
            $('#layoutSidenav_content, .content').addClass('collapsed');
        }

        // Close dropdowns when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.dropdown').length) {
                $('.dropdown-menu').removeClass('show');
            }
        });
    });
</script>