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

    $ssn= $_POST["ssn"]; 
    $Bname= $_POST["Bname"]; 
    $email= $_POST["email"]; 
    $address= $_POST["address"]; 
    $phone=$_POST["phone"];



if(strlen($ssn)==0 || strlen($Bname)==0 || strlen($email)==0 || strlen($address)==0 ||strlen($phone)==0)

{echo "</br></br>all detals required";}else{

if(strlen($ssn)==9){
    $ssn1 = substr($ssn, 0, 3);
    $ssn2 = substr($ssn, 3, 2);  
    $ssn3 = substr($ssn, 5, 4);
    $ssn= "{$ssn1}-{$ssn2}-{$ssn3}";
}
else{echo "SSN should contain 9 characters";
    exit();}
$query1 = "SELECT * FROM borrower where ssn='$ssn';";
$result = $connection->query($query1);
if ($result->num_rows > 0) {
    echo "</br></br>Borrower with same SSN exits in DataBase";
    exit();}
else {
$query2="INSERT INTO borrower(ssn, Bname, address, phone) 
VALUES ('$ssn','$Bname','$address','$phone');";
if ($connection->query($query2) === TRUE) {
    echo "</br></br>New record created successfully";
} else {
    echo "Error: " . $query2 . "<br>" . $connection->error;
}
}
}

?>
