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
        position: fixed;
        top: 0;
        left: 250px;
        right: 0;
        height: 90px;
        background: white;
        border-bottom: 1px solid #e5e5e5;
        padding: 15px 30px;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 20px;
        z-index: 2000;
    }


    .topbar-title {
        text-align: right;
    }

    .content {
        margin-left: 250px;
        padding: 30px;
        padding-top: 120px;
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

    <div class="topbar-title text-end">
        <h5 class="fw-bold mb-0">kurikulum@smkn1.com</h5>
        @php
            use Carbon\Carbon;
            Carbon::setLocale('id');
            $today = Carbon::now()->translatedFormat('l, d F Y');
        @endphp
        <p class="text-muted small mb-0">{{ $today }}</p>
    </div>

    <img src="{{ asset('assets/images/akun.png') }}" width="55">
</div>

<div class="sidebar" id="sidebar">

    <div class="logo-box mb-4">
        <img src="{{ asset('assets/images/logo-smk.png') }}" width="80" class="mb-2">
        <h6 class="fw-bold">SMKN 1 KOTA BEKASI</h6>
    </div>

    <hr class="border-light">

    <a href="{{ route('admin.dashboard') }}"
        class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <svg fill="none" stroke-width="2">
            <path d="M3 12L12 3l9 9v9H3z" />
        </svg>
        Beranda
    </a>

    <a href="{{ route('admin.guru.index') }}"
        class="menu-item {{ request()->routeIs('admin.guru.*') ? 'active' : '' }}">
        <svg fill="none" stroke-width="2">
            <circle cx="12" cy="7" r="4" />
            <path d="M4 21c0-4 4-6 8-6s8 2 8 6" />
        </svg>
        Kelola Guru
    </a>

    <a href="{{ route('admin.kelas.index') }}"
        class="menu-item {{ request()->routeIs('admin.kelas.*') ? 'active' : '' }}">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <rect x="2" y="4" width="20" height="14" rx="1.5"></rect>
            <path d="M8 20h8"></path>
            <path d="M7 8h10"></path>
            <path d="M7 12h6"></path>
        </svg>
        Kelola Kelas
    </a>


    <a href="{{ route('admin.siswa.index') }}"
        class="menu-item {{ request()->routeIs('admin.siswa.*') ? 'active' : '' }}">
        <svg fill="none" stroke-width="2">
            <circle cx="9" cy="7" r="4" />
            <circle cx="17" cy="11" r="4" />
            <path d="M3 21c0-4 4-6 8-6" />
            <path d="M13 17c4 0 8 2 8 6" />
        </svg>
        Kelola Siswa
    </a>

    <a href="{{ route('admin.mapel.index') }}"
        class="menu-item {{ request()->routeIs('admin.mapel.*') ? 'active' : '' }}">
        <svg fill="none" stroke-width="2">
            <path d="M4 4h16v16H4z" />
            <path d="M4 10h16" />
        </svg>
        Kelola Mapel
    </a>

    <a href="{{ route('admin.jadwalmapel.index') }}"
        class="menu-item {{ request()->routeIs('admin.jadwalmapel.*') ? 'active' : '' }}">
        <svg fill="none" stroke-width="2">
            <rect x="3" y="5" width="18" height="16" rx="2" />
            <path d="M16 3v4M8 3v4M3 11h18" />
        </svg>
        Jadwal Mapel
    </a>

    <a href="{{ route('admin.pengumuman.index') }}"
        class="menu-item {{ request()->routeIs('admin.pengumuman.*') ? 'active' : '' }}">
        <svg fill="none" stroke-width="2">
            <path d="M3 11l18-6v14l-18-6v8l6 2" />
        </svg>
        Pengumuman
    </a>

    <hr class="border-light">

    <form action="{{ route('logout') }}" method="POST" class="menu-item">
        @csrf
        <svg fill="none" stroke-width="2">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
            <path d="M16 17l5-5l-5-5" />
            <path d="M21 12H9" />
        </svg>
        <button type="submit">Keluar</button>
    </form>
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
