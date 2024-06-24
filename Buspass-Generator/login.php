<?php 
include "header.php";
?>
<?php
// Start the session to access session variables
session_start();

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

$loginMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Process login form data
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Validate and sanitize input data if needed

    // Verify login credentials against the database
    $stmt = $conn->prepare("SELECT * FROM users_details WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);

    // Execute the prepared statement
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if login credentials are valid
    if ($result->num_rows > 0) {
        $loginMessage = "Login successful";
        // Set session variable
        $_SESSION['email'] = $email;
        // Redirect to dashboard page after successful login
        header("Location: dashboard.php");
        exit();
    } else {
        $loginMessage = "Invalid email or password";
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
    <title>Login Form</title>

</head>

<body>
    <form id="loginForm" method="post" action="">
        <h2>Login Form</h2>

        <?php
        if ($loginMessage) {
            echo "<p>{$loginMessage}</p>";
        }
        ?>

        <label for="email">Email:</label>
        <br>
        <input type="email" id="email" name="email" required>
        <br>
        <label for="password">Password:</label>
        <br>
        <input type="password" id="password" name="password" required>
        <br><br>
        <input type="submit" class="button" value="Login">
    </form>
</body>

</html>