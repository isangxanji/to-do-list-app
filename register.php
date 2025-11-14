<?php
include 'db.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];
    if ($password !== $confirm) {
        $message = '<div class="alert alert-danger">Passwords do not match!</div>';
    } elseif (!isset($_POST['terms'])) {
        $message = '<div class="alert alert-danger">You must agree to terms!</div>';
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        try {
            $stmt->execute([$username, $email, $hashed]);
            $message = '<div class="alert alert-success">Registered! <a href="index.php">Login now</a></div>';
        } catch (PDOException $e) {
            $message = '<div class="alert alert-danger">Username or email already taken!</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/CSS.css">
    <script src="assets/validation.js"></script>
    <style>
        body {
    background-color: #e0e0e0; /* light gray background */
    font-family: "Poppins", sans-serif;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    min-height: 100vh;
    padding-top: 3rem; /* spacing from top */
}

/* ===== Container ===== */
.container {
    width: 100%;
    max-width: 400px;
    padding-left: 15px;
    padding-right: 15px;
}

/* ===== Card ===== */
.card {
    background-color: #ffffff; /* white card for contrast */
    border-radius: 1rem;
    border: 1px solid #c0c0c0; /* subtle gray border */
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease-in-out;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
}

/* ===== Card Body ===== */
.card-body {
    padding: 2rem;
}

/* ===== Title ===== */
.card-body h2 {
    text-align: center;
    font-size: 1.8rem;
    margin-bottom: 1.5rem;
    color: #333333; /* dark gray */
}

/* ===== Form Groups ===== */
.mb-3 {
    margin-bottom: 1rem;
}

label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #555555; /* medium gray for labels */
}

/* ===== Inputs ===== */
input.form-control {
    width: 100%;
    padding: 0.55rem 0.75rem;
    border: 1px solid #b0b0b0;
    border-radius: 0.5rem;
    font-size: 1rem;
    background-color: #f5f5f5; /* light gray background for inputs */
    transition: all 0.2s ease-in-out;
}

input.form-control:focus {
    border-color: #7a7a7a; /* darker gray on focus */
    outline: none;
    background-color: #ffffff;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
}

/* ===== Checkbox ===== */
.form-check {
    display: flex;
    align-items: center;
}

.form-check-input {
    width: 18px;
    height: 18px;
    margin-right: 0.5rem;
    border: 2px solid #b0b0b0;
    border-radius: 0.25rem;
}

.form-check-input:checked {
    background-color: #7a7a7a; /* checked gray */
    border-color: #5a5a5a;
}

.form-check-label {
    color: #555555;
}

/* ===== Button ===== */
button.btn {
    width: 100%;
    padding: 0.6rem 0;
    background-color: #7a7a7a; /* medium gray button */
    border: none;
    border-radius: 0.5rem;
    color: #ffffff;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.2s ease-in-out;
}

button.btn:hover {
    background-color: #5a5a5a; /* darker gray on hover */
    transform: scale(1.02);
}

/* ===== Footer Link ===== */
p.text-center {
    margin-top: 1rem;
    text-align: center;
}

p.text-center a {
    color: #7a7a7a;
    text-decoration: none;
    font-weight: 500;
}

p.text-center a:hover {
    color: #5a5a5a;
    text-decoration: underline;
}


</style>
        
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-body">
            <h2 class="text-center">Register</h2>
            <?php echo $message; ?>
            <form method="POST">
                <div class="mb-3"><label>Username</label><input type="text" name="username"class="form-control" required></div>
                <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
                <div class="mb-3"><label>Password</label><input type="password" name="password" class="form-control" required minlength="6"></div> <!--minimum of password is 6-->
                <div class="mb-3"><label>Confirm</label><input type="password"name="confirm_password" class="form-control" required></div>
                <div class="form-check mb-3"><input type="checkbox" name="terms" class="form-check-input" required><label class="form-check-label">Agree to terms</label></div>
                <button type="submit" class="btn btn-primary w-100">Register</button>
                <p class="text-center mt-3"><a href="index.php">Already have account? Login</a></p>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
