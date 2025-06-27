<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    /* Hard-coded admin */
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['user_id']  = 0;
        $_SESSION['username'] = 'admin';
        $_SESSION['is_admin'] = true;
        header('Location: admin.php'); exit;
    }

    /* fetch user */
    $stmt = $pdo->prepare('SELECT * FROM users WHERE TRIM(username) = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    $ok = false;
    if ($user) {
        if (!empty($user['password_hash'])) {
            /* normal bcrypt */
            $ok = password_verify($password, $user['password_hash']);
        } else {
            /* fallback to clear-text (temporary) */
            $ok = ($password === $user['password']);
        }
    }

    if ($ok) {
        $_SESSION['user_id']  = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['is_admin'] = false;
        header('Location: dashboard.php'); exit;
    }

    $error = "Invalid username or password";
}
?>

<?php include 'partials/header.php'; ?>
<section class="min-h-screen bg-cover bg-center flex items-center justify-center"
         style="background-image: url('assets/images/index_bg.jpg');">
  <div class="bg-black/70 p-8 rounded-xl text-white shadow-xl w-full max-w-md">
    <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>

    <?php if (!empty($error)): ?>
      <p class="mb-4 text-red-400 text-center"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" class="space-y-6">
      <input type="text" name="username" placeholder="Username" required
             class="w-full px-4 py-2 rounded text-black text-lg">
      <input type="password" name="password" placeholder="Password" required
             class="w-full px-4 py-2 rounded text-black text-lg">
      <button type="submit"
              class="w-full py-2 bg-blue-600 hover:bg-blue-700 rounded text-lg font-semibold shadow">
        Login
      </button>
    </form>
  </div>
</section>
<?php include 'partials/footer.php'; ?>
