<?php require_once 'config.php'; ?>
<?php include 'partials/header.php'; ?>

<!-- full-screen hero background -->
<section class="bg-cover bg-center bg-no-repeat h-[32rem] rounded-2xl m-4 shadow-2xl overflow-hidden"
         style="background-image:url('assets/images/index_bg.jpg');">
  <div class="flex items-center justify-center h-full bg-black/40">
    <h1 class="text-5xl md:text-6xl font-extrabold text-white drop-shadow-lg">
      Track · Improve · Win
    </h1>
  </div>
</section>

<!-- action cards -->
<section class="container mx-auto p-8 grid md:grid-cols-2 gap-8">

  <?php if (!isset($_SESSION['user_id'])): ?>
    <!-- NOT logged-in: show Login + Sign-Up -->
    <div class="p-8 bg-black/60 text-white rounded-xl shadow-lg text-center space-y-4">
      <h2 class="text-2xl font-semibold">Ready to race?</h2>
      <a href="login.php"
         class="inline-block px-6 py-3 bg-blue-600 hover:bg-blue-700 rounded text-lg font-medium shadow">
        Login
      </a>
    </div>

    <div class="p-8 bg-black/60 text-white rounded-xl shadow-lg text-center space-y-4">
      <h2 class="text-2xl font-semibold">New driver?</h2>
      <a href="signup.php"
         class="inline-block px-6 py-3 bg-blue-600 hover:bg-blue-700 rounded text-lg font-medium shadow">
        Create Account
      </a>
    </div>

  <?php else: ?>
    <!-- Logged-in: show Add Lap + View Progress -->
    <div class="p-8 bg-black/60 text-white rounded-xl shadow-lg text-center space-y-4">
      <h2 class="text-2xl font-semibold">Add a new lap</h2>
      <a href="add_lap.php"
         class="inline-block px-6 py-3 bg-green-600 hover:bg-green-700 rounded text-lg font-medium shadow">
        + Add Lap
      </a>
    </div>

    <div class="p-8 bg-black/60 text-white rounded-xl shadow-lg text-center space-y-4">
      <h2 class="text-2xl font-semibold">Review your progress</h2>
      <a href="progress.php"
         class="inline-block px-6 py-3 bg-purple-600 hover:bg-purple-700 rounded text-lg font-medium shadow">
        View Progress
      </a>
    </div>
  <?php endif; ?>

</section>

<?php include 'partials/footer.php'; ?>
