<!DOCTYPE html>
<html>

<head>
  <title>Auto Part Direct</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous" />
  <link rel="stylesheet" type="text/css" href="css/account.css" />
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
    integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="css/home.css">
</head>

<body>
  <nav class="navbar navbar-expand-md bg-dark navbar-dark">
    <a class="navbar-brand" href="index.php?action=home">Auto Part Direct</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="collapsibleNavbar">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <a class="nav-link" href="?action=home">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="?action=products">Products</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active href=" ?action=account">My Account</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="?action=reg">Register</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="index.php?action=cart"> Cart <i class="fas fa-shopping-cart"></i>
            <span class="label label-primary"></span>
            <?php echo $_SESSION['cartqt']; ?>
            </span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="?action=contact">Contact Us</a>
        </li>
      </ul>
      <form class="navbar-form" action="." method="get" role="search">
        <div class="input-group">
          <input type="hidden" name="action" value="search">
          <input type="text" class="form-control" placeholder="Search" name="qry">
          <div class="input-group-btn">
            <button class="btn btn-default" type="submit" id="search"><i class="fas fa-search"></i></button>
          </div>
        </div>
      </form>
      <form class="form-inline my-2 my-lg-0" action="index.php">
        <input type="hidden" name="action" value="logout">
        <button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Logout</button>
      </form>
    </div>
  </nav>
  <div class="container-fluid">
    <h1 class="text-center">Orders:</h1>
    <?php foreach ($orders as $order): ?>
    <div class="row">
      <div class="col">
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
          </div>
          <div class="col text-right">
            Total price:
            $<?php echo $order['total_price']; ?>
            <br>
            <form action="." method="post">
              <input type="hidden" name="action" value="details">
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