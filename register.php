<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'bloodconnect');
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Initialize variables
    $firstName = $_POST['fullName'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $bloodType = $_POST['bloodType'] ?? '';
    $location = $_POST['location'] ?? '';
    $hospitalName = $_POST['hospitalName'] ?? '';

    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Determine if the user is registering as a hospital or individual
    if (isset($_POST['register_hospital'])) {
        // Prepare statement for hospital registration
        $stmt = $conn->prepare("INSERT INTO registration(firstName, email, password, location, hospitalName) VALUES(?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $firstName, $email, $hashedPassword, $location, $hospitalName);

        // Execute and handle the result
        if ($stmt->execute()) {
            // Store user details in session
            $_SESSION['username'] = $email;
            $_SESSION['fullName'] = $hospitalName; // Use hospital name for the session

            // Redirect to dashboard.php
            header('Location: dashboard.php');
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    } elseif (isset($_POST['register_people'])) {
        // Prepare statement for people registration with gender and blood type
        $stmt = $conn->prepare("INSERT INTO registration(firstName, email, password, gender, bloodType) VALUES(?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $firstName, $email, $hashedPassword, $gender, $bloodType);

        // Execute and handle the result
        if ($stmt->execute()) {
            // Store user details in session
            $_SESSION['username'] = $email;
            $_SESSION['fullName'] = $firstName; // Set full name in session for individuals
            
            // Redirect to profile.php for individuals
            header('Location: profile.php');
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        die("Invalid registration type.");
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <style>
        /* General page styling */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: url('register.jpeg') no-repeat center center fixed; 
            background-size: cover; /* Make the background image cover the whole page */
            color: #333;
        }

        .container {
            background: rgba(255, 255, 255, 0.9); /* Add a slight transparency for better readability */
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
            padding: 2rem;
            max-width: 500px;
            text-align: center;
            animation: fadeIn 0.8s ease-in-out;
        }

        h1 {
            font-size: 2.2rem;
            color: #b22222;
            margin-bottom: 1.5rem;
        }

        .button-container {
            margin-bottom: 1.5rem;
        }

        .button-container button {
            padding: 0.8rem 1.2rem;
            font-size: 1rem;
            border: none;
            border-radius: 5px;
            background-color: #b22222;
            color: #fff;
            cursor: pointer;
            margin: 0 0.5rem;
            transition: background 0.3s;
        }

        .button-container button:hover {
            background-color: #ff4d4d;
        }

        form {
            display: none;
            transition: opacity 0.4s ease;
        }

        form.active {
            display: block;
            opacity: 1;
        }

        h2 {
            color: #333;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        label {
            display: block;
            text-align: left;
            font-size: 0.9rem;
            margin: 0.5rem 0 0.3rem;
            color: #666;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 0.8rem;
            margin: 0.3rem 0 1rem;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 1rem;
        }

        button[type="submit"] {
            width: 100%;
            padding: 0.8rem;
            font-size: 1rem;
            border-radius: 5px;
            background-color: #b22222;
            color: #fff;
            cursor: pointer;
            transition: background 0.3s;
        }

        button[type="submit"]:hover {
            background-color: #ff4d4d;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Register Here</h1>
        <div class="button-container">
            <button id="hospitalBtn">Hospital</button>
            <button id="peopleBtn">People</button>
        </div>
        
        <!-- Hospital Registration Form -->
        <form id="hospitalForm" class="form" method="POST" action="">
            <h2>Hospital Registration</h2>
            <label>Location:</label>
            <select id="hospitalLocation" name="location" required>
                <option disabled selected>Select Location</option>
                <option value="Amaravathi">Amaravathi</option>
                <option value="Visakhapatnam">Visakhapatnam</option>
                <option value="Vijayawada">Vijayawada</option>
                <option value="Tirupati">Tirupati</option>
                <option value="Guntur">Guntur</option>
                <option value="Kakinada">Kakinada</option>
            </select>
            <label>Hospital Name:</label>
            <select id="hospitalName" name="hospitalName" required>
                <option disabled selected>Select Hospital</option>
                <option value="Andhra Medical College">Andhra Medical College</option>
                <option value="Guntur Medical College">Guntur Medical College</option>
                <option value="Kamineni Hospital">Kamineni Hospital</option>
                <option value="Sri Venkateswara Institute of Medical Sciences">Sri Venkateswara Institute of Medical Sciences</option>
                <option value="Government General Hospital, Vijayawada">Government General Hospital, Vijayawada</option>
            </select>
            <label>Email:</label>
            <input type="email" name="email" required>
            <label>Password:</label>
            <input type="password" name="password" required>
            <button type="submit" name="register_hospital">Register Hospital</button>
        </form>
        
        <!-- People Registration Form -->
        <form id="peopleForm" class="form" method="POST" action="">
            <h2>People Registration</h2>
            <label>Full Name:</label>
            <input type="text" name="fullName" required>
            <label>Gender:</label>
            <select name="gender" required>
                <option disabled selected>Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
            <label>Blood Type:</label>
            <select name="bloodType" required>
                <option disabled selected>Select Blood Type</option>
                <option value="A+">A+</option>
                <option value="A-">A-</option>
                <option value="B+">B+</option>
                <option value="B-">B-</option>
                <option value="O+">O+</option>
                <option value="O-">O-</option>
                <option value="AB+">AB+</option>
                <option value="AB-">AB-</option>
            </select>
            <label>Email:</label>
            <input type="email" name="email" required>
            <label>Password:</label>
            <input type="password" name="password" required>
            <button type="submit" name="register_people">Register People</button>
        </form>
    </div>

    <script>
        const hospitalForm = document.getElementById('hospitalForm');
        const peopleForm = document.getElementById('peopleForm');
        const hospitalBtn = document.getElementById('hospitalBtn');
        const peopleBtn = document.getElementById('peopleBtn');

        hospitalBtn.addEventListener('click', () => {
            hospitalForm.classList.add('active');
            peopleForm.classList.remove('active');
            hospitalBtn.classList.add('active');
            peopleBtn.classList.remove('active');
        });

        peopleBtn.addEventListener('click', () => {
            peopleForm.classList.add('active');
            hospitalForm.classList.remove('active');
            peopleBtn.classList.add('active');
            hospitalBtn.classList.remove('active');
        });
    </script>
</body>
</html>
