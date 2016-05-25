<?php

session_start();

if (isset($_SESSION['terms']['shop'])) {
	unset($_SESSION['terms']['shop']);
}
if (isset($_SESSION['terms']['inventory'])) {
	unset($_SESSION['terms']['inventory']);
}
if (isset($_POST['clearSearch'])) {
	unset($_SESSION['terms']['orders']);
}

include('includes/inc_connect.php');

//Get data from 'orders table'
$stmt = "SELECT * FROM orders ";

if (isset($_POST['go']) && !empty($_POST['search']))
{
	$_SESSION['terms'] = array();
	$_SESSION['terms']['orders'] = $_POST['search'];
	$stmt .= " WHERE orderNum  LIKE '%".$_SESSION['terms']['orders']."%'
								OR status LIKE '%".$_SESSION['terms']['orders']."%'
								OR customer  LIKE '%".$_SESSION['terms']['orders']."%'";						
}

if (isset($_SESSION['terms']['orders'])) {
	$stmt = "SELECT * FROM orders 
					 WHERE orderNum LIKE '%".$_SESSION['terms']['orders']."%' 
					 OR status      LIKE '%".$_SESSION['terms']['orders']."%' 
					 OR customer    LIKE '%".$_SESSION['terms']['orders']."%' ";
}

if ($_GET['sort'] == 'orderNum_up') 
{
  $stmt .= " ORDER BY orderNum DESC";
}
elseif ($_GET['sort'] == 'orderNum_down') 
{
  $stmt .= " ORDER BY orderNum ASC";
} 
elseif ($_GET['sort'] == 'orderDate_up') 
{
  $stmt .= " ORDER BY orderDate DESC";
} 
elseif ($_GET['sort'] == 'orderDate_down') 
{
  $stmt .= " ORDER BY orderDate ASC";
} 
elseif ($_GET['sort'] == 'status_up') 
{
    $stmt .= " ORDER BY status DESC";
} 
elseif ($_GET['sort'] == 'status_down') 
{
  $stmt .= " ORDER BY status ASC";
} 
elseif ($_GET['sort'] == 'customer_up') 
{
  $stmt .= " ORDER BY customer DESC";
} 
elseif ($_GET['sort'] == 'customer_down') 
{
  $stmt .= " ORDER BY customer ASC";
}

$result = $db->prepare($stmt);
//Execute query
$result->execute();

?>

<form action="" method="post">
<div class="row">
  <div class="col-lg-6 col-lg-offset-3">
    <div class="input-group input-group-lg"style="background-color:#333333;padding:10px;border-radius:10px;">
      <input type="text" class="form-control" name="search" placeholder="Search for..." >
      <span class="input-group-btn">
        <button class="btn btn-default" type="submit" name="go">Go!</button>
      </span>
    </div><!-- /input-group -->
  </div><!-- /.col-lg-6 -->
</div><!-- /.row -->

<center>
<?php 
echo (isset($_SESSION['terms']['orders'])) ? "<h3>Showing results for: <strong>".$_SESSION['terms']['orders']."</strong><button class='btn btn-warning' type='submit' name='clearSearch' style='margin-left:30px;'>Clear Search Terms</button></h3>" : "" 
?>
</center>
</form>

<br>

<table class="table table-bordered table-striped" id="orders">
	<thead>
		<tr>

			<th width="10%">OrderNum<br><small><a href="index.php?subpage=orders&sort=orderNum_up"><span class="glyphicon glyphicon-chevron-up"></span></a><a href="index.php?subpage=orders&sort=orderNum_down"><span class="glyphicon glyphicon-chevron-down"></span></a></small></th>

			<th>Order Date<br><small><a href="index.php?subpage=orders&sort=orderDate_up"><span class="glyphicon glyphicon-chevron-up"></span></a><a href="index.php?subpage=orders&sort=orderDate_down"><span class="glyphicon glyphicon-chevron-down"></span></a></small></th>

			<th>Customer<br><small><a href="index.php?subpage=orders&sort=customer_up"><span class="glyphicon glyphicon-chevron-up"></span></a><a href="index.php?subpage=orders&sort=customer_down"><span class="glyphicon glyphicon-chevron-down"></span></a></small></th>			

			<th>Status<br><small><a href="index.php?subpage=orders&sort=status_up"><span class="glyphicon glyphicon-chevron-up"></span></a><a href="index.php?subpage=orders&sort=status_down"><span class="glyphicon glyphicon-chevron-down"></span></a></small></th>
			
			<th>View/Modify<br>&nbsp;</th>
		</tr>
	</thead>
	<tbody>


<?php
for ($i=0; $row = $result->fetch(); $i++) { 
?>
		<tr>
		<td><?php echo $row['orderNum']; ?></td>

		<td><?php echo date('Y-m-d H:i:s',$row['orderDate']); ?></td>

		<td><?php echo $row['customer']; ?></td>
		
		<td><?php echo $row['status']; ?></td>

		<td>
			<a class="btn btn-primary" 
					 style="margin-right:5px;font-size:1.1em;" 
					 href="index.php?subpage=vieworder&id=<?php echo $row['orderNum']; ?>&u=<?php echo $row['customer'] ?>">View Order
			</a>
			<a class="btn btn-danger" style="margin-right:5px;font-size:1.1em;" href="includes/delete_prod.php?order=<?php echo $row['orderNum']; ?>" >Delete Order
			</a>
		</td>
		<!-- <a class="btn btn-danger" href="includes/delete_prod.php?id=<?php //echo $row['id']; ?>" >Delete</a> -->
		</tr>

<?php
} //End for loop
?>
	</tbody>
</table>