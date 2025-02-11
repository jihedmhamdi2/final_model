<?php
session_start();
include 'includes/db_connect.php';
include 'includes/header.php';

// Fetch featured models
$stmt = $pdo->query("SELECT * FROM models ORDER BY RAND() LIMIT 4");
$featured_models = $stmt->fetchAll();

// Slider images
$slider_images = [
    'assets/images/slider1.jpg',
    'assets/images/slider2.jpg',
    'assets/images/slider3.jpg',
    'assets/images/slider4.jpg',
    'assets/images/slider5.jpg',
    'assets/images/slider6.jpg'
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>P&I Modeling Agency</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <script defer src="/assets/js/script.js"></script>
    <style>
        :root {
            --primary-color: #ff4d6d;
            --secondary-color: #1a1a2e;
            --accent-color: #ffd166;
            --text-color: #333333;
            --background-color: #f8f9fa;
            --card-background: #ffffff;
            --footer-background: #2c3e50;
            --footer-text: #ecf0f1;
        }

        /* Featured Models Section */
        .featured-models {
            margin: 80px auto;
            max-width: 1100px;
            padding: 40px;
            background: var(--card-background);
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
            animation: fadeIn 1s ease-in-out;
        }

        /* Animated H2 Title */
        .featured-models h2 {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(to right, var(--primary-color), var(--accent-color));
            -webkit-background-clip: text;
            color: transparent;
            opacity: 0;
            animation: slideUp 1s ease-out forwards;
        }

        /* Grid of Models */
        .model-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        /* Individual Model Card */
        .model-card {
            background: var(--background-color);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            animation: fadeIn 1s ease-in-out;
        }

        .model-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .model-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .model-info h3 {
            font-size: 1.4rem;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 5px;
        }

        .model-info p {
            color: var(--text-color);
            margin-bottom: 5px;
        }

        .btn {
            display: inline-block;
            background: var(--primary-color);
            color: #fff;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.3s ease, transform 0.3s ease;
        }

        .btn:hover {
            background: #d43f5e;
            transform: scale(1.05);
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <header>
        <!-- Header content here -->
    </header>

    <div class="container">
        <section class="slider">
            <div class="slider-wrapper">
                <?php foreach ($slider_images as $index => $image): ?>
                    <div class="slide">
                        <?php
                        $img_src = file_exists($image) ? $image : "https://via.placeholder.com/1200x600.png?text=Slide+{$index}";
                        ?>
                        <img src="<?php echo htmlspecialchars($img_src); ?>" alt="Slider Image <?php echo $index + 1; ?>">
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="featured-models">
            <h2>Featured Models</h2>
            <div class="model-grid">
                <?php foreach ($featured_models as $model): ?>
                    <div class="model-card">
                        <img src="<?php echo htmlspecialchars($model['profile_image_path'] ?? 'assets/images/default-profile.jpg'); ?>" alt="<?php echo htmlspecialchars($model['first_name'] . ' ' . $model['last_name']); ?>" class="model-image">
                        <div class="model-info">
                            <h3><?php echo htmlspecialchars($model['first_name'] . ' ' . $model['last_name']); ?></h3>
                            <p>Age: <?php echo htmlspecialchars($model['age']); ?></p>
                            <p>Height: <?php echo htmlspecialchars($model['height']); ?> cm</p>
                            <a href="model_profile.php?id=<?php echo $model['id']; ?>" class="btn">View Profile</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </div>

    <footer>
        <p>&copy; 2025 P&I Modeling Agency. All Rights Reserved.</p>
        <p><a href="/privacy.php">Privacy Policy</a> | <a href="/terms.php">Terms of Service</a></p>
    </footer>
</body>

</html>
