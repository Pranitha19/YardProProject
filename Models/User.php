<?php
require_once __DIR__ . '/../config/pdo.php';

class User {
    public function emailExists($email) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT 1 FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        return $stmt->fetchColumn();
    }

    public function createUser($first, $last, $email, $hash, $phone, $addr) {
        global $pdo;
        $stmt = $pdo->prepare("
            INSERT INTO users (first_name, last_name, email, password_hash, phone_no, address)
            VALUES (:f, :l, :e, :h, :p, :a)
        ");
        $stmt->execute([
            ':f' => $first, ':l' => $last, ':e' => $email,
            ':h' => $hash, ':p' => $phone, ':a' => $addr
        ]);
    }

    public function getUserByEmail($email) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findById($id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $first, $last, $phone, $addr) {
        global $pdo;
        $stmt = $pdo->prepare("
            UPDATE users
            SET first_name = :f, last_name = :l, phone_no = :p, address = :a
            WHERE user_id = :id
        ");
        $stmt->execute([':f' => $first, ':l' => $last, ':p' => $phone, ':a' => $addr, ':id' => $id]);
    }

   
public function getBookingsByUser($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT 
            b.booking_id,b.center_id, s.name AS center_name,
            b.service_name,b.price,b.status,
            b.booking_date,b.booking_time,
            e.name AS employee_name
        FROM bookings b
        JOIN service_centers s ON b.center_id = s.center_id
        LEFT JOIN employees e ON b.employee_id = e.employee_id
        WHERE b.user_id = :uid
        ORDER BY b.booking_date DESC, b.booking_time DESC");
    $stmt->execute([':uid' => $user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


public function getBookingById($booking_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM bookings WHERE booking_id = :id");
    $stmt->execute([':id' => $booking_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

 public function updateBookingDateTime($booking_id, $date, $time) {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE bookings SET booking_date = :d, booking_time = :t 
            WHERE booking_id = :id
        ");
        $stmt->execute([ ':d' => $date,':t' => $time,':id' => $booking_id ]);
    }
public function isSlotTaken($center_id, $date, $time, $exclude_booking_id = null) {
    global $pdo;

    $query = "
        SELECT COUNT(*) FROM bookings
        WHERE center_id = :cid
          AND booking_date = :bdate
          AND booking_time = :btime
    ";

    // If editing an existing booking, exclude that same booking_id
    if ($exclude_booking_id) {
        $query .= " AND booking_id != :bid";
    }

    $stmt = $pdo->prepare($query);
    $params = [
        ':cid' => $center_id,
        ':bdate' => $date,
        ':btime' => $time
    ];

    if ($exclude_booking_id) {
        $params[':bid'] = $exclude_booking_id;
    }

    $stmt->execute($params);
    return $stmt->fetchColumn() > 0; // true if slot is taken
}


}
