<style>
    body {
        background: #f7f9f8;
        font-family: 'Poppins', sans-serif;
        margin: 0;
        padding: 0;
    }

    .sidebar {
        width: 250px;
        background: #256343;
        color: #fff;
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        padding: 25px 15px;
        transition: .3s;
        z-index: 1500;
    }

    .sidebar .logo-box {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .sidebar .menu-item {
        padding: 10px 12px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 12px;
        color: #ffffff;
        text-decoration: none;
        font-size: 15px;
        margin-bottom: 5px;
        transition: .2s;
        cursor: pointer;
    }

    .sidebar .menu-item:hover,
    .sidebar .menu-item.active {
        background: #173e2a;
    }

    .sidebar svg {
        width: 22px;
        height: 22px;
        stroke: #fff;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .topbar {
        margin-left: 250px;
        height: 90px;
        background: white;
        border-bottom: 1px solid #e5e5e5;
        padding: 15px 30px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        transition: .3s;
        position: relative;
        z-index: 1000;
    }

    .topbar-left {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .topbar-title {
        text-align: left;
        min-width: fit-content;
    }

    .topbar-right {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-left: auto;
    }

    .search-box {
        display: flex;
        align-items: center;
        background: #f5f5f5;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 8px 15px;
        width: 300px;
    }

    .search-box input {
        background: none;
        border: none;
        outline: none;
        padding: 0;
        font-size: 14px;
        color: #333;
        width: 100%;
    }

    .search-box input::placeholder {
        color: #999;
    }

    .search-box svg {
        width: 18px;
        height: 18px;
        stroke: #999;
        margin-right: 8px;
    }

    .content {
        margin-left: 250px;
        padding: 30px;
        padding-top: 20px !important;
        transition: .3s;
    }

    .hamburger {
        display: none;
        font-size: 28px;
        background: transparent;
        border: none;
        color: #256343;
        cursor: pointer;
        margin-right: auto;
    }

    @media(max-width: 768px) {
        .sidebar {
            left: -260px;
            z-index: 2000;
        }

        .sidebar.show {
            left: 0;
        }

        .topbar {
            margin-left: 0 !important;
            height: auto;
            padding: 10px 15px;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
        }

        .topbar-left {
            order: 1;
            width: 100%;
            margin-bottom: 10px;
        }

        .topbar-right {
            order: 3;
            width: 100%;
            margin-left: 0;
        }

        .topbar-title h5 {
            font-size: 16px;
            margin-bottom: 2px;
        }

        .topbar-title p {
            font-size: 12px;
        }

        .topbar img {
            width: 40px;
        }

        .search-box {
            width: 100%;
            order: 2;
        }

        .content {
            margin-left: 0 !important;
            margin-top: 10px;
            padding: 20px 15px;
        }

        .hamburger {
            display: block;
            font-size: 26px;
            margin-right: 10px;
        }
    }
</style>

<div class="topbar shadow-md">
    <div class="topbar-left">
        <button class="hamburger" id="toggleMenu">☰</button>

        <div class="topbar-title">
            <h5 class="fw-bold mb-0">Guru</h5>
            <p class="text-muted small mb-0">{{ date('d M Y') }}</p>
        </div>

        <div class="search-box">
            <svg fill="none" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"></circle>
                <path d="m21 21-4.35-4.35"></path>
            </svg>
            <input type="text" placeholder="Cari kelas, tugas, atau siswa...">
        </div>
    </div>

    <div class="topbar-right">
        <img src="{{ asset('assets/images/akun.png') }}" width="55">
    </div>
</div>

<div class="sidebar" id="sidebar">

    <div class="logo-box mb-4">
        <img src="{{ asset('assets/images/logo-smk.png') }}" width="80" class="mb-2">
        <h6 class="fw-bold">SMKN 1 KOTA BEKASI</h6>
    </div>

    <hr class="border-light">

    <a href="{{ route('guru.dashboard') }}"
        class="menu-item {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
        <svg fill="none" stroke-width="2">
            <path d="M3 12L12 3l9 9v9H3z" />
        </svg>
        Beranda
    </a>

    <a href="{{ Route::has('guru.kelas.index') ? route('guru.kelas.index') : route('guru.dashboard') }}"
        class="menu-item {{ request()->routeIs('guru.kelas.*') ? 'active' : '' }}">
        <svg fill="none" stroke-width="2">
            <rect x="3" y="5" width="18" height="16" rx="2" />
            <path d="M16 3v4M8 3v4M3 11h18" />
        </svg>
        Kelas Saya
    </a>

    <a href="{{ Route::has('guru.tugas.index') ? route('guru.tugas.index') : route('guru.dashboard') }}"
        class="menu-item {{ request()->routeIs('guru.tugas.*') ? 'active' : '' }}">
        <svg fill="none" stroke-width="2">
            <path d="M4 4h16v16H4z" />
            <path d="M4 10h16" />
        </svg>
        Tugas
    </a>

    <a href="{{ Route::has('guru.absensi.index') ? route('guru.absensi.index') : route('guru.dashboard') }}"
        class="menu-item {{ request()->routeIs('guru.absensi.*') ? 'active' : '' }}">
        <svg fill="none" stroke-width="2">
            <circle cx="12" cy="7" r="4" />
            <path d="M4 21c0-4 4-6 8-6s8 2 8 6" />
        </svg>
        Absensi
    </a>

    <a href="{{ Route::has('guru.izin.index') ? route('guru.izin.index') : route('guru.dashboard') }}"
        class="menu-item {{ request()->routeIs('guru.izin.*') ? 'active' : '' }}">
        <svg fill="none" stroke-width="2">
            <path d="M4 4h16v16H4z" />
            <path d="M4 10h16" />
        </svg>
        Pengajuan Izin
    </a>

    <a href="{{ Route::has('guru.pengumuman.index') ? route('guru.pengumuman.index') : route('guru.dashboard') }}"
        class="menu-item {{ request()->routeIs('guru.pengumuman.*') ? 'active' : '' }}">
        <svg fill="none" stroke-width="2">
            <path d="M3 11l18-6v14l-18-6v8l6 2" />
        </svg>
        Pengumuman
    </a>

    <hr class="border-light">

    <a href="#"
        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
        class="menu-item">
        <svg fill="none" stroke-width="2">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
            <path d="M16 17l5-5l-5-5" />
            <path d="M21 12H9" />
        </svg>
        Keluar
    </a>

</div>

<script>
    const toggleMenu = document.getElementById('toggleMenu');
    const sidebar = document.getElementById('sidebar');

    toggleMenu.addEventListener('click', () => {
        sidebar.classList.toggle('show');
    });

    document.querySelectorAll('.menu-item').forEach(item => {
        item.addEventListener('click', () => {
            if (window.innerWidth <= 768) {
                sidebar.classList.remove('show');
            }
        });
    });

    const searchBox = document.querySelector('.search-box input');
    searchBox.addEventListener('input', function(e) {
        console.log('Pencarian:', e.target.value);
    });
</script>