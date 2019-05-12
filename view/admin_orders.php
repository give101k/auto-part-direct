<!DOCTYPE html>
<html>

<head>
  <title>Auto Part Direct</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous" />
  <link rel="stylesheet" type="text/css" href="css/admin_orders.css" />
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
    integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>

<body>
  <nav class="navbar navbar-expand-md bg-dark navbar-dark fixed-top">
    <a class="navbar-brand" href="index.php?action=adminhome">Auto Part Direct</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="collapsibleNavbar">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <a class="nav-link" href="?action=adminhome">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="?action=adminorders">Orders</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="?action=addproductpage">Add Products</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="?action=changestock">Change Stock</a>
        </li>
        <li class="nav-item">
          <a href="?action=regemp" class="nav-link">Register Employee</a>
        </li>
      </ul>
      <form class="form-inline my-2 my-lg-0" action="index.php">
        <?php if ($_SESSION['is_valid'] == true): ?>
        <input type="hidden" name="action" value="logout" />
        <button class="btn btn-outline-primary my-2 my-sm-0" type="submit">
          Logout
        </button>
        <?php else: ?>
        <input type="hidden" name="action" value="login" />
        <button class="btn btn-outline-primary my-2 my-sm-0" type="submit">
          Login
        </button>
        <?php endif; ?>
      </form>
    </div>
  </nav>
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-2 bar text-center">
        <a href="?action=account">Orders</a>
      </div>
      <div class="col-sm-10"></div>
    </div>
    <?php foreach ($orders as $order): ?>
    <div class="row">
      <div class="col-sm-2"></div>
      <div class="col-sm-10 ">
        <div class="row order">
          <div class="col">
            Order Date:
            <?php
            $date = new DateTime($order['date']);
            echo $date->format('m-d-Y h:i a T');
            ?>
            <br>
            Order Number:
            <?php echo $order['order_number']; ?>
          </div>
          <div class="col text-center">
            Status:
            <?php echo $order['status']; ?>
            <br>
            Client Username:
            <?php echo $order['cusername']; ?>
          </div>
          <div class="col text-right">
            Total price:
            $<?php echo $order['total_price']; ?>
            <br>
            <form action="." method="post">
              <input type="hidden" name="action" value="admindetails">
              <input type="hidden" name="odnum" value="<?php echo $order[
                'order_number'
              ]; ?>">
              <input type="submit" class="btn btn-primary" value="Details">
            </form>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</body>

</html>