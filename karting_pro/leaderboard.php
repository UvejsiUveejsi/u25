<?php
require_once 'config.php';
$stmt = $pdo->query('
  SELECT u.username, MIN(l.lap_time_seconds) AS best_lap
    FROM users u
    JOIN laps l ON u.id = l.user_id
GROUP BY u.id
ORDER BY best_lap ASC
LIMIT 50
');
$rows = $stmt->fetchAll();
?>
<?php include 'partials/header.php'; ?>
<section class="bg-cover bg-center min-h-screen" style="background-image:url('assets/images/leaderboard_bg.jpg');">
  <div class="max-w-4xl mx-auto px-6 py-12">
    <div class="bg-black/60 text-white rounded-xl shadow-xl p-10">
      <h2 class="text-3xl font-bold mb-6 text-center">🏁 Leaderboard</h2>
      <table class="min-w-full text-sm divide-y divide-zinc-600">
        <thead><tr class="bg-black/40">
          <th class="px-4 py-2 text-center">#</th>
          <th class="px-4 py-2 text-left">Driver</th>
          <th class="px-4 py-2 text-center">Best Lap (s)</th>
        </tr></thead>
        <tbody class="divide-y divide-zinc-600">
        <?php foreach ($rows as $index => $row): ?>
          <?php
          $classes = '';
          if     ($index === 0)                    $classes = 'bg-yellow-600/40';
          elseif ($index === 1)                    $classes = 'bg-slate-400/40';
          elseif ($index === 2)                    $classes = 'bg-amber-700/40';
          elseif (($_SESSION['username'] ?? '') === $row['username'])
                                                  $classes = 'bg-blue-600/30';
          ?>
          <tr class="<?php echo $classes; ?>">
            <td class="px-4 py-2 text-center font-bold"><?php echo $index + 1; ?><?php if ($index < 3): ?> 🏆<?php endif; ?></td>
            <td class="px-4 py-2"><?php echo htmlspecialchars($row['username']); ?></td>
            <td class="px-4 py-2 text-center"><?php echo number_format($row['best_lap'], 3); ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>
<?php include 'partials/footer.php'; ?>