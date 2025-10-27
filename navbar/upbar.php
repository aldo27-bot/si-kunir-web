<nav class="sb-topnav navbar navbar-expand navbar-dark" style="background: linear-gradient(90deg, #17a2b8, #0d6efd); height: 80px;">
    <!-- Navbar Brand and Sidebar Toggle grouped in flexbox -->
    <div class="d-flex align-items-center ps-0 ps-lg-3">
        <button class="btn btn-link btn-sm text-white me-3" id="sidebarToggle">
            <i class="fas fa-bars fs-5"></i>
        </button>
        <a href="dashboard.php" class="d-flex align-items-center" style="text-decoration: none;">
            <img src="assets/img/logonganjuk.png" height="50" style="margin-right: 1px;" alt="Logo">
            <span class="navbar-brand fs-3 fw-bold text-light">Ngetos</span>
        </a>
    </div>

    <!-- Navbar Items -->
    <ul class="navbar-nav ms-auto me-5">
        <!-- Notifications Dropdown -->
        <li class="nav-item dropdown me-1">
            <a class="nav-link dropdown-toggle text-white position-relative" id="notificationDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-bell fa-lg"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-count" style="font-size: 0.6rem;">
                    0
                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-end shadow-lg notification-menu" aria-labelledby="notificationDropdown" style="width: 300px; max-height: 400px; overflow-y: auto;">
                <h6 class="dropdown-header">Notifikasi Surat Masuk</h6>
                <div class="notifications-container">
                    <!-- Notifications will be populated here -->
                </div>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-center small text-muted" href="suratmasuk.php">Lihat Semua Surat</a>
            </div>
        </li>
        
        <li class="nav-item dropdown me-5 me-lg-0">
            <a class="nav-link dropdown-toggle text-white me-5 me-lg-0" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-user-circle fa-lg me-2"></i> Admin
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow-lg me-5 me-lg-0" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="crud/gantipasswordadmin.php"><i class="fas fa-key me-2"></i> Ganti Password</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
    <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
        <i class="fas fa-sign-out-alt me-2"></i> Keluar
    </a>
</li>            
</ul>
        </li>
    </ul>
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

<!-- Add this script section at the bottom of your page, before closing body tag -->
<script>
function updateNotifications() {
    // Update notification count
    $.post('notification_count.php', {action: 'get_count'}, function(count) {
        $('.notification-count').text(count > 0 ? count : '0');
    });

    // Update notification list
    $.post('notifications.php', {action: 'get_notifications'}, function(data) {
        try {
            const notifications = JSON.parse(data);
            const container = $('.notifications-container');
            container.empty();

            if (notifications.length === 0) {
                container.append('<div class="dropdown-item text-muted">Tidak ada surat masuk baru</div>');
            } else {
                notifications.forEach(function(notif) {
                    const notifHtml = `
                        <a class="dropdown-item notification-item" 
                           href="suratmasuk_detail.php?no_pengajuan=${notif.no_pengajuan}&kode_surat=${notif.kode_surat}&id=${notif.id}">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="small text-muted">${notif.tanggal}</div>
                                    <div class="fw-bold">${notif.nama}</div>
                                    <div class="small">Surat ${notif.kode_surat}</div>
                                </div>
                                <div class="ms-2">
                                    <span class="badge bg-primary">Baru</span>
                                </div>
                            </div>
                        </a>
                    `;
                    container.append(notifHtml);
                });
            }
        } catch (error) {
            console.error('Error parsing notifications:', error);
            $('.notifications-container').html('<div class="dropdown-item text-muted">Error loading notifications</div>');
        }
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.error('Ajax request failed:', textStatus, errorThrown);
        $('.notifications-container').html('<div class="dropdown-item text-muted">Error loading notifications</div>');
    });
}

// Update notifications immediately and then every 30 seconds
$(document).ready(function() {
    updateNotifications();
    setInterval(updateNotifications, 30000);
});

// Update when notification dropdown is opened
$('#notificationDropdown').on('show.bs.dropdown', function () {
    updateNotifications();
});
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>