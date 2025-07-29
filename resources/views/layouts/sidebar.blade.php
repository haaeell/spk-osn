<nav class="sidebar sidebar-offcanvas">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('home') }}">
                <i class="fas fa-tachometer-alt menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <hr>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('users.index') }}">
                <i class="fas fa-user menu-icon"></i>
                <span class="menu-title">Data Pengguna</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('siswa.index') }}">
                <i class="fas fa-users menu-icon"></i>
                <span class="menu-title">Data Siswa</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('kriteria.index') }}">
                <i class="fas fa-list-alt menu-icon"></i>
                <span class="menu-title">Data Kriteria</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('penilaian.index') }}">
                <i class="fas fa-calculator menu-icon"></i>
                <span class="menu-title">Penilaian</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('perhitungan.index') }}">
                <i class="fas fa-calculator menu-icon"></i>
                <span class="menu-title">Perhitungan</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('hasil.index') }}">
                <i class="fas fa-chart-bar menu-icon"></i>
                <span class="menu-title">Hasil Akhir</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('riwayat.index') }}">
                <i class="fas fa-history menu-icon"></i>
                <span class="menu-title">Riwayat</span>
            </a>
        </li>
        <hr>
        <li class="nav-item">
            <a class="nav-link" href="#"
                onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">
                <i class="fas fa-sign-out-alt menu-icon"></i>
                <span class="menu-title">Logout</span>
            </a>
            <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </li>
    </ul>
</nav>
