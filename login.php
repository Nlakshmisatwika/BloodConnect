<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'bloodconnect');

if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Prepare statement to fetch user details
    $stmt = $conn->prepare("SELECT * FROM registration WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['username'] = $email;
            $_SESSION['fullName'] = $user['firstName'];
            // Redirect to profile page
            header('Location: profile.php');
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No user found with that email.";
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
    <title>Login</title>
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
            background: url('login.JPG') no-repeat center center fixed; /* Add background image */
            background-size: cover; /* Cover the whole viewport */
            background-blend-mode: overlay; /* Blend with gradient */
            color: #333;
        }

        .container {
            background: rgba(255, 255, 255, 0.9); /* Slight transparency for better readability */
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
            padding: 2rem;
            max-width: 400px;
            text-align: center;
            animation: fadeIn 0.8s ease-in-out;
        }

        h1 {
            font-size: 2rem;
            color: #b22222;
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            text-align: left;
            font-size: 0.9rem;
            margin: 0.5rem 0 0.3rem;
            color: #666;
        }

        input[type="email"],
        input[type="password"] {
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
        <h1>Login</h1>
        <?php if (isset($error)): ?>
            <p style="color:red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <label>Email:</label>
            <input type="email" name="email" required>
            <label>Password:</label>
            <input type="password" name="password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
