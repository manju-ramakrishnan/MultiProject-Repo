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

// Initialize $userDetails to an empty array
$userDetails = [];

// Check if the user is logged in
if (isset($_SESSION['email'])) {
// Retrieve user details
$email = $_SESSION['email'];
$sql = "SELECT * FROM users_details WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$userDetails = $result->fetch_assoc();
} else {
// Redirect to login page if user is not logged in
header("Location: login.php");
exit();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
    }

    header {
        background-color: #333;
        padding: 10px 0;
        top: 0px;
        margin-bottom: 20px;
        text-align: center;
    }

    .h2 {
        font-size: 30px;
        text-align: center;
    }

    h2 {
        text-align: center;
    }

    nav {
        text-align: center;
    }

    nav ul {
        list-style: none;
        padding: 0;
    }

    nav ul li {
        display: inline;
        margin-right: 20px;
    }

    nav a {
        text-decoration: none;
        color: #fff;
        font-weight: bold;
    }

    nav a:hover {
        text-decoration: underline;
    }

    table {
        width: 80%;
        border-collapse: collapse;
        margin: 20px auto;
        margin-bottom: 20px;
    }

    th,
    td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
        background-color: rgb(255, 242, 224);
    }

    th {
        background-color: wheat;
    }

    a {
        color: #0066cc;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }

    .button-container {
        margin-top: 20px;
        text-align: center;
    }

    .button-container button {
        background-color: #4caf50;
        color: #fff;
        padding: 8px;
        cursor: pointer;
        border: none;
        border-radius: 4px;
        margin-right: 10px;
    }

    .button-container button:hover {
        background-color: #45a049;
    }

    .form-container {
        margin-top: 20px;
        text-align: center;
    }

    .form-container form {
        background-color: #fff;
        padding: 10px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        width: 60%;
        margin: 0 auto;
    }

    .form-container label {
        display: block;
        margin-bottom: 8px;
    }

    .form-container input {
        width: 80%;
        padding: 8px;
        margin-bottom: 12px;
        box-sizing: border-box;
        margin: 5px auto;
    }

    .form-container input[type="submit"] {
        background-color: #4caf50;
        color: #fff;
        cursor: pointer;
    }

    .form-container input[type="submit"]:hover {
        background-color: #45a049;
    }
    </style>
</head>

<body>
    <?php if (!empty($userDetails)): ?>
    <h2>Welcome, <?php echo $userDetails['username']; ?>!</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Mobile</th>
            <th>Email</th>
            <th>Passcode</th>
            <th>Password</th>
            <th>Validity Date</th>
            <th>From (Place)</th>
            <th>To (Destination)</th>
        </tr>
        <tr>
            <td><?php echo $userDetails['id']; ?></td>
            <td><?php echo $userDetails['username']; ?></td>
            <td><?php echo $userDetails['mobile']; ?></td>
            <td><?php echo $userDetails['email']; ?></td>
            <td><?php echo $userDetails['passcode']; ?></td>
            <td><?php echo $userDetails['password']; ?></td>
            <td><?php echo $userDetails['validity_date']; ?></td>
            <td><?php echo $userDetails['from_place']; ?></td>
            <td><?php echo $userDetails['to_destination']; ?></td>
        </tr>
    </table>

    <br>
    <center>
        <a href="logout.php" class="logout-button">Logout</a>
    </center>
    <?php else: ?>
    <p>User not logged in.</p>
    <?php endif; ?>
</body>

</html>