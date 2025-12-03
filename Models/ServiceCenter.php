<?php
require_once __DIR__ . '/../config/pdo.php';

class ServiceCenter
{
    public function addServiceCenter(array $data, array $timings): int
    {
        global $pdo;

        try {
            $pdo->beginTransaction();

            // Insert center
            $stmt = $pdo->prepare(
                "INSERT INTO service_centers
                    (name, email, phone_no, description, address, base_price, image_url)
                VALUES (?, ?, ?, ?, ?, ?, ?)"
            );

            $stmt->execute([
                $data['name'],
                $data['email']       ?? null,
                $data['phone_no']    ?? null,
                $data['description'] ?? null,
                $data['address'],
                $data['base_price']  ?? 0.00,
                $data['image_url']   ?? null,
            ]);

            $centerId = (int)$pdo->lastInsertId();

            // Save weekly timings
            $this->saveTimings($centerId, $timings);

            $pdo->commit();
            return $centerId;

        } catch (PDOException $e) {

            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            echo "<pre style='color:red;'>ADD CENTER ERROR:\n";
            echo $e->getMessage();
            echo "</pre>";
            exit;
        }
    }


    // Update center & timings
    public function updateServiceCenter(array $data, array $timings): bool
    {
        global $pdo;

        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare(
                "UPDATE service_centers
                SET name = ?, email = ?, phone_no = ?, description = ?, address = ?,
                base_price = ?, image_url = ?
                WHERE center_id = ?"
            );

            $ok = $stmt->execute([
                $data['name'],
                $data['email']       ?? null,
                $data['phone_no']    ?? null,
                $data['description'] ?? null,
                $data['address'],
                $data['base_price']  ?? 0.00,
                $data['image_url']   ?? null,
                $data['center_id'],
            ]);

            if ($ok) {
                $this->saveTimings((int)$data['center_id'], $timings);
            }

            $pdo->commit();
            return $ok;

        } catch (PDOException $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            echo "<pre style='color:red;'>UPDATE CENTER ERROR:\n";
            echo $e->getMessage();
            echo "</pre>";
            exit;
        }
    }
    public function getAllCenters(): array
    {
        global $pdo;
        $sql = "SELECT * FROM service_centers ORDER BY created_at DESC";
        return $pdo->query($sql)->fetchAll();
    }


    // Get timings
    public function getCenterTimings(int $centerId): array
    {
        global $pdo;

        $stmt = $pdo->prepare(
            "SELECT day_of_week, start_time, end_time
            FROM timings
            WHERE center_id = ?
            ORDER BY day_of_week"
        );
        $stmt->execute([$centerId]);

        $rows = $stmt->fetchAll();
        $result = [];

        foreach ($rows as $row) {
            $day = (int)$row['day_of_week'];
            $result[$day] = [
                'start' => $row['start_time'],
                'end'   => $row['end_time'],
            ];
        }

        return $result;
    }
    public function deleteServiceCenter(int $centerId): bool
    {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM service_centers WHERE center_id = ?");
        return $stmt->execute([$centerId]);
    }


    // Save weekly timings
    private function saveTimings(int $centerId, array $timings): void
    {
        global $pdo;

        $pdo->prepare("DELETE FROM timings WHERE center_id = ?")->execute([$centerId]);

        $ins = $pdo->prepare(
            "INSERT INTO timings (center_id, day_of_week, start_time, end_time)
            VALUES (?, ?, ?, ?)"
        );

        for ($day = 1; $day <= 7; $day++) {

            $dayData = $timings[$day] ?? [];

            $isOpen = !empty($dayData['open']);
            $start  = $dayData['start'] ?? null;
            $end    = $dayData['end']   ?? null;

            if (!$isOpen || !$start || !$end) {
                $startDb = null;
                $endDb   = null;
            } else {
                $startDb = $start;
                $endDb   = $end;
            }

            $ins->execute([$centerId, $day, $startDb, $endDb]);
        }
    }
}
