<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'bloodconnect');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Accept Donation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['donation_id'])) {
    $donationId = $_POST['donation_id'];

    // Fetch the blood group of the donor
    $donationQuery = $conn->prepare("SELECT donorBloodGroup, donorEmail FROM donations WHERE id = ?");
    $donationQuery->bind_param("i", $donationId);
    $donationQuery->execute();
    $donationQuery->bind_result($donorBloodGroup, $donorEmail);
    
    if ($donationQuery->fetch()) {
        $donationQuery->close();

        // Define points based on blood group
        $pointsMapping = [
            'A+' => 10,
            'A-' => 15,
            'B+' => 10,
            'B-' => 15,
            'AB+' => 20,
            'AB-' => 25,
            'O+' => 10,
            'O-' => 30 // O- is rare, hence higher points
        ];

        // Get points for the donor's blood group
        $pointsEarned = $pointsMapping[$donorBloodGroup] ?? 0;

        // Update the donation status
        $updateDonationQuery = $conn->prepare("UPDATE donations SET status = 'Accepted' WHERE id = ?");
        $updateDonationQuery->bind_param("i", $donationId);
        if ($updateDonationQuery->execute()) {
            // Update the user's points
            $updatePointsQuery = $conn->prepare("UPDATE registration SET points = points + ? WHERE email = ?");
            $updatePointsQuery->bind_param("is", $pointsEarned, $donorEmail);
            if ($updatePointsQuery->execute()) {
                $_SESSION['success'] = "Donation accepted successfully! You earned $pointsEarned points!";
            } else {
                $_SESSION['error'] = "Failed to update points for the donor.";
            }
            $updatePointsQuery->close();
        } else {
            $_SESSION['error'] = "Failed to update the donation status.";
        }
        $updateDonationQuery->close();
    } else {
        $_SESSION['error'] = "No donation found with the given ID.";
    }
} else {
    $_SESSION['error'] = "Invalid request.";
}

$conn->close();
header("Location: dashboard.php");
exit();
?>
