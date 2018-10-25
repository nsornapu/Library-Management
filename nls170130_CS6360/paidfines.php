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


$sql = "SELECT borrower.card_id,borrower.Bname,sum(fines.fine_amt) as totalfineamt,fines.paid
		FROM book_loans, fines, borrower 
		where book_loans.loan_id = fines.loan_id and book_loans.card_id = borrower.card_id  
		GROUP BY borrower.card_id  
		HAVING totalfineamt>0;";


$result = $connection->query($sql);

if (!$result) die ("Database access failed: " . $connection->error);

  $rows = $result->num_rows;
  if ($rows== 0) {
	  echo "No results"; 
  }
  for ($j = 0 ; $j < $rows ; ++$j)
  {
    $result->data_seek($j);
    $row = $result->fetch_array(MYSQLI_NUM);

    echo <<<_END
  <pre>
  <table border='1'>
	<tr><th>cardid</th>
	<th>fullname</th>	
	<th>totalfineamt</th>
	<th>1-paid/0-Not paid</th></tr>
	<tr><td>$row[0]</td>
	<td>$row[1]</td>
	<td>$row[2]</td>
	<td>$row[3]</td></tr>
	</table>
  </pre>
  
_END;
}


mysqli_close($connection);
?>