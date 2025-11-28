<?php
require_once __DIR__ . '/../config/pdo.php';

class User {

    /* ============================
       USER AUTH / PROFILE
    ============================ */

    public function emailExists($email) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT 1 FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        return $stmt->fetchColumn();
    }

    public function createUser($data) {
        global $pdo;

        $stmt = $pdo->prepare("
            INSERT INTO users (first_name, last_name, email, password_hash, phone_no, address)
            VALUES (:f, :l, :e, :h, :p, :a)
        ");

        $stmt->execute([
            ':f' => $data['first_name'],
            ':l' => $data['last_name'],
            ':e' => $data['email'],
            ':h' => $data['password_hash'],
            ':p' => $data['phone_no'],
            ':a' => $data['address']
        ]);

        return $pdo->lastInsertId();
    }
    public function authenticate($email, $password) {
    $user = $this->getUserByEmail($email);

    if ($user && password_verify($password, $user['password_hash'])) {
        return $user;   // successful login
    }

    return false;        // invalid credentials
   }

    public function getUserByEmail($email) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findById($user_id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :id");
        $stmt->execute([':id' => $user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateProfile($data) {
        global $pdo;

        $stmt = $pdo->prepare("
            UPDATE users 
            SET first_name = :f, last_name = :l, phone_no = :p, address = :a
            WHERE user_id = :id
        ");

        $stmt->execute([
            ':f' => $data['first_name'],
            ':l' => $data['last_name'],
            ':p' => $data['phone_no'],
            ':a' => $data['address'],
            ':id' => $data['user_id']
        ]);

        return true;
    }


    /* ============================
       SERVICE CENTERS
    ============================ */

    public function getServiceCenters($search = "") {
        global $pdo;

        if ($search) {
            $stmt = $pdo->prepare("
                SELECT * FROM service_centers 
                WHERE name LIKE :search 
                ORDER BY name ASC
            ");
            $stmt->execute([':search' => "%$search%"]);
        } else {
            $stmt = $pdo->query("SELECT * FROM service_centers ORDER BY name ASC");
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getServiceCenterById($center_id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM service_centers WHERE center_id = :cid");
        $stmt->execute([':cid' => $center_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    /* ============================
       BOOKINGS
    ============================ */

    public function getBookingsByUser($user_id) {
        global $pdo;

        $stmt = $pdo->prepare("
            SELECT 
                b.*, 
                s.name AS center_name,
                e.name AS employee_name
            FROM bookings b
            JOIN service_centers s ON b.center_id = s.center_id
            LEFT JOIN employees e ON b.employee_id = e.employee_id
            WHERE b.user_id = :uid
            ORDER BY b.booking_date DESC, b.booking_time DESC
        ");

        $stmt->execute([':uid' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBookingById($booking_id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM bookings WHERE booking_id = :id");
        $stmt->execute([':id' => $booking_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createBooking(array $data) {
        global $pdo;

        $stmt = $pdo->prepare("
            INSERT INTO bookings
              (user_id, center_id, service_name, price, booking_date, booking_time, status)
            VALUES
              (:uid, :cid, :sname, :price, :bdate, :btime, 'Requested')
        ");

        $stmt->execute([
            ':uid'   => $data['user_id'],
            ':cid'   => $data['center_id'],
            ':sname' => $data['service_name'],
            ':price' => $data['price'],
            ':bdate' => $data['booking_date'],
            ':btime' => $data['booking_time'],
        ]);

        return $pdo->lastInsertId();
    }

    public function updateBookingDateTime($booking_id, $new_date, $new_time) {
        global $pdo;

        $stmt = $pdo->prepare("
            UPDATE bookings 
            SET booking_date = :bdate, booking_time = :btime 
            WHERE booking_id = :bid
        ");

        return $stmt->execute([
            ':bdate' => $new_date,
            ':btime' => $new_time,
            ':bid'   => $booking_id
        ]);
    }


    /* ============================
       CANCEL BOOKING
    ============================ */

public function cancelBooking($booking_id) {
    global $pdo;

    // Fetch booking
    $stmt = $pdo->prepare("SELECT booking_date, booking_time, status 
                           FROM bookings WHERE booking_id = :id");
    $stmt->execute([':id' => $booking_id]);
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$booking) return "not_found";
    if ($booking['status'] !== "Requested") return "not_allowed";

    // Compare DATE only (not time)
    $bookingDateOnly = strtotime($booking['booking_date']);
    $todayDateOnly   = strtotime(date("Y-m-d"));

    if ($bookingDateOnly < $todayDateOnly) {
        return "past";  // Cannot cancel past dates
    }

    // Allow cancellation
    $stmt = $pdo->prepare("UPDATE bookings SET status = 'Cancelled' WHERE booking_id = :id");
    $stmt->execute([':id' => $booking_id]);

    return "success";
}


    /* ============================
       SLOT CHECK
    ============================ */

    public function isSlotTaken($center_id, $date, $time, $exclude_id = null) {
        global $pdo;

        $query = "
            SELECT COUNT(*) FROM bookings 
            WHERE center_id = :cid 
              AND booking_date = :bdate
              AND booking_time = :btime
        ";

        if ($exclude_id) {
            $query .= " AND booking_id != :bid";
        }

        $stmt = $pdo->prepare($query);

        $params = [
            ':cid'  => $center_id,
            ':bdate'=> $date,
            ':btime'=> $time
        ];

        if ($exclude_id) {
            $params[':bid'] = $exclude_id;
        }

        $stmt->execute($params);

        // Allow max 2 bookings per slot
        return $stmt->fetchColumn() >= 2;
    }


    /* ============================
       PAYMENT
    ============================ */

    public function createPayment($data) {
        global $pdo;

        $stmt = $pdo->prepare("
            INSERT INTO payments 
                (booking_id, amount, card_holder, card_number, card_type, cvv, expiry_date)
            VALUES 
                (:bid, :amt, :holder, :cnum, :ctype, :cvv, :exp)
        ");

        return $stmt->execute([
            ':bid'    => $data['booking_id'],
            ':amt'    => $data['amount'],
            ':holder' => $data['card_holder'],
            ':cnum'   => $data['card_number'],
            ':ctype'  => $data['card_type'],
            ':cvv'    => $data['cvv'],
            ':exp'    => $data['expiry_date']
        ]);
    }

}
