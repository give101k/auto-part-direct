<!DOCTYPE html>
<html>

<head>
  <title>Auto Part Direct</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous" />
  <link rel="stylesheet" type="text/css" href="css/admin_orders.css" />
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
    integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous" />
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
    <div class="order">
      <div class="row">
        <div class="col-sm-12">
          <div class="row">
            <div class="col">
              Order Date:
              <?php
              $date = new DateTime($order[0]['date']);
              echo $date->format('h:i a T m-d-Y');
              ?>
              <br />
              Order Number:
              <?php echo $order[0]['order_number']; ?>
            </div>
            <div class="col text-center">
              Status:
              <?php echo $order[0]['status']; ?>
              <br />
              Client Username:
              <?php echo $order[0]['cusername']; ?>
            </div>
            <div class="col text-right">
              Total price: $<?php echo $order[0]['total_price']; ?>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col">
              First Name: <?php echo $info['First_name']; ?>
              <br>
              Last Name: <?php echo $info['Last_name']; ?>
            </div>
            <div class="col text-center">
              Address: <?php echo $info['address']; ?>
              <br>
              Town: <?php echo $info['town']; ?>
              <br>
              State: <?php echo $info['state']; ?>
            </div>
            <div class="col"></div>
          </div>
          <br />
          <?php foreach ($items as $item): ?>
          <hr />
          <div class="row">
            <div class="col">
              Item:
              <?php echo $item['name']; ?>
            </div>
            <div class="col text-center">
              Quantity:
              <?php echo $item['qt']; ?>
            </div>
            <div class="col text-right">
              Price:
              <?php echo $item['price']; ?>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-10"></div>
        <div class="col-sm-2">
          <form action="." method="post" id="update">
            <input type="hidden" name="action" value="updatestatus" />
            <input type="hidden" name="odnum" value="<?php echo $order[0][
              'order_number'
            ]; ?>" />
            <select name="status" id="" class="form-control">
              <option value="ordered">Ordered</option>
              <option value="shipped">Shipped</option>
            </select>
            <input type="submit" class="btn btn-primary" value="Update" />
          </form>
        </div>
      </div>
    </div>
  </div>
</body>

</html>