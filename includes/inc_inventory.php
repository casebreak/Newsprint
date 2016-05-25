<?php
session_start();

if (isset($_SESSION['terms']['shop'])) {
	unset($_SESSION['terms']['shop']);
}
if (isset($_SESSION['terms']['orders'])) {
	unset($_SESSION['terms']['orders']);
}
if (isset($_POST['clearSearch'])) {
	unset($_SESSION['terms']['inventory']);
}

include('includes/inc_connect.php');
$stmt = "SELECT * FROM products";
if (isset($_POST['go']) && !empty($_POST['search'])) 
{
	$_SESSION['terms']['inventory'] = $_POST['search'];
	$stmt .= " WHERE name     LIKE '%".$_SESSION['terms']['inventory']."%' 
								OR id       LIKE '%".$_SESSION['terms']['inventory']."%' 
								OR price    LIKE '%".$_SESSION['terms']['inventory']."%'
								OR cond     LIKE '%".$_SESSION['terms']['inventory']."%' 
								OR category LIKE '%".$_SESSION['terms']['inventory']."%' ";
}

if (isset($_SESSION['terms']['inventory'])) {
	$stmt = "SELECT * FROM products 
					 WHERE name     LIKE '%".$_SESSION['terms']['inventory']."%' 
					 		OR id       LIKE '%".$_SESSION['terms']['inventory']."%' 
					 		OR price    LIKE '%".$_SESSION['terms']['inventory']."%' 
					 		OR cond     LIKE '%".$_SESSION['terms']['inventory']."%' 
					 		OR category LIKE '%".$_SESSION['terms']['inventory']."%' ";
}

if ($_GET['sort'] == 'name_up') 
{
  $stmt .= " ORDER BY name DESC";
} 
elseif ($_GET['sort'] == 'name_down') 
{
  $stmt .= " ORDER BY name ASC";
} 
elseif ($_GET['sort'] == 'id_up') 
{
  $stmt .= " ORDER BY id DESC";
} 
elseif ($_GET['sort'] == 'id_down') 
{
  $stmt .= " ORDER BY id ASC";
} 
elseif ($_GET['sort'] == 'price_up') 
{
  $stmt .= " ORDER BY price DESC";
} 
elseif ($_GET['sort'] == 'price_down') 
{
  $stmt .= " ORDER BY price ASC";
} 
elseif ($_GET['sort'] == 'qty_up') 
{
  $stmt .= " ORDER BY qty DESC";
} 
elseif ($_GET['sort'] == 'qty_down') 
{
  $stmt .= " ORDER BY qty ASC";
}

$result = $db->prepare($stmt);
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
<?php echo (isset($_SESSION['terms']['inventory'])) ? "<h3>Showing results for: <strong>".$_SESSION['terms']['inventory']."</strong><button class='btn btn-warning' type='submit' name='clearSearch' style='margin-left:30px;'>Clear Search Terms</button></h3>" : "" ?>
</center>
</form>
<br>
<table class="table table-bordered table-striped" id="invProducts">
	<thead>
		<tr>
			<th>Image<br>&nbsp;</th>

			<th width="10%">ID<br><small><a href="index.php?subpage=inventory&sort=id_up"><span class="glyphicon glyphicon-chevron-up"></span></a><a href="index.php?subpage=inventory&sort=id_down"><span class="glyphicon glyphicon-chevron-down"></span></a></small></th>

			<th>Name<br><small><a href="index.php?subpage=inventory&sort=name_up"><span class="glyphicon glyphicon-chevron-up"></span></a><a href="index.php?subpage=inventory&sort=name_down"><span class="glyphicon glyphicon-chevron-down"></span></a></small></th>

			<th>Price<br><small><a href="index.php?subpage=inventory&sort=price_up"><span class="glyphicon glyphicon-chevron-up"></span></a><a href="index.php?subpage=inventory&sort=price_down"><span class="glyphicon glyphicon-chevron-down"></span></a></small></th>

			<th>Description<br>&nbsp;</th>

			<th width="10%">Qty<br><small><a href="index.php?subpage=inventory&sort=qty_up"><span class="glyphicon glyphicon-chevron-up"></span></a><a href="index.php?subpage=inventory&sort=qty_down"><span class="glyphicon glyphicon-chevron-down"></span></a></small></th>

			<th>Change<br>&nbsp;</th>
		</tr>
	</thead>
	<tbody>


<?php
for ($i=0; $row = $result->fetch(); $i++) { 
?>
		<tr>
		<td><center><img width="70" src="upload/<?php echo $row['filename']; ?>"><br><a href="index.php?subpage=editimg&id=<?php echo $row['id']; ?>">Change</a></center></td>
		<td><?php echo $row['id']; ?></td>
		<td width="15%"><span style="font-weight: 600;"><?php echo $row['name']; ?></span></td>
		<td><strong>$</strong><?php echo number_format($row['price'],2); ?></td>
		<td style="text-align:left;"><?php echo $row['description']; ?><br><br><span style="font-weight: 600;">Condition: </span><?php echo $row['cond']; ?></td>
		<td><?php echo $row['qty']; ?></td>
		<td><a class="btn btn-warning" style="margin-bottom:5px;" href="index.php?subpage=editprod&id=<?php echo $row['id']; ?>">Edit</a><br><a class="btn btn-danger" href="includes/delete_prod.php?id=<?php echo $row['id']; ?>" >Delete</a></td>
		</tr>
<?php
} //End for loop
?>
	</tbody>
</table>