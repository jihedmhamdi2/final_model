<?php
session_start();
include 'includes/header.php'; // Ensure this path is correct
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About P&I Modeling Agency</title>
    <style>
        /* Add only custom styles specific to this page */
        .about-section {
            background: #fff;
            margin: 20px 0;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .about-section h2 {
            color: #e8491d;
        }
        .features {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        .feature-box {
            flex-basis: 30%;
            background: #f9f9f9;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }
        .stats {
            background: #35424a;
            color: #ffffff;
            padding: 30px;
            margin-top: 20px;
            text-align: center;
        }
        .stat-item {
            display: inline-block;
            margin: 0 30px;
        }
        .cta {
            background: #e8491d;
            color: #ffffff;
            padding: 30px;
            margin-top: 20px;
            text-align: center;
        }
        .cta h2 {
            margin-bottom: 15px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background: #35424a;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px;
        }
        .button:hover {
            background: #294047;
        }
    </style>
</head>
<body>
    <!-- Header is already included via header.php, so no need to duplicate it here -->

    <div class="container">
        <section class="about-section">
            <h2>Our Story</h2>
            <p>Founded in 2024, P&I Modeling Agency has quickly risen to become one of the most respected names in the fashion industry. Our journey began with a simple yet powerful vision: to discover and nurture the next generation of modeling talent while setting new standards for professionalism and integrity in the industry.</p>
            <p>Over the years, we've built a reputation for excellence, working with top brands, photographers, and fashion houses worldwide. Our success is rooted in our commitment to our models' growth, well-being, and long-term career development.</p>
        </section>

        <section class="about-section">
            <h2>What Sets Us Apart</h2>
            <div class="features">
                <div class="feature-box">
                    <h3>Diverse Talent Pool</h3>
                    <p>From fresh faces to established professionals, we represent a wide range of talent to meet any client's needs.</p>
                </div>
                <div class="feature-box">
                    <h3>Global Reach</h3>
                    <p>With partnerships across the globe, we offer our models international exposure and opportunities.</p>
                </div>
                <div class="feature-box">
                    <h3>Industry Recognition</h3>
                    <p>Our agency and models have received numerous awards and accolades for our contributions to the fashion world.</p>
                </div>
            </div>
        </section>

        <section class="about-section">
            <h2>Our Services</h2>
            <ul>
                <li>Model scouting and development</li>
                <li>Portfolio creation and management</li>
                <li>Booking and contract negotiation</li>
                <li>Career guidance and mentorship</li>
                <li>Fashion show casting and coordination</li>
                <li>Brand partnership facilitation</li>
            </ul>
        </section>

        <section class="about-section">
            <h2>Join P&I Modeling Agency</h2>
            <p>Whether you're an aspiring model looking to start your career or an established professional seeking new opportunities, P&I Modeling Agency is here to help you reach your full potential in the fashion industry.</p>
            <a href="contact.php" class="button">Contact Us</a>
        </section>

        <section class="stats">
            <h2>Our Impact in Numbers</h2>
            <div class="stat-item">
                <h3>500+</h3>
                <p>Models Represented</p>
            </div>
            <div class="stat-item">
                <h3>1000+</h3>
                <p>Successful Campaigns</p>
            </div>
            <div class="stat-item">
                <h3>50+</h3>
                <p>Fashion Week Appearances</p>
            </div>
            <div class="stat-item">
                <h3>20+</h3>
                <p>Industry Awards</p>
            </div>
        </section>

        <section class="cta">
            <h2>Ready to start your modeling career?</h2>
            <p>Join P&I Modeling Agency today.</p>
            <a href="register.php" class="button">Apply Now</a> <!-- Updated href to register.php -->
            <a href="contact.php" class="button">Contact Us</a>
        </section>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>