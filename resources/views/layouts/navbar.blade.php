<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button">
        <i class="fas fa-bars"></i>
      </a>
    </li>
  </ul>

  <!-- Right navbar (User Info) -->
  <ul class="navbar-nav ml-auto">
    @if(Auth::check())
    <li class="nav-item dropdown">
      <a class="nav-link d-flex align-items-center" data-toggle="dropdown" href="#">
        <!-- Avatar -->
        <img src="https://randomuser.me/api/portraits/men/32.jpg" 
             class="rounded-circle mr-2" 
             alt="User Avatar" 
             width="32" height="32">
        <!-- Info -->
        <div class="d-none d-md-block text-left" style="line-height: 1.2;">
          <span class="font-weight-bold">{{ Auth::user()->name }}</span><br>
          <small class="text-muted">Administrator</small>
        </div>
        <i class="fas fa-caret-down ml-2"></i>
      </a>

      <!-- Dropdown Profile Card -->
      <div class="dropdown-menu dropdown-menu-right p-3" style="min-width: 250px;">
        <div class="text-center">
          <img src="https://randomuser.me/api/portraits/men/32.jpg" 
               class="rounded-circle mb-2" 
               alt="User Avatar" 
               width="64" height="64">
          <h6 class="mb-0">{{ Auth::user()->name }}</h6>
          <small class="text-muted">Administrator</small>
        </div>
        <div class="dropdown-divider"></div>
        <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-sm btn-block">
          <i class="fas fa-user-cog mr-1"></i> Profile
        </a>
      </div>
    </li>
    @else
    <li class="nav-item">
      <a class="nav-link" href="{{ route('login') }}">Login</a>
    </li>
    @endif
  </ul>
</nav>
