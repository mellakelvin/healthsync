<?php
if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
  $basePath = dirname($_SERVER['SCRIPT_NAME']);
  $basePath = rtrim($basePath, '/');
  header('Location: ' . $basePath);
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<?php
$pageTitle = "Home";
include './head.php'; ?>

<body>
  <button class="bg-blue-500">Sign-out</button>
</body>

</html>