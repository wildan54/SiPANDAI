<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>@yield('title', 'Portal SiPANDAI')</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      background-color: #f8f9fa;
    }
    .card-custom {
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .btn-download {
      background-color: #ffc107;
      color: #000;
    }
    .btn-view {
      background-color: #030F6B;
      color: #fff;
    }
    .nav-tag .btn {
      border-radius: 20px;
      margin: 0 5px 5px 0;
    }
    .search-box {
      max-width: 500px;
    }
  </style>
</head>
<body>

  @include('public.partials.header')

  <div class="container-fluid py-2 px-5">
    @yield('content')
  </div>

  @include('public.partials.footer')

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>