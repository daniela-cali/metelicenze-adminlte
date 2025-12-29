<!DOCTYPE html>
<html lang="it">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="MeTe Licenze - Gestione licenze software per la tua azienda">
  <meta name="author" content="MeTe Software">
  <meta name="keywords" content="MeTe, Licenze, Software, Gestione, Azienda">
  <!-- Open Graph / Facebook -->
  <meta name="og:title" content="MeTe Licenze">
  <meta name="og:description" content="Gestione licenze software per la tua azienda">
  <meta name="og:type" content="website">
  <meta name="og:url" content="<?= base_url() ?>">
  <meta name="og:image" content="<?= base_url('assets/images/og-image.png') ?>">
  <!-- Twitter -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="MeTe Licenze">
  <meta name="twitter:description" content="Gestione licenze software per la tua azienda">
  <meta name="twitter:image" content="<?= base_url('assets/images/twitter-card.png') ?>">


  <title><?= esc($title ?? 'MeTe Licenze') ?></title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <!-- DataTables 2.x + Bootstrap 5 -->
  <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.min.css">

  <!-- Estensioni DataTables 2.x -->
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.1.2/css/buttons.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/4.0.1/css/fixedHeader.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/select/2.0.4/css/select.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/searchpanes/2.3.5/css/searchPanes.bootstrap5.min.css">

  <!-- CSS personalizzato -->
   <?php use Config\SiteConfig; 
   $theme = config('SiteConfig')->siteTheme; ?>
  <link rel="stylesheet" href="<?= base_url('assets/css/themes/' . $theme . '-style.css') ?>">

  <!-- Favicon -->
  <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('assets/icons/favicon-32x32.png') ?>">
  <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('assets/icons/favicon-16x16.png') ?>">
  <link rel="shortcut icon" href="<?= base_url('assets/icons/favicon.ico') ?>">


  <?= $this->renderSection('styles') ?>
</head>

<body>

  <!-- Navbar -->
  <?= $this->include('partials/navbar') ?>

  <!-- Toast messages -->
  <?= $this->include('partials/toasts') ?>

  <!-- Contenuto principale -->
  <main class="container my-4">
    <?= $this->renderSection('content') ?>
  </main>

  <!-- Footer -->
  <?= $this->include('partials/footer') ?>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <!-- DataTables 2.x + Bootstrap 5 -->
  <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.min.js"></script>

  <!-- Estensioni DataTables -->
  <script src="https://cdn.datatables.net/buttons/3.1.2/js/dataTables.buttons.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.bootstrap5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.print.min.js"></script>

  <!-- Dipendenze export -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

  <!-- FixedHeader -->
  <script src="https://cdn.datatables.net/fixedheader/4.0.1/js/dataTables.fixedHeader.min.js"></script>

  <!-- Responsive -->
  <script src="https://cdn.datatables.net/responsive/3.0.3/js/dataTables.responsive.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/3.0.3/js/responsive.bootstrap5.min.js"></script>

  <!-- Select -->
  <script src="https://cdn.datatables.net/select/2.0.4/js/dataTables.select.min.js"></script>

  <!-- SearchPanes -->
  <script src="https://cdn.datatables.net/searchpanes/2.3.5/js/dataTables.searchPanes.min.js"></script>
  <script src="https://cdn.datatables.net/searchpanes/2.3.5/js/searchPanes.bootstrap5.min.js"></script>

  <!-- baseUrl per i file locali -->
  <script>
    const baseUrl = "<?= base_url() ?>";
  </script>
  
  <!-- Inizializzazione tooltip Bootstrap -->
  <script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    });
  </script>

  <!-- Defaults: bottoni con classi Bootstrap -->
  <script>
    DataTable.Buttons.defaults.dom.button.className = 'btn btn-sm btn-outline-primary';
  </script>

  <!-- Init globale -->
  <script src="<?= base_url('assets/js/datatable-init.js') ?>"></script>

  <!-- Script specifici pagina -->
  <?= $this->renderSection('scripts') ?>
</body>

</html>