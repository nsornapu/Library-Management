<?php // mysqlitest.php
  require_once 'login.php';
  $connection = new mysqli($db_hostname, $db_username, $db_password, $db_database);

  if ($connection->connect_error) die($connection->connect_error);

  
  $query  = "select * from book";
  $result = $connection->query($query);
  if (!$result) die ("Database access failed: " . $connection->error);

  $rows = $result->num_rows;
  
  for ($j = 0 ; $j < $rows ; ++$j)
  {
    $result->data_seek($j);
    $row = $result->fetch_array(MYSQLI_NUM);

    echo <<<_END
  <pre>
    isbn13           <input type="text" name="isbn13" value = $row[0] disabled> 
    title          <input type="text" name="Title" value=   $row[1]>
    availabilty         <input type="text" name="availability" value=$row[2]>
  </pre>
  
_END;
}
?>