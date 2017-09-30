<?php
  require_once("db_connect.php");
  
  //Check if form was submitted
  if ($_SERVER['REQUEST_METHOD'] == "POST")
  {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $testEmail = "alexdevontetaylor@gmail.com";
    
    //Intialize errrors array
    $errors['errors'] = array();
  
    if (empty($name))
    {
      $errors['errors']['name'] = "Name can't be empty";
    }
    if (empty($email))
    {
      $errors['errors']['email'] = "Email can't be empty";
    }
    if (empty($phone))
    {
      $errors['errors']['phone'] = "Phone can't be empty";
    }
    if (empty($subject))
    {
      $errors['errors']['subject'] = "Subject can't be empty";
    }
    
    if (count($errors['errors']) > 0)
    {
      echo json_encode($errors['errors']);
      exit;
    }
    
    $data = array($name, $email, $phone, $subject, $message);
    
    //Santize all form data
    foreach($data as $d)
    {
      sanitizeInput($d);
    }
    
    //Validate form data
    
    //Insert the data into the database
    try
    {
      $query = $connection->prepare("INSERT INTO inquiry (name, email, phone, subject, message, date_submitted)
                                  VALUES (:name, :email, :phone, :subject, :message, NOW())");
    
      $query->execute(array("name" => $name, "email" => $email, "phone" => $phone, "subject" => $subject, "message" => $message));
      echo "SUCCESS";
      
      //Send email with inquiry information if data insert was successfully
     /* $to = $testEmail;
      $subject = "Customer Inquiry - $subject";
      $body = "Name: $name<br>" .
              "Email: $email<br>" .
              "Phone: $phone<br><br><br>" . $message;
      $header = "From: Grizzle Inquiry";
      
      // mail(to, subject, body, header)
      if (mail($to, $subject, $body, $header))
      {
        echo "A confirmation email has been sent to <a href='#'>" . $testEmail . "</a>";
      }
      else
      {
        echo "There was a problem sending the email";
      }*/
    }
    catch (PDOException $e)
    {
      echo $e->getMessage();
    }
  }
  /**
   * sanitizeInput()
   *
   * Cleans and removes spaces and uncessary characters from variable.
   *
   */
  function sanitizeInput($input)
  {
    $input = strip_tags($input);
    $input = htmlspecialchars($input);
    $input = htmlentities($input);
    return $input;
  }
  
  function validateInput($input)
  {
    
  }
  
  function validateEmail($email)
  {
    if (validateInput($email))
    {
      
    }
  }
  
  function validatePhone($phone)
  {
    if (validateInput($phone))
    {
      
    }
  }
?>