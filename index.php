<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "store";

$message = "";

$conn = new mysqli($servername, $username, $password, $dbname);

if($conn->connect_error){
    die("Connection Failed:" . $conn->connect_error);
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    // var_dump($_POST);
    $username = $_POST['username'];
    $password = $_POST['password'];

    if(empty($username) || empty($password)) {
        $message = "Please fill all empty fields!";
    } else {
        $check_Username = "SELECT * FROM users WHERE username = '$username'";
        $check = $conn->query($check_Username);

        if ($check->num_rows > 0) {
            $message = "Username is already taken!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";

            // $conn -> query($sql);

            if($conn -> query($sql) === TRUE) {
                $message = "Registered successfully!";
            } else {
                $message = "Error:" . $conn -> error;
            }
        }
    }
}

// echo "Connected Successfully!";
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
        <h1>Register</h1>
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

        <button type="submit">Submit</button>
    </form>
</body>
</html>