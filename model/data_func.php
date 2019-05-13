<?php
require_once 'database.php';
function get_car_year()
{
  global $db;
  $query = 'SELECT DISTINCT year
            FROM CAR
            ORDER BY year DESC';
  $statement = $db->prepare($query);
  $statement->execute();
  $row = $statement->fetchAll();
  $statement->closeCursor();
  return $row;
}

function get_car_make($year)
{
  global $db;
  $query = 'SELECT DISTINCT make
            FROM CAR
            WHERE year = :year
            ORDER BY make';
  $statement = $db->prepare($query);
  $statement->bindValue(':year', $year);
  $statement->execute();
  $row = $statement->fetchAll();
  $statement->closeCursor();
  return $row;
}

function get_car_model($year, $make)
{
  global $db;
  $query = 'SELECT DISTINCT model
            FROM CAR
            WHERE year = :year AND make = :make
            ORDER BY model';
  $statement = $db->prepare($query);
  $statement->bindValue(':year', $year);
  $statement->bindValue(':make', $make);
  $statement->execute();
  $row = $statement->fetchAll();
  $statement->closeCursor();
  return $row;
}

function get_car_engine($year, $make, $model)
{
  global $db;
  $query = 'SELECT DISTINCT engine
            FROM CAR
            WHERE year = :year AND make = :make AND model = :model
            ORDER BY engine';
  $statement = $db->prepare($query);
  $statement->bindValue(':year', $year);
  $statement->bindValue(':make', $make);
  $statement->bindValue(':model', $model);
  $statement->execute();
  $row = $statement->fetchAll();
  $statement->closeCursor();
  return $row;
}

function get_car_id($car)
{
  global $db;
  $query = 'SELECT DISTINCT car_id
            FROM CAR
            WHERE year = :year AND make =:make AND model = :model AND engine = :engine';
  $statement = $db->prepare($query);
  $statement->bindValue(':year', $car['year']);
  $statement->bindValue(':make', $car['make']);
  $statement->bindValue(':model', $car['model']);
  $statement->bindValue(':engine', $car['engine']);
  $statement->execute();
  $row = $statement->fetchAll();
  $statement->closeCursor();
  return $row;
}

function get_part_cat($crid)
{
  global $db;
  $query = 'SELECT DISTINCT HAS_PARTS.category
            FROM HAS_PARTS
            WHERE HAS_PARTS.cr_id = :carid
            ORDER BY HAS_PARTS.category ASC';
  $statement = $db->prepare($query);
  $statement->bindValue(':carid', $crid);
  $statement->execute();
  $row = $statement->fetchAll();
  $statement->closeCursor();
  return $row;
}

function get_products($cat, $id)
{
  global $db;
  $query = 'SELECT DISTINCT PART.part_number, PART.brand, PART.name, PART.price, PART.description, PART.img_url, HAS_PARTS.category, PART.num_stock
            FROM HAS_PARTS, PART
            WHERE HAS_PARTS.cr_id = :carid AND HAS_PARTS.pt_num = PART.part_number AND HAS_PARTS.category = :cat';
  $statement = $db->prepare($query);
  $statement->bindValue(':carid', $id);
  $statement->bindValue(':cat', $cat);
  $statement->execute();
  $row = $statement->fetchAll();
  $statement->closeCursor();
  return $row;
}

function get_prod_into($pnum)
{
  global $db;
  $query = 'SELECT PART.brand, PART.name, PART.price, PART.part_number, PART.img_url
            FROM PART
            WHERE PART.part_number = :pnum';
  $statement = $db->prepare($query);
  $statement->bindValue(':pnum', $pnum);
  $statement->execute();
  $row = $statement->fetchAll();
  $statement->closeCursor();
  return $row[0];
}

function in_cart($pnum)
{
  for ($i = 0; $i < sizeof($_SESSION['cart']); $i++) {
    if ($_SESSION['cart'][$i] == $pnum) {
      return true;
    }
  }
  return false;
}
function ordernum_exist($onum)
{
  global $db;
  $query = 'SELECT ORDERS.order_number
            FROM ORDERS
            WHERE ORDERS.order_number = :onum';
  $statement = $db->prepare($query);
  $statement->bindValue(':onum', $onum);
  $statement->execute();
  $row = $statement->fetchAll();
  $statement->closeCursor();
  if (!$row) {
    return false;
  } else {
    return true;
  }
}

function load_cart()
{
  global $db;
  $query = 'SELECT CART.cname, CART.part, CART.qyt
            FROM CART
            WHERE CART.cname = :cname';
  $statement = $db->prepare($query);
  $statement->bindValue(':cname', $_SESSION['username']);
  $statement->execute();
  $row = $statement->fetchAll();
  $statement->closeCursor();
  if (!$row) {
    return 0;
  } else {
    foreach ($row as $prod) {
      array_push($_SESSION['cart'], $prod['part']);
      $_SESSION['quantity'][$prod['part']] = $prod['qyt'];
    }
  }
}

function update_cart($part)
{
  global $db;
  $qyt = 1;
  $query = 'INSERT INTO CART(cname, part, qyt) VALUES (:cname, :part, :qyt)';
  $statement = $db->prepare($query);
  $statement->execute(array(
    'cname' => $_SESSION['username'],
    'part' => $part,
    'qyt' => $qyt
  ));
  $statement->closeCursor();
}

function remove_item_from_cart($pnum)
{
  global $db;
  $query = 'DELETE FROM CART WHERE CART.cname = :cname AND CART.part = :pnum';
  $statement = $db->prepare($query);
  $statement->execute(array('cname' => $_SESSION['username'], 'pnum' => $pnum));
  $statement->closeCursor();
}

function valid_order()
{
  global $db;
  foreach ($_SESSION['cart'] as $pt) {
    $query = 'SELECT num_stock
              FROM PART
              WHERE part_number = :pnum';
    $statement = $db->prepare($query);
    $statement->bindValue(':pnum', $pt);
    $statement->execute();
    $row = $statement->fetchAll();
    $statement->closeCursor();
    if ($row[0]['num_stock'] < $_SESSION['quantity'][$pt]) {
      return false;
    }
  }
  return true;
}
function insert_order($od_number, $sub, $tx, $tot)
{
  global $db;
  $timestamp = date('Y-m-d G:i:s');
  $query = 'INSERT INTO ORDERS(order_number, total_price, status, date, cusername, tax, subtotal) 
            VALUES (:odnum, :tot, :status, :date, :cname, :tx, :sub)';
  $statement = $db->prepare($query);
  $statement->execute(array(
    'odnum' => $od_number,
    'tot' => $tot,
    'status' => "ordered",
    'date' => $timestamp,
    'cname' => $_SESSION['username'],
    'tx' => $tx,
    'sub' => $sub
  ));
  $statement->closeCursor();
}
function insert_order_products($od_number)
{
  global $db;
  foreach ($_SESSION['cart'] as $pt_num) {
    $query =
      'INSERT INTO PARTS_PURCHASED(od_number, pt_nm, qt) VALUES (:od_number, :pt_nm, :qt)';
    $statement = $db->prepare($query);
    $statement->execute(array(
      'od_number' => $od_number,
      'pt_nm' => $pt_num,
      'qt' => $_SESSION['quantity'][$pt_num]
    ));
    $statement->closeCursor();
  }
}
function clear_cart()
{
  global $db;
  $query = 'DELETE FROM `CART` WHERE `cname` = :username';
  $statement = $db->prepare($query);
  $statement->execute(array(
    'username' => $_SESSION['username']
  ));
  $statement->closeCursor();
}
function update_inv($ptnum)
{
  global $db;
  $query = 'UPDATE PART
          SET PART.num_stock = PART.num_stock - :qt
          WHERE PART.part_number =:ptnum';
  $statement = $db->prepare($query);
  $statement->execute(array(
    'qt' => $_SESSION['quantity'][$ptnum],
    'ptnum' => $ptnum
  ));
  $statement->closeCursor();
}
function getname()
{
  global $db;
  $query = 'SELECT CLIENT.First_name, CLIENT.Last_name
            FROM CLIENT
            WHERE CLIENT.usrname = :uname';
  $statement = $db->prepare($query);
  $statement->bindValue(':uname', $_SESSION['username']);
  $statement->execute();
  $row = $statement->fetchAll();
  $statement->closeCursor();
  return $row[0];
}
function get_orders()
{
  global $db;
  $query = 'SELECT *
            FROM ORDERS
            WHERE ORDERS.cusername = :uname
            ORDER BY date DESC';
  $statement = $db->prepare($query);
  $statement->bindValue(':uname', $_SESSION['username']);
  $statement->execute();
  $row = $statement->fetchAll();
  $statement->closeCursor();
  return $row;
}
function get_items($odnum)
{
  global $db;
  $query = 'SELECT PART.name, PART.price, PARTS_PURCHASED.qt
            FROM PART, PARTS_PURCHASED
            WHERE PART.part_number = PARTS_PURCHASED.pt_nm AND PARTS_PURCHASED.od_number = :odnum';
  $statement = $db->prepare($query);
  $statement->bindValue(':odnum', $odnum);
  $statement->execute();
  $row = $statement->fetchAll();
  $statement->closeCursor();
  return $row;
}
function get_order($odnum)
{
  global $db;
  $query = 'SELECT *
            FROM ORDERS
            WHERE ORDERS.order_number = :odnum';
  $statement = $db->prepare($query);
  $statement->bindValue(':odnum', $odnum);
  $statement->execute();
  $row = $statement->fetchAll();
  $statement->closeCursor();
  return $row;
}
function valid_user($uname)
{
  global $db;
  $query = 'SELECT usrname
            FROM CLIENT
            WHERE usrname = :uname';
  $statement = $db->prepare($query);
  $statement->bindValue(':uname', $uname);
  $statement->execute();
  $row = $statement->fetchAll();
  $statement->closeCursor();
  if ($row == null) {
    return true;
  } else {
    return false;
  }
}
function add_user($username, $fname, $lname, $add, $town, $state)
{
  global $db;
  $query = 'INSERT INTO CLIENT(usrname, First_name, Last_name, address, town, state) 
            VALUES (:username, :fname, :lname, :add, :town, :state)';
  $statement = $db->prepare($query);
  $statement->execute(array(
    'username' => $username,
    'fname' => $fname,
    'lname' => $lname,
    'add' => $add,
    'town' => $town,
    'state' => $state
  ));
  $statement->closeCursor();
}
function add_user_pass($username, $pass)
{
  global $db;
  $query = 'INSERT INTO USERS(username, password, type_usr)  
            VALUES (:username, :pass, :type)';
  $statement = $db->prepare($query);
  $statement->execute(array(
    'username' => $username,
    'pass' => $pass,
    'type' => "client"
  ));
  $statement->closeCursor();
}
function search_db($qry)
{
  $temp = "%" . $qry;
  $qry = $temp . "%";
  global $db;
  $query = 'SELECT * 
            FROM PART 
            WHERE PART.name LIKE :qry';
  $statement = $db->prepare($query);
  $statement->bindValue(':qry', $qry);
  $statement->execute();
  $row = $statement->fetchAll();
  $statement->closeCursor();
  return $row;
}
function get_all_orders()
{
  global $db;
  $query = 'SELECT *
            FROM ORDERS
            ORDER BY date DESC';
  $statement = $db->prepare($query);
  $statement->execute();
  $row = $statement->fetchAll();
  $statement->closeCursor();
  return $row;
}
function get_info($uname)
{
  global $db;
  $query = 'SELECT CLIENT.First_name, CLIENT.Last_name, CLIENT.address, CLIENT.town, CLIENT.state
            FROM CLIENT 
            WHERE CLIENT.usrname = :uname';
  $statement = $db->prepare($query);
  $statement->bindValue(':uname', $uname);
  $statement->execute();
  $row = $statement->fetchAll();
  $statement->closeCursor();
  return $row[0];
}
function updatestatus($odnum, $stat)
{
  global $db;
  $query = 'UPDATE ORDERS SET status = :status WHERE order_number = :odnum';
  $statement = $db->prepare($query);
  $statement->execute(array(
    'odnum' => $odnum,
    'status' => $stat
  ));
  $statement->closeCursor();
}
function product_exist($pnum)
{
  global $db;
  $query = 'SELECT PART.part_number
            FROM PART 
            WHERE PART.PART_number = :pnum';
  $statement = $db->prepare($query);
  $statement->bindValue(':pnum', $pnum);
  $statement->execute();
  $row = $statement->fetchAll();
  $statement->closeCursor();
  if ($row == null) {
    return false;
  } else {
    return true;
  }
}
function insert_product($pnum, $brand, $name, $price, $stocknum, $desc, $imgurl)
{
  global $db;
  $query = 'INSERT INTO PART(PART_number, brand, name, price, num_stock, description, img_url) 
            VALUES (:PART_number, :brand, :name, :price, :num_stock, :description, :img_url)';
  $statement = $db->prepare($query);
  $statement->execute([
    'PART_number' => $pnum,
    'brand' => $brand,
    'name' => $name,
    'price' => $price,
    'num_stock' => $stocknum,
    'description' => $desc,
    'img_url' => $imgurl
  ]);
  $statement->closeCursor();
}
function insert_to_car($carid, $pnum, $cat)
{
  global $db;
  $query = 'INSERT INTO HAS_PARTS(category, cr_id, pt_num) 
            VALUES (:category, :cr_id, :pt_num)';
  $statement = $db->prepare($query);
  $statement->execute([
    'category' => $cat,
    'cr_id' => $carid,
    'pt_num' => $pnum
  ]);
  $statement->closeCursor();
}
function get_car_ids()
{
  global $db;
  $query = 'SELECT car_id
            FROM CAR';
  $statement = $db->prepare($query);
  $statement->execute();
  $row = $statement->fetchAll();
  $statement->closeCursor();
  return $row;
}
function get_all_prod()
{
  global $db;
  $query = 'SELECT *
            FROM PART';
  $statement = $db->prepare($query);
  $statement->execute();
  $row = $statement->fetchAll();
  $statement->closeCursor();
  return $row;
}
function delete_product($pdnum)
{
  global $db;
  $query = 'DELETE FROM PART WHERE part_number =  :pdnum';
  $statement = $db->prepare($query);
  $statement->execute(array(
    'pdnum' => $pdnum
  ));
  $statement->closeCursor();
}
function change_inv($pdnum, $qt)
{
  global $db;
  $query = 'UPDATE PART SET num_stock = :num_stock WHERE part_number = :pdnum';
  $statement = $db->prepare($query);
  $statement->execute(array(
    'pdnum' => $pdnum,
    'num_stock' => $qt
  ));
  $statement->closeCursor();
}
function add_emp($urname, $type)
{
  global $db;
  $query = 'INSERT INTO EMPLOYEE(usrname, postion)
            VALUES (:username, :postion)';
  $statement = $db->prepare($query);
  $statement->execute(array(
    'username' => $urname,
    'postion' => $type
  ));
  $statement->closeCursor();
}
function add_emp_pass($username, $pass, $type)
{
  global $db;
  $query = 'INSERT INTO USERS(username, password, type_usr)  
            VALUES (:username, :pass, :type)';
  $statement = $db->prepare($query);
  $statement->execute(array(
    'username' => $username,
    'pass' => $pass,
    'type' => $type
  ));
  $statement->closeCursor();
}
?>