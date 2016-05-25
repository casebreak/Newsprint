<?php 
session_start();
$errCount = 0;
$duplicate = FALSE;
$success = FALSE;
if (isset($_POST['register'])) {
  if (isset($_POST['username'])) {
    $username_value = $_POST['username'];
  }
  if (isset($_POST['password'])) {
    $password_value = $_POST['password'];
  }
  if (isset($_POST['email'])) {
    $email_value = $_POST['email'];
  }
  if (isset($_POST['fname'])) {
    $fname_value = $_POST['fname'];
  }
  if (isset($_POST['lname'])) {
    $lname_value = $_POST['lname'];
  }
  if (isset($_POST['street'])) {
    $street_value = $_POST['street'];
  }
  if (isset($_POST['city'])) {
    $city_value = $_POST['city'];
  }
  if (isset($_POST['state'])) {
    $state_value = $_POST['state'];
  }
  if (isset($_POST['zip'])) {
    $zip_value = $_POST['zip'];
  }
  if (isset($_POST['phone'])) {
    $phone_value = $_POST['phone'];
  }
  if (isset($_POST['cardNum'])) {
    $cardNum_value = $_POST['cardNum'];
  }
  if (isset($_POST['expMonth'])) {
    $expMonth_value = $_POST['expMonth'];
  }
  if (isset($_POST['expYear'])) {
    $expYear_value = $_POST['expYear'];
  }
  /* START VALIDATION */
  function clean($data) {
    $data = trim($data);
    $trans = array(" " => "", "-" => "", "(" => "", ")" => "");
    $data = strtr($data, $trans);
    return $data;    
  }
  function validUserName($data) {
    include('includes/inc_connect.php');
    global $errCount;
    global $duplicate;
    $data = clean($data);
    $pattern = "/^\w{4,25}$/";
    $result = $db->prepare("SELECT * FROM customer WHERE username = :username");
    $result->bindParam(':username',$data);
    $result->execute();
    $numRows = $result->fetchColumn();
    if ($numRows > 0) {
      $duplicate = TRUE;
      $errCount++;
    }
    if (!preg_match($pattern, $data)) {
      $errCount++;
    } else {
      return $data;
    }   
  }
  function validPassword($data) {
    global $errCount;
    $data = trim($data);
    $pattern = "/^.{6,21}$/";
    if (!preg_match($pattern, $data)) {
      $errCount++;
    } else {
      return md5($data);
    }   
  }
  function validEmail($data) {
    include('includes/inc_connect.php');
    global $errCount;
    global $duplicate;
    $data = trim($data);
    $pattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[_a-z0-9-]+(\.[_a-z0-9-]+)*(\.[a-z]{2,5})$/i";
    $result = $db->prepare("SELECT * FROM customer WHERE email = :email");
    $result->bindParam(':email',$data);
    $result->execute();
    $numRows = $result->fetchColumn();
    if ($numRows > 0) {
      $duplicate = TRUE;
      $errCount++;
    }
    if (!preg_match($pattern, $data)) {
      $errCount++;
    } else {
      return $data;
    }   
  }
  function validFName($data) {
    global $errCount;
    $data = trim($data);
    if (empty($data)) {
      $errCount++;
    } else {
      return $data;
    }
  }
  function validLName($data) {
    global $errCount;
    $data = trim($data);
    if (empty($data)) {
      $errCount++;
    } else {
      return $data;
    }
  }
  function validStreet($data) {
    global $errCount;
    if (empty($data)) {
      $errCount++;
    } else {
      return $data;
    }
  }
  function validCity($data) {
    global $errCount;
    if (empty($data)) {
      $errCount++;
    } else {
      return $data;
    }
  }
  function validState($data) {
    global $errCount;
    $data = clean($data);
    $pattern = "/^[A-Za-z]{2}$/";
    if (empty($data) || (!preg_match($pattern, $data))) {
      $errCount++;
    } else {
      return $data;
    }
  }
  function validZip($data) {
    global $errCount;
    $data = clean($data);
    $pattern = "/^\d{5}$/";
    if (empty($data) || (!is_numeric($data)) || (!preg_match($pattern, $data))) {
      $errCount++;
    } else {
      return $data;
    }
  }  
  function validPhone($data) {
    global $errCount;
    $data = clean($data);
    $pattern = "/^\d{10,11}$/";
    if (empty($data) || (!preg_match($pattern, $data))) {
      $errCount++;
    } else {
      return $data;
    }
  }  
  function validCardNum($data) {
    global $errCount;
    $data = clean($data);
    $pattern = "/^\d{16}$/";
    if (empty($data) || (!preg_match($pattern, $data))) {
      $errCount++;
    } else {
      return $data;
    }
  }  
  function validExpMonth($data) {
    global $errCount;
    $data = clean($data);
    $pattern = "/\d{2}/";
    if (empty($data) || (!preg_match($pattern, $data))) {
      $errCount++;
    } else {
      return $data;
    }
  }  
  function validExpYear($data) {
    global $errCount;
    $data = clean($data);
    $pattern = "/\d{4}/";
    if (empty($data) || (!preg_match($pattern, $data))) {
      $errCount++;
    } else {
      return $data;
    }
  }
  $_SESSION['username'] = validUserName($_POST['username']);
  $_SESSION['password'] = validPassword($_POST['password']);
  $_SESSION['email'] = validEmail($_POST['email']);
  $_SESSION['fname'] = validFName($_POST['fname']);
  $_SESSION['lname'] = validLName($_POST['lname']);
  $_SESSION['street'] = validStreet($_POST['street']);
  $_SESSION['city'] = validCity($_POST['city']);
  $_SESSION['state'] = validState($_POST['state']);
  $_SESSION['zip'] = validZip($_POST['zip']);
  $_SESSION['phone'] = validPhone($_POST['phone']);
  $_SESSION['cardNum'] = validCardNum($_POST['cardNum']);
  $_SESSION['expMonth'] = validExpMonth($_POST['expMonth']);
  $_SESSION['expYear'] = validExpYear($_POST['expYear']);
}
?>
<h1 class="content-header">Create an Account</h1>
<div class="row">
<div class="col-lg-8 col-lg-offset-2" style="padding:0;">
<?php
if (isset($_POST['register'])) {
  if ($errCount > 0) {
  	echo "<div class='alert alert-danger' role='alert'>";
  	echo "<h4 style='margin:0;'>Please fix your ".$errCount." error(s) in the form below.</h4>";
  	echo "</div>";
  } else {
    try {

      include('includes/inc_connect.php');
      $sql = "INSERT INTO customer (username,
                                    password,
                                    email,
                                    fname,
                                    lname,
                                    street,
                                    city,
                                    state,
                                    zip,
                                    phone,
                                    cardNum,
                                    expMonth,
                                    expYear)
                                    VALUES
                                   (:username,
                                    :password,
                                    :email,
                                    :fname,
                                    :lname,
                                    :street,
                                    :city,
                                    :state,
                                    :zip,
                                    :phone,
                                    :cardNum,
                                    :expMonth,
                                    :expYear)";
      $stmt = $db->prepare($sql);
      $stmt->bindParam(':username',$_SESSION['username']);
      $stmt->bindParam(':password',$_SESSION['password']);
      $stmt->bindParam(':email',$_SESSION['email']);
      $stmt->bindParam(':fname',$_SESSION['fname']);
      $stmt->bindParam(':lname',$_SESSION['lname']);
      $stmt->bindParam(':street',$_SESSION['street']);
      $stmt->bindParam(':city',$_SESSION['city']);
      $stmt->bindParam(':state',$_SESSION['state']);
      $stmt->bindParam(':zip',$_SESSION['zip']);
      $stmt->bindParam(':phone',$_SESSION['phone']);
      $stmt->bindParam(':cardNum',$_SESSION['cardNum']);
      $stmt->bindParam(':expMonth',$_SESSION['expMonth']);
      $stmt->bindParam(':expYear',$_SESSION['expYear']);
      if ($stmt->execute()) {
        $success = TRUE;
        echo "<div class='alert alert-success' role='alert' style='line-height:34px;overflow:auto;'>";
        echo "<h4 style='margin:0;'>Registration Successful!<a class='btn btn-primary pull-right' style='margin-top:-7px;' href='index.php?page=login'>Login</a></h4>";
        echo "</div>";
      }
    }//End try
    catch(PDOException $e) {
      echo "<div class='alert alert-danger' role='alert'>";
      echo "<h4 style='margin:0;'>Something went wrong. Please contact us.</h4>";
      echo "Error: ".$e->getMessage();
      echo "</div>";      
    }
  }//End if
}//End if
?>
</div>
</div>

<div class="row">
<div class="col-lg-8 col-lg-offset-2" style="padding:0;">

<!-- ******** START FORM ******** -->
  <form action="" method="POST">
  	<h3>Account Information</h3>
    <div class="input-group input-group-lg">
      <span class="input-group-addon" id="basic-addon3">Username</span>
      <input type="text" name="username" class="form-control" value="<?php echo $username_value ?>" required>
    </div>
      <?php 
      if (isset($_POST['register'])) {
        if (!validUserName($_POST['username'])) {
          echo "<p class='wrong bg-danger text-danger'>Please enter a valid Username (Minimum 4 characters)</p>"; 
        }
        if (($duplicate) && (!$success)) {
          echo "<p class='wrong bg-danger text-danger'>Username already taken. Please choose another Username.</p>"; 
        }
      }
      ?>
    <div class="input-group input-group-lg">
      <span class="input-group-addon" id="basic-addon3">Password</span>
      <input type="password" name="password" class="form-control" value="<?php echo $password_value ?>" required>
    </div>
      <?php if (isset($_POST['register']) && (!validPassword($_POST['password']))) echo "<p class='wrong bg-danger text-danger'>Please enter a valid Password (Minimum 6 characters)</p>"; ?>
    <div class="input-group input-group-lg">
      <span class="input-group-addon" id="basic-addon3">Email</span>
      <input type="text" name="email" class="form-control" placeholder="name@example.com" value="<?php echo $email_value ?>" required>
    </div>
      <?php 
      if (isset($_POST['register'])) {
        if (!validEmail($_POST['email'])) {
          echo "<p class='wrong bg-danger text-danger'>Please enter a valid Email (name@example.com)</p>"; 
        }
        if (($duplicate) && (!$success)) {
          echo "<p class='wrong bg-danger text-danger'>This email is already registered to a different account. Choose a different email or <a href='index.php?page=login'>Login</a></p>"; 
        }
      }
      ?>

    <h3>Billing Information</h3>
    <div class="input-group input-group-lg">
    	<span class="input-group-addon" id="basic-addon3">First Name</span>
      <input type="text" name="fname" class="form-control" value="<?php echo $fname_value; ?>" required>
      <span class="input-group-addon" id="basic-addon3">Last Name</span>
      <input type="text" name="lname" class="form-control" value="<?php echo $lname_value; ?>" required>
    </div>
    <div class="input-group input-group-lg">
      <span class="input-group-addon" id="basic-addon3">Street/Apt</span>
      <input type="text" name="street" class="form-control" value="<?php echo $street_value; ?>" required>
    </div>
    <div class="input-group input-group-lg">
      <span class="input-group-addon" id="basic-addon3">City</span>
      <input type="text" name="city" class="form-control" value="<?php echo $city_value; ?>" required>
      <span class="input-group-addon" id="basic-addon3">State</span>
      <input type="text" name="state" class="form-control" placeholder="2 character State code" value="<?php echo $state_value; ?>" required>
    </div>
    <div class="row">
      <div class="col-lg-6 col-lg-offset-6">
      <?php if (isset($_POST['register']) && (!validState($_POST['state']))) echo "<p class='wrong bg-danger text-danger'>Please enter your 2 character state code</p>"; ?>        
      </div>
    </div>
    <div class="input-group input-group-lg">
      <span class="input-group-addon" id="basic-addon3">Zip Code</span>
      <input type="text" name="zip" class="form-control" value="<?php echo $zip_value; ?>" required>
      <span class="input-group-addon" id="basic-addon3">Phone</span>
      <input type="text" name="phone" class="form-control" value="<?php echo $phone_value; ?>" required>
    </div>
    <div class="row">
      <div class="col-lg-6">
      <?php if (isset($_POST['register']) && (!validZip($_POST['zip']))) echo "<p class='wrong bg-danger text-danger'>Please enter a valid Zip Code (5 digits)</p>"; ?>        
      </div>
      <div class="col-lg-6">
      <?php if (isset($_POST['register']) && (!validPhone($_POST['phone']))) echo "<p class='wrong bg-danger text-danger'>Please enter a valid Phone Number (10-11 characters)</p>"; ?>        
      </div>
    </div>

    <!-- Card Information -->
		<div class="input-group input-group-lg">
  		<span class="input-group-addon" id="basic-addon3">Card Number</span>
      <input type="text" name="cardNum" class="form-control" value="<?php echo $cardNum_value; ?>" required>		
    </div>
    <?php if (isset($_POST['register']) && (!validCardNum($_POST['cardNum']))) echo "<p class='wrong bg-danger text-danger'>Please enter a valid 16-digit Card Number</p>"; ?> 
    <div class="input-group input-group-lg">
  		<span class="input-group-addon" id="basic-addon3">Exp Month</span>
      <input type="text" name="expMonth" class="form-control" placeholder="##" value="<?php echo $expMonth_value; ?>" required>

  		<span class="input-group-addon" id="basic-addon3">Exp Year</span>  
      <input type="text" name="expYear" class="form-control" placeholder="####" value="<?php echo $expYear_value; ?>" required>
    </div>
    <div class="row">
      <div class="col-lg-6">
      <?php if (isset($_POST['register']) && (!validExpMonth($_POST['expMonth']))) echo "<p class='wrong bg-danger text-danger'>Please enter your card's 2-digit expiration month</p>"; ?>        
      </div>
      <div class="col-lg-6">
      <?php if (isset($_POST['register']) && (!validExpYear($_POST['expYear']))) echo "<p class='wrong bg-danger text-danger'>Please enter your card's 4-digit expiration year</p>"; ?>        
      </div>
    </div>
    <button type="submit" name="register" class="btn btn-lg btn-success pull-right" style="margin-top:10px;">Register</button>
  </form>
</div>
</div>
