<?php
require_once 'config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare(
    'SELECT lap_time_seconds, note, recorded_at
       FROM laps
      WHERE user_id = ?
   ORDER BY recorded_at ASC'   /* chronological for chart */
);
$stmt->execute([$user_id]);
$rows = $stmt->fetchAll();

/* quick stats */
$times   = array_column($rows, 'lap_time_seconds');
$total   = count($times);
$best    = $times ? min($times) : null;
$average = $times ? array_sum($times) / $total : null;
?>
<?php include 'partials/header.php'; ?>

<section class="bg-cover bg-center min-h-screen"
         style="background-image:url('assets/images/progress_bg.jpg');">
  <div class="max-w-6xl mx-auto p-8">
    <div class="bg-black/60 text-white rounded-xl shadow-2xl p-10">

      <h2 class="text-3xl font-bold text-center mb-8">📈 Your Lap Progress</h2>

      <!-- stats -->
      <div class="grid sm:grid-cols-3 gap-6 mb-10 text-center">
        <div class="p-4 bg-black/40 rounded-xl">
          <p class="text-sm opacity-80">Total Sessions</p>
          <p class="text-3xl font-extrabold"><?php echo $total; ?></p>
        </div>
        <div class="p-4 bg-black/40 rounded-xl">
          <p class="text-sm opacity-80">Best Lap&nbsp;(s)</p>
          <p class="text-3xl font-extrabold">
            <?php echo $best ? number_format($best,3) : '—'; ?>
          </p>
        </div>
        <div class="p-4 bg-black/40 rounded-xl">
          <p class="text-sm opacity-80">Average Lap&nbsp;(s)</p>
          <p class="text-3xl font-extrabold">
            <?php echo $average ? number_format($average,3) : '—'; ?>
          </p>
        </div>
      </div>

      <?php if ($rows): ?>
        <canvas id="lapChart" class="w-full max-w-4xl mx-auto mb-12"></canvas>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-zinc-600 text-sm">
            <thead>
              <tr class="bg-black/40">
                <th class="px-4 py-2 text-left">Date</th>
                <th class="px-4 py-2 text-center">Lap&nbsp;(s)</th>
                <th class="px-4 py-2 text-left">Note</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-zinc-600">
              <?php foreach ($rows as $r): ?>
              <tr>
                <td class="px-4 py-2">
                  <?php echo date('Y-m-d H:i', strtotime($r['recorded_at'])); ?>
                </td>
                <td class="px-4 py-2 text-center">
                  <?php echo number_format($r['lap_time_seconds'],3); ?>
                </td>
                <td class="px-4 py-2"><?php echo htmlspecialchars($r['note'] ?? ''); ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <p class="text-center text-lg">No laps recorded yet – hit the track!</p>
      <?php endif; ?>

    </div>
  </div>
</section>

<?php include 'partials/footer.php'; ?>

<?php if ($rows): ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', ()=> {
  const ctx    = document.getElementById('lapChart').getContext('2d');
  const labels = <?php echo json_encode(array_map(fn($r)=>date('m/d',strtotime($r['recorded_at'])),$rows)); ?>;
  const data   = <?php echo json_encode(array_map(fn($r)=>(float)$r['lap_time_seconds'],$rows)); ?>;
  new Chart(ctx,{
    type:'line',
    data:{labels,datasets:[{label:'Lap Time (s)',data,tension:.3,fill:false,borderWidth:2,pointRadius:4}]},
    options:{responsive:true,scales:{y:{beginAtZero:false}}}
  });
});
</script>
<?php endif; ?>
