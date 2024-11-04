<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donate</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, rgba(255, 204, 188, 0.8), rgba(255, 171, 145, 0.8)), url('donate.JPEG') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            display: flex;
            justify-content: center; /* Center horizontally */
            align-items: center; /* Center vertically */
            height: 100vh; /* Full viewport height */
            margin: 0; /* Remove default margin */
            padding: 20px;
            text-align: center;
        }

        main {
            background: rgba(255, 255, 255, 0.9); /* Semi-transparent white for better readability */
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 500px;
            width: 100%; /* Make it responsive */
        }

        h2 {
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .error {
            color: red;
            font-weight: bold;
        }

        .success {
            color: green;
            font-weight: bold;
        }

        button {
            background-color: #ff5252;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #ff1744;
        }

        form {
            margin-top: 20px;
            display: none; /* Initially hidden */
        }

        .hidden {
            display: none;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        input:focus, select:focus {
            border-color: #ff5252;
            outline: none;
        }

        h3 {
            margin: 10px 0;
            font-size: 1.5rem;
            color: #ff5252;
        }

        @media (max-width: 600px) {
            main {
                padding: 20px;
            }

            h2 {
                font-size: 1.5rem;
            }

            h3 {
                font-size: 1.2rem;
            }

            button {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    
    <main>
        <h2>Blood Donate ❤️</h2>

        <?php if (isset($_GET['error'])): ?>
            <p class="error"><?php echo htmlspecialchars($_GET['error']); ?></p>
        <?php endif; ?>

        <?php if (isset($_GET['success'])): ?>
            <p class="success"><?php echo htmlspecialchars($_GET['success']); ?></p>
        <?php endif; ?>
        
        <button onclick="showDonateBloodForm()">💉 Donate Blood</button>

        <form id="donateBloodForm" class="hidden" method="POST" action="dashboard.php" onsubmit="return submitForm(event);">
            <h3>Donate Blood</h3>
            <input type="text" id="donorName" name="donorName" placeholder="Name" required>
            
            <select id="donorBloodGroup" name="donorBloodGroup" required>
                <option value="">Select Blood Group</option>
                <option value="A+">A+</option>
                <option value="A-">A-</option>
                <option value="B+">B+</option>
                <option value="B-">B-</option>
                <option value="AB+">AB+</option>
                <option value="AB-">AB-</option>
                <option value="O+">O+</option>
                <option value="O-">O-</option>
            </select>

            <input type="text" id="donorPhoneNumber" name="donorPhoneNumber" placeholder="Phone Number" required>
            
            <input type="text" id="donorAddress" name="donorAddress" placeholder="Address" required>

            <select id="location" name="location" required>
                <option value="">Select Location</option>
                <option value="Vijayawada">Vijayawada</option>
                <option value="Guntur">Guntur</option>
                <option value="Visakhapatnam">Visakhapatnam</option>
                <option value="Tirupati">Tirupati</option>
            </select>

            <button type="submit">Submit</button>
        </form>
    </main>
    
    <script>
        function showDonateBloodForm() {
            document.getElementById('donateBloodForm').classList.remove('hidden');
            document.getElementById('donateBloodForm').style.display = 'block'; // Show form
        }

        function submitForm(event) {
            event.preventDefault(); // Prevent the default form submission

            // Gather form data
            const formData = new FormData(document.getElementById('donateBloodForm'));

            // Send the data using Fetch API
            fetch('process_donation.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    alert(data.message);
                    document.getElementById('donateBloodForm').reset(); // Reset the form
                    document.getElementById('donateBloodForm').classList.add('hidden'); // Hide form again
                } else {
                    // Show error message
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while submitting the form.');
            });
        }
    </script>
</body>
</html>
