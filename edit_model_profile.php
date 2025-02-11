<?php
session_start();
include 'includes/db_connect.php';
include 'includes/header.php';

// Check if the user is logged in and is a model
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'model') {
    header("Location: login.php");
    exit();
}

// Fetch the model's current details
$stmt = $pdo->prepare("SELECT * FROM models WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$model = $stmt->fetch();

if (!$model) {
    echo "<div class='container'><p>Model profile not found.</p></div>";
    exit();
}

// Fetch current album images
$stmt = $pdo->prepare("SELECT * FROM model_images WHERE model_id = ?");
$stmt->execute([$model['id']]);
$album_images = $stmt->fetchAll();

$errors = [];
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate and sanitize input
    $first_name = trim(filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING));
    $last_name = trim(filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING));
    $age = filter_input(INPUT_POST, 'age', FILTER_VALIDATE_INT);
    $height = filter_input(INPUT_POST, 'height', FILTER_VALIDATE_FLOAT);
    $bio = trim(filter_input(INPUT_POST, 'bio', FILTER_SANITIZE_STRING));

    // Validation
    if (empty($first_name)) $errors[] = "First name is required.";
    if (empty($last_name)) $errors[] = "Last name is required.";
    if ($age === false || $age < 18) $errors[] = "Please enter a valid age (18+).";
    if ($height === false || $height < 100) $errors[] = "Please enter a valid height in cm.";

    // Handle profile picture upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['profile_picture']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            $errors[] = "Invalid file format for profile picture. Allowed formats: " . implode(', ', $allowed);
        } else {
            $upload_dir = 'uploads/profile_pictures/';
            $new_filename = uniqid() . '.' . $ext;
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $upload_dir . $new_filename)) {
                // Delete old profile picture if exists
                if (!empty($model['profile_image_path']) && file_exists($model['profile_image_path'])) {
                    unlink($model['profile_image_path']);
                }
                $model['profile_image_path'] = $upload_dir . $new_filename;
            } else {
                $errors[] = "Failed to upload profile picture.";
            }
        }
    }

    // Handle album photos upload
    if (isset($_FILES['album_photos'])) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $upload_dir = 'uploads/album_photos/';
        
        foreach ($_FILES['album_photos']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['album_photos']['error'][$key] == 0) {
                $filename = $_FILES['album_photos']['name'][$key];
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                if (in_array($ext, $allowed)) {
                    $new_filename = uniqid() . '.' . $ext;
                    if (move_uploaded_file($tmp_name, $upload_dir . $new_filename)) {
                        // Insert new album photo into database
                        $stmt = $pdo->prepare("INSERT INTO model_images (model_id, image_path) VALUES (?, ?)");
                        $stmt->execute([$model['id'], $upload_dir . $new_filename]);
                    } else {
                        $errors[] = "Failed to upload album photo: " . $filename;
                    }
                } else {
                    $errors[] = "Invalid file format for album photo: " . $filename . ". Allowed formats: " . implode(', ', $allowed);
                }
            }
        }
    }

    // If no errors, update the database
    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE models SET first_name = ?, last_name = ?, age = ?, height = ?, bio = ?, profile_image_path = ? WHERE user_id = ?");
        if ($stmt->execute([$first_name, $last_name, $age, $height, $bio, $model['profile_image_path'], $_SESSION['user_id']])) {
            $success_message = "Profile updated successfully!";
            // Refresh the model data
            $stmt = $pdo->prepare("SELECT * FROM models WHERE user_id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $model = $stmt->fetch();
            
            // Refresh album images
            $stmt = $pdo->prepare("SELECT * FROM model_images WHERE model_id = ?");
            $stmt->execute([$model['id']]);
            $album_images = $stmt->fetchAll();
        } else {
            $errors[] = "An error occurred while updating your profile.";
        }
    }
}

// Handle album photo deletion
if (isset($_POST['delete_photo']) && isset($_POST['photo_id'])) {
    $photo_id = filter_input(INPUT_POST, 'photo_id', FILTER_VALIDATE_INT);
    if ($photo_id) {
        $stmt = $pdo->prepare("SELECT image_path FROM model_images WHERE id = ? AND model_id = ?");
        $stmt->execute([$photo_id, $model['id']]);
        $photo = $stmt->fetch();
        if ($photo) {
            if (unlink($photo['image_path'])) {
                $stmt = $pdo->prepare("DELETE FROM model_images WHERE id = ?");
                if ($stmt->execute([$photo_id])) {
                    $success_message = "Photo deleted successfully.";
                    // Refresh album images
                    $stmt = $pdo->prepare("SELECT * FROM model_images WHERE model_id = ?");
                    $stmt->execute([$model['id']]);
                    $album_images = $stmt->fetchAll();
                } else {
                    $errors[] = "Failed to delete photo from database.";
                }
            } else {
                $errors[] = "Failed to delete photo file.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Model Profile - P&I Modeling Agency</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
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
        .btn {
            display: inline-block;
            background-color: #e8491d;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }
        .error-message {
            color: red;
            margin-bottom: 10px;
        }
        .success-message {
            color: green;
            margin-bottom: 10px;
        }
        .album-images {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 20px;
        }
        .album-image {
            position: relative;
            width: 150px;
        }
        .album-image img {
            width: 100%;
            height: auto;
        }
        .delete-photo {
            position: absolute;
            top: 5px;
            right: 5px;
            background-color: rgba(255, 0, 0, 0.7);
            color: white;
            border: none;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 12px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Model Profile</h1>
        
        <?php if (!empty($errors)): ?>
            <div class="error-message">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($success_message): ?>
            <div class="success-message">
                <p><?php echo htmlspecialchars($success_message); ?></p>
            </div>
        <?php endif; ?>

        <form action="edit_model_profile.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($model['first_name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($model['last_name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="age">Age:</label>
                <input type="number" id="age" name="age" value="<?php echo htmlspecialchars($model['age']); ?>" required min="18">
            </div>

            <div class="form-group">
                <label for="height">Height (cm):</label>
                <input type="number" id="height" name="height" value="<?php echo htmlspecialchars($model['height']); ?>" required step="0.1" min="100">
            </div>

            <div class="form-group">
                <label for="bio">Bio:</label>
                <textarea id="bio" name="bio" rows="4"><?php echo htmlspecialchars($model['bio']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="profile_picture">Profile Picture:</label>
                <input type="file" id="profile_picture" name="profile_picture" accept="image/*">
            </div>

            <div class="form-group">
                <label for="album_photos">Add Album Photos:</label>
                <input type="file" id="album_photos" name="album_photos[]" accept="image/*" multiple>
            </div>

            <button type="submit" class="btn">Update Profile</button>
        </form>

        <h2>Current Album Photos</h2>
        <div class="album-images">
            <?php foreach ($album_images as $image): ?>
                <div class="album-image">
                    <img src="<?php echo htmlspecialchars($image['image_path']); ?>" alt="Album Photo">
                    <form action="edit_model_profile.php" method="post" style="display: inline;">
                        <input type="hidden" name="photo_id" value="<?php echo $image['id']; ?>">
                        <button type="submit" name="delete_photo" class="delete-photo" onclick="return confirm('Are you sure you want to delete this photo?')">×</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>

        <p><a href="model_profile.php">Back to Profile</a></p>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>