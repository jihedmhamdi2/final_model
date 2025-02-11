<?php
session_start();
include 'includes/db_connect.php';
include 'includes/header.php';

$model_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($model_id) {
    // Fetch model data for public viewing
    $stmt = $pdo->prepare("SELECT * FROM models WHERE id = ?");
    $stmt->execute([$model_id]);
    $model = $stmt->fetch();

    if (!$model) {
        echo "Model not found.";
        include 'includes/footer.php';
        exit();
    }

    // Fetch album images
    $stmt = $pdo->prepare("SELECT * FROM model_images WHERE model_id = ?");
    $stmt->execute([$model_id]);
    $album_images = $stmt->fetchAll();
} else {
    // For logged-in models to edit their profile
    if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'model') {
        header("Location: login.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare("SELECT * FROM models WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $model = $stmt->fetch();

    $success_message = '';
    $error_message = '';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $first_name = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
        $last_name = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
        $age = filter_input(INPUT_POST, 'age', FILTER_VALIDATE_INT);
        $height = filter_input(INPUT_POST, 'height', FILTER_VALIDATE_FLOAT);
        $bio = filter_input(INPUT_POST, 'bio', FILTER_SANITIZE_STRING);

        if ($first_name && $last_name && $age && $height) {
            if (!$model) {
                // Create new model profile
                $stmt = $pdo->prepare("INSERT INTO models (user_id, first_name, last_name, age, height, bio) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$user_id, $first_name, $last_name, $age, $height, $bio]);
                $model_id = $pdo->lastInsertId();
            } else {
                // Update existing model profile
                $model_id = $model['id'];
                $stmt = $pdo->prepare("UPDATE models SET first_name = ?, last_name = ?, age = ?, height = ?, bio = ? WHERE id = ?");
                $stmt->execute([$first_name, $last_name, $age, $height, $bio, $model_id]);
            }

            // Handle profile image upload
            if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                $filename = $_FILES['profile_image']['name'];
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                if (in_array($ext, $allowed)) {
                    $upload_dir = 'uploads/profile_images/';
                    $new_filename = uniqid() . '.' . $ext;
                    $upload_path = $upload_dir . $new_filename;

                    if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $upload_path)) {
                        $stmt = $pdo->prepare("UPDATE models SET profile_image_path = ? WHERE id = ?");
                        $stmt->execute([$upload_path, $model_id]);
                    } else {
                        $error_message = "Failed to upload profile image.";
                    }
                } else {
                    $error_message = "Invalid file type for profile image.";
                }
            }

            // Handle album images upload
            if (isset($_FILES['album_images'])) {
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                $upload_dir = 'uploads/album_images/';

                foreach ($_FILES['album_images']['tmp_name'] as $key => $tmp_name) {
                    if ($_FILES['album_images']['error'][$key] == 0) {
                        $filename = $_FILES['album_images']['name'][$key];
                        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                        if (in_array($ext, $allowed)) {
                            $new_filename = uniqid() . '.' . $ext;
                            $upload_path = $upload_dir . $new_filename;

                            if (move_uploaded_file($tmp_name, $upload_path)) {
                                $stmt = $pdo->prepare("INSERT INTO model_images (model_id, image_path) VALUES (?, ?)");
                                $stmt->execute([$model_id, $upload_path]);
                            } else {
                                $error_message .= "Failed to upload an album image. ";
                            }
                        } else {
                            $error_message .= "Invalid file type for an album image. ";
                        }
                    }
                }
            }

            $success_message = "Profile updated successfully!";
            
            // Refresh model data after update
            $stmt = $pdo->prepare("SELECT * FROM models WHERE id = ?");
            $stmt->execute([$model_id]);
            $model = $stmt->fetch();
        } else {
            $error_message = "Please fill in all required fields.";
        }
    }

    // Fetch album images
    if ($model) {
        $stmt = $pdo->prepare("SELECT * FROM model_images WHERE model_id = ?");
        $stmt->execute([$model['id']]);
        $album_images = $stmt->fetchAll();
    } else {
        $album_images = [];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Model Profile - P&I Modeling Agency</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        h1, h2 {
            color: #2c3e50;
        }
        .success-message {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 20px;
        }
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 20px;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background-color: #3498db;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #2980b9;
        }
        .profile-image {
            max-width: 200px;
            border-radius: 50%;
        }
        .album-images {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .album-image {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Model Profile</h1>
        <?php if (isset($success_message)): ?>
            <div class="success-message"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <?php if (isset($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'model' && (!$model || $_SESSION['user_id'] == $model['user_id'])): ?>
            <form action="model_profile.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="first_name">First Name:</label>
                    <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($model['first_name'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name:</label>
                    <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($model['last_name'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="age">Age:</label>
                    <input type="number" id="age" name="age" value="<?php echo htmlspecialchars($model['age'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="height">Height (cm):</label>
                    <input type="number" id="height" name="height" step="0.1" value="<?php echo htmlspecialchars($model['height'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="bio">Bio:</label>
                    <textarea id="bio" name="bio"><?php echo htmlspecialchars($model['bio'] ?? ''); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="profile_image">Profile Image:</label>
                    <input type="file" id="profile_image" name="profile_image">
                </div>
                <div class="form-group">
                    <label for="album_images">Album Images:</label>
                    <input type="file" id="album_images" name="album_images[]" multiple>
                </div>
                <button type="submit">Update Profile</button>
            </form>
        <?php else: ?>
            <div class="model-info">
                <h2><?php echo htmlspecialchars($model['first_name'] . ' ' . $model['last_name']); ?></h2>
                <p>Age: <?php echo htmlspecialchars($model['age']); ?></p>
                <p>Height: <?php echo htmlspecialchars($model['height']); ?> cm</p>
                <p>Bio: <?php echo nl2br(htmlspecialchars($model['bio'])); ?></p>
            </div>
        <?php endif; ?>

        <h2>Profile Image</h2>
        <?php if (isset($model['profile_image_path']) && $model['profile_image_path']): ?>
            <img src="<?php echo htmlspecialchars($model['profile_image_path']); ?>" alt="Profile Image" class="profile-image">
        <?php else: ?>
            <p>No profile image uploaded yet.</p>
        <?php endif; ?>

        <h2>Album Images</h2>
        <div class="album-images">
            <?php if (!empty($album_images)): ?>
                <?php foreach ($album_images as $image): ?>
                    <img src="<?php echo htmlspecialchars($image['image_path']); ?>" alt="Album Image" class="album-image">
                <?php endforeach; ?>
            <?php else: ?>
                <p>No album images uploaded yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>

