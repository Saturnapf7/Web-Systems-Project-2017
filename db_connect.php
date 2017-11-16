<?php
  $db_hostname = 'localhost';
  $db_database = 'grizzle';
  $db_username = 'Tgrizzle';
  $db_password = 'Chattahoochee1!';
  
  try
  {
    $connection = new PDO("mysql:host=$db_hostname; dbname=$db_database",
                          $db_username, $db_password);
    
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }
  catch (PDOException $e)
  {
    echo json_encode("Connection Failed: " . $e->getMessage());
  }
?>
