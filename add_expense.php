<?php
include('config.php');

session_start();

if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    header("location: index.php");
    exit;
}

$user_id = $_SESSION["id"];

// Fetch user's budgets for dropdown
$select_budgets_query = "SELECT BudgetID, BudgetName FROM budget WHERE UserID = ?";
$stmt_budgets = mysqli_prepare($conn, $select_budgets_query);
mysqli_stmt_bind_param($stmt_budgets, "i", $user_id);
mysqli_stmt_execute($stmt_budgets);
$result_budgets = mysqli_stmt_get_result($stmt_budgets);

// Fetch expense categories for dropdown
$select_categories_query = "SELECT id, name FROM categories";
$result_categories = mysqli_query($conn, $select_categories_query);

// Handle expense submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_expense'])) {
    $budget_id = $_POST['budget_id'];
    $expense_category = $_POST['expense_category'];
    $expense_amount = $_POST['expense_amount'];
    $expense_date = $_POST['expense_date'];
    $description = $_POST['description'];

    $insert_query = "INSERT INTO expenses (UserID, BudgetID, ExpenseCategory, ExpenseAmount, ExpenseDate, Description) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $insert_query);
    mysqli_stmt_bind_param($stmt, "iiidss", $user_id, $budget_id, $expense_category, $expense_amount, $expense_date, $description);

    if (mysqli_stmt_execute($stmt)) {
        $expense_added = true;
    } else {
        $expense_error = "Error adding expense: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Expense</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <?php include('navbar.php'); ?>

    <div class="container mt-5">
        <?php
        if (isset($expense_added)) {
            echo "<p class='text-success'>Expense added successfully!</p>";
        } elseif (isset($expense_error)) {
            echo "<p class='text-danger'>$expense_error</p>";
        }
        ?>

        <h2>Add Expense</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="budget_id">Select Budget:</label>
                <select class="form-control" id="budget_id" name="budget_id" required>
                    <?php
                    while ($row_budget = mysqli_fetch_assoc($result_budgets)) {
                        echo "<option value='{$row_budget['BudgetID']}'>{$row_budget['BudgetName']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="expense_category">Select Expense Category:</label>
                <select class="form-control" id="expense_category" name="expense_category" required>
                    <?php
                    while ($row_category = mysqli_fetch_assoc($result_categories)) {
                        echo "<option value='{$row_category['id']}'>{$row_category['name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="expense_amount">Expense Amount:</label>
                <input type="number" class="form-control" id="expense_amount" name="expense_amount" required>
            </div>
            <div class="form-group">
                <label for="expense_date">Expense Date:</label>
                <input type="date" class="form-control" id="expense_date" name="expense_date" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description"></textarea>
            </div>
            <button type="submit" class="btn btn-primary" name="submit_expense">Add Expense</button>
        <a class="btn btn-dark" href="view_expenses.php">View Expenses</a>

        </form>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
