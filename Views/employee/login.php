<?php
// we start a session first so we can remember the logged-in user.
session_start();

//require_once connects to our database from pdo.php using PDO. 
require_once __DIR__ . '/../../config/pdo.php';

// If an employee is already logged in, they shouldn’t see the login page again.
// So this redirects them straight to their dashboard (viewrequests.php).
if (isset($_SESSION['role']) && $_SESSION['role'] === 'employee') {
    header("Location: viewrequests.php");
    exit;
}

//A simple form with email and password fields. When “Login” is clicked, 
// it sends data to the same page (action="").
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Employee Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
</head>

<body class="container py-5">
    <h3>Employee Login</h3>
    <form method="post" action="" id="loginForm" class="w-50">
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" id="password" class="form-control" required minlength="6">
        </div>
        <button type="submit" name="login" class="btn btn-primary">Login</button>
    </form>

    <script>
    // Simple jQuery validation - Before submitting the form, this checks that email isn't
    // blank and password has at least 6 characters.It's just for user convenience (real validation happens in PHP below).
    $(function() {
        $('#loginForm').on('submit', function(e) {
            let email = $('#email').val().trim();
            let pw = $('#password').val();
            if (email === '' || pw.length < 6) {
                e.preventDefault();
                alert("Please enter a valid email and password (min 6 chars).");
            }
        });
    });
    </script>
</body>

</html>

<?php
//Checks if the form was submitted.Reads user input.Queries the employees table
//  for that email
if(isset($_POST['login'])){
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM employees WHERE email=? LIMIT 1");
    $stmt->execute([$email]);
    $emp = $stmt->fetch();

//password_verify() checks if the entered password matches the hashed one stored 
// in the database.If yes, we create a secure session and redirect to the dashboard.
    if($emp && password_verify($password, $emp['password_hash'])){
        session_regenerate_id(true);
        $_SESSION['user_id'] = $emp['employee_id'];
        $_SESSION['role'] = 'employee';
        $_SESSION['user_name'] = $emp['name'];
        header("Location: viewrequests.php");
        exit;
        // If not,will display an error message.
    } else {
        echo "<p class='text-danger mt-3'>Invalid email or password!</p>";
    }
}
?>