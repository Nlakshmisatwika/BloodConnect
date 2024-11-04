<?php
session_start();


// Display success or error messages if set
if (isset($_SESSION['success'])) {
    echo '<p style="color: green;">' . $_SESSION['success'] . '</p>';
    unset($_SESSION['success']);
}

if (isset($_SESSION['error'])) {
    echo '<p style="color: red;">' . $_SESSION['error'] . '</p>';
    unset($_SESSION['error']);
}



// Database connection
$conn = new mysqli('localhost', 'root', '', 'bloodconnect');
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

// Fetch user details based on session
if (isset($_SESSION['username'])) {
    $email = $_SESSION['username'];
    
    // Prepare the SQL statement for fetching hospital details
    $stmt = $conn->prepare("SELECT hospitalName, location FROM registration WHERE email = ?");
    if ($stmt === false) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }
    
    // Bind parameters
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    // Check if any rows were returned
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hospitalName, $location);
        $stmt->fetch();
    } else {
        echo "User not found.";
        exit(); // Stop script execution if user is not found
    }
} else {
    header('Location: register.php'); // Redirect to registration if not logged in
    exit();
}

// Fetch messages to display
$messageQuery = "SELECT message, created_at FROM messages ORDER BY created_at DESC";
$result = $conn->query($messageQuery);

// Fetch pending donation requests
$donationQuery = "SELECT id, donorName, donorBloodGroup, donorPhoneNumber, donorAddress, location FROM donations WHERE status = 'Pending' ORDER BY created_at DESC";
$donationResult = $conn->query($donationQuery);

// Check if the query was successful
if ($donationResult === false) {
    die("Error executing query: " . $conn->error);
}

// Handle Accept Donation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['donation_id'])) {
    $donationId = $_POST['donation_id'];

    // Update the donation status in the database
    $updateQuery = $conn->prepare("UPDATE donations SET status = 'Accepted' WHERE id = ?");
    $updateQuery->bind_param("i", $donationId);
    $updateQuery->execute();
    $updateQuery->close();

    $_SESSION['success'] = "Donation accepted successfully!";
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #f4f4f9;
            color: #333;
            text-align: center;
            margin: 0;
            padding: 0;
        }
        main {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #e74c3c;
        }
        .profile-icon {
            font-size: 1.5em;
            cursor: pointer;
            position: absolute;
            top: 20px;
            right: 20px;
        }
        .profile-details {
            display: none;
            position: absolute;
            right: 20px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
        .profile-details p {
            margin: 0;
        }
        .logout, .blood-request {
            margin-top: 20px;
            padding: 10px 20px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .logout:hover, .blood-request:hover {
            background: #2980b9;
        }
        .blood-request-form {
            margin-top: 20px;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
        .donation {
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: #f9f9f9;
        }
        .accept-button {
            margin-top: 10px;
            padding: 5px 10px;
            background: #2ecc71;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .accept-button:hover {
            background: #27ae60;
        }
    </style>
</head>
<body>
    
<main>
    <span class="profile-icon" onclick="toggleProfileDetails()">👤</span>
    <div class="profile-details" id="profileDetails">
        <p><strong>Name:</strong> <?php echo htmlspecialchars($hospitalName ? $hospitalName : ''); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
        <?php if ($hospitalName) { ?>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($location); ?></p>
        <?php } ?>
    </div>
    
    <h2>Welcome to the Blood Connect </h2>
    <p>Here you can request blood and find donors!</p>
    
    <button class="blood-request" onclick="toggleBloodRequestForm()">Request Blood</button>
    <div class="blood-request-form" id="bloodRequestForm" style="display: none;">
        <h3>Request Blood</h3>
        <form id="requestForm" action="process_blood_request.php" method="POST">
            <label for="bloodType">Blood Type:</label>
            <select id="bloodType" name="bloodType" required>
                <option value="">Select Blood Type</option>
                <option value="A+">A+</option>
                <option value="A-">A-</option>
                <option value="B+">B+</option>
                <option value="B-">B-</option>
                <option value="AB+">AB+</option>
                <option value="AB-">AB-</option>
                <option value="O+">O+</option>
                <option value="O-">O-</option>
            </select>
            <label for="location">Location:</label>
            <select id="location" name="location" required>
                <option value="">Select City</option>
                <option value="Amaravati">Amaravati</option>
                <option value="Visakhapatnam">Visakhapatnam</option>
                <option value="Vijayawada">Vijayawada</option>
                <option value="Guntur">Guntur</option>
                <option value="Tirupati">Tirupati</option>
                <option value="Kakinada">Kakinada</option>
                <option value="Nellore">Nellore</option>
                <option value="Rajahmundry">Rajahmundry</option>
                <option value="Kadapa">Kadapa</option>
                <option value="Eluru">Eluru</option>
                <option value="Chittoor">Chittoor</option>
            </select>
            <label for="contactNumber">Contact Number:</label>
            <input type="tel" id="contactNumber" name="contactNumber" placeholder="+91" required pattern="[+][9][1][0-9]{10}" title="Please enter a valid contact number starting with +91 and followed by 10 digits.">
            <button type="submit">Submit Request</button>
            <button type="button" onclick="toggleBloodRequestForm()">Cancel</button>
        </form>
    </div>
    
    <!-- Pending Donations Section -->
<h3>Pending Donations</h3>
<div class="donation-requests">
    <?php if ($donationResult->num_rows > 0): ?>
        <?php while ($row = $donationResult->fetch_assoc()): ?>
            <div class="donation">
                <p><strong>Donor Name:</strong> <?php echo htmlspecialchars($row['donorName']); ?></p>
                <p><strong>Blood Group:</strong> <?php echo htmlspecialchars($row['donorBloodGroup']); ?></p>
                <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($row['donorPhoneNumber']); ?></p>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($row['donorAddress']); ?></p>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($row['location']); ?></p>
                <form action="" method="POST">
                    <input type="hidden" name="donation_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" class="accept-button">Accept</button>
                </form>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No pending donations at this time.</p>
    <?php endif; ?>
</div>

    
    <?php
    // Display success message if set
    if (isset($_SESSION['success'])) {
        echo '<p style="color: green;">' . $_SESSION['success'] . '</p>';
        unset($_SESSION['success']);
    }
    ?>
    
    <form action="logout.php" method="POST" style="margin-top: 20px;">
        <button class="logout" type="submit">Logout</button>
    </form>
</main>

<script>
function toggleProfileDetails() {
    var profileDetails = document.getElementById('profileDetails');
    profileDetails.style.display = profileDetails.style.display === 'block' ? 'none' : 'block';
}

function toggleBloodRequestForm() {
    var bloodRequestForm = document.getElementById('bloodRequestForm');
    bloodRequestForm.style.display = bloodRequestForm.style.display === 'block' ? 'none' : 'block';
}
</script>

</body>
</html>
