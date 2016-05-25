<?php 
session_start();

if (!empty($_GET['user'])) {
  session_destroy();
  echo "<br><br><h1>You were successfully logged out.<br>You will be redirected in 5 seconds.</h1>";
  header("refresh:5; url=../index.php?page=home"); 
} 

if (isset($_GET['delete'])) {    
	try {
	  include('inc_connect.php');
	  $sql = "DELETE FROM customer WHERE username = :username";
	  $stmt = $db->prepare($sql);
	  $stmt->bindParam(':username',$_SESSION['username']);
	  if ($stmt->execute()) {
	    $success = TRUE;
		  session_destroy();
		  echo "<br><br><h1>Your account was successfully deleted.<br>You will be redirected in 5 seconds.</h1>";
		  header("refresh:5; url=../index.php?page=home"); 
	  }
	}//End try
	catch(PDOException $e) {
	  echo "<div class='alert alert-danger' role='alert'>";
	  echo "<h4 style='margin:0;'>Something went wrong. Please contact us.</h4>";
	  echo "Error: ".$e->getMessage();
	  echo "</div>";      
	}
}
?>