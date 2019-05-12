<?php
session_start();
require_once "model/database.php";
require_once "model/login_func.php";
require_once 'model/data_func.php';

$login_message = "";

if (!isset($_SESSION['quantity'])) {
  $_SESSION['quantity'] = array();
}
if (!isset($_SESSION['user_type'])) {
  $_SESSION['user_type'] = '';
}

$action = filter_input(INPUT_POST, 'action');
if ($action == null) {
  $action = filter_input(INPUT_GET, 'action');
  if ($action == null) {
    $action = 'home';
  }
}

if (!isset($_SESSION['is_valid'])) {
  $action = 'login';
}

switch ($action) {
  case 'login':
    $user = filter_input(INPUT_POST, 'email');
    $password = filter_input(INPUT_POST, 'password');
    if (valid_login($user, $password)) {
      $_SESSION['is_valid'] = true;
      $_SESSION['username'] = $user;
      if (usr_type($user) == "admin") {
        $_SESSION['user_type'] = 'admin';
        include 'view/admin.php';
      } elseif (usr_type($user) == "client") {
        $_SESSION['cart'] = array();
        load_cart();
        if (isset($_SESSION['cartqt'])) {
          $_SESSION['cartqt'] = array_sum($_SESSION['quantity']);
        } else {
          $_SESSION['cartqt'] = 0;
        }

        include 'view/client.php';
      } else {
        echo "invlaid type";
      }
    } elseif ($password !== null && !valid_login($user, $password)) {
      $login_message =
        "Error: Invlaid credential, you must correctly login to view this site";
      include 'view/login.php';
    } else {
      include 'view/login.php';
    }
    break;

  case 'home':
    include 'view/client.php';
    break;

  case 'logout':
    session_unset();
    session_destroy();
    $login_message = 'You have been logged out.';
    include 'view/login.php';
    break;

  case 'products':
    $year = get_car_year();
    include 'view/products.php';
    break;

  case 'car':
    $carYear = filter_input(INPUT_POST, 'year');
    $carMake = filter_input(INPUT_POST, 'make');
    $carModel = filter_input(INPUT_POST, 'model');
    $carEngine = filter_input(INPUT_POST, 'engine');
    $car = array(
      'year' => $carYear,
      'make' => $carMake,
      'model' => $carModel,
      'engine' => $carEngine
    );
    $carid = get_car_id($car);
    $part_cat = get_part_cat($carid[0]['car_id']);
    include 'view/car.php';
    break;

  case 'display':
    $cat = filter_input(INPUT_GET, 'cat');
    $id = filter_input(INPUT_GET, 'carid');
    $products = get_products($cat, $id);
    include 'view/displayproducts.php';
    break;

  case 'buy':
    $product = filter_input(INPUT_POST, 'product');
    if (in_cart($product) == false) {
      array_push($_SESSION['cart'], $product);
      $_SESSION['quantity'][$product] = 1;
      $_SESSION['cartqt'] = array_sum($_SESSION['quantity']);
      update_cart($product);
    }
    for ($i = 0; $i < sizeof($_SESSION['cart']); $i++) {
      $prod[$i] = get_prod_into($_SESSION['cart'][$i]);
    }
    include 'view/cart.php';
    break;

  case 'cartupdate':
    $qt = filter_input(INPUT_POST, 'updateqt');
    $ptnum = filter_input(INPUT_POST, 'pnum');
    if ($qt == 0 && in_array($ptnum, $_SESSION['cart']) == true) {
      $key = array_search($ptnum, $_SESSION['cart']);
      array_splice($_SESSION['cart'], $key, $key + 1);
      unset($_SESSION['quantity'][$ptnum]);
      $_SESSION['cartqt']--;
      remove_item_from_cart($ptnum);
    }
    $_SESSION['quantity'][$ptnum] = $qt;
    for ($i = 0; $i < sizeof($_SESSION['cart']); $i++) {
      $prod[$i] = get_prod_into($_SESSION['cart'][$i]);
    }
    $_SESSION['cartqt'] = array_sum($_SESSION['quantity']);
    include 'view/cart.php';
    break;

  case 'checkout':
    if (valid_order() == true) {
      do {
        $odnum = uniqid();
      } while (ordernum_exist($odnum) == true);
      $subtotal = 0;
      for ($i = 0; $i < sizeof($_SESSION['cart']); $i++) {
        $prod[$i] = get_prod_into($_SESSION['cart'][$i]);
      }
      foreach ($prod as $p) {
        $subtotal += $p['price'] * $_SESSION['quantity'][$p['part_number']];
      }
      $tax = $subtotal * 0.06;
      $total = $subtotal + $tax;
      insert_order($odnum, $subtotal, $tax, $total);
      insert_order_products($odnum);
      clear_cart();
      foreach ($_SESSION['cart'] as $p) {
        update_inv($p);
      }
      $_SESSION['cart'] = array();
      $_SESSION['cartqt'] = 0;
      $_SESSION['quantity'] = array();
      $name = getname();
      include 'view/purchaced.php';
    } elseif (valid_order() == false) {
      include 'view/toomany.php';
    }
    break;

  case 'cart':
    for ($i = 0; $i < sizeof($_SESSION['cart']); $i++) {
      $prod[$i] = get_prod_into($_SESSION['cart'][$i]);
    }
    include 'view/cart.php';
    break;

  case 'account':
    if ($_SESSION['is_valid'] == false) {
      include 'view/login.php';
    } else {
      $orders = get_orders();
      include 'view/account.php';
    }
    break;

  case 'details':
    $odnum = filter_input(INPUT_POST, 'odnum');
    $order = get_order($odnum);
    $items = get_items($odnum);
    include 'view/details.php';
    break;

  case 'reg':
    $message = null;
    include 'view/register.php';
    break;

  case 'submit':
    $fname = filter_input(INPUT_POST, 'fname');
    $lname = filter_input(INPUT_POST, 'lname');
    $username = filter_input(INPUT_POST, 'username');
    $pass = filter_input(INPUT_POST, 'pass');
    $confirmpass = filter_input(INPUT_POST, 'confirmpass');
    $add = filter_input(INPUT_POST, 'add');
    $town = filter_input(INPUT_POST, 'town');
    $state = filter_input(INPUT_POST, 'state');
    if ($pass != $confirmpass) {
      $message = "Passwords do not match";
      include 'view/register.php';
    }
    if (valid_user($username) == false) {
      $message = "Please pick a different Username. That one has been taken.";
      include 'view/register.php';
    }
    $hash = password_hash($pass, PASSWORD_BCRYPT);
    add_user_pass($username, $hash);
    add_user($username, $fname, $lname, $add, $town, $state);
    include 'view/login.php';
    break;

  case 'search':
    $searchqry = filter_input(INPUT_GET, 'qry');
    $products = search_db($searchqry);
    include 'view/displayproducts.php';
    break;

  case 'contact':
    include 'view/contact.php';
    break;

  case 'adminhome':
    include 'view/admin.php';
    break;

  case 'adminorders':
    $orders = get_all_orders();
    include 'view/admin_orders.php';
    break;

  case 'admindetails':
    $odnum = filter_input(INPUT_POST, 'odnum');
    $order = get_order($odnum);
    $info = get_info($order[0]['cusername']);
    $items = get_items($odnum);
    include 'view/admin_details.php';
    break;

  case 'updatestatus':
    $status = filter_input(INPUT_POST, 'status');
    $odnum = filter_input(INPUT_POST, 'odnum');
    updatestatus($odnum, $status);
    $order = get_order($odnum);
    $info = get_info($order[0]['cusername']);
    $items = get_items($odnum);
    include 'view/admin_details.php';
    break;

  case 'addproduct':
    $pnum = filter_input(INPUT_POST, 'pnum');
    $brand = filter_input(INPUT_POST, 'brand');
    $name = filter_input(INPUT_POST, 'name');
    $price = filter_input(INPUT_POST, 'price');
    $stocknum = filter_input(INPUT_POST, 'stocknum');
    $desc = filter_input(INPUT_POST, 'desc');
    $imgurl = filter_input(INPUT_POST, 'imgurl');
    $carid = filter_input(INPUT_POST, 'carid');
    $cat = filter_input(INPUT_POST, 'cat');
    $carids = get_car_ids();
    if (product_exist($pnum) == false) {
      $add_message = 'Product Added';
      insert_product($pnum, $brand, $name, $price, $stocknum, $desc, $imgurl);
      insert_to_car($carid, $pnum, $cat);
    } else {
      $add_message = 'Product Number already exist';
    }

    include 'view/addproducts.php';
    break;

  case 'addproductpage':
    $carids = get_car_ids();
    include 'view/addproducts.php';
    break;

  case 'changestock':
    $products = get_all_prod();
    include 'view/stock.php';
    break;

  case 'delete':
    $pdnum = filter_input(INPUT_POST, 'product');
    delete_product($pdnum);
    $products = get_all_prod();
    include 'view/stock.php';
    break;

  case 'changeinv':
    $pdnum = filter_input(INPUT_POST, 'product');
    $qt = filter_input(INPUT_POST, 'qt');
    change_inv($pdnum, $qt);
    $products = get_all_prod();
    include 'view/stock.php';
    break;

  case 'regemp':
    include 'view/regemp.php';
    break;

  case 'empreg':
    $username = filter_input(INPUT_POST, 'username');
    $pass = filter_input(INPUT_POST, 'password');
    $confirmpass = filter_input(INPUT_POST, 'confirmpassword');
    $type = filter_input(INPUT_POST, 'type');
    if ($pass != $confirmpass) {
      $message = "Passwords do not match";
      include 'view/regemp.php';
    }
    if (valid_user($username) == false) {
      $message = "Please pick a different Username. That one has been taken.";
      include 'view/regemp.php';
    }
    $hash = password_hash($pass, PASSWORD_BCRYPT);
    add_emp_pass($username, $hash, $type);
    add_emp($username, $type);
    include 'view/login.php';
    break;
}
?>