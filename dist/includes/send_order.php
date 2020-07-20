<?php
require './connection.php';

if(filter_input(INPUT_POST, 'action') === 'send_order') {
     $orderComment = strip_tags(trim(filter_input(INPUT_POST, 'comment')));
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
          $insert_order = "INSERT INTO orders(order_person_id, order_date, order_status, order_total, order_comment) VALUES('1', '".date('Y-m-d')."', 'pending', '" . $orderTotal . "', '" . $orderComment . "')";  
     
          $order_id = "";  
          if(mysqli_query($connection, $insert_order)) {  
               $order_id = mysqli_insert_id($connection);  
          }
     
          $_SESSION["order_id"] = $order_id;  
          $order_details = "";  
          foreach($_SESSION["shopping_cart"] as $keys => $values) {
               $order_details .= "
               INSERT INTO order_details(order_id, product_name, product_price, product_quantity)  
               VALUES('".$order_id."', '".$values["productName"]."', '".$values["productPrice"]."', '".$values["productQuantity"]."');
               UPDATE products SET product_availability=product_availability-$values[productQuantity] WHERE product_id=$values[productID];
               UPDATE person SET person_orders=person_orders+1 WHERE person_id=1;
               ";
          }
          
          if(mysqli_multi_query($connection, $order_details)) {
               echo json_encode('order_success');
          }
     }
} else {
     echo 'You have no right to access this page!';
}  
?>