<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }
include 'db.php';
$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bio = trim($_POST['bio']);
    $profile_pic = $user['profile_pic'];
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        $file = $_FILES['profile_pic'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png']) && $file['size'] < 2000000) {
            $new_name = "uploads/" . time() . "_" . $file['name'];
            if (move_uploaded_file($file['tmp_name'], $new_name)) {
                $profile_pic = $new_name;
            }
        }
    }
    $update = $pdo->prepare("UPDATE users SET bio = ?, profile_pic = ? WHERE id = ?");
    $update->execute([$bio, $profile_pic, $user_id]);
    header("Location: profile.php");
    exit;
}
?>
<!DOCTYPE html>
<html><head><title>Profile</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Body */
        body {
            background-color: #e0e0e0; /* light gray */
            font-family: "Poppins", sans-serif;
        }

        .container {
            margin-top: 3rem;
        }

        /* Profile Image */
        .profile-img {
            width: 180px;
            height: 180px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #b0b0b0; /* gray border */
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        /* Username */
        .username {
            margin-top: 1rem;
            color: #333333;
            font-weight: 600;
        }

        /* Cards */
        .card {
            border-radius: 1rem;
            border: 1px solid #c0c0c0;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease-in-out;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.12);
        }

        .card-header {
            background-color: #d9d9d9; /* light gray header */
            font-weight: 600;
            color: #333333;
        }

        .card-body p {
            color: #555555;
        }

        /* Form Inputs */
        .form-control, textarea {
            border-radius: 0.5rem;
            border: 1px solid #b0b0b0;
            background-color: #f5f5f5;
            transition: all 0.2s ease-in-out;
        }

        .form-control:focus, textarea:focus {
            border-color: #7a7a7a;
            background-color: #ffffff;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
            outline: none;
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

        /* File input */
        input[type="file"] {
            border-radius: 0.5rem;
        }
    </style>
</head><body>
<?php include 'navbar.php'; ?>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-4 text-center">
            <img src="<?php echo $user['profile_pic']; ?>" class="img-fluid rounded-circle" width="180" alt="Profile">
            <h4 class="mt-3"><?php echo htmlspecialchars($user['username']); ?></h4>
        </div>
        <div class="col-md-8">
            <div class="card"><div class="card-header"><h5>About Me</h5></div>
            <div class="card-body"><p><?php echo nl2br(htmlspecialchars($user['bio'] ?: 'No bio.'));?></p></div></div>
            <div class="card mt-4"><div class="card-header"><h5>Update Profile</h5></div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3"><label>Bio</label><textarea name="bio" class="form-control" rows="4"><?php echo htmlspecialchars($user['bio']); ?></textarea></div>
                    <div class="mb-3"><label>Profile Picture (JPG/PNG, <2MB)</label><inputtype="file" name="profile_pic" class="form-control" accept="image/*"></div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div></div>
        </div>
    </div>
</div>
</body></html>