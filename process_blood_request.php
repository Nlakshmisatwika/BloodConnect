<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'bloodconnect');
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

// Check if blood request form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the posted data
    $bloodType = $_POST['bloodType'];
    $location = $_POST['location'];
    $contactNumber = $_POST['contactNumber']; // Retrieve contact number

    // Prepare the message
    $message = "Blood request for type $bloodType in $location. Contact number: +91$contactNumber."; // Include contact number

    // Insert the message into the messages table
    $stmt = $conn->prepare("INSERT INTO messages (message) VALUES (?)");
    if ($stmt === false) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }
    $stmt->bind_param("s", $message);
    $stmt->execute();
    $stmt->close();

    // Redirect back to the dashboard or wherever you want
    header('Location: dashboard.php'); // Adjust this as necessary
    exit();
}
?>
