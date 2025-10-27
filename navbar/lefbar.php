<nav class="sb-sidenav accordion sb-sidenav-custom" id="sidenavAccordion" style="background: linear-gradient(90deg, #17a2b8, #189bbf); color: #FFFFFF;"> 
    <div class="sb-sidenav-menu" style="margin-top: 20px">
        <a class="nav-link" href="dashboard.php" style="color: #FFFFFF; padding: 15px 20px; margin-bottom: 10px; border-radius: 5px; display: flex; align-items: center; justify-content: start; text-decoration: none; transition: background 0.3s, transform 0.3s;">
            <div class="sb-nav-link-icon" style="width: 25px; margin-right: 10px;"><i class="fas fa-tachometer-alt"></i></div>
            Dashboard
        </a>
        <a class="nav-link" href="suratmasuk.php" style="color: #FFFFFF; padding: 15px 20px; margin-bottom: 10px; border-radius: 5px; display: flex; align-items: center; justify-content: start; text-decoration: none; transition: background 0.3s, transform 0.3s;">
            <div class="sb-nav-link-icon" style="width: 25px; margin-right: 10px;"><i class="fas fa-columns"></i></div>
            Pengajuan Surat
        </a>
         <a class="nav-link" href="list_aspirasi.php" style="color: #FFFFFF; padding: 15px 20px; margin-bottom: 10px; border-radius: 5px; display: flex; align-items: center; justify-content: start; text-decoration: none; transition: background 0.3s, transform 0.3s;">
            <div class="sb-nav-link-icon" style="width: 25px; margin-right: 10px;"><i class="fas fa-columns"></i></div>
            Pengajuan Aspirasi
        </a>
        <a class="nav-link" href="laporan.php" style="color: #FFFFFF; padding: 15px 20px; margin-bottom: 10px; border-radius: 5px; display: flex; align-items: center; justify-content: start; text-decoration: none; transition: background 0.3s, transform 0.3s;">
            <div class="sb-nav-link-icon" style="width: 25px; margin-right: 10px;"><i class="fas fa-book-open"></i></div>
            Laporan
        </a>
        <a class="nav-link" href="kabardesa.php" style="color: #FFFFFF; padding: 15px 20px; margin-bottom: 10px; border-radius: 5px; display: flex; align-items: center; justify-content: start; text-decoration: none; transition: background 0.3s, transform 0.3s;">
            <div class="sb-nav-link-icon" style="width: 25px; margin-right: 10px;"><i class="fa-regular fa-newspaper"></i></div>
            Kabar Desa
        </a>
    </div>
</nav>

<!-- CSS Inline untuk Menghapus Background Hover -->
<style>
    .sb-sidenav-custom .nav-link {
        position: relative;
        overflow: hidden;
        z-index: 0;
    }
    
    .sb-sidenav-custom .nav-link:hover::before {
        left: 100%;
    }
    
    .sb-sidenav-custom .nav-link:hover {
        color: #FFFFFF;
        transform: scale(1.05);
        box-shadow: 0px 4px 15px rgba(23, 162, 184, 0.4);
    }
    
    .sb-sidenav-custom .nav-link:active {
        background-color: #138496;
        transform: scale(0.98);
    }

    .sb-sidenav-custom .sb-nav-link-icon i {
        color: #FFFFFF;
        opacity: 0.8;
        transition: opacity 0.3s;
    }
    .sb-sidenav-custom .nav-link:hover .sb-nav-link-icon i {
        opacity: 1;
    }
</style>
