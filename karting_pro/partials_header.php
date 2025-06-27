<header class="bg-black/80 text-white shadow p-4 flex justify-between items-center">
  <h1 class="text-xl font-bold"><a href="index.php">🏎️ Karting Pro</a></h1>
  <nav class="space-x-4">
    <a href="dashboard.php" class="hover:underline">Dashboard</a>
    <a href="progress.php" class="hover:underline">Progress</a>
    <a href="leaderboard.php" class="hover:underline">Leaderboard</a>
    <a href="add_lap.php" class="hover:underline">Add Lap</a>
    <a href="admin.php" class="hover:underline">Admin</a>
    <?php if (isset($_SESSION['user_id'])): ?>
      <a href="logout.php" class="hover:underline">Logout</a>
    <?php else: ?>
      <a href="login.php" class="hover:underline">Login</a>
    <?php endif; ?>
  </nav>
</header>