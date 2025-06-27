<?php
require_once 'config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $time = (float)$_POST['lap_time'];
    $note = trim($_POST['note'] ?? '');

    /* save lap */
    $stmt = $pdo->prepare(
        'INSERT INTO laps (user_id, lap_time_seconds, note, recorded_at)
         VALUES (?, ?, ?, NOW())'
    );
    $stmt->execute([$_SESSION['user_id'], $time, $note]);

    /* ---- dynamic achievements ---- */
    require_once 'lib/achievements.php';

    /* first-lap achievement */
    $total = $pdo->prepare('SELECT COUNT(*) FROM laps WHERE user_id = ?');
    $total->execute([$_SESSION['user_id']]);
    if ((int)$total->fetchColumn() === 1) {
        unlock_achievement($pdo, $_SESSION['user_id'], 'FIRST_LAP');
    }

    /* sub-40-seconds achievement */
    if ($time < 40.000) {
        unlock_achievement($pdo, $_SESSION['user_id'], 'SUB_40');
    }

    header('Location: progress.php');
    exit;
}
?>
<?php include 'partials/header.php'; ?>

<section class="min-h-screen bg-cover bg-center py-20"
         style="background-image:url('assets/images/progress_bg.jpg');">
  <div class="max-w-xl mx-auto bg-black/60 text-white rounded-xl shadow-2xl p-10">
    <h2 class="text-3xl font-bold mb-6 text-center">🏁 Add Lap Time</h2>

    <form action="add_lap.php" method="POST" class="space-y-6">
      <input  type="number" step="0.001" name="lap_time" required
              placeholder="Lap time (seconds)"
              class="w-full p-3 text-lg rounded text-black">

      <textarea name="note" rows="3"
                placeholder="Notes (weather, kart #, etc.)"
                class="w-full p-3 rounded text-black"></textarea>

      <button type="submit"
              class="w-full py-2 bg-green-600 hover:bg-green-700 rounded text-lg font-semibold shadow">
        Submit Lap
      </button>
    </form>
  </div>
</section>

<?php include 'partials/footer.php'; ?>
