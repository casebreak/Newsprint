<?php 
session_start();
include('includes/inc_connect.php');
$user = $_POST['user'];
$pass = md5($_POST['pass']);
if (isset($_POST['login'])) {
  $result = $db->prepare("SELECT * FROM customer WHERE username = :username");
  $result->bindParam(':username',$user);
  $result->execute();

  $check = $result->fetch(PDO::FETCH_ASSOC);
  if (($pass === $check['password']) && ($user === $check['username'])) {
    $_SESSION['user'] = $check['username'];
    $_SESSION['username'] = $check['username'];
    $_SESSION['password'] = $check['password'];
    $_SESSION['fname'] = $check['fname'];
    $_SESSION['lname'] = $check['lname'];
    $_SESSION['email'] = $check['email'];
    $_SESSION['street'] = $check['street'];
    $_SESSION['city'] = $check['city'];
    $_SESSION['state'] = $check['state'];
    $_SESSION['zip'] = $check['zip'];
    $_SESSION['phone'] = $check['phone'];
    $_SESSION['cardNum'] = $check['cardNum'];
    $_SESSION['expMonth'] = $check['expMonth'];
    $_SESSION['expYear'] = $check['expYear'];
    if ($check['adminFlag']) {
      $_SESSION['admin'] = TRUE;
    } else {
      $_SESSION['admin'] = FALSE;
    }
    $db = null;
    header("location: index.php?page=home&user=".$_SESSION['user']."");
  } elseif (($pass != $check['password']) && ($user === $check['username'])) {
    echo "<br><br><div class='alert alert-danger' role='alert'><h3>Invalid Password</h3></div>";
  } elseif (($pass === $check['password']) && ($user != $check['username'])) {
    echo "<br><br><div class='alert alert-danger' role='alert'><h3>Invalid Username</h3></div>";
  } else {
    echo "<br><br><div class='alert alert-danger' role='alert'><h3>Invalid Username and Password</h3></div>";
  }
}
?>
<!-- <div class="container" style="padding-top: 50px;"> -->
  <div class="row">
    <div class="logo-head col-lg-6 col-lg-offset-3">
      NEWS&nbsp;PRINT
    </div>
    <div class="logo-footer col-lg-6 col-lg-offset-3">
      <span style="padding-left: 10px;">
        <span style="color:#FF0000">L</span><span style="color:#FF7F00">o</span><span style="color:#FFFF00">g</span><span style="color:#39FF14">i</span><span style="color:#0000FF">n</span>
      </span>
    </div>
    <div class="col-lg-6 col-lg-offset-3" style="padding:0;">
      <form action="" method="POST">
        <div class="input-group input-group-lg">
          <span class="input-group-addon" id="sizing-addon1"><span class="glyphicon glyphicon-user"></span></span>
          <input type="text" name="user" class="form-control" placeholder="Username" required>
        </div>
        <div class="input-group input-group-lg">
          <span class="input-group-addon" id="sizing-addon1"><span class="glyphicon glyphicon-asterisk"></span></span>
          <input type="password" name="pass" class="form-control" placeholder="Password" required>
        </div>
        <button type="submit" name="login" class="btn btn-lg btn-success pull-right" style="margin-top:10px;">Login</button>
      </form>
      <br>
      <strong>Dont have an account? <a href="index.php?page=register">Create one here.</a></strong>
    </div>
  </div>
<!-- </div> -->