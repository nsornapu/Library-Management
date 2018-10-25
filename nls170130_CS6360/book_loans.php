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
// mysqlitest.php
  require_once 'login.php';
  $connection = new mysqli($db_hostname, $db_username, $db_password, $db_database);

  if ($connection->connect_error) die($connection->connect_error);



$cardId=$_REQUEST['search'];
$query1 = "SELECT * FROM borrower WHERE card_id='$cardId'";
$result = $connection->query($query1);
if (!$result) die ("Database access failed: " . $connection->error);

$rows = $result->num_rows;
  
for ($j = 0 ; $j < $rows ; ++$j)
{
  $result->data_seek($j);
  $row = $result->fetch_array(MYSQLI_NUM);
  $cardid=$row[0];
}
if ($rows > 0) {
    for ($j = 0 ; $j < $rows ; ++$j)
    {
        $result->data_seek($j);
        $row = $result->fetch_array(MYSQLI_NUM);
        $cardid=$row["0"];
    }
}
    else { 
           echo "Invalid Card ID";
         }

$isbn=$_SESSION["isbn"];


$query2 = "SELECT count(*) AS countBookLoans
           FROM book_loans 
           WHERE book_loans.card_id = $cardid AND date_in is NULL AND date_out IS NOT NULL";
  $result = $connection->query($query2);
  if (!$result) die ("Database access failed: " . $connection->error);

  $rows = $result->num_rows;
if ($rows > 0) {
    $row = $result->fetch_array(MYSQLI_NUM);
    $countBookLoans=$row[0];
    if($countBookLoans>2)
    	{ echo "</br></br><b>Maximum Book Limit Reached(Max 3 Books per CardID)<b>";
    		   }
    else
    {

    $query3 = "SELECT max(loan_id) AS maximumLoanID FROM book_loans";
    $result = $connection->query($query3);

	$maxLoanIDRow =  $result->fetch_array(MYSQLI_NUM);
	$maxLoanID = $maxLoanIDRow[0];
	$loanID = $maxLoanID + 1;
	$query4 = "INSERT INTO book_loans VALUES ($loanID,'$isbn',$cardid,NOW(),DATE_ADD(NOW(), INTERVAL 14 DAY),null)";
    $result = $connection->query($query4);
    $query5 = "UPDATE book SET availability='0' WHERE isbn13='$isbn'";
    echo $isbn;
    $result = $connection->query($query5);
	echo "<h4>Check Out Successful</h4>";
    }	


 } else {    echo "Invalid CardID"; }

?>

