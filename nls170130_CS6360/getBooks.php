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
$query = "SELECT book.isbn13,book.title, authors.name, book.availability FROM book, book_authors, authors 
           where book.isbn13 = book_authors.isbn and book_authors.author_id = authors.author_id and 
           ((book.title like '%$searchValue%') or (authors.name like '%$searchValue%') or (book.isbn13 like '%$searchValue%'))";


$result = $connection->query($query);
if (!$result) die ("Database access failed: " . $connection->error);

$rows = $result->num_rows;
if ($rows>0){
for ($j = 0 ; $j < $rows ; ++$j)
{
  $result->data_seek($j);
  $row = $result->fetch_array(MYSQLI_NUM);
    // output data of each row
    
    echo <<<_END
   <pre>
    <table border='1'>
 	<tr><th>ISBN</th>
	<th>Title</th>	
	<th>Author(s)</th>
	<th>Avalability</th></tr>
    <tr><td>$row[0]</td>
    <td>$row[1]</td>
    <td>$row[2]</td>
    <td>$row[3]</td>
    <td><form action="Book_Check_Out.php" method="POST">
    <input type="hidden" name= "name" value= $row[0]>
    <button type="submit">Check Out</button></form></td></tr>
    </pre>   
_END;
}
}
?>