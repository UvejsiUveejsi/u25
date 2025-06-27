<?php
require_once 'config.php';
if(!isset($_SESSION['user_id'])){
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$message = '';

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['avatar'])){
    $file = $_FILES['avatar'];
    if($file['error'] === UPLOAD_ERR_OK){
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if(in_array($ext, ['jpg','jpeg','png','gif','webp'])){
            
            $uploadDir = __DIR__ . '/uploads/';
            if(!is_dir($uploadDir)){
                mkdir($uploadDir, 0755, true);
            }
            $dest = $uploadDir . uniqid('avatar_') . '.' . $ext;
            $relativeDest = 'uploads/' . basename($dest);
        
            if(move_uploaded_file($file['tmp_name'], $dest)){
                // Save path in DB
                $stmt = $pdo->prepare('UPDATE users SET avatar = ? WHERE id = ?');
                $stmt->execute([$relativeDest, $user_id]);
                $_SESSION['avatar'] = $dest;
                $message = 'Avatar updated successfully!';
            } else {
                $message = 'Failed to move uploaded file.';
            }
        } else {
            $message = 'Invalid file type.';
        }
    } else {
        $message = 'File upload error.';
    }
}

// Fetch user avatar
$stmt = $pdo->prepare('SELECT avatar FROM users WHERE id = ?');
$stmt->execute([$user_id]);
$avatar = $stmt->fetchColumn();
?>
<?php include 'partials/header.php'; ?>
<section class="bg-cover bg-center bg-no-repeat py-12" style="background-image: url('assets/images/avatar_bg.jpg');">
    <div class="mx-auto max-w-6xl bg-white/80 dark:bg-black/70 backdrop-blur-lg rounded-xl shadow-xl p-8">
        <section class="container mx-auto p-8 max-w-md">
    <h2 class="text-2xl font-bold mb-6">Upload Avatar</h2>
    <?php if($message): ?>
        <p class="mb-4 <?php echo strpos($message, 'success') ? 'text-green-500' : 'text-red-500'; ?>"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <form action="avatar.php" method="POST" enctype="multipart/form-data" class="space-y-4">
        <input type="file" name="avatar" accept="image/*" required class="w-full">
        <button type="submit" class="px-4 py-2 accent-bg text-white rounded">Upload</button>
    </form>
    <?php if($avatar): ?>
        <img src="<?php echo htmlspecialchars($avatar); ?>" alt="Avatar" class="mt-6 w-32 h-32 rounded-full object-cover">
    <?php endif; ?>
</section>
        </section>
    </div>
</section>
<?php include 'partials/footer.php'; ?>