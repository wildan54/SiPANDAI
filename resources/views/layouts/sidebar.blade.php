<aside class="main-sidebar elevation-4" style="background-color: #030F6B;">

  <style>
    /* Semua link sidebar berwarna putih */
    .sidebar .nav-link {
      color: #ffffff !important;
    }

    /* Hover / aktif */
    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
      background-color: #1a237e !important; /* biru tua saat aktif/hover */
      color: #ffffff !important;
    }

    /* Icon di nav-link */
    .sidebar .nav-icon {
      color: #ffffff !important;
    }

    /* Submenu icon */
    .sidebar .nav-treeview .nav-icon {
      color: #ffffff !important;
    }

    /* Saat sidebar collapse, sembunyikan teks */
    body.sidebar-collapse .sidebar .sidebar-text {
      display: none;
    }

    /* Tetap tampilkan ikon */
    body.sidebar-collapse .sidebar .sidebar-header i {
      margin-right: 0; /* agar icon centering lebih rapi */
    }

  </style>

  <!-- Sidebar -->
  <div class="sidebar d-flex flex-column">
    
    <!-- Header: Logo + Judul -->
    <div class="d-flex align-items-center sidebar-header pt-4 py-4 px-3">
      <i class="fas fa-file-alt fa-2x text-white mr-2"></i>
      <div class="sidebar-text">
        <h5 class="mb-0 text-white">Dashboard</h5>
        <h3 class="mb-0 text-white">
          <strong class="px-1">SiPANDAI</strong>
        </h3>
      </div>
    </div>

    <!-- Menu -->
    <nav class="mt-2 flex-grow-1">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">

        {{-- ================= MENU UTAMA ================= --}}
        <li class="nav-header text-white">MENU UTAMA</li>

        <li class="nav-item">
          <a href="{{ route('dashboard') }}" class="nav-link {{ Request::routeIs('dashboard') ? 'active' : '' }}">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>

        {{-- Dokumen --}}
        <li class="nav-item {{ Request::routeIs('documents.*') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ Request::routeIs('documents.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-folder"></i>
            <p>
              Dokumen
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('documents.index') }}"
                class="nav-link {{ Request::routeIs('documents.index') ? 'active' : '' }}">
                <i class="nav-icon fas fa-folder-open"></i>
                <p>Semua Dokumen</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="{{ route('documents.drafts') }}"
                class="nav-link {{ Request::routeIs('documents.drafts') ? 'active' : '' }}">
                <i class="nav-icon fas fa-pencil-alt"></i>
                <p>Draf</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="{{ route('documents.submitted') }}"
                class="nav-link {{ Request::routeIs('documents.submitted') ? 'active' : '' }}">
                <i class="nav-icon fas fa-paper-plane"></i>
                <p>Tersubmit</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="{{ route('documents.approved') }}"
                class="nav-link {{ Request::routeIs('documents.approved') ? 'active' : '' }}">
                <i class="nav-icon fas fa-check-circle"></i>
                <p>Disetujui</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="{{ route('documents.rejected') }}"
                class="nav-link {{ Request::routeIs('documents.rejected') ? 'active' : '' }}">
                <i class="nav-icon fas fa-times-circle"></i>
                <p>Ditolak</p>
              </a>
            </li>

          </ul>
        </li>

        {{-- ================= MASTER DATA ================= --}}
        @if(auth()->user()->role === 'administrator')
        <li class="nav-header text-white">MASTER DATA</li>

        <li class="nav-item">
          <a href="{{ route('bidang.index') }}" class="nav-link {{ Request::routeIs('bidang.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-th-large"></i>
            <p>Bidang</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ route('documents.categories.index') }}" class="nav-link {{ Request::routeIs('documents.categories.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-tags"></i>
            <p>Kategori Dokumen</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ route('documents.types.index') }}" class="nav-link {{ Request::routeIs('documents.types.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-file-signature"></i>
            <p>Tipe Dokumen</p>
          </a>
        </li>

        <li class="nav-item {{ Request::routeIs('users.*') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ Request::routeIs('users.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-users"></i>
            <p>
              Pengguna
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('users.index') }}" class="nav-link {{ Request::routeIs('users.index') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i> Semua Pengguna
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('users.create') }}" class="nav-link {{ Request::routeIs('users.create') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i> Tambah Pengguna
              </a>
            </li>
          </ul>
        </li>
        @endif

        {{-- ================= MONITORING ================= --}}
        @if(auth()->user()->role === 'administrator')
        <li class="nav-header text-white">MONITORING</li>

        <li class="nav-item">
          <a href="{{ route('users.access_logs.index') }}" class="nav-link {{ Request::routeIs('users.access_logs.index') ? 'active' : '' }}">
            <i class="nav-icon fas fa-chart-bar"></i>
            <p>Statistik Portal</p>
          </a>
        </li>
        @endif

      </ul>
    </nav>

    <!-- Logout di bawah -->
    <ul class="nav nav-pills nav-sidebar flex-column mt-auto" role="menu">
      <li class="nav-item">
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" 
              class="nav-link d-flex align-items-center" 
              style="background-color: #FEBC2F; border: none; width: 100%;">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p class="d-none d-sm-inline text-white">Logout</p>
          </button>
        </form>
      </li>
    </ul>

  </div>
  <!-- /.sidebar -->
</aside>