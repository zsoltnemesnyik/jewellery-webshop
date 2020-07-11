<?php  
include '../includes/connection.php';
?>  

<!DOCTYPE html>  
<html>  
     <head>  
          <title>Order Finished</title>
          <base href="../">
          <?php require '../includes/head.php';?>
     </head>  
     <body>  
          <?php include '../includes/header.php';?>
          <main>
               <section class="finish-order">
                    <div class="container">  
                         <?php  
                         if(isset($_POST["place_order"]))  
                         {  
                              $insert_order = "  
                              INSERT INTO orders(order_person_id, order_date, order_status)  
                              VALUES('1', '".date('Y-m-d')."', 'pending')  
                              ";  
                              $order_id = "";  
                              if(mysqli_query($connection, $insert_order))  
                              {  
                                   $order_id = mysqli_insert_id($connection);  
                              }  
                              $_SESSION["order_id"] = $order_id;  
                              $order_details = "";  
                              foreach($_SESSION["shopping_cart"] as $keys => $values)  
                              {  
                                   $order_details .= "
                                   INSERT INTO order_details(order_id, product_name, product_price, product_quantity)  
                                   VALUES('".$order_id."', '".$values["productName"]."', '".$values["productPrice"]."', '".$values["productQuantity"]."');
                                   UPDATE products SET product_availability=product_availability-". $_SESSION['shopping_cart'][$keys]['productQuantity'] ." WHERE product_id=" . $_SESSION['shopping_cart'][$keys]['productID'] . ";
                                   ";
                              }  
                              if(mysqli_multi_query($connection, $order_details))  
                              {  
                                   unset($_SESSION["shopping_cart"]);  
                                   echo '<script>alert("You have successfully place an order...Thank you")</script>';  
                                   echo '<script>window.location.href="../dist/pages/cart.php"</script>';  
                                   var_dump($_SESSION['shopping_cart']);
                              }  
                         }  
                         if(isset($_SESSION["order_id"]))  
                         {  
                              $customer_details = '';  
                              $order_details = '';  
                              $total = 0;  
                              $query = '  
                              SELECT * FROM orders
                              INNER JOIN order_details  
                              ON order_details.order_id = orders.order_id  
                              INNER JOIN person
                              ON person.person_id = orders.order_person_id
                              WHERE orders.order_id = "'.$_SESSION["order_id"].'"  
                              ';  
                              $result = mysqli_query($connection, $query);  
                              while($row = mysqli_fetch_array($result))  
                              {  
                                   $customer_details = '  
                                   <p class="details__customer-data">'.$row["person_name"].'</p>
                                   <p class="details__customer-data">'.$row["person_email"].'</p>
                                   <p class="details__customer-data">'.$row["person_email"].'</p>  
                                   <p class="details__customer-data">'.$row["person_phone"].', '.$row["person_address"].'</p>
                                   ';  
                                   $order_details .= "
                                        <p class='details__order-data details__order-data--product-name'>".$row["product_name"]."</p>  
                                        <p>".$row["product_quantity"]."</p>  
                                        <p>".number_format($row["product_price"], 2)."</p>  
                                        <p>".number_format($row["product_quantity"] * $row["product_price"], 2)."</p>
                                   ";  
                                   $total = $total + ($row["product_quantity"] * $row["product_price"]);  
                              }  
                              echo '
                              <h2 class="finish-order__title">Order Summary for Order No.'.$_SESSION["order_id"].'</h2>  
                              <div class="details">
                                   <div class="details__customer">
                                        <h3 class="details__customer-label">Customer Details</h3>
                                        '.$customer_details.'
                                   </div>
                                   <div class="details__logo">
                                        <img src="./img/logo.png" alt="Order Finished Logo">
                                        <h3>Jewelry Webshop</h3>
                                   </div>
                                   <h3 class="details__order-label">Order Details</h3>
                                   <div class="details__order">
                                        <h4 class="details__order-heading">Product Name</h4>  
                                        <h4 class="details__order-heading">Quantity</h4>  
                                        <h4 class="details__order-heading">Price</h4>  
                                        <h4 class="details__order-heading">Total</h4>  
                                        '.$order_details.'  
                                        <div class="details-total">
                                             <h3 class="details-total__label">Total</h3>
                                             <p>'.number_format($total, 2).'$</p>
                                        </div>
                                   </div>
                              </div>
                              ';  
                         }  
                         ?>  
                    </div>  
               </section>
          </main>
     </body>  
</html> 