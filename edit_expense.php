<?php
include('config.php');

session_start();

if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    header("location: index.php");
    exit;
}

$user_id = $_SESSION["id"];

// Check if expense ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("location: view_expenses.php");
    exit;
}

$expense_id = $_GET['id'];

// Fetch expense details
$select_expense_query = "SELECT expenses.ExpenseID, expenses.BudgetID, expenses.ExpenseCategory, expenses.ExpenseAmount, expenses.ExpenseDate, expenses.Description, budget.BudgetName, categories.name AS CategoryName
FROM expenses
JOIN budget ON expenses.BudgetID = budget.BudgetID
JOIN categories ON expenses.ExpenseCategory = categories.id
WHERE expenses.ExpenseID = ? AND expenses.UserID = ?";
$stmt_expense = mysqli_prepare($conn, $select_expense_query);
mysqli_stmt_bind_param($stmt_expense, "ii", $expense_id, $user_id);
mysqli_stmt_execute($stmt_expense);
$result_expense = mysqli_stmt_get_result($stmt_expense);

if ($row_expense = mysqli_fetch_assoc($result_expense)) {
    $budget_id = $row_expense['BudgetID'];
    $expense_category = $row_expense['ExpenseCategory'];
    $expense_amount = $row_expense['ExpenseAmount'];
    $expense_date = $row_expense['ExpenseDate'];
    $description = $row_expense['Description'];
} else {
    // Redirect if expense not found
    header("location: view_expenses.php");
    exit;
}

// Fetch user's budgets for dropdown
$select_budgets_query = "SELECT BudgetID, BudgetName FROM budget WHERE UserID = ?";
$stmt_budgets = mysqli_prepare($conn, $select_budgets_query);
mysqli_stmt_bind_param($stmt_budgets, "i", $user_id);
mysqli_stmt_execute($stmt_budgets);
$result_budgets = mysqli_stmt_get_result($stmt_budgets);

// Fetch expense categories for dropdown
$select_categories_query = "SELECT id, name FROM categories";
$result_categories = mysqli_query($conn, $select_categories_query);

// Handle expense update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_update'])) {
    $budget_id = $_POST['budget_id'];
    $expense_category = $_POST['expense_category'];
    $expense_amount = $_POST['expense_amount'];
    $expense_date = $_POST['expense_date'];
    $description = $_POST['description'];

    $update_query = "UPDATE expenses SET BudgetID = ?, ExpenseCategory = ?, ExpenseAmount = ?, ExpenseDate = ?, Description = ? WHERE ExpenseID = ? AND UserID = ?";
    $update_stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($update_stmt, "iidssii", $budget_id, $expense_category, $expense_amount, $expense_date, $description, $expense_id, $user_id);

    if (mysqli_stmt_execute($update_stmt)) {
        $update_success = true;
    } else {
        $update_error = "Error updating expense: " . mysqli_error($conn);
    }

    mysqli_stmt_close($update_stmt);
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Expense</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <?php include('navbar.php'); ?>

    <div class="container mt-5">
        <?php
        if (isset($update_success)) {
            echo "<p class='text-success'>Expense updated successfully!</p>";
        } elseif (isset($update_error)) {
            echo "<p class='text-danger'>$update_error</p>";
        }
        ?>

        <h2>Edit Expense</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=$expense_id"; ?>" method="post">
            <div class="form-group">
                <label for="budget_id">Select Budget:</label>
                <select class="form-control" id="budget_id" name="budget_id" required>
                    <?php
                    while ($row_budget = mysqli_fetch_assoc($result_budgets)) {
                        $selected = ($row_budget['BudgetID'] == $budget_id) ? "selected" : "";
                        echo "<option value='{$row_budget['BudgetID']}' $selected>{$row_budget['BudgetName']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="expense_category">Select Expense Category:</label>
                <select class="form-control" id="expense_category" name="expense_category" required>
                    <?php
                    while ($row_category = mysqli_fetch_assoc($result_categories)) {
                        $selected = ($row_category['id'] == $expense_category) ? "selected" : "";
                        echo "<option value='{$row_category['id']}' $selected>{$row_category['name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="expense_amount">Expense Amount:</label>
                <input type="number" class="form-control" id="expense_amount" name="expense_amount" value="<?php echo $expense_amount; ?>" required>
            </div>
            <div class="form-group">
                <label for="expense_date">Expense Date:</label>
                <input type="date" class="form-control" id="expense_date" name="expense_date" value="<?php echo $expense_date; ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description"><?php echo $description; ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary" name="submit_update">Update Expense</button>
        </form>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
