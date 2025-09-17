<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($pageTitle ?? 'Document'); ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" rel="stylesheet" />
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="/healthsync/assets/js/axios.min.js"></script>

  <?php
  if (!empty($scripts) && is_array($scripts)) {
    foreach ($scripts as $script) {
      echo '<script src="' . htmlspecialchars($script) . '"></script>' . PHP_EOL;
    }
  }
  if (!empty($deferScripts) && is_array($deferScripts)) {
    foreach ($deferScripts as $script) {
      echo '<script defer src="' . htmlspecialchars($script) . '"></script>' . PHP_EOL;
    }
  }
  ?>

  <link rel="stylesheet" href="/healthsync/assets/stylesheets/style.css">
</head>