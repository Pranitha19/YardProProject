<?php
require_once __DIR__ . '/../config/pdo.php';

class VisitTracker {

    public function track() {
        global $pdo;

        // 1. Create or read visitor_id cookie
        if (!isset($_COOKIE['visitor_id'])) {
            $visitor_id = bin2hex(random_bytes(16));
            setcookie('visitor_id', $visitor_id, time() + (86400 * 30), "/"); 
        } else {
            $visitor_id = $_COOKIE['visitor_id'];
        }

        // 2. If user logged in, get user_id
        $user_id = $_SESSION['user_id'] ?? null;

        // 3. Check if visitor already exists
        $stmt = $pdo->prepare("SELECT visit_id FROM user_visits WHERE visitor_id = :v LIMIT 1");
        $stmt->execute([':v' => $visitor_id]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            // 4. Update visit count + last visit
            $update = $pdo->prepare("
                UPDATE user_visits
                SET visit_count = visit_count + 1,
                    last_visit_at = NOW(),
                    user_id = COALESCE(:uid, user_id)
                WHERE visitor_id = :v
            ");
            $update->execute([
                ':uid' => $user_id,
                ':v'   => $visitor_id
            ]);
        } else {
            // 5. Insert new visit record
            $insert = $pdo->prepare("
                INSERT INTO user_visits (visitor_id, user_id, visit_count, last_visit_at)
                VALUES (:v, :uid, 1, NOW())
            ");
            $insert->execute([
                ':v' => $visitor_id,
                ':uid' => $user_id
            ]);
        }
    }
}
?>
