<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>KartingPro</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      darkMode: 'class'
    };
  </script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const toggle = document.getElementById('themeToggle');
      const root = document.documentElement;
      const theme = localStorage.getItem('theme');
      if (theme === 'dark') root.classList.add('dark');
      toggle?.addEventListener('click', () => {
        root.classList.toggle('dark');
        localStorage.setItem('theme', root.classList.contains('dark') ? 'dark' : 'light');
      });
    });
  </script>
</head>

<body class="bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 min-h-screen flex flex-col">
  <header class="container mx-auto px-6 py-4 flex justify-between items-center">
    <a href="index.php" class="text-3xl font-bold text-blue-600 dark:text-red-500">Karting<span
        class="text-zinc-800 dark:text-zinc-200">Pro</span></a>
    <nav class="space-x-4">
      <a href="index.php" class="hover:underline">Home</a>

      <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
        <span class="text-red-400 font-bold">(Admin)</span>
      <?php endif; ?>
      <?php if (isset($_SESSION['user_id'])): ?>
        <a href="dashboard.php" class="hover:underline">Dashboard</a>
        <a href="add_lap.php" class="hover:underline">Add Lap</a>
        <a href="progress.php" class="hover:underline">Progress</a>
        <a href="leaderboard.php" class="hover:underline">Leaderboard</a>
        <?php if ($_SESSION['role'] ?? '' === 'admin'): ?>
          <a href="admin.php" class="hover:underline">Admin</a>
        <?php endif; ?>
        <a href="logout.php" class="hover:underline">Logout</a>
      <?php else: ?>
        <a href="login.php" class="hover:underline">Login</a>
        <a href="signup.php" class="hover:underline">Sign Up</a>
      <?php endif; ?>
      <button id="themeToggle" class="px-3 py-1 border rounded">🌓</button>
    </nav>
  </header>
  <main class="flex-1">