<?php session_start(); ?>
<!doctype html>
<html>
<head>
<?php include('includes/inc_links.htm'); ?>
</head>
<body>
  <?php include('includes/inc_nav.php'); ?>
  <div class="container">
  <div class="half-border">
  <div class="well">
  <?php 
  if (isset($_GET['update'])) {
    include('includes/inc_cart.php');
  }
  else {
  //switch satement displays appropriate page when links are clicked
    switch ($_GET['page']) {
      case 'shop':
        include('shop.php');
        break;
      case 'login':
        include('login.php');
        break; 
      case 'account':
        include('includes/inc_account.php');
        break; 
      case 'register':
        include('includes/inc_register.php');
        break; 
      case 'edit':
        include('includes/inc_edit.php');
        break; 
      case 'cart':
        include('includes/inc_cart.php');
        break;   
      case 'custorder':
        include('includes/inc_custorder.php');
        break; 
      default:
        if ($_SESSION['admin']) { //Check to see if user is Admin or Customer
        ?>
        <!-- If Admin -->
        <center><h1 style="font-size:3em;">Welcome to the <span style="font-family:'Bangers',cursive;">NEWS PRINT</span> Admin Portal</h1></center>
        <?php 
        //Generate admin inner navigation bar
        include('includes/inc_adminnav.php');
        switch ($_REQUEST['subpage']) {
          //Add new product
          case 'add':
            include('includes/inc_add.php');
            break; 
          //Edit product 
          case 'editprod':
            include('includes/edit_prod.php');
            break;
          //Edit image and image name    
          case 'editimg':
            include('includes/edit_img.php');
            break; 
          //Manage inventory
          case 'inventory':
            include('includes/inc_inventory.php');
            break;
          //Manage customer orders
          case 'orders':
            include('includes/inc_orders.php');
            break;
          case 'vieworder':
            include('includes/inc_vieworder.php');
            break;
          default:
            break;
        }
        ?>
</div>
<?php } else { ?>

<!-- If Customer -->
<h1>Welcome to <span style="font-family:'Bangers',sans-serif;">NEWS PRINT!</span> Your galactic treasure trove of the World's greatest Comic Books, new and old!</h1>

<h2>We sell all sorts of comics, new and old. You're sure to find something you like, and if we don't have it, we'll get it for you. To get started, please create an account under our Login section. Or, feel free to browse our catalog of products.</h2>
<?php } // End else

        break;
    } //End master switch
  }//End main if/else
  ?>
  </div>
  </div>
</body>
</html>