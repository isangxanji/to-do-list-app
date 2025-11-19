<?php
session_start();
include 'db.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: dashboard.php");
        exit;
    } else {
        $message = '<div class="alert alert-danger">Invalid email or password!</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/CSS.css">
    <style>
        body {
            background: linear-gradient(135deg, #2E4A7F, #0A0A0A);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: "Poppins", sans-serif;
            margin: 0;
        
            
        }

        .login-card {
            width: 280px;
            padding: 35px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(12px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.4);
        }

        .login-card h2 {
            font-weight: 700;
            color: #ffffff;
            text-align: center;
            margin-bottom: 20px;
        }

        /*/.form-label {
            color: #d9e2ff;
        }

        .form-control {
            border-radius: 12px;
            background: rgba(255,255,255,0.10);
            border: 1px solid rgba(255,255,255,0.35);
            color: #fff;
        }

        .form-control::placeholder {
            color: #bbbbbb;
        }

        /*.form-control:focus {
            background: rgba(255,255,255,0.18);
            border-color: #4a6bb8;
            box-shadow: 0 0 6px #4a6bb8;
            color: #fff;
        }*/

        .btn-primary {
            background-color: #2E4A7F;
            border-radius: 12px;
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: #1f3359;
        }

        a {
            color: #9ab4ff;
            text-decoration: none;
            transition: 0.2s;
        }

        a:hover {
            color: #c9d6ff;
        }

        .links {
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-body">
            <h2 class="text-center">Login</h2>
            <?php echo $message; ?>
            <form method="POST">
                <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
                <div class="mb-3"><label>Password</label><input type="password" name="password" class="form-control" required></div>
                <button type="submit" class="btn btn-success w-100">Login</button>
                <p class="text-center mt-3">
                    <a href="register.php">Register</a> |
                    <a href="forgot_password.php">Forgot Password?</a>
                </p>
            </form>
        </div>
    </div>
</div>
</body>
</html>