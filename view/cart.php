<!DOCTYPE html>
<html>

<head>
  <title>Auto Parts Direct</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous" />
  <link rel="stylesheet" type="text/css" href="css/cart.css" />
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
          <a class="nav-link " href="?action=home">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="?action=products">Products</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="?action=account">My Account</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="?action=reg">Register</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="index.php?action=cart"> Cart <i class="fas fa-shopping-cart"></i>
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
        <input type="hidden" name="action" value="logout" />
        <button class="btn btn-outline-primary my-2 my-sm-0" type="submit">
          Logout
        </button>
      </form>
    </div>
  </nav>
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-12">
        <div class="page-header">
          <h1>Your Shopping Cart</h1>
        </div>
      </div>
    </div>
    <?php if (isset($prod) == true):
      foreach ($prod as $pd): ?>
    <div class="row prod-row">
      <div class="col">
        <img class="cart-img" src="<?php echo $pd['img_url']; ?>" alt="" />
      </div>
      <div class="col text-center">
        <h3><?php echo $pd['brand'] . ' ' . $pd['name']; ?></h3>
      </div>
      <div class="col text-center">
        <h3><?php echo '$' . number_format($pd['price'], 2); ?></h3>
      </div>
      <div class="col">
        <form action="." method="post">
          <input type="hidden" name="action" value="cartupdate" />
          <input type="hidden" name="pnum" value="<?php echo $pd[
            'part_number'
          ]; ?>" />
          <input type="number" name="updateqt" class="form-control ml-auto "
            value="<?php echo $_SESSION['quantity'][$pd['part_number']]; ?>" />
          <input id="updatebt" type="submit" class="btn btn-primary ml-auto " value="update" />
        </form>
      </div>
      <div class="col text-center">
        <h3>
          <?php echo '$' .
            number_format(
              $pd['price'] * $_SESSION['quantity'][$pd['part_number']],
              2
            ); ?>
        </h3>
      </div>
    </div>
    <?php endforeach;
    elseif (isset($prod) == false): ?>
    <div>
      Cart is empty
    </div>
    <?php endif; ?>
    <div class="row">
      <div class="col-sm-9"></div>
      <div class="col-sm-3">
        <h3 align="right">
          Sub Total:
          <?php
          $subtotal = 0;
          if (isset($prod)) {
            foreach ($prod as $p) {
              $subtotal +=
                $p['price'] * $_SESSION['quantity'][$p['part_number']];
            }
          }
          echo '$' . number_format($subtotal, 2);
          ?>
        </h3>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-9"></div>
      <div class="col-sm-3">
        <h3 align="right">
          Tax:
          <?php
          $tax = $subtotal * 0.06;
          echo '$' . number_format($tax, 2);
          ?>
        </h3>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-9"></div>
      <div class="col-sm-3">
        <h3 align="right">
          Total:
          <?php
          $total = $subtotal + $tax;
          echo '$' . number_format($total, 2);
          ?>
        </h3>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-9"></div>
      <div class="col-sm-3">
        <form action="." method="post">
          <input type="hidden" name="action" value="checkout">
          <input type="submit" class="btn btn-primary" value="Checkout" id="checkout">
        </form>
      </div>
    </div>
  </div>
</body>

</html>