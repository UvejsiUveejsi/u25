<?php
require_once 'config.php';
if (!($_SESSION['is_admin'] ?? false)) {
    header('Location: login.php');
    exit;
}

// delete single lap
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_lap'])) {
    $pdo->prepare('DELETE FROM laps WHERE id = ?')->execute([(int)$_POST['delete_lap']]);
}

// delete all laps of a user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_user_laps'])) {
    $pdo->prepare('DELETE FROM laps WHERE user_id = ?')->execute([(int)$_POST['remove_user_laps']]);
}

// restore user by inserting dummy lap
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['restore_user'])) {
    $pdo->prepare('INSERT INTO laps (user_id, lap_time_seconds, note, recorded_at) VALUES (?, ?, ?, NOW())')
        ->execute([(int)$_POST['restore_user'], 99.999, 'Restored lap']);
}

// update lap time
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_lap_id'], $_POST['new_time'])) {
    $pdo->prepare('UPDATE laps SET lap_time_seconds = ? WHERE id = ?')
        ->execute([(float)$_POST['new_time'], (int)$_POST['edit_lap_id']]);
}
?>

<?php include 'partials/header.php'; ?>
<section class="min-h-screen bg-cover bg-center" style="background-image: url('assets/images/dashboard_bg.jpg');">
  <div class="max-w-6xl mx-auto px-6 py-10">
    <div class="bg-black/60 text-white rounded-xl shadow-xl p-8">
      <h2 class="text-3xl font-bold mb-6 text-center">🛠️ Admin Panel</h2>

      <!-- All Laps -->
      <h3 class="text-xl font-semibold mt-6 mb-4">Recent Laps</h3>
      <div class="overflow-x-auto mb-8">
        <table class="min-w-full text-sm divide-y divide-zinc-600 text-white">
          <thead>
            <tr class="bg-black/40">
              <th class="px-3 py-2">Driver</th>
              <th class="px-3 py-2 text-center">Lap (s)</th>
              <th class="px-3 py-2">Note</th>
              <th class="px-3 py-2">Date</th>
              <th class="px-3 py-2 text-center">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-zinc-600">
            <?php
            $laps = $pdo->query("
              SELECT l.id, u.username, l.lap_time_seconds, l.note, l.recorded_at
              FROM laps l
              JOIN users u ON u.id = l.user_id
              ORDER BY l.recorded_at DESC
              LIMIT 100
            ")->fetchAll();

            foreach ($laps as $lap):
            ?>
            <tr>
              <td class="px-3 py-2"><?php echo htmlspecialchars($lap['username']); ?></td>
              <td class="px-3 py-2 text-center"><?php echo number_format($lap['lap_time_seconds'], 3); ?></td>
              <td class="px-3 py-2"><?php echo htmlspecialchars($lap['note']); ?></td>
              <td class="px-3 py-2"><?php echo $lap['recorded_at']; ?></td>
              <td class="px-3 py-2 space-y-1 text-center">
                <form method="POST" onsubmit="return confirm('Delete this lap?');" style="display:inline-block">
                  <input type="hidden" name="delete_lap" value="<?php echo $lap['id']; ?>">
                  <button class="px-2 py-1 bg-red-600 rounded text-white">Delete</button>
                </form>
                <form method="POST" style="display:inline-block">
                  <input type="hidden" name="edit_lap_id" value="<?php echo $lap['id']; ?>">
                  <input type="number" step="0.001" name="new_time" placeholder="New time" class="px-2 py-1 rounded text-black w-24">
                  <button class="px-2 py-1 bg-yellow-600 rounded text-white">Update</button>
                </form>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <!-- User Lap Removal -->
      <h3 class="text-xl font-semibold mt-10 mb-4">User Leaderboard Control</h3>
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm divide-y divide-zinc-600 text-white">
          <thead>
            <tr class="bg-black/40">
              <th class="px-4 py-2 text-left">Username</th>
              <th class="px-4 py-2 text-center">Total Laps</th>
              <th class="px-4 py-2 text-center">Action</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-zinc-600">
            <?php
            $users = $pdo->query("
              SELECT u.id, u.username, COUNT(l.id) AS lap_count
              FROM users u
              LEFT JOIN laps l ON l.user_id = u.id
              GROUP BY u.id
              ORDER BY lap_count DESC
            ")->fetchAll();

            foreach ($users as $u):
            ?>
            <tr>
              <td class="px-4 py-2"><?php echo htmlspecialchars($u['username']); ?></td>
              <td class="px-4 py-2 text-center"><?php echo $u['lap_count']; ?></td>
              <td class="px-4 py-2 text-center">
                <?php if ($u['lap_count'] > 0): ?>
                  <form method="POST" onsubmit="return confirm('Remove all laps for this user?');">
                    <input type="hidden" name="remove_user_laps" value="<?php echo $u['id']; ?>">
                    <button class="px-3 py-1 bg-red-600 hover:bg-red-700 rounded">Remove Laps</button>
                  </form>
                <?php else: ?>
                  <form method="POST">
                    <input type="hidden" name="restore_user" value="<?php echo $u['id']; ?>">
                    <button class="px-3 py-1 bg-green-600 hover:bg-green-700 rounded">Restore</button>
                  </form>
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>

<?php include 'partials/footer.php'; ?>
