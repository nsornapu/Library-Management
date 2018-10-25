<html>
<head>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
 </head>  
<body>
<a href='index.html'>Go to home page</a></br></br></br>
<h3>Table1: Fine payment will be done only for books which are Checked IN</br></h3>
<h3>Table2: List of All Books which are Checked IN and Not Checked IN</br></h3>
</body>
</html>
<?php
  require_once 'login.php';
  $connection = new mysqli($db_hostname, $db_username, $db_password, $db_database);

  if ($connection->connect_error) die($connection->connect_error);

  

$q=$_REQUEST['search'];

$sql = "SELECT borrower.card_id,borrower.Bname,sum(fines.fine_amt) as total, book_loans.date_in, fines.paid 
        FROM book_loans, fines, borrower 
        where book_loans.loan_id = fines.loan_id and book_loans.card_id = borrower.card_id  and borrower.card_id='$q' and fines.paid=0 and book_loans.date_in is not null 
        GROUP BY borrower.card_id 
        HAVING total>0;";

$result = $connection->query($sql);
if (!$result) die ("Database access failed: " . $connection->error);

$rows = $result->num_rows;

if ($rows > 0) {
    $flag=0;

  echo "<table border='1'>
    <th>cardid</th>
    <th>fullname</th>  
    <th>totalfineamt</th></tr>";
    $rows = $result->num_rows;
  
    for ($j = 0 ; $j < $rows ; ++$j)
    {
      $result->data_seek($j);
      $row = $result->fetch_array(MYSQLI_NUM);
	if($row[4]=='0'){    
    	echo "<tr><td>$row[0]</td>
    			  <td>$row[1]</td>
    			  <td>$row[2]</td>
    			  <td>".'<form action="payment.php" method="POST"><input type="hidden" name= "name" value= '.$q.' ><button type="submit">Pay Fine</button></form>'."</td></tr>";
                 
    			}
    }
}

else {    echo "Check In Books to pay fine"; }	



$sql2 = "SELECT fines.loan_id,borrower.card_id,borrower.Bname,fines.fine_amt, book_loans.date_in, fines.paid 
        FROM book_loans, fines, borrower
        where book_loans.loan_id = fines.loan_id and book_loans.card_id = borrower.card_id  and borrower.card_id='$q' and fines.paid=0 
        HAVING fines.fine_amt>0;";

$result2 = $connection->query($sql2);
if (!$result2) die ("Database access failed: " . $connection->error);

$rows2 = $result2->num_rows;
if ($rows2 > 0) {

    $flag=0;
  
  echo "</br></br><table border='1'>
    <tr><th>Loanid</th>
    <th>cardid</th>
    <th>fullname</th>  
    <th>fineamt</th>
    <th>datein</th>
    <th>1-paid/0-Not paid</th></tr>";
    for ($j = 0 ; $j < $rows2 ; ++$j)
    {
      $result2->data_seek($j);
      $row2 = $result2->fetch_array(MYSQLI_NUM);
	
	if($row2[5]=='0'){    
    	echo "<tr><td>".$row2[0]."</td>
    	<td>".$row2[1]."</td>
    			  <td>".$row2[2]."</td>
    			  <td>".$row2[3]."</td><td>".$row2[4]."</td><td>".$row2[5]."</td></tr>";
                 
    			}


    }


}
mysqli_close($connection);
?>