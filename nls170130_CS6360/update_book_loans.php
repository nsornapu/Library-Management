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
session_start();

require_once 'login.php';
$connection = new mysqli($db_hostname, $db_username, $db_password, $db_database);

if ($connection->connect_error) die($connection->connect_error);



$query=$_REQUEST['name'];
$loanid= $query;

$sql = "SELECT isbn FROM book_loans where loan_id='$loanid';";

$result = $connection->query($sql);
if (!$result) die ("Database access failed: " . $connection->error);

$rows = $result->num_rows;
  
for ($j = 0 ; $j < $rows ; ++$j)
{
    $result->data_seek($j); 
    $row = $result->fetch_array(MYSQLI_NUM);
    $isbn=$row[0];
    echo $isbn;
    $sql2= "UPDATE book_loans SET date_in = current_date() WHERE loan_id = $loanid";
    $sql3= "UPDATE book SET availability='1' WHERE isbn13='$isbn'";
    $connection->query($sql2);
    $connection->query($sql3);
    echo "<h4>Book Checked in successfully</h4>";
}
mysqli_close($connection);
?>