<div class="row">
<div class="col-lg-8 col-lg-offset-2" style="padding:0;">
<?php

include('includes/inc_connect.php');

$s_orders = "SELECT * FROM orders WHERE orderNum = :orderNum ";
$s_cust = "SELECT * FROM customer WHERE username = :username ";

$orders_result = $db->prepare($s_orders);
$cust_result = $db->prepare($s_cust);
$orders_result->bindParam(':orderNum',$_GET['id']);
$cust_result->bindParam(':username',$_GET['u']);

//Execute querys
$orders_result->execute();
$cust_result->execute();

while ($cust_row = $cust_result->fetch(PDO::FETCH_ASSOC))
{
	$username = $cust_row['username'];
	$fname = $cust_row['fname'];
	$lname = $cust_row['lname'];
	$street = $cust_row['street'];
	$stateInfo = $cust_row['city'].", ".$cust_row['state']." ".$cust_row['zip'];
}

while ($orders_row = $orders_result->fetch(PDO::FETCH_ASSOC))
{
	$orderNum = $orders_row['orderNum'];
	$orderDate = $orders_row['orderDate'];
	$productsArray = objectToArray(json_decode($orders_row['products_InOrder']));
	$totalPrice = $orders_row['totalPrice'];
	$status = $orders_row['status'];
}

if (isset($_POST['update'])) 
{
	if ($_POST['status'] == "1") 
	{
		echo "<div class='alert alert-danger' role='alert'>";
		echo "<h4 style='margin:0;'>Please update the order status.</h4>";
		echo "</div>";
	}
	else
	{
		$query = "UPDATE orders SET status = :status WHERE orderNum = :orderNum";

		$result = $db->prepare($query);
		$result->bindParam(':status',$_POST['status']);
		$result->bindParam(':orderNum',$orderNum);

		if ($result->execute()) 
		{
			echo "<div class='alert alert-success' role='alert'>";
			echo "<h4 style='margin:0;'>Order status has been successfully updated! <a href='?subpage=orders'>Go back to Orders View</a></h4>";
			echo "</div>";
		}
		else
		{
			echo "<div class='alert alert-danger' role='alert'>";
			echo "<h4 style='margin:0;'>There was an error updating the database.</h4>";
			echo "</div>";			
		}
	}
}

/*  This function converts stdObjects to an associative array. 
Taken from http://www.if-not-true-then-false.com/2009/php-tip-convert-stdclass-object-to-multidimensional-array-and-convert-multidimensional-array-to-stdclass-object/  */
function objectToArray($d) {
	if (is_object($d)) {
	// Gets the properties of the given object
	// with get_object_vars function
	$d = get_object_vars($d);
	}

	if (is_array($d)) {
	/*
	* Return array converted to object
	* Using __FUNCTION__ (Magic constant)
	* for recursive call
	*/
	return array_map(__FUNCTION__, $d);
	}
	else {
	// Return array
	return $d;
	}
}
?>

</div>
</div>

<div class="well" style="overflow:auto;">

	<div class="blue-box pull-left">
		<h2>Order #: <strong><?php echo $orderNum; ?></strong></h2>
		<h1 style="margin:0"><strong><?php echo $fname." ".$lname; ?></strong></h1>
		<h2 style="margin:0"><?php echo $street; ?></h2>
		<h2 style="margin-top:0"><?php echo $stateInfo; ?></h2>
	</div>

	<table class="table table-bordered table-striped" id="viewOrder">
		<thead>
			<tr>
				<th>ID</th>
				<th>Name</th>
				<th>Price</th>
				<th>Qty</th>
			</tr>
		</thead>
		<tbody>

<?php
foreach ($productsArray as $itemNum => $item) {
?>

		<tr>
			<td>NPC_<?php echo $item['ID']; ?></td>
			<td><?php echo $item['Name']; ?></td>
			<td><?php echo $item['Price']; ?></td>
			<td><?php echo $item['Quantity']; ?></td>
		</tr>

<?php
}
?>
		</tbody>
	</table>

	<div class="pull-left">
		<h3>Order Cost: $ <?php echo number_format($totalPrice,2); ?></h3>
	</div>

	<div class="pull-right">
		<form action="" method="post">

			<select class="form-control input-lg" name="status" style="margin-top:10px;" required>
				<option value="1">Status. Please select one.</option>
				<option value="Pending">Pending</option>
				<option value="Processing">Processing</option>
				<option value="Shipped">Shipped</option>
				<option value="Completed">Completed</option>
			</select>

			<button class="btn btn-lg btn-success" 
							style="margin-top: 10px;"
							type="submit"
							name="update"
							value="Update Status">Update Status
			</button>

		</form>
	</div>

</div>