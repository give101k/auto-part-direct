<!DOCTYPE html>
<html>

<head>
  <title>Auto Parts Direct</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous" />
  <link rel="stylesheet" type="text/css" href="css/productdisplay.css" />
  <script type="text/javascript" src="javascript/products.js"></script>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
    integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous" />
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
          <a class="nav-link active" href="?action=products">Products</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="?action=account">My Account</a>
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
      <form class="form-inline my-2 my-lg-0" action=".">
        <input type="hidden" name="action" value="logout" />
        <button class="btn btn-outline-primary my-2 my-sm-0" type="submit">
          Logout
        </button>
      </form>
    </div>
  </nav>
  <main class="container">
    <div class="card-columns">
      <?php foreach ($products as $prod): ?>
      <div class="card">
        <img class="card-img-top" src="<?php echo $prod[
          'img_url'
        ]; ?>" alt="Card image" />
        <div class="card-body">
          <h4 class="card-title"><?php echo $prod['name']; ?></h4>
          <p class="card-text">
            Brand:
            <?php echo $prod['brand']; ?><br />Description:
            <br /><?php echo $prod['description']; ?><br />Price:
            <?php echo $prod['price']; ?>
            <br>
            Number in stock:
            <?php echo $prod['num_stock']; ?>
          </p>
          <form action="." method="post" id="product">
            <input type="hidden" name="action" value="buy" />
            <input type="hidden" name="product" value="<?php echo $prod[
              'part_number'
            ]; ?>" />
            <input type="submit" class="btn btn-primary" value="Buy" />
          </form>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </main>
</body>

</html>