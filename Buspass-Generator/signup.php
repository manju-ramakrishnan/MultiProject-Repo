<?php
include "header.php";
?>
<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "buspass";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$registrationMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Process registration form data
    $username = $_POST["username"];
    $mobile = $_POST["mobile"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Generate a random 5-digit passcode
    $passcode = rand(10000, 99999);

    // Calculate validity date as 90 days from the current signup date
    $validityDate = date('Y-m-d', strtotime('+90 days'));

    // Additional fields
    $fromPlace = $_POST["from_place"];
    $toDestination = $_POST["to_destination"];

    // Validate and sanitize input data if needed

    // Insert data into the database
    $stmt = $conn->prepare("INSERT INTO users_details (username, mobile, email, passcode, password, validity_date, from_place, to_destination) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $username, $mobile, $email, $passcode, $password, $validityDate, $fromPlace, $toDestination);

    // Execute the prepared statement
    if ($stmt->execute()) {
        $registrationMessage = "Registration successful";

        // Redirect to login page after successful registration
        header("Location: login.php");
        exit();
    } else {
        $registrationMessage = "Registration failed";
    }

    // Close statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
</head>

<body>
    <form id="registrationForm" class="signup_form" method="post" action="">
        <h2>Registration Form</h2>

        <?php
        if ($registrationMessage) {
            echo "<p>{$registrationMessage}</p>";
        }
        ?>

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br>

        <label for="mobile">Mobile:</label>
        <input type="text" id="mobile" name="mobile" required>
        <br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>

        <label for="from_place">From (Place):</label>
        <input type="text" id="from_place" name="from_place" required>
        <br>

        <label for="to_destination">To (Destination):</label>
        <input type="text" id="to_destination" name="to_destination" required>
        <br>
        <input type="submit" class="signup-button" value="Register">
    </form>
</body>

</html>