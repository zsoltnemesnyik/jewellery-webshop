<?php
    require './connection.php';

    if(filter_input(INPUT_POST, 'place_order')) {
        $orderTotal = 0;
        foreach($_SESSION["shopping_cart"] as $keys => $values) {
             $orderTotal += $_SESSION['shopping_cart'][$keys]['productQuantity'] * $_SESSION['shopping_cart'][$keys]['productPrice'];
        }

        $insert_order = "INSERT INTO orders(order_person_id, order_date, order_status, order_total) VALUES('1', '".date('Y-m-d')."', 'pending', '" . $orderTotal . "')";  

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
             UPDATE products SET product_availability=product_availability-". $_SESSION['shopping_cart'][$keys]['productQuantity'] ." WHERE product_id=" . $_SESSION['shopping_cart'][$keys]['productID'] . ";
             UPDATE person SET person_orders=person_orders+1 WHERE person_id=1;
             ";
        }  
        if(mysqli_multi_query($connection, $order_details)) {
             echo '<script>alert("You have successfully place an order...Thank you")</script>';  
             echo '<script>window.location.href="../pages/finish_order.php"</script>';
        }  
   }  
?>