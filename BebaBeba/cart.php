<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>







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
    <title>Cart </title>
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

    <div class="container mt-5" style="margin-top: 200px;">
        <h2>Your Cart Contents</h2>
        <div id="cart-items" class="row justify-content-center" style="margin-top: 60px; background-color:wheat ">
            <?php
            $cartItems = isset($_SESSION['cartItems']) ? $_SESSION['cartItems'] : [];

            if (empty($cartItems)) {
                echo '<p>Your cart is empty.</p>';
            } else {
                foreach ($cartItems as $index => $item) {
                    echo '<div class="col-md-6">';
                    echo '<div class="card mb-3">';
                    echo '<div class="row g-0">';
                    echo '<div class="col-md-4">';
                    echo '<img src="' . $item['image'] . '" alt="' . $item['numberPlate'] . '" class="img-fluid">';
                    echo '</div>';
                    echo '<div class="col-md-8">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . $item['numberPlate'] . '</h5>';
                    echo '<p class="card-text">From: ' . $item['from'] . ' To: ' . $item['to'] . '</p>';
                    echo '<p class="card-text">Arrival Time: ' . $item['arrival_time'] . '</p>';
                    echo '<p class="card-text">Departure Time: ' . $item['departure_time'] . '</p>';
                    echo '<p class="card-text">Price: ksh' . number_format($item['price'], 2) . '</p>';
                    echo '<p class="card-text">Quantity: ' . $item['quantity'] . '</p>';
                    echo '<button class="btn btn-danger" onclick="removeFromCart(' . $index . ')">Remove</button>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            }
            ?>
        </div>
        <div style="text-align: center;">
            <button onclick="clearCart()" class="btn btn-danger">Clear Cart</button>
            <button onclick="checkout()" class="btn btn-success">Checkout</button>
        </div>
    </div>
    <script>
        function removeFromCart(index) {
            fetch('book.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'remove', index: index }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload(); // Reload to update the cart display
                } else {
                    alert('Failed to remove item from cart');
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function clearCart() {
            fetch('book.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'clear' }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload(); // Reload to update the cart display
                } else {
                    alert('Failed to clear cart');
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function checkout() {
            // Add your checkout logic here
            alert('Proceeding to checkout...');
        }
    </script>
</body>
</html>
