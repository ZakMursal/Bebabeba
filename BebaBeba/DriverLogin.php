<?php
session_start();
include("db.php"); // Include your database connection logic

// Handle Signup
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['signup'])) {
        $name = htmlspecialchars($_POST['driver_Name']);
        $licenseNo = htmlspecialchars($_POST['driver_LicenseNo']);
        $phone = htmlspecialchars($_POST['driver_phone_no']);
        $password = $_POST['driver_Password'];

        // Hash the password before storing in database
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // SQL query to insert data into driver_details table
        $stmt = $con->prepare("INSERT INTO driver_details (Name, LicenseNo, Phone_Number, Password) VALUES (?, ?, ?, ?)");

        if (!$stmt) {
            // Display any errors in the preparation of the statement
            echo "Error preparing statement: " . $con->error;
        } else {
            $stmt->bind_param("ssss", $name, $licenseNo, $phone, $hashed_password);

            if ($stmt->execute()) {
                // Registration successful, redirect to Driver.php
                $_SESSION['driver'] = [
                    'id' => $stmt->insert_id,
                    'name' => $name,
                    'licenseNo' => $licenseNo,
                    'phone_no' => $phone
                ];
                $stmt->close();
                $con->close();
                header("Location: Driver.php");
                exit();
            } else {
                // Registration failed, handle error (e.g., duplicate license number, database error)
                echo "Error executing statement: " . $stmt->error;
            }
        }
    }

    // Handle Login
    if (isset($_POST['login'])) {
        $licenseNo = htmlspecialchars($_POST['driver_LicenseNo']);
        $password = $_POST['driver_Password'];

        if (!empty($licenseNo) && !empty($password)) {
            $stmt = $con->prepare("SELECT * FROM driver_details WHERE LicenseNo = ?");

            if (!$stmt) {
                // Display any errors in the preparation of the statement
                echo "Error preparing statement: " . $con->error;
            } else {
                $stmt->bind_param("s", $licenseNo);

                if ($stmt->execute()) {
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $storedPassword = $row['Password'];

                        // Verify the password
                        if (password_verify($password, $storedPassword)) {
                            $_SESSION['driver'] = [
                                'id' => $row['id'],
                                'name' => $row['Name'],
                                'licenseNo' => $row['LicenseNo'],
                                'phone_no' => $row['Phone_Number']
                            ];
                            $stmt->close();
                            $con->close();
                            header("Location: Driver.php"); // Redirect to Driver.php after successful login
                            exit();
                        } else {
                            echo "<script>alert('Invalid password.');</script>"; // Password doesn't match
                        }
                    } else {
                        echo "<script>alert('Invalid license number.');</script>"; // License number not found
                    }
                } else {
                    echo "Error executing statement: " . $stmt->error;
                }
            }
        } else {
            echo "<script>alert('Please fill in both license number and password.');</script>"; // Fields not filled
        }
    }
}

$con->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Signup and Login Forms</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body{
             background: url(Pics/background.jpeg);
             
        }
        .form-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: darkcyan;
            transition: transform 0.6s ease-in-out;
            backface-visibility: hidden;
        }
        .form-container h2 {
            margin-bottom: 20px;
        }
        .rotate {
            transform: rotateY(180deg);
        }
        .dropdown {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <div class="container">
        <h2 style="margin-left: 100px;color:orangered">Driver Signup/Login</h2>
        <!-- Toggle Buttons -->
        <div class="text-center mb-4">
            <button class="btn btn-outline-primary" onclick="showSignup()" style="background-color: blue; color:burlywood">Driver Signup</button>
            <button class="btn btn-outline-secondary" onclick="showLogin()" style="background-color: blue;color:burlywood"> Driver Login</button>
        </div>

        <!-- Driver Signup Form -->
        <div id="signup" class="form-container">
            <h2>Driver Signup</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="signup">
                <div class="form-group">
                    <label for="driver_Name">Driver Name</label>
                    <input type="text" class="form-control" id="driver_Name" name="driver_Name" placeholder="Enter your name" required>
                </div>
                <div class="form-group">
                    <label for="driver_LicenseNo">Driver License Number</label>
                    <input type="text" class="form-control" id="driver_LicenseNo" name="driver_LicenseNo" placeholder="Enter your license number" required>
                </div>
                <div class="form-group">
                    <label for="driver_phone_no">Phone Number</label>
                    <input type="tel" class="form-control" id="driver_phone_no" name="driver_phone_no" placeholder="Enter your phone number" required>
                </div>
                <div class="form-group">
                    <label for="driver_Password">Password</label>
                    <input type="password" class="form-control" id="driver_Password" name="driver_Password" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="btn btn-primary">Signup</button>
            </form>
        </div>

        <!-- Driver Login Form -->
        <div id="login" class="form-container" style="display:none;">
            <h2>Driver Login</h2>
            <form action="DriverLogin.php" method="post">
                <input type="hidden" name="login">
                <div class="form-group">
                    <label for="driver_LicenseNo_Login">Driver License Number</label>
                    <input type="text" class="form-control" id="driver_LicenseNo_Login" name="driver_LicenseNo" placeholder="Enter your license number" required>
                </div>
                <div class="form-group">
                    <label for="driver_Password_Login">Password</label>
                    <input type="password" class="form-control" id="driver_Password_Login" name="driver_Password" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
            </form>
        </div>
    </div>

    <script>
        function showSignup() {
            document.getElementById('signup').style.display = 'block';
            document.getElementById('signup').classList.remove('rotate');
            document.getElementById('login').style.display = 'none';
            document.getElementById('login').classList.add('rotate');
        }

        function showLogin() {
            document.getElementById('signup').style.display = 'none';
            document.getElementById('signup').classList.add('rotate');
            document.getElementById('login').style.display = 'block';
            document.getElementById('login').classList.remove('rotate');
        }
    </script>
</body>
</html>
