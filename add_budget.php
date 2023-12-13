<?php
include('config.php');

session_start();

if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    header("location: index.php");
    exit;
}

$user_id = $_SESSION["id"];

// Handle budget submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_budget'])) {
    $budget_name = $_POST['budget_name'];
    $budgeted_amount = $_POST['budgeted_amount'];
    $month = $_POST['month'];
    $year = $_POST['year'];

    $insert_query = "INSERT INTO budget (UserID, BudgetName, BudgetedAmount, Month, Year) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $insert_query);
    mysqli_stmt_bind_param($stmt, "isdss", $user_id, $budget_name, $budgeted_amount, $month, $year);

    if (mysqli_stmt_execute($stmt)) {
        $budget_added = true;
    } else {
        $budget_error = "Error adding budget: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Budget</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <?php include('navbar.php'); ?>

    <div class="container mt-5">
        <?php
        if (isset($budget_added)) {
            echo "<p class='text-success'>Budget added successfully!</p>";
        } elseif (isset($budget_error)) {
            echo "<p class='text-danger'>$budget_error</p>";
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="budget_name">Budget Name:</label>
                <input type="text" class="form-control" id="budget_name" name="budget_name" required>
            </div>
            <div class="form-group">
                <label for="budgeted_amount">Budgeted Amount:</label>
                <input type="number" class="form-control" id="budgeted_amount" name="budgeted_amount" required>
            </div>
            <div class="form-group">
                <label for="month">Month:</label>
                <select class="form-control" id="month" name="month" required>
                    <option value="January">January</option>
                    <option value="February">February</option>
                    <option value="March">March</option>
                    <option value="April">April</option>
                    <option value="May">May</option>
                    <option value="June">June</option>
                    <option value="July">July</option>
                    <option value="August">August</option>
                    <option value="September">September</option>
                    <option value="October">October</option>
                    <option value="November">November</option>
                    <option value="December">December</option>
                </select>
            </div>

            <div class="form-group">
                <label for="year">Year:</label>
                <input type="number" class="form-control" id="year" name="year" required>
            </div>
            <button type="submit" class="btn btn-primary" name="submit_budget">Add Budget</button>
        <a class="btn btn-dark" href="view_budgets.php">View Budget</a>

        </form>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>