  <h1 class='content-header'>Your Account</h1>
  <?php
  $_SESSION['user'] = $_SESSION['username'];
  if ($_GET['ud'] == true) {
    echo "<div class='alert alert-success' role='alert' style='line-height:34px;overflow:auto;'>";
    echo "<h4 style='margin:0;'>Your information has been updated successfully!</h4>";
    echo "</div>";
  }
  ?>
  <div class="well">
    <table class="table table-bordered table-striped">
      <tbody>
        <tr>
          <td><h3 class="user-info"><strong>Username: </strong></h3></td>
          <td><h3 class="user-info"><?php  echo $_SESSION['username']; ?></h3></td>
        </tr>
        <tr>
          <td><h3 class="user-info"><strong>Name: </strong></h3></td>
          <td><h3 class="user-info"><?php  echo $_SESSION['fname']." ".$_SESSION['lname']; ?></h3></td>
        </tr>
        <tr>
          <td><h3 class="user-info"><strong>Email: </strong></h3></td>
          <td><h3 class="user-info"><?php  echo $_SESSION['email']; ?></h3></td>
        </tr>
        <tr>
          <td><h3 class="user-info"><strong>Street Address: </strong></h3></td>
          <td><h3 class="user-info"><?php  echo $_SESSION['street']; ?></h3></td>
        </tr>        
        <tr>
          <td><h3 class="user-info"><strong>City/State/Zip: </strong></h3></td>
          <td><h3 class="user-info"><?php  echo $_SESSION['city'].", ".$_SESSION['state']." ".$_SESSION['zip']; ?></h3></td>
        </tr>
        <tr>
          <td><h3 class="user-info"><strong>Phone: </strong></h3></td>
          <td><h3 class="user-info"><?php  echo $_SESSION['phone']; ?></h3></td>
        </tr>
        <tr>
          <td><h3 class="user-info"><strong>Card Number: </strong></h3></td>
          <td><h3 class="user-info"><?php  echo $_SESSION['cardNum']; ?></h3></td>
        </tr>
        <tr>
          <td><h3 class="user-info"><strong>Expiration Month/Year: </strong></h3></td>
          <td><h3 class="user-info"><?php  echo $_SESSION['expMonth']."/".$_SESSION['expYear']; ?></h3></td>
        </tr>
      </tbody>
    </table>
  </div>
  <center>
    <a class="btn btn-warning btn-lg" href="index.php?page=edit"><h3 style="margin:0;">Edit Your Info</h3></a>
    <form method="post">
    <button type="submit" class="btn btn-lg btn-danger" name="delete" style="margin-top:5px;"><h3 style="margin:0;">Delete Account</h3></button>
    </form>
  </center>
  <br>


  <h1 class="content-header">Order History</h1>

  <table class="table table-bordered table-striped" id="custOrders">
  <thead>
    <tr>

      <th width="10%">OrderNum</th>

      <th>Order Date</th>      

      <th>Status</th>

      <th>View Order</th>
    </tr>
  </thead>
  <tbody>

<?php

include('includes/inc_connect.php');

//Get data from 'orders table'
$stmt = "SELECT * FROM orders WHERE customer = :customer";

$result = $db->prepare($stmt);
$result->bindParam(':customer',$_SESSION['user']);
//Execute query
$result->execute();

while ($row = $result->fetch()) 
{
?>

    <tr>
    <td><?php echo $row['orderNum']; ?></td>

    <td><?php echo date('Y-m-d',$row['orderDate']); ?></td>
    
    <td><?php echo $row['status']; ?></td>

    <td>
      <a class="btn btn-primary" 
           style="margin-right:5px;display:block;font-size:1.1em;" 
           href="index.php?page=custorder&id=<?php echo $row['orderNum']; ?>&u=<?php echo $row['customer'] ?>">View Order
      </a>
    </td>
    <!-- <a class="btn btn-danger" href="includes/delete_prod.php?id=<?php //echo $row['id']; ?>" >Delete</a> -->
    </tr>

<?php
} // End while
  
if (isset($_POST['delete'])) 
{
  echo "<script type='text/javascript'>";
  echo "if (confirm('This action will permanently delete your account. Are you sure you want to proceed?')) {window.location = 'includes/inc_logout.php?delete=true';} else {}";
  echo "</script>";
}
?>
  </tbody>
</table>