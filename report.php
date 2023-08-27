<?php
session_start();
if (!isset($_SESSION['id'])) {
    // Redirect to the login page if the user is not authenticated
    header("Location: login.php");
    exit();
}

$user_id = null;
// Retrieve the user ID from the URL query parameter
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}// Initialize variables for user data
$name = $img = $mail = $phn = $dob = "";

// Prepare and execute the SQL statement
$stmt = $conn->prepare("SELECT * FROM registration WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $name = $row["Name"];
        $img = $row["img"];
        $mail = $row["Email"];

    }
} else {
    echo "No records found for the provided user ID.";
}

$sql = "SELECT id, purpose, SUM(amount) AS total_amount FROM transaction WHERE id = $user_id GROUP BY id, purpose";
$result = $conn->query($sql);


if (!$result) {
    die("Query failed: " . $conn->error);
}

$expenseData = array();
while ($row = $result->fetch_assoc()) {
    $expenseData[] = $row;
}

$sqlEarnings = "SELECT SUM(amount) AS total_earnings FROM transaction WHERE id = ? AND type = 'earnings'";
$stmtEarnings = $conn->prepare($sqlEarnings);
$stmtEarnings->bind_param("i", $user_id);
$stmtEarnings->execute();
$resultEarnings = $stmtEarnings->get_result();
$rowEarnings = $resultEarnings->fetch_assoc();
$totalEarnings = $rowEarnings['total_earnings'];

// Retrieve total expenses for the user
$sqlExpenses = "SELECT SUM(amount) AS total_expenses FROM transaction WHERE id = ? AND type = 'expense'";
$stmtExpenses = $conn->prepare($sqlExpenses);
$stmtExpenses->bind_param("i", $user_id);
$stmtExpenses->execute();
$resultExpenses = $stmtExpenses->get_result();
$rowExpenses = $resultExpenses->fetch_assoc();
$totalExpenses = $rowExpenses['total_expenses'];

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
    <title>Report</title>

    <style>
.totals {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 30vh; /* Adjust the height as needed */
}

.totals-table {
    width: auto;
    border-collapse: collapse;
    border: 1px solid #ccc;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    margin: 20px;
}

.totals-table td {
    padding: 10px;
    border: 1px solid #ccc;
    text-align: center;
}

.totals-table h4 {
    margin: 0;
    font-size: 16px;
    font-weight: bold;
}

.totals-table p {
    margin: 0;
    font-size: 18px;
}

.charts-container {
    display: flex;
    /*justify-content: space-between;*/
    align-items: center;
    margin-top: 20px;
}

.chart {
    border: 0px solid #ccc;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    margin-right: 20px;
}
.generate-pdf-button {
    background-color: #007bff;
    color: #ffffff;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    font-weight: bold;
    transition: background-color 0.3s ease;
}

.generate-pdf-button:hover {
    background-color: #0056b3;
}

/* Optional: Center the button on the page */
.button-container {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}

    </style>
    
    <link href="profile-style.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <script type="text/javascript">
    google.charts.load('current', {'packages':['corechart', 'bar']});
    google.charts.setOnLoadCallback(drawCharts);

    function drawCharts() {
        drawPieChart();
        drawBarChart();
    }

    function drawPieChart() {
        var pieData = new google.visualization.DataTable();
        pieData.addColumn('string', 'purpose');
        pieData.addColumn('number', 'Total Amount');

        <?php
        foreach ($expenseData as $row) {
            echo "pieData.addRow(['" . $row['purpose'] . "', " . $row['total_amount'] . "]);";
        }
        ?>

        var pieOptions = {
            title: 'Purposes of User',
            is3D: true // You can adjust the chart appearance as needed
        };

        var pieChart = new google.visualization.PieChart(document.getElementById('piechart'));

        pieChart.draw(pieData, pieOptions);
    }

    function drawBarChart() {
        var data = google.visualization.arrayToDataTable([
            ['Category', 'Amount'],
            ['Total Earnings', <?php echo $totalEarnings; ?>],
            ['Total Expenses', <?php echo $totalExpenses; ?>],
            ['Total Balance', <?php echo $totalEarnings - $totalExpenses; ?>]
        ]);

        var options = {
            chart: {
                title: 'Financial Summary',
                subtitle: 'Earnings, Expenses, and Balance'
            },
            bars: 'vertical', // Vertical bars
            width: 800, // Width of the chart
            height: 500 // Height of the chart
        };

        var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
    }
</script>
    
</head>

<body>
   <input type="checkbox" id="menu-toggle">
    <div class="sidebar">
        <div class="side-header">
            <h3>K<span>oineze</span></h3>
        </div>
        
        <div class="side-content">
            <div class="profile">
                <div class="profile-img bg-img" style="background-image: url(<?php echo $img; ?>)"></div>
                <h4><?php echo htmlspecialchars($name); ?></h4>
            </div>

            <div class="side-menu">
                <ul>
                    <li>
                       <a href="dboard.php?id=<?php echo $user_id; ?>" >
                            <span class="las la-home"></span>
                            <small>Dashboard</small>
                        </a>
                    </li>
                    <li>
                       <a href="profile.php?id=<?php echo $user_id; ?>">
                            <span class="las la-user-alt"></span>
                            <small>Profile</small>
                        </a>
                    </li>
                    <li>
                       <a href=""class="active">
                            <span class="las la-envelope"></span>
                            <small>Report</small>
                        </a>
                    </li>
                    <li>
                       <a href="expense.php?id=<?php echo $user_id; ?>">
                            <span class="las la-clipboard-list"></span>
                            <small>Add Expense</small>
                        </a>
                    </li>
                    <li>
                       <a href="add.php?id=<?php echo $user_id; ?>">
                            <span class="las la-shopping-cart"></span>
                            <small>Add Cash</small>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>


    <div class="main-content">
    <header>
            <div class="header-content">
                <label for="menu-toggle">
                    <span class="las la-bars"></span>
                </label>
                <div><?php echo htmlspecialchars($name);?></div>
                <div class="header-menu">
                    <div class="user">
                    <div class="bg-img" style="background-image: url(<?php echo htmlspecialchars($img); ?>)"></div>
                        <span class="las la-power-off"></span>
                        <span><a href="logout.php">Logout</a></span>
                    </div>
                </div>
            </div>
        </header>

        <div class="totals">
    <table class="totals-table">
        <tr>
            <td>
                <h4>Total Earnings:</h4>
            </td>
            <td>
                <h4>Total Expenses:</h4>
            </td>
            <td>
                <h4>Total Balance:</h4>
            </td>
        </tr>
        <tr class="result-row">
            <td>
             <p><?php echo $totalEarnings; ?></p>
            </td>
            <td>
                <p><?php echo $totalExpenses; ?></p>
            </td>
            <td>
                <p><?php echo $totalEarnings - $totalExpenses; ?></p>
            </td>
        </tr>
    </table>
</div>

        
        
        

 
<div class="charts-container">
    <div id="piechart" class="chart" style="width: 600px; height: 500px;"></div>
    <div id="columnchart_material" class="chart" style="width: 450px; height: 500px;"></div>
</div>

<!--
<div class ="button-container">
<form method="post" action="generatepdf.php?id=<?php echo $user_id; ?>">
    <button type="submit" name="generatePdfButton" class = "generate-pdf-button">Generate PDF</button>
</form>
</div>
-->


 
</diV>
</body>
</html>



