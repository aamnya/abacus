<?php
// Database connection
$servername = "localhost"; // Use localhost for local MySQL
$db_username = "root"; // Default username for XAMPP
$db_password = ""; // Default password is empty for XAMPP
$dbname = "abacus"; // Replace with your local database name

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $dateofbirth = $_POST['dateofbirth'];
    $auth = $_POST['auth']; // Expecting "1" for allowed, "0" for not allowed
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $level = $_POST['level']; // Expecting "junior" or "senior"

    // Check if the username or email already exists
    $stmt = $conn->prepare("SELECT * FROM students WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "Username or email already exists. Please choose another.";
    } else {
        // Prepare and bind for insertion
        $stmt = $conn->prepare("INSERT INTO students (name, username, phone, email, dateofbirth, auth, password, level) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssi", $name, $username, $phone, $email, $dateofbirth, $auth, $password, $level);

        // Execute the statement
        if ($stmt->execute()) {
            echo "New record created successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Sign Up</h2>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="tel" class="form-control" id="phone" name="phone" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="dateofbirth" class="form-label">Date of Birth</label>
            <input type="date" class="form-control" id="dateofbirth" name="dateofbirth" required>
        </div>
        <div class="mb-3">
            <label for="auth" class="form-label">Authentication (1 = Allowed, 0 = Not Allowed)</label>
            <input type="number" class="form-control" id="auth" name="auth" required min="0" max="1">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
            <label for="level" class="form-label">Level</label>
            <select class="form-select" id="level" name="level" required>
                <option value="junior">Junior</option>
                <option value="senior">Senior</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Sign Up</button>
    </form>
    <div class="mt-3">
        <a href="login.php">Already have an account? Log in here!</a>
    </div>
</div>
</body>
</html>
