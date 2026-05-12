<?php

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

if (session_status() === PHP_SESSION_NONE) session_start();

$adminEmail = 'admin@uob.edu.bh';
$error = '';

function safe_to(string $to, string $fallback = 'index.php'): string {
  $to = trim($to);
  if ($to === '') return $fallback;
  if (preg_match('~^[a-zA-Z][a-zA-Z0-9+.-]*://~', $to)) return $fallback;
  if (str_starts_with($to, '//')) return $fallback;
  if (preg_match('~^[a-zA-Z]:\\\\~', $to)) return $fallback;
  return ltrim($to);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = strtolower(trim($_POST['email'] ?? ''));

  if ($email === strtolower($adminEmail)) {
    $_SESSION['user_email'] = $email;
    $_SESSION['role'] = 'admin';
  } elseif (str_ends_with($email, '@stu.uob.edu.bh')) {
    $_SESSION['user_email'] = $email;
    $_SESSION['role'] = 'user';
  } else {
    $error = "غير مسموح. يجب استخدام بريد جامعي ينتهي بـ @stu.uob.edu.bh";
  }

  if (!$error) {
    $defaultTo = ($_SESSION['role'] === 'admin')
      ? 'admin/review-agreements.php'
      : 'index.php';

    $to = safe_to($_GET['to'] ?? '', $defaultTo);
    header("Location: " . $to);
    exit;
  }
}

$pageTitle = "تسجيل الدخول";
$pageSubtitle = "الدخول لطلاب الجامعة (@stu.uob.edu.bh) — والأدمن بريد محدد.";
$breadcrumb = [
  ['label' => 'تسجيل الدخول', 'href' => 'login.php', 'active' => true]
];

require_once __DIR__ . '/header.php';
?>

<div class="row justify-content-center">
  <div class="col-md-6 col-lg-5">

    <div class="uob-card">
      <div class="uob-card-body">

        <?php if ($error): ?>
          <div class="alert alert-danger"><?= h($error) ?></div>
        <?php endif; ?>

        <form method="post" autocomplete="off">

          <div class="mb-3">
            <label class="form-label">البريد الإلكتروني</label>
            <input
              class="form-control"
              name="email"
              type="email"
              placeholder="name@stu.uob.edu.bh"
              required
            >
          </div>

          <button class="btn btn-primary w-100" type="submit">
            دخول
          </button>

        </form>

        <div class="small text-muted mt-3">
          <div><strong>Admin:</strong> <?= h($adminEmail) ?></div>
          <div><strong>User:</strong> any<code>@stu.uob.edu.bh</code></div>
        </div>

      </div>
    </div>

  </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
```
