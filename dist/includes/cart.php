<?php
    session_start();
    $connection = mysqli_connect('localhost', 'root', '', 'jewelry');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div>
        <?php
            if (filter_input(INPUT_POST, 'place_order')) {
                $insert_order = "
                    INSERT INTO orders (order_person_id, order_date, order_status) VALUES ('1', '" . date('Y-m-d') . "', 'pending')
                ";
                $order_id = '';
            
                if(mysqli_query($connection, $insert_order)) {
                    $order_id = mysqli_insert_id($connection);
                }

                $_SESSION['order_id'] = $order_id;
                $order_details = '';

                foreach($_SESSION['shopping_cart'] as $keys => $values) {
                    $order_details .= "
                        INSERT INTO order_details(order_id, product_name, product_price, product_quantity) VALUES('" . $order_id . "', '" . $values['productName'] . "', '" . $values['productPrice'] . "', '" . $values['productQuantity'] . "');
                    ";
                }

                if (mysqli_multi_query($connection, $order_details)) {
                    unset($_SESSION['shopping_cart']);
                    echo '<script>alert("You have successfully placed an order...Thank you!")</script>';
                    // echo '<script>window.location.href="./includes/cart.php"</script>';
                }
            }

            if (isset($_SESSION['order_id'])) {
                $customer_details = '';
                $order_details = '';
                $total = 0;

                $query = '
                    SELECT * FROM orders
                    INNER JOIN order_details
                    ON order_details.order_id = orders.order_id
                    INNER JOIN person
                    ON person.person_id = orders.order_person_id
                    WHERE orders.order_id = "' . $_SESSION['order_id'] . '"
                ';

                $result = mysqli_query($connection, $query);

                while ($row = mysqli_fetch_array($result)) {
                    $customer_details = '
                        <label>' . $row['person_name'] . '</label>
                        <p>' . $row['person_address'] . '</p>
                        <p>' . $row['person_phone'] . '</p>
                        <p>' . $row['person_email'] . '</p>
                    ';

                    $order_details .= "
                        <tr>
                            <td>" . $row['product_name'] . "</td>
                            <td>" . $row['product_quantity'] . "</td>
                            <td>" . $row['product_price'] . "</td>
                            <td>" . number_format($row['product_quantity'] * $row['product_price'], 2) . "</td>
                        </tr>
                    ";

                    $total = $total + ($row['product_quantity'] * $row['product_price']);

                    echo '
                        <h3>Order Summary for Order No.' . $_SESSION['order_id'] . '</h3>
                        <div>
                            <table>
                                <tr>
                                    <td><label>Customer Details</label></td>
                                </tr>
                                <tr>
                                    <td>' . $customer_details . '</td>
                                </tr>
                                <tr>
                                    <td>
                                        <table>
                                            <tr>
                                                <th width="50%">Product Name</th>
                                                <th width="15%">Quantity</th>
                                                <th width="15%">Price</th>
                                                <th width="20%">Total</th>
                                            </tr>
                                            ' . $order_details . '
                                            <tr>
                                                <td><label>Total</label></td>
                                                <td>' . number_format($total, 2) . '</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    ';
                }
            }
        ?>
    </div>
    
</body>
</html>