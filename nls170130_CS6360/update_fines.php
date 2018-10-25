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



$query1="SELECT  loan_id, TIMESTAMPDIFF(Day, due_date, date_in)*0.25 AS fineAmount 
		 FROM book_loans 
		 WHERE date_in IS NOT NULL  and current_date > due_date; ";

$result = $connection->query($query1);
$flag='0';

if($result->num_rows>0){
	$rows = $result->num_rows;
	$flag='1';
	for ($j = 0 ; $j < $rows ; ++$j)
	{
	    $result->data_seek($j);
	    $row = $result->fetch_array(MYSQLI_NUM);
	    $loan=$row[0];
		$fine=$row[1];
		$query2="select loan_id from fines where loan_id=$loan and paid=0;";
		$result2 = $connection->query($query2);
		if($result->num_rows>0)	{
			$query3="update fines set fine_amt=$fine where loan_id=$loan and paid=0;";
			$result3 = $connection->query($query3);
		}
		else{
			$query4="insert into fines (fine_amt,loan_id) values('$fine','$loan');";
			$result4 = $connection->query($query4);		
		}
	}  
	
}

$query5="SELECT loan_id, TIMESTAMPDIFF(Day, due_date,current_date)*0.25 as fineAmount 
		 FROM book_loans 
		 where current_date > due_date and date_in is NULL;";
  $result5 = $connection->query($query5);
  if (!$result5) die ("Database access failed: " . $connection->error);
	if($result5->num_rows>0){
		$rows = $result5->num_rows;
		$flag='1';
		for ($j = 0 ; $j < $rows ; ++$j)
  		{
    		$result5->data_seek($j);
    		$row2 = $result5->fetch_array(MYSQLI_NUM);	
			$loan=$row2[0];
			$fine=$row2[1];
		
			$query6="select loan_id from fines where loan_id=$loan and paid=0;";
			$result6 = $connection->query($query6);
			if($result6->num_rows>0)	{
			
			$query7="update fines set fine_amt=$fine where loan_id=$loan and paid=0;";
			$result7 = $connection->query($query7);
			}
			else{
			
			$query8="insert into fines (fine_amt,loan_id) values('$fine','$loan');";
			$result8 = $connection->query($query8);			
			}

		}
	
	
}
if($flag=='1')
{echo "</br></br></br>Fines for each record have been updated";
}
else{
	echo "There is nothing to update";
}

mysqli_close($connection);
?>