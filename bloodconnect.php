<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Connect</title>
    <style>
        body {
            font-family: 'Verdana', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
            background: url('index.JPG') no-repeat center center fixed; 
            background-size: cover; 
            color: white;
        }

        main {
            background: rgba(0, 0, 0, 0.5);
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.6);
            text-align: center;
            transition: transform 0.4s ease;
        }

        main:hover {
            transform: scale(1.08);
        }

        h1 {
            font-family: 'Georgia', serif;
            font-size: 3rem; /* Increased font size for better visibility */
            margin-bottom: 1.5rem;
            letter-spacing: 2px; /* Slightly increased letter spacing */
            color: white; /* Set text color to white */
            text-shadow: 
                2px 2px 4px rgba(0, 0, 0, 0.7), /* Black shadow */
                0 0 25px rgba(255, 255, 255, 0.8), /* White glow effect */
                0 0 5px rgba(255, 255, 255, 0.5); /* Smaller white glow */
            transform: rotate(-2deg); /* Slightly tilted for a dynamic look */
            animation: pulse 1.5s infinite; /* Pulsing animation */
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        button {
            padding: 0.75rem 1.5rem;
            margin: 0.5rem;
            color: white;
            background-color: #8B0000;
            border: none;
            border-radius: 30px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        button:hover {
            background-color: #FF0000;
            transform: translateY(-4px);
        }

        button:active {
            transform: translateY(2px);
        }

        a {
            text-decoration: none;
        }

        .emoji {
            font-size: 1.5rem;
            margin-right: 8px;
        }
    </style>
</head>
<body>
    <main>
        <h1>Welcome to Blood Connect 💉</h1>

        <form method="POST" action="">
            <button type="submit" name="login"><span class="emoji">🔐</span>Login</button>
            <button type="submit" name="register"><span class="emoji">📝</span>Register</button>
            <button type="submit" name="about"><span class="emoji">ℹ️</span>About</button>
        </form>
    </main>

    <?php
    // PHP logic to handle button clicks
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['login'])) {
            header('Location: login.php');
            exit();
        } elseif (isset($_POST['register'])) {
            header('Location: register.php');
            exit();
        } elseif (isset($_POST['about'])) {
            header('Location: about.php');
            exit();
        }
    }
    ?>
</body>
</html>
