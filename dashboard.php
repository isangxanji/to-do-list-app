<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }
include 'db.php';
$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

$pending = $pdo->prepare("SELECT COUNT(*) FROM todos WHERE user_id = ? AND status = 'pending'");
$pending->execute([$user_id]);
$pending_count = $pending->fetchColumn();

$overdue = $pdo->prepare("SELECT COUNT(*) FROM todos WHERE user_id = ? AND due_date < CURDATE() AND status != 'completed'");
$overdue->execute([$user_id]);
$overdue_count = $overdue->fetchColumn();
?>
<!DOCTYPE html>
<html><head><title>Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Body with gradient */
        body {
            background: #fff;
            min-height: 100vh;
            font-family: "Poppins", sans-serif;
            color: #ffffff;
        }

        /* Container */
        .container {
            margin-top: 3rem;
        }

        /* Cards (glassmorphic style) */
        .card {
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.5);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.7);
        }

        /* Card headings */
        .card h5 {
            color: #d9e2ff;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .card h2 {
            color: #ffffff;
            font-weight: 700;
        }

        /* Button */
        .btn-primary {
            background-color: #5f94f6ff;
            border: none;
            font-weight: 600;
            transition: all 0.2s ease-in-out;
        }

        .btn-primary:hover {
            background-color: #1f3359;
        }

        /* Dashboard heading */
        h2.dashboard-title {
            margin-bottom: 2rem;
            text-shadow: 0 0 8px rgba(46, 74, 127, 0.6);
        }
    </style>
</head><body>
<?php include 'navbar.php'; ?>
<div class="container mt-5">
    <h2>Welcome, <strong><?php echo htmlspecialchars($user['username']); ?></strong>!</h2>
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card text-white bg-warning"><div class="card-body"><h5>Pending Tasks</h5><h2><?php echo $pending_count; ?></h2></div></div>
        </div>
        <div class="col-md-6">
            <div class="card text-white bg-danger"><div class="card-body"><h5>Overdue</h5><h2><?php echo $overdue_count; ?></h2></div></div>
        </div>
    </div>
    <div class="mt-4"><a href="todos.php" class="btn btn-primary">Go to TODOs</a></div>
</div>
</body></html>