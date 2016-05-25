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

<center>
	<h3><a href="index.php?page=account">&lt;&lt;&nbsp;Back to My Account</a></h3>
</center>

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
		<h3>Order Status: <?php echo $status ?></h3>
	</div>

</div>