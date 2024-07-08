<?php
session_start();
include("db.php"); // Include your database connection logic

// Handle Signup
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['signup'])) {
        // Handle signup logic (insertion into database, file upload, etc.)
        $name = htmlspecialchars($_POST['stud_Name']);
        $email = htmlspecialchars($_POST['stud_Email']);
        $phone = htmlspecialchars($_POST['stud_phone_no']);
        $address = htmlspecialchars($_POST['stud_Address']);
        $password = $_POST['password'];

        // Hash the password before storing it in the database
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Handle profile picture upload
        $profilePic = null;
        if (isset($_FILES['profile_Picture']) && $_FILES['profile_Picture']['error'] == 0) {
            $profilePic = file_get_contents($_FILES['profile_Picture']['tmp_name']);
        }

        // SQL query to insert data into the student_details table
        $stmt = $con->prepare("INSERT INTO student_details (Name, Email, Phone_Number, Address, Password, Profile_Picture) VALUES (?, ?, ?, ?, ?, ?)");

        if ($stmt) {
            $null = NULL;
            $stmt->bind_param("sssssb", $name, $email, $phone, $address, $hashed_password, $null);

            if ($profilePic !== null) {
                $stmt->send_long_data(5, $profilePic);
            }

            if ($stmt->execute()) {
                // Registration successful, set session data
                $_SESSION['student'] = [
                    'id' => $stmt->insert_id,
                    'name' => $name,
                    'email' => $email,
                    'phone_no' => $phone,
                    'address' => $address,
                    'profilePic' => $profilePic ? base64_encode($profilePic) : '' // Store the profile picture as a base64 string in the session
                ];
                $stmt->close();
                $con->close();
                header("Location: home.php"); // Redirect to homepage after successful signup
                exit();
            } else {
                // Registration failed, handle error (e.g., duplicate email, database error)
                echo "<script>alert('Error registering user.');</script>";
            }
        } else {
            echo "<script>alert('Error preparing statement.');</script>";
        }
    }
}

// Handle Login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = htmlspecialchars($_POST['student_Email']);
    $password = $_POST['password'];

    // Fetch the user from the database
    $stmt = $con->prepare("SELECT id, Name, Email, Phone_Number, Address, Password, Profile_Picture FROM student_details WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['Password'])) {
            // Password is correct, set session data
            $_SESSION['student'] = [
                'id' => $user['id'],
                'name' => $user['Name'],
                'email' => $user['Email'],
                'phone_no' => $user['Phone_Number'],
                'address' => $user['Address'],
                'profilePic' => $user['Profile_Picture'] ? base64_encode($user['Profile_Picture']) : ''
            ];
            header("Location: home.php"); // Redirect to homepage after successful login
            exit();
        } else {
            echo "<script>alert('Incorrect password.');</script>";
        }
    } else {
        echo "<script>alert('No user found with this email.');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup and Login Forms</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<div class="container">
    <div style="display: flex; text-align:center" >
    <img src="Pics/logo.png" alt="" srcset="" height="100px" style="margin-top:;">
    <h2 style="margin-left: 100px;margin-top:30px">BEBABEBA</h2>
    </div>
    <!-- Toggle Buttons -->
    <div class="text-center mb-4">
        <button class="btn btn-outline-primary" onclick="showSignup()">Signup</button>
        <button class="btn btn-outline-secondary" onclick="showLogin()">Login</button>
    </div>

    <!-- Signup and Login Forms -->
    <div id="signup" class="form-container">
        <h2>Signup</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="signup">
            <div class="form-group">
                <label for="stud_Name">Name:</label>
                <input type="text" class="form-control" id="stud_Name" name="stud_Name" required>
            </div>
            <div class="form-group">
                <label for="stud_Email">Email:</label>
                <input type="email" class="form-control" id="stud_Email" name="stud_Email" required>
            </div>
            <div class="form-group">
                <label for="stud_phone_no">Phone Number:</label>
                <input type="text" class="form-control" id="stud_phone_no" name="stud_phone_no" required>
            </div>
            <div class="form-group">
                <label for="stud_Address">Address:</label>
                <textarea class="form-control" id="stud_Address" name="stud_Address" required></textarea>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="profile_Picture">Profile Picture:</label>
                <input type="file" class="form-control-file" id="profile_Picture" name="profile_Picture" accept="image/*">
            </div>
            <button type="submit" class="btn btn-primary">Signup</button>
        </form>
        <div class="dropdown">
            <a class="btn btn-secondary dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Not a student?
            </a>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="DriverLogin.php">Driver</a></li>
                <li><a class="dropdown-item" href="Admin.php">Supervisor</a></li>
            </ul>
        </div>
    </div>

    <div id="login" class="form-container" style="display:none;">
        <h2>Login</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="hidden" name="login">
            <div class="form-group">
                <label for="student_Email">Email</label>
                <input type="email" class="form-control" id="student_Email" name="student_Email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
            <div class="dropdown">
                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Not a student?
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="DriverLogin.php">Driver</a></li>
                    <li><a class="dropdown-item" href="Admin.php">Supervisor</a></li>
                </ul>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybBogGz8U4JpobgqLrPaqOKuVY8XHfGfyvzF4pV0kA5V8JGpG" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-A3iJNk6mWVpe8YPQzVLSJq7xS8p8S6KbYB4PlVYt8gAmOs7Bf0MygtgT6fvvAUj4" crossorigin="anonymous"></script>
<script>
function showSignup() {
    document.getElementById('signup').style.display = 'block';
    document.getElementById('login').style.display = 'none';
}
function showLogin() {
    document.getElementById('signup').style.display = 'none';
    document.getElementById('login').style.display = 'block';
}
</script>
</body>
</html>
