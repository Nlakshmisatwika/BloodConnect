<!DOCTYPE html>
<html lang="en">
<head>
    <title>Blood Connect</title>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
         body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, rgba(128, 0, 128, 0.7), rgba(255, 105, 180, 0.7)), url('about.JPG'); /* Purple to pink gradient over the image */
            background-size: Center; /* Ensure the image covers the entire background */
            background-position: center; /* Center the image */
            color: white;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            height: 100vh;
            overflow-y: scroll;
            overflow-x: hidden;
        }

        .content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            height: 100%;
            overflow-y: scroll;
            padding: 20px;
            opacity: 0;
            animation: fadeIn 1s forwards; /* Fade in effect */
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }

        h1 {
            font-family: 'Pacifico', cursive;
            font-size: 4rem;
            text-align: center;
            margin: 20px 0;
            color: #fff;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.4);
        }

        p {
            font-size: 1.25rem;
            text-align: center;
            max-width: 700px;
            margin: 10px auto 20px;
            line-height: 1.6;
        }

        .blood-info {
            margin-top: 20px;
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 800px;
        }

        .blood-info h2 {
            font-size: 2rem;
            text-align: center;
            margin-bottom: 10px;
            text-shadow: 1px 1px 8px rgba(0, 0, 0, 0.3);
        }

        .blood-table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }

        .blood-table th, .blood-table td {
            padding: 10px;
            border: 1px solid #fff;
            font-size: 1rem;
        }

        .blood-table th {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .blood-table td {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .buttons-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        .anybutton {
            background: rgba(255, 255, 255, 0.15);
            border: 2px solid white;
            border-radius: 50px;
            color: white;
            font-size: 1.25rem;
            padding: 15px 30px;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .anybutton:hover {
            background-color: #fff;
            color: #ee0979;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.4);
            transform: scale(1.05); /* Slightly enlarge the button */
        }

        .anybutton a {
            text-decoration: none;
            color: inherit;
        }

        .description {
            font-size: 1rem;
            color: #f0f0f0;
            max-width: 600px;
            margin-top: 10px;
            text-align: center;
            opacity: 0.8;
        }
    </style>
</head>
<body>

    <div class="content">
        <h1>Welcome to Blood Connect ❤️</h1>

        <!-- Blood Compatibility Info -->
        <div class="blood-info">
            <h2>Blood Group Compatibility</h2>
            <table class="blood-table">
                <thead>
                    <tr>
                        <th>Blood Group</th>
                        <th>Can Donate To</th>
                        <th>Can Receive From</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Sample blood group compatibility data
                    $blood_groups = [
                        ['A+', 'A+, AB+', 'A+, A-, O+, O-'],
                        ['A-', 'A+, A-, AB+, AB-', 'A-, O-'],
                        ['B+', 'B+, AB+', 'B+, B-, O+, O-'],
                        ['B-', 'B+, B-, AB+, AB-', 'B-, O-'],
                        ['AB+', 'AB+', 'All blood types'],
                        ['AB-', 'AB+, AB-', 'AB-, A-, B-, O-'],
                        ['O+', 'O+, A+, B+, AB+', 'O+, O-'],
                        ['O-', 'All blood types', 'O-']
                    ];

                    // Displaying the data in the table
                    foreach ($blood_groups as $group) {
                        echo "<tr>
                                <td>{$group[0]}</td>
                                <td>{$group[1]}</td>
                                <td>{$group[2]}</td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <p>BloodConnect is a web-based platform that connects blood donors with recipients in need of blood transfusions. 
            The platform aims to bridge the gap between blood banks, hospitals, and individuals seeking blood donations, ensuring a seamless and efficient blood donation process.</p>

        <div class="buttons-container">
            <!-- Login Button -->
            <div>
                <a href="login.php"><button class="anybutton">🔐 Login</button></a>
                <p class="description">Sign in to access your account and start donating or requesting blood.</p>
            </div>
            
            <!-- Register Button -->
            <div>
                <a href="register.php"><button class="anybutton">📝 Register</button></a>
                <p class="description">Create an account to join our community of life-saving donors.</p>
            </div>
        </div>
    </div>

</body>
</html>
