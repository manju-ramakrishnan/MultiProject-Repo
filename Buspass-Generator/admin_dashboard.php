<?php 
include "header.php";
?>
<?php
// Start the session to access session variables
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_email'])) {
    // Redirect to admin login page if not logged in
    header("Location: admin_login.php");
    exit();
}

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

// Handle change password form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['change_password'])) {
    $userId = $_POST['user_id'];
    $newPassword = $_POST['new_password'];

    // Update password in the database
    $stmt = $conn->prepare("UPDATE users_details SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $newPassword, $userId);
    $stmt->execute();
    $stmt->close();
}

// Handle renew validity form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['renew_validity'])) {
    $userId = $_POST['user_id'];
    $newValidityDate = $_POST['new_validity'];

    // Update validity date in the database
    $stmt = $conn->prepare("UPDATE users_details SET validity_date = ? WHERE id = ?");
    $stmt->bind_param("si", $newValidityDate, $userId);
    $stmt->execute();
    $stmt->close();
}

// Fetch all user details
$sql = "SELECT * FROM users_details";
$result = $conn->query($sql);

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
    body {
        font-family: Arial, sans-serif;
    }

    h2,
    h3 {
        color: #333;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
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
    }

    .button-container button {
        background-color: #4caf50;
        color: #fff;
        padding: 8px;
        cursor: pointer;
        border: none;
        border-radius: 4px;
    }

    .button-container button:hover {
        background-color: #45a049;
    }

    .form-container {
        margin-top: 20px;
    }

    .form-container form {
        background-color: #fff;
        padding: 10px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        width: 300px;
    }

    .form-container label {
        display: block;
        margin-bottom: 8px;
    }

    .form-container input {
        width: 100%;
        padding: 8px;
        margin-bottom: 12px;
        box-sizing: border-box;
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
    <h2>Welcome, Admin!</h2>
    <center>
        <h3>User Details</h3>
    </center>
    <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>Mobile</th>
                <th>Email</th>
                <th>Passcode</th>
                <th>Password</th>
                <th>Validity Date</th>
                <th>From (Place)</th>
                <th>To (Destination)</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['username']; ?></td>
                <td><?php echo $row['mobile']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['passcode']; ?></td>
                <td><?php echo $row['password']; ?></td>
                <td><?php echo $row['validity_date']; ?></td>
                <td><?php echo $row['from_place']; ?></td>
                <td><?php echo $row['to_destination']; ?></td>
                <td>
                    <div class="button-container">
                        <button onclick="changePassword(<?php echo $row['id']; ?>)">Change Password</button>
                        <button onclick="renewValidity(<?php echo $row['id']; ?>)">Renew Validity</button>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <center>
        <a href="admin_logout.php" class="logout-button">Logout</a>
    </center>
    <script>
    function changePassword(userId) {
        // Implement logic to change password for the user with the given userId
        var newPassword = prompt("Enter the new password:");
        if (newPassword !== null) {
            // Submit the form with new password
            submitForm(userId, newPassword, 'change_password');
        }
    }

    function renewValidity(userId) {
        // Implement logic to renew validity for the user with the given userId
        var newValidity = prompt("Enter the new validity date:");
        if (newValidity !== null) {
            // Submit the form with new validity date
            submitForm(userId, newValidity, 'renew_validity');
        }
    }

    function submitForm(userId, value, action) {
        var form = document.createElement("form");
        form.method = "post";
        form.action = "admin_dashboard.php";

        var inputUserId = document.createElement("input");
        inputUserId.type = "hidden";
        inputUserId.name = "user_id";
        inputUserId.value = userId;

        var inputValue = document.createElement("input");
        inputValue.type = "hidden";
        inputValue.name = action === 'change_password' ? "new_password" : "new_validity";
        inputValue.value = value;

        var inputAction = document.createElement("input");
        inputAction.type = "hidden";
        inputAction.name = action;

        form.appendChild(inputUserId);
        form.appendChild(inputValue);
        form.appendChild(inputAction);

        document.body.appendChild(form);
        form.submit();
    }
    </script>
</body>

</html>