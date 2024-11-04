<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'bloodconnect');
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $donorName = $_POST['donorName'];
    $donorBloodGroup = $_POST['donorBloodGroup'];
    $donorPhoneNumber = $_POST['donorPhoneNumber'];
    $donorAddress = $_POST['donorAddress'];
    $location = $_POST['location'];

    // Prepare and bind the statement
    $stmt = $conn->prepare("INSERT INTO donations (donorName, donorBloodGroup, donorPhoneNumber, donorAddress, location, status) VALUES (?, ?, ?, ?, ?, 'Pending')");
    $stmt->bind_param("sssss", $donorName, $donorBloodGroup, $donorPhoneNumber, $donorAddress, $location);

    if ($stmt->execute()) {
        // Success response
        echo json_encode(['success' => true, 'message' => 'Donation request submitted successfully!']);
    } else {
        // Error response
        echo json_encode(['success' => false, 'message' => 'Failed to submit donation request.']);
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    // Invalid request method
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
