<?php
require_once 'config.php';
if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}
$user_id = $_SESSION['user_id'];
?>
<?php include 'partials/header.php'; ?>
<section class="bg-cover bg-center min-h-screen" style="background-image:url('assets/images/dashboard_bg.jpg');">
  <div class="max-w-6xl mx-auto p-10">
    <div class="bg-black/60 text-white rounded-xl shadow-xl p-10">
      <h2 class="text-3xl font-bold mb-8 text-center">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> 👋</h2>

      <!-- Achievements -->
      <h3 class="text-xl font-semibold mb-4">Achievements</h3>
      <div class="grid md:grid-cols-3 gap-6">
      <?php
      $defs = [
        'FIRST_LAP' => ['icon'=>'assets/icons/trophy.svg','label'=>'First Lap'],
        'SUB_40'    => ['icon'=>'assets/icons/flash.svg', 'label'=>'Sub-40 Lap'],
        'TOP_10'    => ['icon'=>'assets/icons/star.svg',  'label'=>'Top-10 Driver'],
      ];
      $unlocked = $pdo->prepare('SELECT code FROM achievements WHERE user_id=?');
      $unlocked->execute([$user_id]);
      $codes = array_column($unlocked->fetchAll(), 'code');
      foreach ($defs as $code=>$info):
        $got = in_array($code,$codes);
      ?>
        <div class="p-4 <?php echo $got?'bg-black/60':'bg-black/30 opacity-40'; ?> text-white rounded-xl shadow flex items-center space-x-3">
          <img src="<?php echo $info['icon']; ?>" class="w-10 h-10">
          <span class="text-lg"><?php echo $info['label']; ?></span>
        </div>
      <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>
<?php include 'partials/footer.php'; ?>