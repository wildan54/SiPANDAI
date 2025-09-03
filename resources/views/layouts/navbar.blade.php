<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button">
        <i class="fas fa-bars"></i>
      </a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="{{ url('/') }}" class="nav-link font-weight-bold">
        <i class="fas fa-folder-open mr-2"></i> Dokumen App
      </a>
    </li>
  </ul>

  <!-- Right navbar -->
  <ul class="navbar-nav ml-auto">
    <!-- User Dropdown -->
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="fas fa-user mr-1"></i>
        <span class="d-none d-sm-inline">{{ Auth::user()->name }}</span>
        <i class="fas fa-caret-down ml-1"></i>
      </a>
      <div class="dropdown-menu dropdown-menu-right">
        <a href="{{ route('profile.edit') }}" class="dropdown-item">
          <i class="fas fa-user-cog mr-2"></i> Profile
        </a>
        <div class="dropdown-divider"></div>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="dropdown-item">
            <i class="fas fa-sign-out-alt mr-2"></i> Log Out
          </button>
        </form>
      </div>
    </li>
  </ul>
</nav>
