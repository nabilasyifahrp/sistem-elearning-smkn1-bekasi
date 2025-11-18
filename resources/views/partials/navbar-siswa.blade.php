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
        justify-content: flex-end;
        gap: 20px;
        transition: .3s;
        position: relative;
        z-index: 1000;
    }

    .topbar-title {
        text-align: right;
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
            height: 70px;
            padding: 10px 15px;
            justify-content: space-between;
            gap: 10px;
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
    <button class="hamburger" id="toggleMenu">☰</button>

    <div class="topbar-title">
        <h5 class="fw-bold mb-0">{{ Auth::user()->siswa->nama }}</h5>
        <p class="text-muted small mb-0">{{ Auth::user()->siswa->kelas->tingkat }} {{ Auth::user()->siswa->kelas->jurusan }} {{ Auth::user()->siswa->kelas->kelas }}</p>
    </div>

    <img src="{{ asset('assets/images/akun.png') }}" width="55">
</div>

<div class="sidebar" id="sidebar">

    <div class="logo-box mb-4">
        <img src="{{ asset('assets/images/logo-smk.png') }}" width="80" class="mb-2">
        <h6 class="fw-bold">SMKN 1 KOTA BEKASI</h6>
    </div>

    <hr class="border-light">

    <a href="{{ route('siswa.dashboard') }}"
        class="menu-item {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
        <svg fill="none" stroke-width="2">
            <path d="M3 12L12 3l9 9v9H3z" />
        </svg>
        Beranda
    </a>

    <a href="{{ route('siswa.absensi') }}"
        class="menu-item {{ request()->routeIs('siswa.absensi') ? 'active' : '' }}">
        <svg fill="none" stroke-width="2">
            <rect x="3" y="5" width="18" height="16" rx="2" />
            <path d="M16 3v4M8 3v4M3 11h18" />
            <path d="M9 15l2 2 4-4" />
        </svg>
        Absensi Saya
    </a>

    <a href="{{ route('siswa.pengajuan_izin') }}"
        class="menu-item {{ request()->routeIs('siswa.pengajuan_izin') || request()->routeIs('siswa.create_pengajuan_izin') ? 'active' : '' }}">
        <svg fill="none" stroke-width="2">
            <rect x="3" y="3" width="18" height="18" rx="2" />
            <path d="M9 11l2 2 4-4" />
        </svg>
        Pengajuan Izin
    </a>

    <a href="{{ route('siswa.pengumuman') }}"
        class="menu-item {{ request()->routeIs('siswa.pengumuman') || request()->routeIs('siswa.detail_pengumuman') ? 'active' : '' }}">
        <svg fill="none" stroke-width="2">
            <path d="M3 11l18-6v14l-18-6v8l6 2" />
        </svg>
        Pengumuman
    </a>

    <hr class="border-light">

    <a href="#" class="menu-item">
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
</script>