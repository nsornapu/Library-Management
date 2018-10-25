
<html>
<head>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
 </head>  
<body>
<a href='index.html'>Go to home page</a>
</body>
</html>
<?php

require_once 'login.php';
$connection = new mysqli($db_hostname, $db_username, $db_password, $db_database);

if ($connection->connect_error) die($connection->connect_error);


$query=$_REQUEST['name'];
echo $query;

$query ="UPDATE fines SET paid='1' WHERE loan_id IN(SELECT loan_id FROM book_loans where card_id='$query' and date_in is NOT NULL);";

$result = $connection->query($query);
if (!$result) die ("Database access failed: " . $connection->error);

echo "<h4>Fine Paid Successful</h4>";



mysqli_close($connection);
?>
