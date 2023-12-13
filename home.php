<?php
include('config.php');

session_start();

if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    header("location: index.php");
    exit;
}

$user_id = $_SESSION["id"];

// Fetch user's budgets
$select_budgets_query = "SELECT * FROM budget WHERE UserID = ?";
$stmt_budgets = mysqli_prepare($conn, $select_budgets_query);
mysqli_stmt_bind_param($stmt_budgets, "i", $user_id);
mysqli_stmt_execute($stmt_budgets);
$result_budgets = mysqli_stmt_get_result($stmt_budgets);

// Fetch total expenses
$select_total_expenses_query = "SELECT SUM(ExpenseAmount) AS TotalExpenses FROM expenses WHERE UserID = ?";
$stmt_total_expenses = mysqli_prepare($conn, $select_total_expenses_query);
mysqli_stmt_bind_param($stmt_total_expenses, "i", $user_id);
mysqli_stmt_execute($stmt_total_expenses);
$result_total_expenses = mysqli_stmt_get_result($stmt_total_expenses);
$total_expenses_row = mysqli_fetch_assoc($result_total_expenses);
$total_expenses = $total_expenses_row['TotalExpenses'];

// Calculate total saved amount
$total_saved_amount = 0;
while ($row_budget = mysqli_fetch_assoc($result_budgets)) {
    $total_saved_amount += $row_budget['BudgetedAmount'];
}
$total_saved_amount -= $total_expenses;

// Close prepared statements
mysqli_stmt_close($stmt_budgets);
mysqli_stmt_close($stmt_total_expenses);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Custom styles for charts */
        .card {
            margin-bottom: 20px;
        }

        canvas {
            max-width: 100%; /* Make sure the chart is responsive */
        }

        /* Styling for budget chart */
        #budgetChart {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Styling for expenses chart */
        #expensesChart {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
    <link rel="stylesheet" href="./css/style.css">

</head>

<body>
    <?php include('navbar.php'); ?>

    <div class="container mt-5">
        <h2 class="mb-4">Dashboard</h2>

        <div class="row">
            <div class="col-md-6">
                <!-- Graphical representation of budgeted amount as a line graph -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Budgeted Amount</h5>
                        <canvas id="budgetChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <!-- Graphical representation of total expenses as a bar chart -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Expenses</h5>
                        <canvas id="expensesChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Display total saved amount -->
        <p class="mt-4">Total Saved Amount: $<?php echo $total_saved_amount; ?></p>
    </div>

    <script>
        // Data for budget chart
        var budgetData = {
            labels: [<?php while ($row_budget = mysqli_fetch_assoc($result_budgets)) echo "'" . $row_budget['BudgetName'] . "',"; ?>],
            datasets: [{
                label: 'Budgeted Amount',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1,
                fill: false, // Do not fill area under the line
                data: [<?php mysqli_data_seek($result_budgets, 0); while ($row_budget = mysqli_fetch_assoc($result_budgets)) echo $row_budget['BudgetedAmount'] . ","; ?>],
            }]
        };

        // Data for expenses chart
        var expensesData = {
            labels: ['Total Expenses'],
            datasets: [{
                label: 'Total Expenses',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1,
                data: [<?php echo $total_expenses; ?>],
            }]
        };

        // Set up budget chart
        var budgetCtx = document.getElementById('budgetChart').getContext('2d');
        var budgetChart = new Chart(budgetCtx, {
            type: 'line', // Use 'line' type for a line graph
            data: budgetData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Set up expenses chart
        var expensesCtx = document.getElementById('expensesChart').getContext('2d');
        var expensesChart = new Chart(expensesCtx, {
            type: 'bar',
            data: expensesData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

    <!-- Include Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.8/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>

