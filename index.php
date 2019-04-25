<?php
session_start();
require_once("model/database.php");
require_once("model/login_func.php");
require_once('model/data_func.php');

$login_message="";


$action = filter_input(INPUT_POST, 'action');
if ($action == NULL) {
  $action = filter_input(INPUT_GET, 'action');
  if ($action == NULL) {
      $action = 'home';
  }
}

if (!isset($_SESSION['is_valid'])) {
  $action = 'login';
}

switch($action){
  case 'login':
    $user = filter_input(INPUT_POST, 'email');
    $password = filter_input(INPUT_POST, 'password'); 
    if(valid_login($user, $password)){
      $_SESSION['is_valid'] = true;
      if(usr_type($user) == "admin"){
        include('admin.php');
      } elseif(usr_type($user) == "client"){
        include('view/client.php');
      }else{
        echo "invlaid type";
      }
    } else if ($password !== NULL && !valid_login($user, $password)) {
      $login_message = "Error: Invlaid credential, you must correctly login to view this site";
      include('view/login.php');
    } else{
      include('view/login.php');
    }
    break;
  case 'home':
    include('view/client.php');
    break;
  case 'logout':
    $_SESSION = array();
    session_destroy(); 
    $login_message = 'You have been logged out.';
    include('view/login.php');
    break;
  case 'products':
    include('view/products.php');
    break;
  case 'car':
    $carYear = filter_input(INPUT_POST, 'year');
    $carMake = filter_input(INPUT_POST, 'make');
    $carModel = filter_input(INPUT_POST, 'model');
    $carEngine = filter_input(INPUT_POST, 'engine');
    $car = array('year' => $carYear, 'make' => $carMake, 'model' => $carModel, 'engine' => $carEngine);
    $carid = get_car_id($car);
    $part_cat = get_part_cat($carid[0]['car_id']);
    include('view/car.php');
    break;
  case 'display':
    $cat = filter_input(INPUT_GET, 'cat');
    $id = filter_input(INPUT_GET, 'carid');
    $products = get_products($cat, $id);
    include('view/displayproducts.php');
    break;
}
?>
