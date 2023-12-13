<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Handle user deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_user'])) {
    $user_id_to_delete = $_POST['delete_user'];
    
    // Delete user from the database
    $delete_query = "DELETE FROM users WHERE id = $user_id_to_delete";
    
    if ($conn->query($delete_query) === TRUE) {
        $user_deleted = true;
    } else {
        $delete_error = "Error deleting user: " . $conn->error;
    }
}

// Handle status update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $user_id_to_update = $_POST['update_status'];
    $new_status = $_POST['new_status'];

    // Update user status in the database
    $update_query = "UPDATE users SET status = '$new_status' WHERE id = $user_id_to_update";
    
    if ($conn->query($update_query) === TRUE) {
        $status_updated = true;
    } else {
        $update_error = "Error updating user status: " . $conn->error;
    }
}

// Fetch all users
$select_users_query = "SELECT * FROM users";
$users_result = $conn->query($select_users_query);

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Home</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include('admin_navbar.php'); ?>

    <div class="container mt-4">
        <h3>View Users</h3>

        <?php
        if (isset($user_deleted)) {
            echo "<p class='text-success'>User deleted successfully!</p>";
        } elseif (isset($delete_error)) {
            echo "<p class='text-danger'>$delete_error</p>";
        } elseif (isset($status_updated)) {
            echo "<p class='text-success'>User status updated successfully!</p>";
        } elseif (isset($update_error)) {
            echo "<p class='text-danger'>$update_error</p>";
        }
        ?>

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Age</th>
                    <th>Status</th>
                    <th>Action</th>
                    <th>Edit Status</th> <!-- Add new column for edit button -->
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = $users_result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['username']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['phone']}</td>
                            <td>{$row['age']}</td>
                            <td>{$row['status']}</td>
                            <td>
                                <form method='POST' action='{$_SERVER['PHP_SELF']}'>
                                    <input type='hidden' name='delete_user' value='{$row['id']}'>
                                    <button type='submit' class='btn btn-danger'>Delete</button>
                                </form>
                            </td>
                            <td>
                                <form method='POST' action='{$_SERVER['PHP_SELF']}'>
                                    <input type='hidden' name='update_status' value='{$row['id']}'>
                                    <select name='new_status' class='form-control'>
                                        <option value='pending'>Pending</option>
                                        <option value='approved'>Approved</option>
                                    </select>
                                    <button type='submit' class='btn btn-primary mt-2'>Update</button>
                                </form>
                            </td>
                        </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
