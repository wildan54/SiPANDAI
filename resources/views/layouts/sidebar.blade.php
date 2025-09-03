<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <div class="sidebar">
    <nav class="mt-2">
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
            <li class="nav-item"><a href="{{ route('documents.kategori') }}" class="nav-link"><i class="far fa-circle nav-icon"></i> Kategori Dokumen</a></li>
            <li class="nav-item"><a href="{{ route('documents.tipe') }}" class="nav-link"><i class="far fa-circle nav-icon"></i> Tipe Dokumen</a></li>
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
  </div>
</aside>