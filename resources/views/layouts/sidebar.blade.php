<aside class="main-sidebar sidebar-dark-primary elevation-4">

  <!-- Sidebar -->
  <div class="sidebar d-flex flex-column">
    <!-- Sapaan -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="info">
        <span class="d-block text-white">
          Selamat datang di <br>
          <strong>Dashboard SiPANDAI</strong>
        </span>
      </div>
    </div>

    <!-- Menu -->
    <nav class="mt-2 flex-grow-1">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
        <li class="nav-item">
          <a href="{{ route('dashboard') }}" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <!-- Dokumen -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-folder"></i>
            <p>Dokumen <i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item"><a href="{{ route('documents.index') }}" class="nav-link"><i class="far fa-circle nav-icon"></i> Semua Dokumen</a></li>
            <li class="nav-item"><a href="{{ route('documents.create') }}" class="nav-link"><i class="far fa-circle nav-icon"></i> Tambah Dokumen</a></li>
            <li class="nav-item"><a href="{{ route('documents.category.categories') }}" class="nav-link"><i class="far fa-circle nav-icon"></i> Kategori Dokumen</a></li>
            <li class="nav-item"><a href="{{ route('documents.type.types') }}" class="nav-link"><i class="far fa-circle nav-icon"></i> Tipe Dokumen</a></li>
          </ul>
        </li>

        <!-- Bidang -->
        <li class="nav-item">
          <a href="{{ route('bidang.index') }}" class="nav-link">
            <i class="nav-icon fas fa-th-large"></i>
            <p>Bidang</p>
          </a>
        </li>

        <!-- Pengguna -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-users"></i>
            <p>Pengguna <i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item"><a href="{{ route('users.index') }}" class="nav-link"><i class="far fa-circle nav-icon"></i> Semua Pengguna</a></li>
            <li class="nav-item"><a href="{{ route('users.create') }}" class="nav-link"><i class="far fa-circle nav-icon"></i> Tambah Pengguna</a></li>
          </ul>
        </li>
      </ul>
    </nav>

    <!-- Tombol Logout (tetap di bawah) -->
    <ul class="nav nav-pills nav-sidebar flex-column mt-auto" role="menu">
      <li class="nav-item">
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" 
            class="nav-link d-flex align-items-center text-white" 
            style="background-color: #dc3545; border: none; width: 100%;">
            <i class="nav-icon fas fa-sign-out-alt text-white"></i>
            <p class="d-none d-sm-inline text-white">Logout</p>
          </button>
        </form>
      </li>
    </ul>
  </div>
  <!-- /.sidebar -->
</aside>