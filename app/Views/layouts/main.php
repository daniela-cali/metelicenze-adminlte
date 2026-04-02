<!DOCTYPE html>
<html lang="it">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
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

  <!-- 1. AdminLTE CSS — prima perché include Bootstrap 5 -->
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/css/adminlte.min.css') ?>">

  <!-- 2. OverlayScrollbars -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css" crossorigin="anonymous">

  <!-- 3. Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous">

  <!-- 4. DataTables 2.x + Bootstrap 5 -->
  <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.1.2/css/buttons.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/4.0.1/css/fixedHeader.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/select/2.0.4/css/select.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/searchpanes/2.3.5/css/searchPanes.bootstrap5.min.css">

  <!-- 5. CSS personalizzato app -->
  <link rel="stylesheet" href="<?= base_url('assets/css/custom.css') ?>">

  <!-- Favicon -->
  <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('assets/icons/favicon-32x32.png') ?>">
  <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('assets/icons/favicon-16x16.png') ?>">
  <link rel="shortcut icon" href="<?= base_url('assets/icons/favicon.ico') ?>">

  <?= $this->renderSection('styles') ?>
</head>

<!--
  layout-fixed        → sidebar fissa, contenuto scorrevole
  sidebar-expand-lg   → la sidebar si espande su schermi >= lg
  bg-body-tertiary    → sfondo grigio chiaro (Bootstrap 5)
-->
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">

  <!-- App Wrapper: contenitore principale AdminLTE -->
  <div class="app-wrapper">

    <!-- Barra superiore -->
    <?= $this->include('layouts/partials/navbar') ?>

    <!-- Sidebar sinistra con menu -->
    <?= $this->include('layouts/partials/sidebar') ?>

    <!-- Contenuto principale -->
    <main class="app-main">

      <!-- Intestazione pagina: titolo + breadcrumb -->
      <div class="app-content-header">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-6">
              <h3 class="mb-0"><?= esc($title ?? 'MeTe Licenze') ?></h3>
            </div>
            <div class="col-sm-6">
              <?= $this->renderSection('breadcrumb') ?>
            </div>
          </div>
        </div>
      </div>

      <!-- Corpo pagina -->
      <div class="app-content">
        <div class="container-fluid">

          <!-- Toast messages -->
          <?= $this->include('layouts/partials/toasts') ?>

          <!-- Contenuto specifico della view -->
          <?= $this->renderSection('content') ?>

        </div>
      </div>

    </main>

    <!-- Footer -->
    <?= $this->include('layouts/partials/footer') ?>

  </div>
  <!-- /App Wrapper -->

  <!-- OverlayScrollbars JS (richiesto da AdminLTE) -->
  <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>

  <!-- Bootstrap JS (incluso in adminlte.min.js, ma serve anche standalone per DataTables) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <!-- AdminLTE JS -->
  <script src="<?= base_url('assets/adminlte/js/adminlte.min.js') ?>"></script>

  <!-- jQuery (usato da DataTables) -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

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
    document.addEventListener('DOMContentLoaded', function() {
      document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function(el) {
        new bootstrap.Tooltip(el);
      });
    });
  </script>

  <!-- Defaults: bottoni con classi Bootstrap -->
  <script>
    DataTable.Buttons.defaults.dom.button.className = 'btn btn-sm btn-outline-primary';
  </script>

  <!-- Init globale -->
  <script src="<?= base_url('assets/js/datatable-init.js') ?>"></script>
  <script src="<?= base_url('assets/js/table-manager.js') ?>"></script>

  <!-- Script specifici pagina -->
  <?= $this->renderSection('scripts') ?>

</body>

</html>
