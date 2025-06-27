<?php
require_once 'config.php';   // starts session + provides $pdo

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm']  ?? '';

    /* basic validation */
    if ($username === '' || $password === '') {
        $error = 'Username and password are required.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        /* check if username already exists */
        $check = $pdo->prepare('SELECT 1 FROM users WHERE username = ?');
        $check->execute([$username]);

        if ($check->fetchColumn()) {
            $error = 'Username already taken.';
        } else {
            /* secure hash */
            $password_hash = password_hash($password, PASSWORD_BCRYPT);

            /* insert user */
            $stmt = $pdo->prepare(
                'INSERT INTO users (username, password_hash) VALUES (?, ?)'
            );
            $stmt->execute([$username, $password_hash]);

            /* get new user id */
            $user_id = $pdo->lastInsertId();

            /* auto-login */
            $_SESSION['user_id']  = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['is_admin'] = false;

            header('Location: dashboard.php');
            exit;
        }
    }
}
?>

<?php include 'partials/header.php'; ?>
<section class="min-h-screen bg-cover bg-center flex items-center justify-center"
         style="background-image: url('assets/images/index_bg.jpg');">
  <div class="bg-black/70 p-8 rounded-xl text-white shadow-xl w-full max-w-md">
    <h2 class="text-2xl font-bold mb-6 text-center">Create Account</h2>

    <?php if (!empty($error)): ?>
      <p class="mb-4 text-red-400 text-center"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" class="space-y-6">
      <input type="text"     name="username" placeholder="Username" required
             class="w-full px-4 py-2 rounded text-black text-lg">
      <input type="password" name="password" placeholder="Password" required
             class="w-full px-4 py-2 rounded text-black text-lg">
      <input type="password" name="confirm"  placeholder="Confirm Password" required
             class="w-full px-4 py-2 rounded text-black text-lg">

      <button type="submit"
              class="w-full py-2 bg-green-600 hover:bg-green-700 rounded text-lg font-semibold shadow">
        Sign Up
      </button>
    </form>
  </div>
</section>
<?php include 'partials/footer.php'; ?>
