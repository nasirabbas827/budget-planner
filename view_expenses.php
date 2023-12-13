<?php
include('config.php');

session_start();

if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    header("location: index.php");
    exit;
}

$user_id = $_SESSION["id"];

// Fetch user's expenses
$select_expenses_query = "SELECT expenses.ExpenseID, expenses.BudgetID, expenses.ExpenseCategory, expenses.ExpenseAmount, expenses.ExpenseDate, expenses.Description, budget.BudgetName, categories.name AS CategoryName
FROM expenses
JOIN budget ON expenses.BudgetID = budget.BudgetID
JOIN categories ON expenses.ExpenseCategory = categories.id
WHERE expenses.UserID = ?";
$stmt_expenses = mysqli_prepare($conn, $select_expenses_query);
mysqli_stmt_bind_param($stmt_expenses, "i", $user_id);
mysqli_stmt_execute($stmt_expenses);
$result_expenses = mysqli_stmt_get_result($stmt_expenses);

// Handle delete expense
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_query = "DELETE FROM expenses WHERE ExpenseID = ? AND UserID = ?";
    $delete_stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($delete_stmt, "ii", $delete_id, $user_id);

    if (mysqli_stmt_execute($delete_stmt)) {
        $delete_success = true;
    } else {
        $delete_error = "Error deleting expense: " . mysqli_error($conn);
    }

    mysqli_stmt_close($delete_stmt);
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>View Expenses</title>
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
            echo "<p class='text-success'>Expense deleted successfully!</p>";
        } elseif (isset($delete_error)) {
            echo "<p class='text-danger'>$delete_error</p>";
        }
        ?>

        <h2>Your Expenses</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Budget</th>
                    <th>Category</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row_expense = mysqli_fetch_assoc($result_expenses)) {
                    echo "<tr>
                            <td>{$row_expense['BudgetName']}</td>
                            <td>{$row_expense['CategoryName']}</td>
                            <td>{$row_expense['ExpenseAmount']}</td>
                            <td>{$row_expense['ExpenseDate']}</td>
                            <td>{$row_expense['Description']}</td>
                            <td>
                                <a href='edit_expense.php?id={$row_expense['ExpenseID']}' class='btn btn-warning'>Edit</a>
                                <a href='view_expenses.php?delete_id={$row_expense['ExpenseID']}' class='btn btn-danger' onclick='return confirm(\"Are you sure?\")'>Delete</a>
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
