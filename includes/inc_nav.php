  <?php session_start(); global $totalItems;?>
  <div class="container-fluid" style="margin-bottom:0;padding:0;">
  <nav class="navbar navbar-inverse navbar-static-top">
    <div class="container">
      <div class="navbar-header">

        <!-- Hamburger menu button -->
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#dropdown-menu" aria-expanded="false">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>

        <!-- Navbar 'logo' or 'brand' -->
        <div class="navbar-brand">
          NEWS PRINT<br>
          <span style="font-family:'Luckiest Guy',cursive;font-size:60%;" class="hidden-xs">
            <span style="color:#FF0000">C</span><span style="color:#FF7F00">o</span><span style="color:#FFFF00">m</span><span style="color:#39FF14">i</span><span style="color:#00AFA8">c</span>&nbsp;&nbsp;<span style="color:#FF69B4">B</span><span style="color:#8F00FF">o</span><span style="color:#FF0000">o</span><span style="color:#FF7F00">k</span><span style="color:#FFF000">s</span>
          </span>
        </div>
      </div>

      <!-- Navbar links -->
      <!-- The list item class values are set depending on the page that gets called -->
      <div class="collapse navbar-collapse" id="dropdown-menu">
        <ul class="nav navbar-nav navbar-right">
          <!-- Home -->
          <li class="<?php echo ($_GET['page'] == 'home' ? 'active' : ' ')?>">
            <a href="index.php?page=home"><span class="glyphicon glyphicon-home"></span> Home</a>
          </li>
          <!-- Shop -->
          <li class="<?php echo ($_GET['page'] == 'shop' || isset($_GET['update']) || $_GET['page'] == 'cart' ? 'active' : ' ')?>">
            <a href="index.php?page=shop"><span class="glyphicon glyphicon-shopping-cart"></span> Shop</a>
          </li>
          <!-- Login / My Account -->
          <li class="<?php echo ($_GET['page'] == 'login' || $_GET['page'] == 'edit' || $_GET['page'] == 'account' ? 'active' : ' ')?>">
            <a href="<?php echo (isset($_SESSION['user']) ? 'index.php?page=account' : 'index.php?page=login') ?>"><span class="glyphicon glyphicon-user"></span> <?php echo (isset($_SESSION['user']) ? ' My Account' : ' Login') ?></a>
          </li>
        </ul>
      </div>
    </div>
  </nav><!-- Close navigation -->

  <!-- Navbar sub menu displaying user's logged in status and shopping cart info when available -->
  <div style="background-color:#FFFF00;margin-bottom:0;border-bottom:2px solid black;">
    <div class="container">
      <h3 style="margin-top:10px;">

        <!-- Only display the 'logged in as' and 'logout' labels if a user is signed in -->
        <?php  if (isset($_SESSION['user'])) { ?>
        Logged in as: <?php 
                      echo $_SESSION['user']; //Display username
                      include('includes/inc_connect.php');
                      $result = $db->prepare("SELECT * FROM customer WHERE username = :username");
                      $result->bindParam(':username',$_SESSION['username']);
                      $result->execute();
                      $check = $result->fetch(PDO::FETCH_ASSOC); 
                      if ($check['adminFlag']) { //Check if admin
                        echo " (Admin)";
                      }
                      ?>

        <!-- Display shopping cart status if shopping cart exists -->
        <?php if (isset($_SESSION['shoppingCart'])) { ?>

        <!-- View cart link. Also displays the number of itmes in the active cart -->
        <a href="index.php?page=cart" style="margin-left:38%;">View Cart</a>

        <?php } //End 'if' check to see if shopping cart exists ?>

        <span class="pull-right"><a style="color:black;" href="includes/inc_logout.php?user=<?php echo $_SESSION['user']; ?>">Logout</a></span>

        <?php } //End 'if' check to see if user is logged in ?>

      </h3>
    </div>
  </div>
  </div><!-- Close nav container -->