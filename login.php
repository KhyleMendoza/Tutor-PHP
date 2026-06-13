<?php 
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "store";

$conn = new mysqli($servername, $username, $password, $dbname);

$message = "";

if ($conn -> connect_error) {
    die("Connection Failed:" . $conn -> connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $message = "Please fill all empty fields!";
    } else {
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = $conn -> query($sql);

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                $message = "Login Successful!";

                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                header("Location: dashboard.php");
                exit();
            } else {
                $message = "Incorrect password!";
            }
        } else {
            $message = "Username not found!";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="POST">
        <h2>Login</h2>
        <div>
            <label for="username">Username:</label>
            <input type="text" name="username">
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" name="password">
        </div>

        <?php if (!empty($message)) {
            echo "<p>$message</p>";
        } ?>

        <button type="submit">Login</button>
        <p>Don't have an account yet? <a href="index.php">click here to register!</a></p>
    </form>
</body>
</html>