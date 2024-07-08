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
    <title>Home</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
        .mainContent {
            margin-top: 140px;
            position: relative;
            overflow: hidden;
        }
        .main-content-text {
            width: 55%;
            padding: 20px;
            background-color:whitesmoke;
            border-radius: 10px;
            animation: fadeInLeft 2s forwards;
            color: black;
            text-align: center;
            font-family: 'Arial', sans-serif;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6);
            position: relative;
            z-index: 2;
            margin: auto;
        }
        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-100%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        .main-content-bg {
            position: absolute;
            top: 0;
            right: 0;
            width: 45%;
            height: 100%;
            background-image: url('Pics/background.jpeg');
            background-size: cover;
            background-position: center;
            animation: fadeInRight 2s forwards;
            z-index: 1;
        }
        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(100%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        h2 {
            font-size: 2em;
            margin-bottom: 10px;
        }
        p {
            font-size: 1em;
            margin: 10px 0;
        }
        ul {
            text-align: left;
            padding-left: 20px;
        }
        li {
            margin-bottom: 10px;
        }
        button {
            background-color: #3d4c74;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 1.2em;
            margin-top: 20px;
        }
        button:hover {
            background-color: #2c3a5a;
        }
        .vehicle-filter {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }

        .vehicle-filter div {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .vehicle-filter input[type="radio"] {
            display: none;
        }

        .vehicle-filter label {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            border: 2px solid orangered;
            border-radius: 25px;
            transition: background-color 0.3s, color 0.3s;
        }

        .vehicle-filter label:hover {
            background-color: orangered;
            color: white;
        }

        .vehicle-filter input[type="radio"]:checked + label {
            background-color: orangered;
            color: white;
        }

        /* Style the span elements */
        .amount, .vehicles-available {
            padding: 2px;
            background-color: orangered;
            border-radius: 50%;
            color: white;
            margin-left: 10px;
        }

        /* Style the vehicles area */
        .vehicles-area {
            display: flex;
            flex-wrap: wrap;
        }

        .vehicles-area .vehicle {
            background-color: #f8f8f8;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            margin: 10px;
            text-align: center;
            transition: box-shadow 0.3s;
        }

        .vehicles-area .vehicle:hover {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .vehicles-area img {
            max-width: 100%;
            height: auto;
            margin-bottom: 15px;
        }

        .vehicles-area h3 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .vehicles-area p {
            margin: 5px 0;
        }

        .vehicles-area .btn {
            background-color: orangered;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .vehicles-area .btn:hover {
            background-color: darkred;
        }
       /* CSS styles for the curved shape */
       .custom-shape-divider-top-1719483927 {
            position: relative;
            top: 150px;
            overflow: hidden;
            line-height: 0;
            transform: rotateY(180deg);
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
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

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

<div class="mainContent">
    <!-- Background image -->
    <div class="main-content-bg"></div>
    
    <!-- Main content text -->
    <div class="main-content-text">
        <h2>YOU ARE IN A RUSH?</h2>
        <h2>WANT TO GO TO HOME OR SCHOOL?</h2>
        <h2>THEN TRAVEL IN STYLE...</h2>
        <p>Welcome to Strathmore University's premier transport service, BEBABEBA. We offer reliable and comfortable transportation solutions tailored to meet the needs of our students and staff. Whether you're heading home after a long day of classes or rushing to an important lecture, our fleet of modern buses ensures you get to your destination safely and on time.</p>
        <p>Why choose BEBABEBA?</p>
        <ul>
            <li>Reliable and timely service</li>
            <li>Comfortable and safe travel</li>
            <li>Free Wi-Fi and air conditioning</li>
            <li>Multiple routes across Nairobi</li>
            <li>Environmentally friendly options</li>
        </ul>
        <p>Join us and experience hassle-free commuting to and from Strathmore University. Our dedicated team is here to ensure your travel experience is top-notch. Don't wait any longer, travel now in style with BEBABEBA!</p>
       <a href="#store"> <button>Travel Now</button> </a>
    </div>
</div>

<div class="custom-shape-divider-bottom-1719732739">
    <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
        <path d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z" class="shape-fill"></path>
    </svg>
</div>
  
<!-- The bus section goes here -->
<div class="container" id="store" style="margin-top: 250px;">
    <h2 class="text-center my-4">BOOK YOUR Vehicle  <i class="fa-solid fa-car"></i></h2>

    <div class="container text-center">
  <div class="row">
  <div class="col">
    <h3 id="date"></h3>

    <script>
        function updateDateTime() {
            let date = new Date();
            let hours = date.getHours();
            let minutes = date.getMinutes();
            let suffix = "AM";

            if (hours >= 12) {
                suffix = "PM";
                if (hours > 12) {
                    hours -= 12;
                }
            } else if (hours === 0) {
                hours = 12;
            }

            if (minutes < 10) {
                minutes = "0" + minutes;
            }

            let day = date.getDate();
            let month = date.toLocaleString('default', { month: 'long' });
            let year = date.getFullYear();

            let formattedDate = `${month} ${day}, ${year}`;
            let formattedTime = `${hours}:${minutes} ${suffix}`;

            document.getElementById("date").innerHTML = `${formattedDate} - ${formattedTime}`;
        }

        // Call the function to update the date and time immediately
        updateDateTime();

        // Set the interval to update the date and time every 60 seconds
        setInterval(updateDateTime, 60000);
    </script>
</div>
<div class="container" style="border:solid, black , 2px;">
    <div class="row" style="display: flex; align-items: center">
        <div class="col" style="display: flex; align-items: center">
            <div class="dropdown" id="from">
                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    From
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#" onclick="selectDropdownItem('from', 'Town')">Town</a></li>
                    <li><a class="dropdown-item" href="#" onclick="selectDropdownItem('from', 'Strathmore University')">Strathmore University</a></li>
                    <li><a class="dropdown-item" href="#" onclick="selectDropdownItem('from', 'Thika')">Thika</a></li>
                </ul>
            </div>
        </div>
        <div class="col" style="display: flex; align-items: center">
            <div class="dropdown" id="to">
                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    To
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#" onclick="selectDropdownItem('to', 'Town')">Town</a></li>
                    <li><a class="dropdown-item" href="#" onclick="selectDropdownItem('to', 'Strathmore University')">Strathmore University</a></li>
                    <li><a class="dropdown-item" href="#" onclick="selectDropdownItem('to', 'Thika')">Thika</a></li>
                </ul>
            </div>
        </div>
        <div class="col">
            <div class="dropdown" id="departure">
                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Departure
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#" onclick="selectDropdownItem('departure', 'Morning')">Morning</a></li>
                    <li><a class="dropdown-item" href="#" onclick="selectDropdownItem('departure', 'Afternoon')">Afternoon</a></li>
                    <li><a class="dropdown-item" href="#" onclick="selectDropdownItem('departure', 'Evening')">Evening</a></li>
                </ul>
                <i id="searchIcon" class="fa-solid fa-magnifying-glass fa-2x" style="margin-left: 50px; cursor: pointer;" onclick="handleFilter()"></i>
            </div>
        </div>
    </div>
</div>
<section id="vehicles" class="container vehicles-section" style="margin-top: 100px;">
    <div class="vehicle-filter" id="filter">
        <div>
            <input type="radio" checked id="all" name="vehicles" />
            <label for="all">
                All
                <span class="amount">12</span>
            </label>
        </div>
        <div>
            <input type="radio" id="Morning" name="vehicles" />
            <label for="Morning">
                Morning
                <span class="vehicles-available">2</span>
            </label>
        </div>
        <div>
            <input type="radio" id="Afternoon" name="vehicles" />
            <label for="Afternoon">
                Afternoon
                <span class="vehicles-available">4</span>
            </label>
        </div>
        <div>
            <input type="radio" id="Evening" name="vehicles" />
            <label for="Evening">
                Evening
                <span class="vehicles-available">4</span>
            </label>
        </div>
        <div>
            <input type="radio" id="Special" name="vehicles" />
            <label for="Special">
                Special
                <span class="vehicles-available">2</span>
            </label>
            
        </div>
    </div>
    <div class="vehicles-area row"></div>
</section>
<div id="notification" class="notification"></div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const allRadio = document.getElementById("all");
        const morningRadio = document.getElementById("Morning");
        const afternoonRadio = document.getElementById("Afternoon");
        const eveningRadio = document.getElementById("Evening");
        const specialRadio = document.getElementById("Special");

        const vehicles = [
            { numberPlate: "KAB 123A", category: "Morning", arrival_time: "6.30 A.m", departure_time: "7.00 A.m", from: "Thika", to: "Strathmore University", price: 200, image: "vehicles/Morning/1.jpeg" },
            { numberPlate: "KAD 789C", category: "Morning", arrival_time: "6.30 A.m", departure_time: "7.00 A.m", from: "Town", to: "Strathmore University", price: 200, image: "vehicles/Morning/2.jpeg" },
            { numberPlate: "KAG 849C", category: "Afternoon", arrival_time: "1.30 P.m", departure_time: "2.00 P.m", from: "Strathmore University", to: "Thika", price: 150, image: "vehicles/Afternoon/1.jpeg" },
            { numberPlate: "KAC 239B", category: "Afternoon", arrival_time: "1.30 P.m", departure_time: "2.00 P.m", from: "Strathmore University", to: "Town", price: 150, image: "vehicles/Afternoon/2.jpeg" },
            { numberPlate: "KAK 539S", category: "Afternoon", arrival_time: "1.30 P.m", departure_time: "2.00 P.m", from: "Town", to: "Strathmore University", price: 150, image: "vehicles/Afternoon/3.jpeg" },
            { numberPlate: "KAY 459J", category: "Afternoon", arrival_time: "1.30 P.m", departure_time: "2.00 P.m", from: "Thika", to: "Strathmore University", price: 150, image: "vehicles/Afternoon/4.jpeg" },
            { numberPlate: "KBK 649D", category: "Evening", arrival_time: "5.30 P.m", departure_time: "6.00 P.m", from: "Thika", to: "Strathmore University", price: 250, image: "vehicles/Evening/1.jpeg" },
            { numberPlate: "KJD 479B", category: "Evening", arrival_time: "5.30 P.m", departure_time: "6.00 P.m", from: "Town", to: "Strathmore University", price: 250, image: "vehicles/Evening/2.jpeg" },
            { numberPlate: "KBK 349A", category: "Evening", arrival_time: "5.30 P.m", departure_time: "6.00 P.m", from: "Strathmore University", to: "Thika", price: 250, image: "vehicles/Evening/3.jpeg" },
            { numberPlate: "KBK 879K", category: "Evening", arrival_time: "5.30 P.m", departure_time: "6.00 P.m", from: "Strathmore University", to: "Town", price: 250, image: "vehicles/Evening/4.jpeg" },
            { numberPlate: "KGH 789K", category: "Special", arrival_time: "Anytime", departure_time: "Anytime", from: "Strathmore University", to: "Town", price: 350, image: "vehicles/Special/special.jpeg" },
            { numberPlate: "KBK 599K", category: "Special", arrival_time: "Anytime", departure_time: "Anytime", from: "Strathmore University", to: "Thika", price: 350, image: "vehicles/Special/special1.jpeg" },
        ];

        function displayFilteredVehicles(filter) {
            const filteredVehicles = filter === "all" ? vehicles : vehicles.filter(vehicle => vehicle.category === filter);
            const vehiclesArea = document.querySelector(".vehicles-area");
            vehiclesArea.innerHTML = "";

            filteredVehicles.forEach(vehicle => {
                const vehicleElement = document.createElement("div");
                vehicleElement.classList.add("col-md-4");
                vehicleElement.innerHTML = `
                    <div class="vehicle">
                        <img src="${vehicle.image}" alt="${vehicle.numberPlate}" />
                        <h3>${vehicle.numberPlate}</h3>
                         <p>From: ${vehicle.from} 
                         <br>                        To: ${vehicle.to}</p>
                        <p>Arrival Time: ${vehicle.arrival_time}</p>
                        <p>Departure Time: ${vehicle.departure_time}</p>
                        <p>Price: ksh${vehicle.price}</p>
                        
                        <button class="btn btn-primary" onclick="addToCart('${vehicle.numberPlate}', '${vehicle.image}', '${vehicle.from}', '${vehicle.to}', '${vehicle.arrival_time}', '${vehicle.departure_time}', ${vehicle.price})">Book Now !</button>
                    </div>
                `;
                vehiclesArea.appendChild(vehicleElement);
            });
        }

        allRadio.addEventListener("change", () => displayFilteredVehicles("all"));
        morningRadio.addEventListener("change", () => displayFilteredVehicles("Morning"));
        afternoonRadio.addEventListener("change", () => displayFilteredVehicles("Afternoon"));
        eveningRadio.addEventListener("change", () => displayFilteredVehicles("Evening"));
        specialRadio.addEventListener("change", () => displayFilteredVehicles("Special"));

        displayFilteredVehicles("all"); // Initially display all vehicles

        window.addToCart = addToCart; // Expose addToCart function to global scope
    });

    function selectDropdownItem(dropdownId, item) {
        document.getElementById(dropdownId).querySelector('.btn').innerText = item;
    }

    function addToCart(numberPlate, image, from, to, arrival_time, departure_time, price) {
        const itemDetails = { numberPlate, image, from, to, arrival_time, departure_time, price, quantity: 1 };
        console.log('Adding to cart:', itemDetails); // Log data being sent

        fetch('book.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'add', item: itemDetails }),
        })
        .then(response => response.json())
        .then(data => {
            console.log('Response from book.php:', data); // Log response from book.php

            if (data.success) {
                alert('Vehicle added to cart');
                loadCart(); // Reload cart display without refreshing the page
            } else {
                alert('Failed to add vehicle to cart');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while adding the vehicle to the cart.');
        });
    }

    function loadCart() {
        fetch('book.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ action: 'load' }) })
        .then(response => response.json())
        .then(data => {
            if (data.cartItems) {
                displayCartItems(data.cartItems);
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function displayCartItems(cartItems) {
        const cartItemsContainer = document.getElementById('cart-items');
        cartItemsContainer.innerHTML = '';
        if (cartItems.length === 0) {
            cartItemsContainer.innerHTML = '<p>Your cart is empty.</p>';
        } else {
            cartItems.forEach((item, index) => {
                const cartItemHTML = `
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="row g-0">
                                <div class="col-md-4">
                                    <img src="${item.image}" alt="${item.numberPlate}" class="img-fluid">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <h5 class="card-title">${item.numberPlate}</h5>
                                        <p class="card-text">From: ${item.from} To: ${item.to}</p>
                                        <p class="card-text">Arrival Time: ${item.arrival_time}</p>
                                        <p class="card-text">Departure Time: ${item.departure_time}</p>
                                        <p class="card-text">Price: ksh${item.price.toFixed(2)}</p>
                                        <p class="card-text">Quantity: ${item.quantity}</p>
                                        <button class="btn btn-danger" onclick="removeFromCart(${index})">Remove</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                cartItemsContainer.insertAdjacentHTML('beforeend', cartItemHTML);
            });
        }
    }

    function removeFromCart(index) {
        fetch('book.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'remove', index: index }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayCartItems(data.cartItems); // Update cart display
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
                displayCartItems(data.cartItems); // Update cart display
            } else {
                alert('Failed to clear cart');
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function handleFilter() {
        const fromDropdown = document.getElementById("from");
        const toDropdown = document.getElementById("to");
        const departureDropdown = document.getElementById("departure");

        const from = fromDropdown.querySelector('.btn').innerText.trim();
        const to = toDropdown.querySelector('.btn').innerText.trim();
        const departure = departureDropdown.querySelector('.btn').innerText.trim();

        const filteredVehicles = vehicles.filter(vehicle => {
            const matchesFrom = from === "From" || vehicle.from === from;
            const matchesTo = to === "To" || vehicle.to === to;
            const matchesDeparture = departure === "Departure" || vehicle.category === departure;

            return matchesFrom && matchesTo && matchesDeparture;
        });

        displayFilteredVehicles(filteredVehicles);
    }
</script>
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
                    <div class="carousel-item active" data-interval="200">
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
                    <!-- Add more carousel-item divs as needed -->
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
