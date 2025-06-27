<?php require_once 'config.php'; ?>
<?php include 'partials/header.php'; ?>
<section class="container mx-auto p-8 max-w-md">
    <h2 class="text-2xl font-bold mb-6">Login</h2>
    <form action="loginlogic.php" method="POST" class="space-y-4">
        <input type="text" name="username" placeholder="Username" required class="w-full p-2 border rounded">
        <input type="password" name="password" placeholder="Password" required class="w-full p-2 border rounded">
        <button type="submit" class="w-full px-4 py-2 bg-blue-600 dark:bg-red-600 text-white rounded">Login</button>
    </form>
    <?php if(isset($_GET['error'])): ?>
        <p class="text-red-500 mt-4"><?php echo htmlspecialchars($_GET['error']); ?></p>
    <?php endif; ?>
</section>
<?php include 'partials/footer.php'; ?>
