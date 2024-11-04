<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'bloodconnect');

if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

$email = $_SESSION['username'];
$stmt = $conn->prepare("SELECT * FROM registration WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "No user found.";
    exit();
}

$user = $result->fetch_assoc();
$_SESSION['fullName'] = $user['firstName']; // Set full name in session if not already set

// Fetch messages
$messageQuery = "SELECT message, created_at FROM messages ORDER BY created_at DESC";
$messageResult = $conn->query($messageQuery);

// Fetch pending blood requests
$requestQuery = "SELECT * FROM messages WHERE message LIKE 'Blood request for%' AND created_at >= NOW() - INTERVAL 1 DAY"; // Adjust the time frame if needed
$requestResult = $conn->query($requestQuery);

// Check if the blood requests query was successful
if (!$requestResult) {
    die("Error executing query: " . $conn->error);
}

// Close the prepared statement but not the connection yet
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <style>
        /* Styles as defined earlier, keeping the same styles for consistency */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        
        .header {
            width: 100%;
            background-color: #b22222;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        .nav-links {
            display: flex;
        }
        
        .nav-links a {
            color: white;
            text-decoration: none;
            padding: 10px;
            margin: 0 10px;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .nav-links a:hover {
            background-color: #ff4d4d;
            border-radius: 5px;
        }

        .profile-icon {
            font-size: 30px;
            cursor: pointer;
            color: #ffffff;
            transition: color 0.3s;
            margin-right: 50px;
        }

        .profile-icon:hover {
            color: #ff4d4d;
        }

        .profile-details {
            display: none;
            text-align: left;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            z-index: 1000;
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .logout-button {
            margin-top: 20px;
        }

        .logout-button a {
            display: inline-block;
            padding: 10px 20px;
            color: white;
            background-color: #b22222;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .logout-button a:hover {
            background-color: #ff4d4d;
        }

        .messages, .blood-requests {
            margin-top: 20px;
            background: #fff;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            width: 100%;
        }

        .message, .request {
            margin-bottom: 10px;
            padding: 5px;
            border-bottom: 1px solid #ddd;
        }

        .message:last-child, .request:last-child {
            border-bottom: none;
        }

        .accept-button {
            padding: 8px 12px;
            color: white;
            background-color: #28a745;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .accept-button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="nav-links">
            <?php if ($user['userType'] === 'hospital'): ?>
                <a href="dashboard.php">Dashboard</a>
            <?php endif; ?>
            <a href="donate.php">Donate Blood</a>
            <a href="about.php">About</a>
        </div>
        <div class="profile-icon" onclick="toggleProfileDetails()">👤</div>
    </div>

    <div id="overlay" class="overlay" onclick="toggleProfileDetails()"></div>

    <div id="profileDetails" class="profile-details">
        <p><strong>Full Name:</strong> <?php echo isset($_SESSION['fullName']) ? htmlspecialchars($_SESSION['fullName']) : "Not provided"; ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <?php if (isset($user['gender'])): ?>
            <p><strong>Gender:</strong> <?php echo htmlspecialchars($user['gender']); ?></p>
        <?php endif; ?>
        <?php if (isset($user['bloodType'])): ?>
            <p><strong>Blood Type:</strong> <?php echo htmlspecialchars($user['bloodType']); ?></p>
        <?php endif; ?>
        <p><strong>Points:</strong> <?php echo htmlspecialchars($user['points']); ?></p>
        
        <div class="logout-button">
            <a href="logout.php">Logout</a>
        </div>
    </div>

    

    <div class="blood-requests">
        <h3>Pending Blood Requests</h3>
        <?php if ($requestResult->num_rows > 0): ?>
            <?php while ($request = $requestResult->fetch_assoc()): ?>
                <div class="request">
                    <p><strong>Request Details:</strong> <?php echo htmlspecialchars($request['message']); ?></p>
                    <form method="post" action="accept_request.php">
                        <input type="hidden" name="request_id" value="<?php echo htmlspecialchars($request['id']); ?>">
                        <button type="submit" class="accept-button">Accept</button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No pending blood requests.</p>
        <?php endif; ?>
    </div>

    <script>
        function toggleProfileDetails() {
            const profileDetails = document.getElementById('profileDetails');
            const overlay = document.getElementById('overlay');
            if (profileDetails.style.display === 'block') {
                profileDetails.style.display = 'none';
                overlay.style.display = 'none';
            } else {
                profileDetails.style.display = 'block';
                overlay.style.display = 'block';
            }
        }
    </script>
</body>
</html>

<?php
// Close the database connection at the end of the script
$conn->close();
?>
