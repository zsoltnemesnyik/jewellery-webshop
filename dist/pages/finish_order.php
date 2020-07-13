<?php
include '../includes/connection.php';

if(isset($_SESSION['order_id'])) {
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
                                        <p class="details__customer-data">'.$row["person_phone"].'</p>  
                                        <p class="details__customer-data">'.$row["person_email"].'</p>
                                        <p class="details__customer-data">'.$row["person_city"] . ', ' . $row["person_country"] .'</p>
                                        <p class="details__customer-data">'.$row["person_zip"] . ', ' . $row["person_address"] .'</p>
                                   ';
                                   $orderMessage = '<p class="details__customer-data">'.$row["order_comment"].'</p>';
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
                                        <h3 class="details__customer-label details__customer-label--message">Order Message</h3>
                                        '.$orderMessage.'
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

                              unset($_SESSION['shopping_cart']);
                              unset($_SESSION['order_id']);
                              
                         ?>
                         <a href="../dist/index.php" class="finish-order__back">Back to the Main Page</a>
                    </div>  
               </section>
          </main>
          <?php include '../includes/footer.php';?>
          <script>
               $('.badge').text(0);
          </script>
     </body>
</html>
<?php
} else {
     echo 'You have no right to access this page!';
}