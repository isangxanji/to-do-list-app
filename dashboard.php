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
        /* Body and container */
        body {
            background-color: #e0e0e0; /* light gray */
            font-family: "Poppins", sans-serif;
        }

        .container {
            margin-top: 3rem;
        }

        /* Cards */
        .card {
            border-radius: 1rem;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.12);
        }

        /* Gray variants for cards */
        .card.bg-warning {
            background-color: #b0b0b0; /* medium gray */
        }

        .card.bg-danger {
            background-color: #7a7a7a; /* darker gray */
        }

        .card.text-white h5,
        .card.text-white h2 {
            color: #ffffff;
        }

        /* Headings */
        h2 {
            color: #333333; /* dark gray */
        }

        /* Button */
        .btn-primary {
            background-color: #7a7a7a;
            border: none;
            transition: all 0.2s ease-in-out;
        }

        .btn-primary:hover {
            background-color: #5a5a5a;
        }

        /* Optional spacing for button container */
        .mt-4 {
            margin-top: 1.5rem !important;
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