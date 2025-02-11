<?php
session_start();
include 'includes/db_connect.php';
include 'includes/header.php';

// Initialize filter variables
$min_age = $_GET['min_age'] ?? '';
$max_age = $_GET['max_age'] ?? '';
$min_height = $_GET['min_height'] ?? '';
$max_height = $_GET['max_height'] ?? '';

// Build the SQL query based on filters
$sql = "SELECT id, first_name, last_name, age, height, profile_image_path FROM models WHERE 1=1";
$params = [];

if (!empty($min_age)) {
    $sql .= " AND age >= :min_age";
    $params[':min_age'] = $min_age;
}
if (!empty($max_age)) {
    $sql .= " AND age <= :max_age";
    $params[':max_age'] = $max_age;
}
if (!empty($min_height)) {
    $sql .= " AND height >= :min_height";
    $params[':min_height'] = $min_height;
}
if (!empty($max_height)) {
    $sql .= " AND height <= :max_height";
    $params[':max_height'] = $max_height;
}

$sql .= " ORDER BY id DESC";

// Prepare and execute the query
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$models = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Models</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        /* Filter Container Styles */
        .filter-container {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .filter-group {
            margin-bottom: 10px;
        }

        .filter-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        .filter-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        .filter-container .btn {
            margin-right: 10px;
            padding: 8px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .filter-container .btn:hover {
            background-color: #0056b3;
        }

        /* Model Grid Styles */
        .model-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .model-card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .model-card:hover {
            transform: translateY(-5px);
        }

        .model-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .model-info {
            padding: 15px;
            text-align: center;
        }

        .model-info h3 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }

        .model-info p {
            margin: 5px 0;
            font-size: 14px;
            color: #666;
        }

        .model-info .btn {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 15px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
        }

        .model-info .btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Our Models</h1>

        <!-- Filter Form -->
        <div class="filter-container">
            <form method="GET" action="">
                <div class="filter-group">
                    <label for="min_age">Min Age:</label>
                    <input type="number" id="min_age" name="min_age" value="<?php echo htmlspecialchars($min_age); ?>" min="0">
                </div>
                <div class="filter-group">
                    <label for="max_age">Max Age:</label>
                    <input type="number" id="max_age" name="max_age" value="<?php echo htmlspecialchars($max_age); ?>" min="0">
                </div>
                <div class="filter-group">
                    <label for="min_height">Min Height (cm):</label>
                    <input type="number" id="min_height" name="min_height" value="<?php echo htmlspecialchars($min_height); ?>" min="0">
                </div>
                <div class="filter-group">
                    <label for="max_height">Max Height (cm):</label>
                    <input type="number" id="max_height" name="max_height" value="<?php echo htmlspecialchars($max_height); ?>" min="0">
                </div>
                <button type="submit" class="btn">Apply Filters</button>
                <a href="models.php" class="btn">Reset Filters</a>
            </form>
        </div>

        <!-- Model Grid -->
        <div class="model-grid">
            <?php foreach ($models as $model): ?>
                <div class="model-card">
                    <img src="<?php echo htmlspecialchars($model['profile_image_path'] ?? 'assets/images/default-profile.jpg'); ?>" 
                         alt="<?php echo htmlspecialchars($model['first_name'] . ' ' . $model['last_name']); ?>" 
                         class="model-image">
                    <div class="model-info">
                        <h3><?php echo htmlspecialchars($model['first_name'] . ' ' . $model['last_name']); ?></h3>
                        <p>Age: <?php echo htmlspecialchars($model['age']); ?></p>
                        <p>Height: <?php echo htmlspecialchars($model['height']); ?> cm</p>
                        <a href="model_profile.php?id=<?php echo urlencode($model['id']); ?>" class="btn">View Profile</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>