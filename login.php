<?php
session_start();
include 'includes/db_connect.php';
include 'includes/header.php';

$errors = [];
$success_message = "";

// Handle success message from registration
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = trim(htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8'));
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $errors[] = "Both username and password are required";
    } else {
        $stmt = $pdo->prepare("SELECT users.*, models.id AS model_id, models.profile_image_path 
                               FROM users 
                               LEFT JOIN models ON users.id = models.user_id 
                               WHERE users.username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_type'] = $user['user_type'];
            $_SESSION['model_id'] = $user['model_id'];
            $_SESSION['profile_image'] = $user['profile_image_path'];

            if ($user['user_type'] == 'model') {
                if (!$user['model_id']) {
                    $_SESSION['complete_profile'] = true;
                } else {
                    header("Location: model_profile.php?id=" . $user['model_id']);
                    exit();
                }
            } else {
                header("Location: index.php");
                exit();
            }
        } else {
            $errors[] = "Invalid username or password";
        }
    }
}

// Handle Profile Completion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_profile'])) {
    $user_id = $_SESSION['user_id'];
    $first_name = trim(htmlspecialchars($_POST['first_name'], ENT_QUOTES, 'UTF-8'));
    $last_name = trim(htmlspecialchars($_POST['last_name'], ENT_QUOTES, 'UTF-8'));
    $age = intval($_POST['age']);
    $height = intval($_POST['height']);
    $bio = trim(htmlspecialchars($_POST['bio'], ENT_QUOTES, 'UTF-8'));

    if (empty($first_name) || empty($last_name) || empty($bio) || empty($age) || empty($height)) {
        $errors[] = "All fields are required!";
    } else {
        if (!empty($_FILES['profile_image']['name'])) {
            $profilePicName = time() . "_" . basename($_FILES['profile_image']['name']);
            $profilePicPath = "uploads/profile_images/" . $profilePicName;
            move_uploaded_file($_FILES['profile_image']['tmp_name'], $profilePicPath);
        } else {
            $profilePicPath = "uploads/profile_images/default.png";
        }

        // Insert into models table
        $stmt = $pdo->prepare("INSERT INTO models (user_id, first_name, last_name, age, height, bio, profile_image_path) 
                               VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $first_name, $last_name, $age, $height, $bio, $profilePicPath]);
        $model_id = $pdo->lastInsertId();

        // Store profile image in session for immediate display
        $_SESSION['profile_image'] = $profilePicPath;
        $_SESSION['model_id'] = $model_id;

        // Handle Album Photos
        if (!empty($_FILES['album_images']['name'][0])) {
            foreach ($_FILES['album_images']['tmp_name'] as $key => $tmp_name) {
                $albumPhotoName = time() . "_" . basename($_FILES['album_images']['name'][$key]);
                $albumPhotoPath = "uploads/album_photos/" . $albumPhotoName;
                move_uploaded_file($tmp_name, $albumPhotoPath);

                $stmt = $pdo->prepare("INSERT INTO album_images (model_id, photo_path) VALUES (?, ?)");
                $stmt->execute([$model_id, $albumPhotoPath]);
            }
        }

        unset($_SESSION['complete_profile']);

        // Redirect to the model's profile page
        header("Location: model_profile.php?id=" . $model_id);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - P&I Modeling Agency</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        /* General Styles */
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #ff9a9e, #fad0c4);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }

        .container {
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            padding: 40px;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h1 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 20px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .success-message, .errors {
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 10px;
            font-size: 0.9rem;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .errors {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        input[type="text"], input[type="email"], input[type="password"], select {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 50px;
            font-size: 1rem;
            outline: none;
            transition: border-color 0.3s;
        }

        input:focus, select:focus {
            border-color: #ff6b6b;
        }

        button {
            background: linear-gradient(135deg, #ff6b6b, #ff9a9e);
            color: #ffffff;
            padding: 12px 25px;
            font-size: 1rem;
            font-weight: bold;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        button:hover {
            background: linear-gradient(135deg, #ff9a9e, #fad0c4);
            transform: translateY(-2px);
        }

        .container a {
            display: block;
            margin-top: 15px;
            text-decoration: none;
            color: #ff6b6b;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }

        .container a:hover {
            color: #d43f5e;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (!isset($_SESSION['complete_profile'])): ?>
            <h1>Login</h1>
        <?php else: ?>
            <h1>Complete Your Profile</h1>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
            <div class="success-message"><?= htmlspecialchars($success_message); ?></div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="errors">
                <?php foreach ($errors as $error): ?>
                    <p><?= htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="post" enctype="multipart/form-data">
            <?php if (!isset($_SESSION['complete_profile'])): ?>
                <div class="form-group">
                    <input type="text" name="username" id="username" placeholder="Enter your username" required>
                </div>

                <div class="form-group">
                    <input type="password" name="password" id="password" placeholder="Enter your password" required>
                </div>

                <button type="submit" name="login" class="btn">Login</button>

            <?php else: ?>
                <div class="form-group">
                    <input type="text" name="first_name" id="first_name" placeholder="Enter your first name" required>
                </div>

                 <div class="form-group">
                    <input type="text" name="last_name" id="last_name" placeholder="Enter your last name" required>
                </div>

                <div class="form-group">
                    <input type="number" name="age" id="age" placeholder="Enter your age" required>
                </div>

                <div class="form-group">
                    <input type="number" name="height" id="height" placeholder="Enter your height (cm)" required>
                </div>

                <div class="form-group">
                    <textarea name="bio" id="bio" placeholder="Write something about yourself..." required></textarea>
                </div>

                <div class="form-group">
                    <input type="file" name="profile_image" id="profile_image" accept="image/*" required>
                </div>

                <div class="form-group">
                    <input type="file" name="album_images[]" id="album_images" multiple accept="image/*">
                </div>

                <button type="submit" name="save_profile" class="btn">Save Profile</button>
            <?php endif; ?>
        </form>

        <?php if (!isset($_SESSION['complete_profile'])): ?>
            <a href="register.php">Don't have an account? Register</a>
        <?php endif; ?>
    </div>
</body>
</html>