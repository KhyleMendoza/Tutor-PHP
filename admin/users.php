<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SESSION['role'] !== "admin") {
    header("Location: ../dashboard.php");
    exit();
}

$servername = "localhost";
$username = "root";
$dbpassword = "";
$dbname = "store";

$message = "";

$conn = new mysqli($servername, $username, $dbpassword, $dbname);

if ($conn-> connect_error) {
    die("Connection Failed" . $conn -> connect_error);
}

$search = $_GET['search'] ?? '';

if (!empty($search)) {
    $sql = "SELECT id, username, role FROM users WHERE username LIKE '%$search%'";
} else {
    $sql = "SELECT id, username, role FROM users";
}

$result = $conn->query($sql);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['delete'])) {
        $id = $_POST['id'];

        $sql = "DELETE FROM users WHERE id = '$id'";
        
        if ($conn->query($sql) === TRUE) {
            $message = "Successfully deleted ";
            header("Location: users.php");
            exit();
        } else {
            die("Delete failed:" . $conn->error);
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
    <nav>
        <a href="../dashboard.php">Dashboard</a>
        <a href="../logout.php">Logout</a>
    </nav>

    <h2>Users Table</h2>

    <form method="GET">
        <input type="text" name="search" placeholder="Search username..." value="<?php echo $search; ?>">
        <button type="submit">Search</button>
    </form>
    
    <table border="1" cellpadding="10">
        <tr>
            <td>ID</td>
            <td>Username</td>
            <td>Role</td>
            <td>Action</td>
        </tr>
        <?php 
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['username'] . "</td>";
                echo "<td>" . $row['role'] . "</td>";
                echo "<td>
                        <form method = 'POST' onsubmit = 'return confirm(\"Are you sure you want to delete this user?\")'>
                            <input type='hidden' name='id' value='" . $row['id'] . "'>
                            <button type='submit' name = 'delete'>Delete</button>
                        </form>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No users found</td></tr>";
        }
        ?>
    </table>
</body>
</html>