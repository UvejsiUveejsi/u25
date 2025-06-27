<?php
/* simple helper – prevents duplicates via INSERT IGNORE */
function unlock_achievement(PDO $pdo, int $userId, string $code): void
{
    $pdo->prepare(
        'INSERT IGNORE INTO achievements (user_id, code) VALUES (?, ?)'
    )->execute([$userId, $code]);
}
