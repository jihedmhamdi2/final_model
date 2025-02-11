<?php
session_start();
include 'includes/db_connect.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle delete action
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $stmt = $pdo->prepare("DELETE FROM models WHERE id = ?");
    $stmt->execute([$id]);
}

// Fetch all models
$stmt = $pdo->query("SELECT * FROM models");
$models = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Models</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex justify-between">
                    <div class="flex space-x-7">
                        <div>
                            <a href="admin_dashboard.php" class="flex items-center py-4 px-2">
                                <span class="font-semibold text-gray-500 text-lg">Admin Dashboard</span>
                            </a>
                        </div>
                    </div>
                    <div class="hidden md:flex items-center space-x-3">
                        <a href="admin_models.php" class="py-2 px-2 font-medium text-white bg-green-500 rounded transition duration-300">Models</a>
                        <a href="admin_users.php" class="py-2 px-2 font-medium text-gray-500 rounded hover:bg-green-500 hover:text-white transition duration-300">Users</a>
                        <a href="logout.php" class="py-2 px-2 font-medium text-white bg-red-500 rounded hover:bg-red-400 transition duration-300">Log Out</a>
                    </div>
                </div>
            </div>
        </nav>

        <div class="container mx-auto mt-8">
            <h1 class="text-3xl font-bold mb-6">Manage Models</h1>
            <div class="bg-white rounded-lg shadow-md overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Age</th>
                            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Height</th>
                            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($models as $model): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($model['first_name'] . ' ' . $model['last_name']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($model['age']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($model['height']); ?> cm</td>
                                <td classHere's the continuation of the text stream from the cut-off point:

model['height']); ?> cm</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="edit_model.php?id=<?php echo $model['id']; ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                    <form method="POST" class="inline">
                                        <input type="hidden" name="id" value="<?php echo $model['id']; ?>">
                                        <button type="submit" name="delete" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this model?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>

