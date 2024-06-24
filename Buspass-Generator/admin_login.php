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
    $adminEmail = $_POST["admin_email"];
    $adminPassword = $_POST["admin_password"];

    // Validate and sanitize input data if needed

    // Verify login credentials against the database
    $stmt = $conn->prepare("SELECT * FROM admin WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $adminEmail, $adminPassword);

    // Execute the prepared statement
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if login credentials are valid
    if ($result->num_rows > 0) {
        // Admin login successful
        $_SESSION['admin_email'] = $adminEmail;
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $loginMessage = "Invalid admin credentials";
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
    <title>Admin Login</title>
</head>

<body>
    <form id="adminLoginForm" method="post" action="">
        <h2>Admin Login</h2>

        <?php
        if ($loginMessage) {
            echo "<p>{$loginMessage}</p>";
        }
        ?>

        <label for="admin_email">Email:</label>
        <br>
        <input type="email" id="admin_email" name="admin_email" required>
        <br>
        <label for="admin_password">Password:</label>
        <br>
        <input type="password" id="admin_password" name="admin_password" required>
        <br><br>
        <input type="submit" class="button" value="Login">
    </form>
</body>

</html>