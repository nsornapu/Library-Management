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

$searchValue=$_REQUEST['search'];
$query = "SELECT book_loans.loan_id, book_loans.isbn, book_loans.card_id, book_loans.date_out, book_loans.due_date, book_loans.date_in, borrower.Bname 
			FROM book_loans, borrower 
			WHERE book_loans.card_id = borrower.card_id AND ((book_loans.card_id LIKE '%$searchValue%') OR (book_loans.isbn LIKE '%$searchValue%') OR (borrower.Bname LIKE '%$searchValue%'));";


$result = $connection->query($query);
if (!$result) die ("Database access failed: " . $connection->error);

$rows = $result->num_rows;

if ($rows > 0) {
	for ($j = 0 ; $j < $rows ; ++$j)
	{
  		$result->data_seek($j);
  		$row = $result->fetch_array(MYSQLI_NUM);
    // output data of each row
    echo "</br></br><h3>Books to be Checked in Based on given Search</h3>";
	echo "<table border='1'>
	 	<tr><th>Loanid</th>
		<th>ISBN</th>	
		<th>CardID(s)</th>
		<th>Borrower Name(s)</th>
		<th>Date Out(s)</th>
		<th>DueDate(s)</th></tr>";

    	$flag='0';
        if(is_null($row[5]))   {   
        	$flag='1';
        	
	 
        echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td>
        		  <td>".$row[2]."</td>
        		  <td>".$row[6]."</td>
        		  <td>".$row[3]."</td>
        		  <td>".$row[4]."</td>
        		  <td>".'<form action="update_book_loans.php" method="POST"><input type="hidden" name= "name" value= '.$row[0].' ><button type="submit">Check In</button></form>'."</td></tr></br></br>";}}
    
    if($flag=='0'){
		 echo "</br></br> No book selected";}}
 else {    echo "Results not found"; }





?>