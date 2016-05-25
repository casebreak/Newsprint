<?php 
session_start();
$errCount = 0;
$duplicate = FALSE;
$success = FALSE;
if (isset($_SESSION['user'])) {
  if (isset($_SESSION['username'])) {
    $username_value = $_SESSION['username'];
  }
  if (isset($_SESSION['password'])) {
    $password_value = $_SESSION['password'];
  }
  if (isset($_SESSION['email'])) {
    $email_value = $_SESSION['email'];
  }
  if (isset($_SESSION['fname'])) {
    $fname_value = $_SESSION['fname'];
  }
  if (isset($_SESSION['lname'])) {
    $lname_value = $_SESSION['lname'];
  }
  if (isset($_SESSION['street'])) {
    $street_value = $_SESSION['street'];
  }
  if (isset($_SESSION['city'])) {
    $city_value = $_SESSION['city'];
  }
  if (isset($_SESSION['state'])) {
    $state_value = $_SESSION['state'];
  }
  if (isset($_SESSION['zip'])) {
    $zip_value = $_SESSION['zip'];
  }
  if (isset($_SESSION['phone'])) {
    $phone_value = $_SESSION['phone'];
  }
  if (isset($_SESSION['cardNum'])) {
    $cardNum_value = $_SESSION['cardNum'];
  }
  if (isset($_SESSION['expMonth'])) {
    $expMonth_value = $_SESSION['expMonth'];
  }
  if (isset($_SESSION['expYear'])) {
    $expYear_value = $_SESSION['expYear'];
  }
  /* START VALIDATION */
  function clean($data) {
    $data = trim($data);
    $trans = array(" " => "", "-" => "", "(" => "", ")" => "");
    $data = strtr($data, $trans);
    return $data;    
  }
  function validEmail($data) {
    include('includes/inc_connect.php');
    global $errCount;
    global $duplicate;
    $data = trim($data);
    $pattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[_a-z0-9-]+(\.[_a-z0-9-]+)*(\.[a-z]{2,5})$/i";
/*    $result = $db->prepare("SELECT * FROM customer WHERE email = :email");
    $result->bindParam(':email',$data);
    $result->execute();
    $numRows = $result->fetchColumn();
    if ($numRows > 0) {
      $duplicate = TRUE;
      $errCount++;
    }*/
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
    if (empty($data)) {
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
    if (empty($data)) {
      $errCount++;
    } else {
      return $data;
    }
  }  
  function validExpYear($data) {
    global $errCount;
    $data = clean($data);
    if (empty($data)) {
      $errCount++;
    } else {
      return $data;
    }
  }
}
?>
<h1 class="content-header">Edit Your Account</h1>
<div class="row">
<div class="col-lg-8 col-lg-offset-2" style="padding:0;">
<?php
if (isset($_POST['register'])) {
  //$_SESSION['username'] = $_SESSION['user'];
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
  if ($errCount > 0) {
  	echo "<div class='alert alert-danger' role='alert'>";
  	echo "<h4 style='margin:0;'>Please fix your ".$errCount." error(s) in the form below.</h4>";
  	echo "</div>";
  } else {
    try {

      include('includes/inc_connect.php');
      $sql = "UPDATE customer SET email = :email,
                                  fname = :fname,
                                  lname = :lname,
                                  street = :street,
                                  city = :city,
                                  state = :state,
                                  zip = :zip,
                                  phone = :phone,
                                  cardNum = :cardNum,
                                  expMonth = :expMonth,
                                  expYear = :expYear 
                                  WHERE username = :username";
      $stmt = $db->prepare($sql);
      $stmt->bindParam(':username',$_SESSION['username']);
      //$stmt->bindParam(':password',$_SESSION['password']);
      $stmt->bindParam(':email',$_POST['email']);
      $stmt->bindParam(':fname',$_POST['fname']);
      $stmt->bindParam(':lname',$_POST['lname']);
      $stmt->bindParam(':street',$_POST['street']);
      $stmt->bindParam(':city',$_POST['city']);
      $stmt->bindParam(':state',$_POST['state']);
      $stmt->bindParam(':zip',$_POST['zip']);
      $stmt->bindParam(':phone',$_POST['phone']);
      $stmt->bindParam(':cardNum',$_POST['cardNum']);
      $stmt->bindParam(':expMonth',$_POST['expMonth']);
      $stmt->bindParam(':expYear',$_POST['expYear']);
      if ($stmt->execute()) {
        $success = TRUE;
        header('location: index.php?page=account&ud=true');
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
      <span class="input-group-addon" id="basic-addon3">Email</span>
      <input type="text" name="email" class="form-control" placeholder="name@example.com" value="<?php echo (isset($_POST['register']) ? $_POST['email'] : $email_value) ?>" required>
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
      <input type="text" name="fname" class="form-control" value="<?php echo (isset($_POST['register']) ? $_POST['fname'] : $fname_value) ?>" required>
      <span class="input-group-addon" id="basic-addon3">Last Name</span>
      <input type="text" name="lname" class="form-control" value="<?php echo (isset($_POST['register']) ? $_POST['lname'] : $lname_value) ?>" required>
    </div>
    <div class="input-group input-group-lg">
      <span class="input-group-addon" id="basic-addon3">Street/Apt</span>
      <input type="text" name="street" class="form-control" value="<?php echo (isset($_POST['register']) ? $_POST['street'] : $street_value) ?>" required>
    </div>
    <div class="input-group input-group-lg">
      <span class="input-group-addon" id="basic-addon3">City</span>
      <input type="text" name="city" class="form-control" value="<?php echo (isset($_POST['register']) ? $_POST['city'] : $city_value) ?>" required>
      <span class="input-group-addon" id="basic-addon3">State</span>
      <input type="text" name="state" class="form-control" placeholder="2 character State code" value="<?php echo (isset($_POST['register']) ? $_POST['state'] : $state_value) ?>" required>
    </div>
    <div class="row">
      <div class="col-lg-6 col-lg-offset-6">
      <?php if (isset($_POST['register']) && (!validState($_POST['state']))) echo "<p class='wrong bg-danger text-danger'>Please enter your 2 character state code</p>"; ?>        
      </div>
    </div>
    <div class="input-group input-group-lg">
      <span class="input-group-addon" id="basic-addon3">Zip Code</span>
      <input type="text" name="zip" class="form-control" value="<?php echo (isset($_POST['register']) ? $_POST['zip'] : $zip_value) ?>" required>
      <span class="input-group-addon" id="basic-addon3">Phone</span>
      <input type="text" name="phone" class="form-control" value="<?php echo (isset($_POST['register']) ? $_POST['phone'] : $phone_value) ?>" required>
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
      <input type="text" name="cardNum" class="form-control" value="<?php echo (isset($_POST['register']) ? $_POST['cardNum'] : $cardNum_value)?>" required>		
    </div>
    <?php if (isset($_POST['register']) && (!validCardNum($_POST['cardNum']))) echo "<p class='wrong bg-danger text-danger'>Please enter a valid 16-digit Card Number</p>"; ?> 
    <div class="input-group input-group-lg">
      <span class="input-group-addon" id="basic-addon3">Exp Month</span>
      <input type="text" name="expMonth" class="form-control" placeholder="##" value="<?php echo (isset($_POST['register']) ? $_POST['expMonth'] : $expMonth_value) ?>" required>

      <span class="input-group-addon" id="basic-addon3">Exp Year</span>  
      <input type="text" name="expYear" class="form-control" placeholder="####" value="<?php echo (isset($_POST['register']) ? $_POST['expYear'] : $expYear_value) ?>" required>
    </div>
    <div class="row">
      <div class="col-lg-6">
      <?php if (isset($_POST['register']) && (!validExpMonth($_POST['expMonth']))) echo "<p class='wrong bg-danger text-danger'>Please enter your card's 2-digit expiration month</p>"; ?>        
      </div>
      <div class="col-lg-6">
      <?php if (isset($_POST['register']) && (!validExpYear($_POST['expYear']))) echo "<p class='wrong bg-danger text-danger'>Please enter your card's 4-digit expiration year</p>"; ?>        
      </div>
    </div>
    <button type="submit" name="register" class="btn btn-lg btn-success pull-right" style="margin-top:10px;">Update Info</button>
  </form>
</div>
</div>
