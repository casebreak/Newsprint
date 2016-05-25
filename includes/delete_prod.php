<?php
include('inc_connect.php');

if (isset($_GET['id'])) 
{
	$id = $_GET['id'];

	$query = $db->prepare("SELECT * FROM products WHERE id = :id");
	$query->bindParam(':id',$id);
	$query->execute();

	$imageFile = $query->fetch(PDO::FETCH_ASSOC);
	//$check = $query->fetch(PDO::FETCH_ASSOC);

	unlink("../upload/".$imageFile['filename']);

	$query1 = $db->prepare("DELETE FROM products WHERE id = :id");
	$query1->bindParam(':id',$id);
	$query1->execute();

	header("Location: ../index.php?subpage=inventory");
}
elseif (isset($_GET['order'])) 
{
	$orderNum = $_GET['order'];

	$query = $db->prepare("DELETE FROM orders WHERE orderNum = :orderNum");
	$query->bindParam(':orderNum',$orderNum);
	$query->execute();

	header("Location: ../index.php?subpage=orders");
}
?>