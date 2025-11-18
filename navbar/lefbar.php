<nav class="sb-sidenav accordion sb-sidenav-custom main-sidebar" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
        <!-- Dashboard -->
        <a href="dashboard.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active' : ''; ?>">
            <div class="sb-nav-link-icon"><i class="fas fa-home"></i></div>
            <span class="menu-text">Dashboard</span>
        </a>

        <!-- Pengajuan Surat -->
        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'suratmasuk.php' ? 'active' : ''; ?>" href="suratmasuk.php">
            <div class="sb-nav-link-icon"><i class="fas fa-file-signature"></i></div>
            <span class="menu-text">Pengajuan Surat</span>
        </a>

        <!-- Pengajuan Aspirasi -->
        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'list_aspirasi.php' ? 'active' : ''; ?>" href="list_aspirasi.php">
            <div class="sb-nav-link-icon"><i class="fas fa-comments"></i></div>
            <span class="menu-text">Pengajuan Aspirasi</span>
        </a>

        <!-- Laporan -->
        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'laporan.php' ? 'active' : ''; ?>" href="laporan.php">
            <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
            <span class="menu-text">Laporan</span>
        </a>

        <!-- Kabar Desa -->
        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'kabardesa.php' ? 'active' : ''; ?>" href="kabardesa.php">
            <div class="sb-nav-link-icon"><i class="fa-regular fa-newspaper"></i></div>
            <span class="menu-text">Informasi Desa</span>
        </a>
    </div>
</nav>

<style>
    /* Sidebar Backdrop */
    .sb-sidenav-custom {
        width: 240px;
        min-height: 100vh;
        background: linear-gradient(180deg, #3629B7, #3629B7, #B36CFF);
        color: #FFFFFF;
        padding-top: 120px;
        box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
        position: fixed;
        left: 0;
        top: 0;
        z-index: 1000;
        transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1), width 0.35s ease;
        /* tambahkan transisi width */
    }

    /* Sudut sidebar dibuat melengkung */
    .main-sidebar {
        border-top-right-radius: 40px;
        border-bottom-right-radius: 40px;
        overflow: hidden;
    }

    /* Container Menu */
    .sb-sidenav-menu {
        padding: 15px 0;
        max-height: calc(100vh - 120px);
        overflow-y: auto;
    }

    /* Style Menu Item */
    .sb-sidenav-custom .nav-link {
        color: #e6f7ff;
        padding: 16px 20px;
        margin: 8px 15px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        font-weight: 500;
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        font-size: 15px;
        text-decoration: none;
        position: relative;
        overflow: hidden;
    }

    /* Efek ripple subtle */
    .sb-sidenav-custom .nav-link::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.15);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .sb-sidenav-custom .nav-link:hover::before {
        width: 300px;
        height: 300px;
    }

    /* Ikon */
    .sb-sidenav-custom .sb-nav-link-icon {
        width: 28px;
        margin-right: 14px;
        font-size: 1.15rem;
        opacity: 0.9;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        z-index: 1;
    }

    /* Text Menu */
    .menu-text {
        position: relative;
        z-index: 1;
        transition: all 0.3s ease;
    }

    /* Hover Effect */
    .sb-sidenav-custom .nav-link:hover {
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
        transform: translateX(8px) scale(1.02);
        box-shadow: 0 4px 15px rgba(255, 255, 255, 0.25);
    }

    .sb-sidenav-custom .nav-link:hover .sb-nav-link-icon {
        opacity: 1;
        transform: scale(1.15) rotate(5deg);
    }

    .sb-sidenav-custom .nav-link:hover .menu-text {
        font-weight: 600;
    }

    /* Active State */
    .sb-sidenav-custom .nav-link.active {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.35), rgba(255, 255, 255, 0.25));
        color: #fff;
        font-weight: 700;
        border-left: 5px solid #ffd54f;
        transform: translateX(6px);
        box-shadow: 0 4px 20px rgba(255, 255, 255, 0.4);
    }

    .sb-sidenav-custom .nav-link.active .sb-nav-link-icon {
        opacity: 1;
        color: #ffd54f;
        transform: scale(1.1);
    }

    /* ==== COLLAPSED MODE ==== */
    .sidebar.collapsed {
        width: 75px;
    }

    .sidebar.collapsed .nav-link {
        padding: 16px 10px;
        justify-content: center;
        margin: 8px 8px;
    }

    .sidebar.collapsed .menu-text {
        opacity: 0;
        width: 0;
        overflow: hidden;
        margin: 0;
    }

    .sidebar.collapsed .sb-nav-link-icon {
        margin-right: 0;
    }

    .sidebar.collapsed .nav-link::after {
        content: attr(title);
        position: absolute;
        left: 100%;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0, 0, 0, 0.85);
        color: white;
        padding: 8px 12px;
        border-radius: 6px;
        white-space: nowrap;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
        margin-left: 10px;
        font-size: 13px;
        z-index: 1001;
    }

    .sidebar.collapsed .nav-link:hover::after {
        opacity: 1;
    }

    /* ==== RESPONSIVE DESIGN — DIPERBAIKI ==== */
    @media (max-width: 991.98px) {
        .sb-sidenav-custom {
            width: 230px;
            padding-top: 100px;
        }

        .sb-sidenav-custom .nav-link {
            padding: 14px 18px;
            font-size: 14px;
        }
    }

    @media (max-width: 767.98px) {
        .sb-sidenav-custom {
            width: 220px;
            padding-top: 90px;
            transform: translateX(-100%);
            transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        /* Sidebar muncul saat ada kelas 'mobile-show' */
        .sb-sidenav-custom.mobile-show {
            transform: translateX(0);
        }

        .sb-sidenav-custom .nav-link {
            margin: 6px 12px;
            padding: 13px 16px;
        }
    }

    @media (max-width: 575.98px) {
        .sb-sidenav-custom {
            width: 190px;
            padding-top: 75px;
        }

        .sb-sidenav-custom .nav-link {
            padding: 12px 12px;
            font-size: 12.5px;
            margin: 5px 8px;
        }

        .sb-sidenav-custom .sb-nav-link-icon {
            font-size: 1rem;
            margin-right: 10px;
        }
    }

    /* Animasi slide-in hanya di desktop */
    @media (min-width: 768px) {
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .sb-sidenav-custom .nav-link {
            animation: slideIn 0.5s ease forwards;
        }

        .sb-sidenav-custom .nav-link:nth-child(1) {
            animation-delay: 0.1s;
        }

        .sb-sidenav-custom .nav-link:nth-child(2) {
            animation-delay: 0.2s;
        }

        .sb-sidenav-custom .nav-link:nth-child(3) {
            animation-delay: 0.3s;
        }

        .sb-sidenav-custom .nav-link:nth-child(4) {
            animation-delay: 0.4s;
        }

        .sb-sidenav-custom .nav-link:nth-child(5) {
            animation-delay: 0.5s;
        }
    }

    /* Scrollbar */
    .sb-sidenav-menu::-webkit-scrollbar {
        width: 6px;
    }

    .sb-sidenav-menu::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
    }

    .sb-sidenav-menu::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.3);
        border-radius: 10px;
    }

    .sb-sidenav-menu::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.5);
    }
</style>
<script>
    $('#sidebarToggle').on('click', function() {
        const isMobile = window.innerWidth <= 767;

        if (isMobile) {
            // Toggle sidebar mobile
            $('.sb-sidenav-custom').toggleClass('mobile-show');
        } else {
            // Toggle collapsed mode di desktop
            $('.sidebar, .sb-sidenav-custom').toggleClass('collapsed');
            $('#layoutSidenav_content, .content').toggleClass('collapsed');
        }

        // Simpan state hanya untuk desktop
        if (!isMobile) {
            const isCollapsed = $('.sidebar').hasClass('collapsed');
            localStorage.setItem('sidebarCollapsed', isCollapsed);
        }
    });
</script>