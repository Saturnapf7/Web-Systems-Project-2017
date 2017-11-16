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
    
    $data = array($name, $email, $phone, $subject, $message);
    
    //Santize all form data
    foreach($data as $d)
    {
      $d = sanitizeInput($d);
    }
    
    //Validate form data
    if (empty($name))
    {
      $errors['errors']['name'] = "Name can't be empty.";
    }
    elseif (strlen($name) < 2)
    {
      $errors['errors']['name'] = "Names must be at least 2 characters in length.";
    }
    elseif (!validateName($name))
    {
      $errors['errors']['name'] = "Names can only consist of letters, spaces, periods and hyphens.";
    }
    if (empty($email))
    {
      $errors['errors']['email'] = "Email can't be empty.";
    }
    elseif(!validateEmail($email))
    {
      $errors['errors']['email'] = "Email address is invalid. Please enter a valid address e.g, 'example@email.com'.";
    }
    if (empty($phone))
    {
      $errors['errors']['phone'] = "Phone can't be empty";
    }
    elseif (!validatePhone($phone))
    {
      $errors['errors']['phone'] = "Phone number is invalid. Please enter a valid number e.g, '000-000-0000' or '0000000000'.";
    }
    else
    {
      //Set phone number equal to value containing digits only
      $phone = validatePhone($phone);
    }
    if (empty($subject))
    {
      $errors['errors']['subject'] = "Subject can't be empty.";
    }
    else if (strlen($subject) < 5)
    {
      $errors['errors']['subject'] = "Subject must be at least 5 characters in length.";
    }
    
    //Displays errors if array isn't empty
    if (count($errors['errors']) > 0)
    {
      echo json_encode($errors['errors']);
      exit;
    }
    
    //Insert the data into the database
    try
    {
      $query = $connection->prepare("INSERT INTO inquiry (name, email, phone, subject, message, date_submitted)
                                  VALUES (:name, :email, :phone, :subject, :message, NOW())");
    
      $query->execute(array("name" => $name, "email" => $email, "phone" => $phone, "subject" => $subject, "message" => $message));
      
      //Send email with inquiry information if data insert was successfully
      $to = $testEmail;
      $subject = "Customer Inquiry - $subject";
      $body= "<html><body>
      <label>Name:</label> $name<br>
      <label>Email:</label> $email<br>
      <label>Phone:</label> $phone<br>
      <label>Message: </label>$message
      </body></html>";
      
      $header = "MIME-Version: 1.0\r\n";
      $header .= "Content-Type: text/html; charset=UTF-8\r\n";
      $header .= "From: Grizzle Inquiry";
     
      
      // mail(to, subject, body, header)
      if (mail($to, $subject, $body, $header))
      {
        //echo json_encode("A confirmation email has been sent to <a href='#'>" . $testEmail . "</a>");
        echo json_encode("Thanks for contacting us!
                       A representative should be contacting you in the next 1 - 3 business days.");
      }
      else
      {
        echo json_encode("There was a problem sending the email");
      }
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
    $input = trim($input);
    $input = htmlspecialchars($input);
    $input = htmlentities($input);
    return $input;
  }
  
  /**
   *
   * validateName()
   *
   * Checks if name is in an acceptable format
   *
   */
   function validateName($name)
   {
     $nameRegex = "/^[a-zA-Z]+(([',. -][a-zA-Z ])?[a-zA-Z]*)*$/";
     if (preg_match($nameRegex, $name))
     {
       return true;
     }
     return false; 
   }
  
  /**
   * validateEmail()
   *
   * Checks if email address is in a valid format
   *
   */
  function validateEmail($email)
  {
    $emailRegex = "/^[^@\s]+@[^@\s]+\.[^@\s]+$/";
    if (preg_match($emailRegex, $email))
    {
      return true;
    }
    return false;
  }
  
  /**
   * validatePhone()
   *
   * Checks if phone number is in a valid format
   *
   */
  function validatePhone($phone)
  {
    $phoneRegex = "/(\d{3}\-\d{3}\-\d{4})$/"; // 000-000-0000
    if (preg_match($phoneRegex, $phone) || (strlen($phone) == 10 && ctype_digit($phone)))
    {
      return true;
    }
    else
    {
      return false;
    }  
  }
?>
