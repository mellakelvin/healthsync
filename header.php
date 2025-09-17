<?php
require __DIR__ . '/utils/connection.php';
require __DIR__ . '/model/User.php';
require __DIR__ . '/model/Notification.php';

session_start();
$userId = $_SESSION['auth-id'] ?? null;
$user = (new User($mysqli))->findById($userId);

$firstName = htmlspecialchars($user['first_name'] ?? 'User');
$middleName = htmlspecialchars($user['middle_name'] ?? 'User');
$lastName = htmlspecialchars($user['last_name'] ?? 'User');
$role = ucfirst(htmlspecialchars($user['role_name']));
$notifications = $userId ? (new Notification($mysqli))->getNotifications($userId) : [];
?>

<header class="d-flex justify-content-between align-items-center mb-4">
  <div class="dropdown">
    <a href="#" class="d-flex align-items-center text-decoration-none text-dark dropdown-toggle" data-bs-toggle="dropdown">
      <img src="<?= $user['image_url'] ? htmlspecialchars($user['image_url']) : '/healthsync/assets/profile-picture.png' ?>" alt="" class="rounded-circle" style="width:48px; height:48px; object-fit:cover;">
      <div class="ms-3 pe-4">
        <h6 class="mb-0 fw-semibold"><?= $firstName ?></h6>
        <small class="text-muted"><?= $role ?></small>
      </div>
    </a>
    <ul class="dropdown-menu dropdown-menu-end mt-2 shadow" style="min-width: 200px;">
      <li><a class="dropdown-item" id="btn-sign-out">Sign-out</a></li>
      <li><a class="dropdown-item" href="/healthsync/users/profile.php">Profile</a></li>
    </ul>
  </div>
  <a href="#" class="text-dark position-relative" data-bs-toggle="offcanvas" data-bs-target="#notifPanel">
    <i class="bi bi-bell fs-4"></i>
    <?php
    $unread = count(array_filter($notifications, fn($n) => $n['is_read'] == 0));
    if ($unread != 0) {
      echo <<<HTML
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            $unread
          </span>
      HTML;
    }
    ?>
  </a>

</header>

<div class="offcanvas offcanvas-end shadow-sm" tabindex="-1" id="notifPanel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title fw-semibold">Notifications</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <?php if (empty($notifications)): ?>
      <p class="text-muted text-center mt-4">No notifications.</p>
    <?php else: ?>
      <div class="list-group">
        <?php foreach ($notifications as $n): ?>
          <div class="list-group-item list-group-item-action mb-2 rounded-3 shadow-sm"
            style="background-color: <?= !$n['is_read'] ? '#0d6efd1a' : '' ?>;">
            <div class="mb-1">
              <span class="badge bg-primary"><?= htmlspecialchars($n['type']) ?></span>
            </div>
            <div><?= $n['content'] ?></div>
            <?php if (!empty($n['url'])): ?>
              <a href="#"
                class="text-primary small d-block mt-1 notification-link"
                data-id="<?= htmlspecialchars($n['id']) ?>"
                data-target="<?= htmlspecialchars($n['url']) ?>">
                View →
              </a>

            <?php endif; ?>
            <small class="text-muted d-block mt-1"><?= date('M j, Y g:i A', strtotime($n['created_at'])) ?></small>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
  <div class="offcanvas-footer px-3 pb-3">
    <a href="/healthsync/users/notifications.php" class="btn btn-outline-primary w-100">View All Notifications</a>
  </div>
</div>

<script>
  $(document).ready(function() {
    $('#btn-sign-out').click(function() {
      axios.post('/healthsync/auth/sign-out.php').then(() => {
        location.href = '/healthsync/';
      });
    });
  });
  $('.notification-link').on('click', function(e) {
    e.preventDefault();

    const notifId = $(this).data('id');
    const target = $(this).data('target');

    const apiUrl = `/healthsync/api/notification/g7rx9.php?notificationId=${notifId}&target=${encodeURIComponent(target)}`;

    axios.get(apiUrl)
      .then(response => {
        const finalTarget = response.data.target;

        if (finalTarget.startsWith('./')) {
          $('.loading-content').addClass('loading-strip');
          $('#main-container').load(finalTarget, function(response, status, xhr) {
            if (status === 'error') {
              $('#main-container').html(
                `<div class="p-4 text-danger">Error loading content: ${xhr.status} ${xhr.statusText}</div>`
              );
            }
            $('.loading-content').removeClass('loading-strip');
            const offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById('notifPanel'));
            if (offcanvas) offcanvas.hide();
          });
        } else {
          window.location.href = `/healthsync/${finalTarget}`;
        }
      })
      .catch(err => {
        console.error('Notification redirect failed:', err);
        alert('An error occurred. Please try again.');
      });
  });
</script>