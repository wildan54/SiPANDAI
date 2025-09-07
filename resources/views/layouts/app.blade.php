<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>@yield('title', 'Dokumen App - AdminLTE 3')</title>
  
  <!-- Bootstrap 4 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <!-- AdminLTE -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">

  <!-- Laravel Vite (CSS & JS app) -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  @stack('styles')
</head>
<body class="hold-transition sidebar-mini">

<div class="wrapper">
  {{-- Navbar --}}
  @include('layouts.navbar')

  {{-- Sidebar --}}
  @include('layouts.sidebar')

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <section class="content">
      <div class="container-fluid py-3">
        @yield('content')
      </div>
    </section>
  </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>

<!-- Inisialisasi DataTables -->
<script>
  $(document).ready(function() {
    $('table.data-table').DataTable({
      responsive: true,
      paging: true,
      lengthChange: true,
      searching: true,
      ordering: true,
      info: true,
      autoWidth: false,
      language: {
        search: "Cari:",
        paginate: {
          first: "Pertama",
          last: "Terakhir",
          next: "Berikut",
          previous: "Sebelumnya"
        },
        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
        lengthMenu: "Tampilkan _MENU_ data"
      }
    });
  });
</script>

@stack('scripts')
</body>
</html>