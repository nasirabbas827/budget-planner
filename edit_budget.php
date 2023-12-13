<?php
include('config.php');

session_start();

if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    header("location: index.php");
    exit;
}

$user_id = $_SESSION["id"];

// Check if budget ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("location: view_budgets.php");
    exit;
}

$budget_id = $_GET['id'];

// Fetch budget details
$select_query = "SELECT * FROM budget WHERE BudgetID = ? AND UserID = ?";
$stmt = mysqli_prepare($conn, $select_query);
mysqli_stmt_bind_param($stmt, "ii", $budget_id, $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    $budget_name = $row['BudgetName'];
    $budgeted_amount = $row['BudgetedAmount'];
    $month = $row['Month'];
    $year = $row['Year'];
} else {
    // Redirect if budget not found
    header("location: view_budgets.php");
    exit;
}

// Handle budget update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_update'])) {
    $budget_name = $_POST['budget_name'];
    $budgeted_amount = $_POST['budgeted_amount'];
    $month = $_POST['month'];
    $year = $_POST['year'];

    $update_query = "UPDATE budget SET BudgetName = ?, BudgetedAmount = ?, Month = ?, Year = ? WHERE BudgetID = ?";
    $update_stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($update_stmt, "sdsii", $budget_name, $budgeted_amount, $month, $year, $budget_id);

    if (mysqli_stmt_execute($update_stmt)) {
        $update_success = true;
    } else {
        $update_error = "Error updating budget: " . mysqli_error($conn);
    }

    mysqli_stmt_close($update_stmt);
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Budget</title>
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
            echo "<p class='text-success'>Budget updated successfully!</p>";
        } elseif (isset($update_error)) {
            echo "<p class='text-danger'>$update_error</p>";
        }
        ?>

        <h2>Edit Budget</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=$budget_id"; ?>" method="post">
            <div class="form-group">
                <label for="budget_name">Budget Name:</label>
                <input type="text" class="form-control" id="budget_name" name="budget_name" value="<?php echo $budget_name; ?>" required>
            </div>
            <div class="form-group">
                <label for="budgeted_amount">Budgeted Amount:</label>
                <input type="number" class="form-control" id="budgeted_amount" name="budgeted_amount" value="<?php echo $budgeted_amount; ?>" required>
            </div>
            <div class="form-group">
                <label for="month">Month:</label>
                <select class="form-control" id="month" name="month" required>
                    <!-- Include selected attribute for the current month -->
                    <?php
                    $months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
                    foreach ($months as $m) {
                        $selected = ($m == $month) ? "selected" : "";
                        echo "<option value='$m' $selected>$m</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="year">Year:</label>
                <input type="number" class="form-control" id="year" name="year" value="<?php echo $year; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary" name="submit_update">Update Budget</button>
        </form>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
