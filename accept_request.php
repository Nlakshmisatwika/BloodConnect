<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'bloodconnect');

if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['request_id'])) {
    $requestId = $_POST['request_id'];
    
    // Prepare the update statement
    $updateQuery = "UPDATE messages SET status = 'accepted' WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);

    // Check if the statement was prepared successfully
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    // Bind the request ID parameter
    $stmt->bind_param("i", $requestId);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect back to profile after accepting
        header('Location: profile.php?msg=Request accepted successfully.');
        exit();
    } else {
        echo "Error accepting request: " . $stmt->error;
    }
    
    $stmt->close();
}

$conn->close();
// Assuming you already have the connection established as in your original code

// Handle Accept Donation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['donation_id'])) {
    $donationId = $_POST['donation_id'];

    // Update the donation status in the database
    $updateQuery = $conn->prepare("UPDATE donations SET status = 'Accepted' WHERE id = ?");
    $updateQuery->bind_param("i", $donationId);
    $updateQuery->execute();

    // Check if the donation status was updated successfully
    if ($updateQuery->affected_rows > 0) {
        $_SESSION['success'] = "Donation accepted successfully!";

        // Fetch the donor's details to send a message
        $donorQuery = $conn->prepare("SELECT donorName, donorBloodGroup, donorPhoneNumber FROM donations WHERE id = ?");
        $donorQuery->bind_param("i", $donationId);
        $donorQuery->execute();
        $donorQuery->bind_result($donorName, $donorBloodGroup, $donorPhoneNumber);
        $donorQuery->fetch();

        // Prepare a message to send to the hospital
        $message = "Accepted blood donation request from $donorName for blood group $donorBloodGroup. Contact number: $donorPhoneNumber.";

        // Insert the message into the messages table
        $insertMessageQuery = $conn->prepare("INSERT INTO messages (message, created_at, type, status) VALUES (?, NOW(), 'notification', 'unread')");
        $insertMessageQuery->bind_param("s", $message);
        $insertMessageQuery->execute();

        // Check for errors in message insertion
        if ($insertMessageQuery->error) {
            die("Error inserting message: " . $insertMessageQuery->error);
        }

        // Close the donor query
        $donorQuery->close();
    } else {
        $_SESSION['error'] = "Error accepting donation. Please try again.";
    }

    // Close the update statement
    $updateQuery->close();
}

// Close the connection
$conn->close();

?>
