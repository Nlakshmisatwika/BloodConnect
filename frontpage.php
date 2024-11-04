<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        body, html {
            font-family: 'Arial', sans-serif;
        }
        .background-image {
            background-image: url('webpage.jpeg');
            background-size: cover; 
            background-position: center;
            background-repeat: no-repeat; 
            position: absolute;
            width: 100%;
            height: 100%;
        }
        button {
            padding: 1rem 2rem;
            font-size: 1.2rem;
            font-weight: bold;
            color: yellow;
            background: #ff4b2b;
            border: none;
            border-radius: 25px;
            text-transform: uppercase;
            transition: all 0.3s ease;
            position: relative;
            margin-top: 20px; 
        }
        button:hover {
            background: #ff6f61;
            transform: scale(1.05);
        }
        a {
            text-decoration: none;
            color: inherit;
        }
    </style>
</head>
<body>
<div class="background-image"></div>
<div style="display: flex; flex-direction: column; justify-content: center; align-items: center; height: 100%;">
    <!-- The form will trigger a PHP script when the button is clicked -->
    <form method="POST" action="">
        <button type="submit" name="visit_page">Click Me to Visit Our Page!</button>
    </form>
</div>

<?php
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['visit_page'])) {
    // Redirect to another page using PHP
    header('Location: bloodconnect.php');
    exit(); // Ensure no further code is executed after the redirect
}
?>
</body>
</html>
