<?php
include('config.php');

session_start();

if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    header("location: index.php");
    exit;
}

$user_id = $_SESSION["id"];

// Fetch user's budgets
$select_query = "SELECT * FROM budget WHERE UserID = ?";
$stmt = mysqli_prepare($conn, $select_query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Handle delete budget
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_query = "DELETE FROM budget WHERE BudgetID = ?";
    $delete_stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($delete_stmt, "i", $delete_id);

    if (mysqli_stmt_execute($delete_stmt)) {
        $delete_success = true;
    } else {
        $delete_error = "Error deleting budget: " . mysqli_error($conn);
    }

    mysqli_stmt_close($delete_stmt);
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>View Budgets</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <?php include('navbar.php'); ?>

    <div class="container mt-5">
        <?php
        if (isset($delete_success)) {
            echo "<p class='text-success'>Budget deleted successfully!</p>";
        } elseif (isset($delete_error)) {
            echo "<p class='text-danger'>$delete_error</p>";
        }
        ?>

        <h2>Your Budgets</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Budget Name</th>
                    <th>Budgeted Amount</th>
                    <th>Month</th>
                    <th>Year</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>{$row['BudgetName']}</td>
                            <td>{$row['BudgetedAmount']}</td>
                            <td>{$row['Month']}</td>
                            <td>{$row['Year']}</td>
                            <td>
                                <a href='edit_budget.php?id={$row['BudgetID']}' class='btn btn-warning'>Edit</a>
                                <a href='view_budgets.php?delete_id={$row['BudgetID']}' class='btn btn-danger' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                            </td>
                        </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
