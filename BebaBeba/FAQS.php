<?php
session_start();
include("db.php"); // Include your database connection logic

// Ensure that the user session data is properly set
if (isset($_SESSION['student'])) {
    // Fetch the user's profile picture and other details from the database
    $stmt = $con->prepare("SELECT Profile_Picture, Name, Email, Address FROM student_details WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['student']['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $_SESSION['student']['profilePic'] = $user['Profile_Picture'] ? base64_encode($user['Profile_Picture']) : '';
        $_SESSION['student']['name'] = $user['Name'];
        $_SESSION['student']['email'] = $user['Email'];
        $_SESSION['student']['address'] = $user['Address'];
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="shortcut icon" href="Pics/download.jpg" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <style>
        .banner {
            width: 100%;
            height: 50px;
            background-color: #3d4c74;
            border-radius: 10px;
            color: white;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }
        .banner p {
            margin: 0 10px;
        }
        .Nav {
            background-color: white;
            position: fixed;
            top: 50px;
            width: 100%;
            z-index: 999;
            margin-top: 0;
            padding: 10px 0;
        }
        .container-fluid {
            background-color: #f8f9fa;
        }
        .mainContent {
            margin-top: 140px;
        }
        .user-sidebar {
            position: fixed;
            color: white;
            top: 0;
            right: 0;
            width: 250px;
            height: 100%;
            background-color: black;
            padding: 20px;
            box-shadow: -2px 0 5px rgba(0, 0, 0, 0.5);
            display: none;
        }
        .profile-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 10px;
        }
       
        .custom-shape-divider-top-1719483927 svg {
            position: relative;
            display: block;
            width: calc(100% + 1.3px);
            height: 200px;
        }
        .custom-shape-divider-top-1719483927 .shape-fill {
            fill:  #3d4c74;
        }
        /* Additional styles for footer content */
        .footer {
            background-color:  #3d4c74;
            color: white;
            padding: 50px 0;
            text-align: center;
        }
        .footer ul {
            list-style: none;
            padding: 0;
        }
        .footer ul li {
            margin-bottom: 10px;
        }
        .footer ul li h5 {
            margin-bottom: 20px;
        }
        .footer ul li a {
            color: white;
            text-decoration: none;
        }
        .footer ul li a:hover {
            text-decoration: underline;
        }
        .partner-card img {
            max-width: 100%;
            height: 30%;
        }
    </style>
</head>
<body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<div class="Nav">
    <div class="banner">
        <i class="fa-solid fa-location-dot" style="margin-left: 10px;"></i>
        <p style="margin-left: 10px;">Nairobi GPO </p>
        <i style="margin-left: 10px;" class="fa-regular fa-envelope"></i>
        <p style="margin-left: 10px;">Strathmore.edu</p>
        <p style="margin-left: 400px;">Privacy/Terms&services/Sales&Refunds</p>
    </div>
    <div class="container-fluid text-center" style="line-height: 100px;">
        <div class="row">
            <div class="col-md-3 d-flex align-items-center">
                <img src="Pics/logo.png" alt="logo" style="height: 90px;">
                <h2 style="color: #3d4c74; margin-left: 10px;">BEBABEBA</h2>
            </div>
            <div class="col-md-6">
                <ul id="menu" class="d-flex align-items-center justify-content-around" style="list-style: none; padding: 0;">
                    <li><a href="home.php" style="text-decoration: none;">Home</a></li>
                    <li><a href="aboutUS.php" style="text-decoration: none;">About Us</a></li>
                    <li><a href="contactUs.php" style="text-decoration: none;">Contact Us</a></li>
                    <li><a href="FAQS.php" style="text-decoration: none;">FAQS</a></li>
                </ul>
            </div>
            <div class="col-md-2 d-flex justify-content-end align-items-center">
                <div style="margin-left: 0px;">
                <a href="cart.php"><i class="fa-sharp fa-solid fa-cart-shopping fa-2x" style="margin-right: 100px;"></i></a>
                <i class="fas fa-user fa-2x" style="color: #3d4c74;" onclick="openUserSidebar()"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="user-sidebar" id="userSidebar">
    <?php if (isset($_SESSION['student'])): ?>
        <?php if (!empty($_SESSION['student']['profilePic'])): ?>
            <img src="data:image/jpeg;base64,<?php echo $_SESSION['student']['profilePic']; ?>" alt="Profile Picture" class="profile-image">
        <?php else: ?>
            <img src="Pics/background.jpeg" alt="Default Profile Picture" class="profile-image">
        <?php endif; ?>
        <h2><?php echo $_SESSION['student']['name']; ?></h2>
        <p><?php echo $_SESSION['student']['email']; ?></p>
        <p><?php echo $_SESSION['student']['address']; ?></p>
    <?php else: ?>
        <p>No user logged in.</p>
    <?php endif; ?>
    <button style="background: none; border: none; margin-top: 20px;" onclick="closeUserSidebar()">
        <i class="fa-solid fa-x fa-2x" style="color: aliceblue;"></i>
    </button>
    <a href="index.php">
        <i class="fa-solid fa-right-from-bracket fa-2x" style="color: red; margin-left: 20px;"></i>
    </a>
</div>
 <!-- Curved shape divider -->
 <div class="custom-shape-divider-top-1719483927">
    <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
        <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill"></path>
    </svg>
</div>

<!-- Our Partners and Affiliates -->
<div style="margin-top: 50px;">
    <div class="container">
        <ul style="list-style: none; color: white;">
            <li><h4>Our Partners and Affiliates</h4></li>
            <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="row">
                            <div class="col-lg-2">
                                <div class="card partner-card">
                                    <img src="Pics/shll.png" class="card-img-top" alt="Partner 1">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="card partner-card">
                                    <img src="Pics/TOTAL.png" class="card-img-top" alt="Partner 2">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="card partner-card">
                                    <img src="Pics/NTSA.jpeg" class="card-img-top" alt="Partner 3">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="card partner-card">
                                    <img src="Pics/STRATH.png" class="card-img-top" alt="Partner 4">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="card partner-card">
                                    <img src="Pics/RSAK.jpeg" class="card-img-top" alt="Partner 5">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="card partner-card">
                                    <img src="Pics/shll.png" class="card-img-top" alt="Partner 6">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </ul>
    </div>
</div>

<!-- Footer -->
<div class="footer">
    <div class="container text-center">
        <div class="row">
            <div class="col">
                <ul style="list-style: none; color: white;">
                    <li><h5>The BebaBeba Transport System</h5></li>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">News</a></li>
                    <li><a href="#">Contact Us</a></li>
                    <li>FOLLOW US</li>
                    <li class="BTN fa-2x text-danger">
                        <a href="https://twitter.com/StrathU?ref_src=twsrc%5Egoogle%7Ctwcamp%5Eserp%7Ctwgr%5Eauthor"><i class="fa-brands fa-x-twitter"></i></a>
                        <a href="https://www.instagram.com/strathmore.university/"><i class="fa-brands fa-instagram"></i></a>
                        <a href="https://ke.linkedin.com/school/strathmore-university/"><i class="fa-brands fa-linkedin"></i></a>
                    </li>
                </ul>
            </div>
            <div class="col">
                <ul style="list-style: none; color: white;">
                    <li><h5>Products</h5></li>
                    <li><a href="#">Farm implements</a></li>
                    <li><a href="#">Fertilizers</a></li>
                    <li><a href="#">Mapping</a></li>
                    <li><a href="#">Delivery</a></li>
                </ul>
            </div>
            <div class="col">
                <ul style="list-style: none; color: white;">
                    <li><h5>Support</h5></li>
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Inquiries</a></li>
                    <li><a href="#">Policies</a></li>
                    <li><a href="#">Legal</a></li>
                    <li><a href="#">Security Response Center</a></li>
                </ul>
            </div>
            <div class="col">
                <ul style="list-style: none; color: white;">
                    <li><h5>Rules & Regulations</h5></li>
                    <li><a href="#">Seating Arrangements</a></li>
                    <li><a href="#">Refunds</a></li>
                    <li><a href="#">Road Safety</a></li>
                    <li><a href="#">Operating Hours</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script src="script.js"></script>

</body>
</html>
