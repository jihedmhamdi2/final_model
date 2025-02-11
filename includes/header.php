<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>P&I Modeling Agency</title>
    <link rel="stylesheet" href="/assets/css/style.css">
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
    --nav-hover: #ff4d6d;
}

body {
    font-family: 'Poppins', sans-serif;
    background-color: var(--background-color);
    color: var(--text-color);
    margin: 0;
    padding: 0;
}

.featured-model h2 {
    text-align: center;
}

header {
    background-color: var(--background-color);
    padding: 1px 5px; /* Slightly increased padding */
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 1000;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    padding:0;
}

.logo img {
    max-width: 140px;
    margin-left: 30px;
}

.nav-links {
    display: flex;
    gap: 20px;
    list-style: none;
    margin: 0;
    padding: 0;
}

.nav-links a {
    color: var(--text-color);
    text-decoration: none;
    font-weight: 500;
    padding: 10px 15px;
    margin-right: 100px;
    border-radius: 5px;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.nav-links a:hover {
    background-color: var(--nav-hover);
    color: var(--text-color);
}

.auth-links {
    display: flex;
    gap: 15px;
}

.auth-btn {
    background-color: var(--primary-color);
    color: #fff;
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    display: inline-block;
    margin-right: 20px;
}

.auth-btn:hover {
    background-color: #d43f5e;
    transform: scale(1.05);
}

/* Hero Section */
.hero {
    margin-top: 120px; /* Increased margin-top for more space */
    display: flex;
    justify-content: center;
    align-items: center;
    height: 60vh;
    padding: 20px;
    margin-top: 120px
}

.hero-card {
    background-color: var(--card-background);
    padding: 40px;
    border-radius: 15px;
    text-align: center;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    max-width: 600px;
    width: 90%;
    animation: fadeIn 1s ease-in-out;
}

/* Animated Hero Text */
.hero-title {
    font-size: 2.5rem;
    font-weight: 700;
    background: linear-gradient(to right, var(--primary-color), var(--accent-color));
    -webkit-background-clip: text;
    color: transparent;
    opacity: 0;
    animation: slideUp 1s ease-out forwards;
}

.hero-subtitle {
    font-size: 1.2rem;
    color: var(--text-color);
    margin-bottom: 20px;
    opacity: 0;
    animation: fadeIn 1.5s ease-in forwards 0.5s;
}

        /* Animation */
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
        <div class="logo">
            <img src="/assets/images/logo.png" alt="P&I Modeling Agency Logo">
        </div>
        <ul class="nav-links">
            <li><a href="/">Home</a></li>
            <li><a href="/models.php">Models</a></li>
            <li><a href="/about.php">About</a></li>
            <li><a href="/contact.php">Contact</a></li>
        </ul>
        <div class="auth-links">
            <?php if (isset($_SESSION['user_id'])): ?>
                <?php if ($_SESSION['user_type'] == 'model'): ?>
                    <a href="/model_profile.php" class="auth-btn">Profile</a>
                <?php endif; ?>
                <a href="/logout.php" class="auth-btn">Logout</a>
            <?php else: ?>
                <a href="/login.php" class="auth-btn">Login</a>
                <a href="/register.php" class="auth-btn">Register</a>
            <?php endif; ?>
        </div>
    </header>

    <!-- Hero Section with Animated Card and Text -->
    <section class="hero">
        <div class="hero-card">
            <h1 class="hero-title">Welcome to P&I Modeling Agency</h1>
            <p class="hero-subtitle">Discover the next generation of modeling talent.</p>
            <a href="/models.php" class="auth-btn">Explore Models</a>
        </div>
    </section>
</body>
</html>
