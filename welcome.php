<?php 
session_start();

if ($_SESSION['admin']) { //Check to see if user is Admin or Customer
?>
<!-- If Admin -->
<center><h1 style="font-size:3em;margin-top:-20px;">Welcome to the <span style="font-family:'Bangers',cursive;">NEWS PRINT</span> Admin Portal</h1></center>
<?php 
include('includes/inc_adminnav.php'); 
?>
<div class="well">
<?php
switch ($_GET['subpage']) {
	case 'add':
		include('includes/inc_add.php');
		break;	
	case 'inventory':
		include('includes/inc_inventory.php');
		break;
	case 'orders':
		include('includes/inc_orders.php');
		break;
	default:
		break;
}
?>
</div>
<?php } else { ?>
<!-- If Customer -->
<h1>Welcome to <span style="font-family:'Bangers',sans-serif;">NEWS PRINT!</span> Your galactic treasure trove of retro Comic Books!</h1>
<?php } ?>