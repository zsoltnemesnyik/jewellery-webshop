<?php
require './connection.php';

// check if action === send_order
if(filter_input(INPUT_POST, 'action') === 'send_order') {
     // validate fields
     $customerName = strip_tags(trim(filter_input(INPUT_POST, 'custName')));
     $customerPhone = strip_tags(trim(filter_input(INPUT_POST, 'custPhone')));
     $customerEmail = strip_tags(trim(filter_input(INPUT_POST, 'custEmail')));
     $customerAddress = strip_tags(trim(filter_input(INPUT_POST, 'custAddress')));
     $customerComment = strip_tags(trim(filter_input(INPUT_POST, 'custComment')));
     
     $orderTotal = 0;
     $error = false;
     $cartMessages = [];

     foreach($_SESSION["shopping_cart"] as $keys => $values) {
          $orderTotal += $_SESSION['shopping_cart'][$keys]['productQuantity'] * $_SESSION['shopping_cart'][$keys]['productPrice'];
          
          // check if quantity isn't greater than productAvailability
          $query = mysqli_query($connection, 'SELECT product_availability FROM products WHERE product_id="' . $_SESSION['shopping_cart'][$keys]['productID'] . '"');

          $availability = mysqli_fetch_array($query, MYSQLI_NUM)[0];
          if ($availability < $_SESSION['shopping_cart'][$keys]['productQuantity']) {
               $cartMessages['errors'][$_SESSION['shopping_cart'][$keys]['productID']] = 'Sorry, but only ' . $availability . ' left';
               $error = true;
          }
     }

     if ($error === true) {
          echo json_encode($cartMessages);
          return;
     } else {
          $customer_id = "";
          $order_id = "";

          if(mysqli_query($connection, "INSERT INTO person(person_name, person_phone, person_email, person_address) values('" . $customerName . "', '" . $customerPhone . "', '" . $customerEmail . "', '" . $customerAddress . "')")) {  
               $customer_id = mysqli_insert_id($connection);  
          }
     
          if(mysqli_query($connection, "INSERT INTO orders(order_person_id, order_date, order_status, order_total, order_comment) VALUES('".$customer_id."','".date('Y-m-d')."', 'pending', '" . $orderTotal . "', '" . $customerComment . "')")) {  
               $order_id = mysqli_insert_id($connection);  
          }
     
          $_SESSION["order_id"] = $order_id;  
          $order_details = "";  
          foreach($_SESSION["shopping_cart"] as $keys => $values) {
               $order_details .= "
               INSERT INTO order_details(order_id, product_name, product_price, product_quantity)  
               VALUES('".$order_id."', '".$values["productName"]."', '".$values["productPrice"]."', '".$values["productQuantity"]."');
               UPDATE products SET product_availability=product_availability-$values[productQuantity] WHERE product_id=$values[productID];";
          }
          
          if(mysqli_multi_query($connection, $order_details)) {
               echo json_encode('order_success');
          }
     }
} else {
     echo 'You have no right to access this page!
     <br>
     <a href="../index.php">Back to the Main Page</a>';
}  
?>