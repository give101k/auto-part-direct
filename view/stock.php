<!DOCTYPE html>
<html>

<head>
  <title>Auto Part Direct</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous" />
  <link rel="stylesheet" type="text/css" href="css/products.css" />
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
    integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="css/home.css">
</head>

<body>
  <nav class="navbar navbar-expand-md bg-dark navbar-dark">
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
          <a class="nav-link" href="?action=adminorders">Orders</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="?action=addproductpage">Add Products</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="?action=changestock">Change Stock</a>
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
  <div class="fluid-container">
    <div class="card-columns">
      <?php foreach ($products as $pd): ?>
      <div class="card">
        <img src="<?php echo $pd[
          'img_url'
        ]; ?>" alt="" class="card-img-top max">
        <div class="card-body">
          <h4 class="card-title"><?php echo $pd['name']; ?></h4>
          <p class="card-text">
            Brand:
            <?php echo $pd['brand']; ?>
            <br>
            Description:
            <?php echo $pd['description']; ?>
            <br>
            Price: $
            <?php echo $pd['price']; ?>
            <br>
            Number in stock:
            <?php echo $pd['num_stock']; ?>
          </p>
          <form action="." method="post">
            <input type="hidden" name="action" value="changeinv">
            <input type="hidden" name="product" value="<?php echo $pd[
              'part_number'
            ]; ?>">
            Number in stock:
            <input type="text" class="form-control" name="qt">
            <input type="submit" class="btn btn-primary" value="Change" id="change">
          </form>
          <form action="." method="post">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="product" value="<?php echo $pd[
              'part_number'
            ]; ?>">
            <input type="submit" class="btn btn-primary" value="Delete">
          </form>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</body>

</html>