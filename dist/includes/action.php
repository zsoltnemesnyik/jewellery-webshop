<?php
    require '../includes/connection.php';

    if (filter_input(INPUT_POST, 'productID')) {
        $orderTable = '';
        $message = '';

        if (filter_input(INPUT_POST, 'action') === 'add') {
            if (isset($_SESSION['shopping_cart'])) {
                $is_available = 0;

                foreach($_SESSION['shopping_cart'] as $keys => $values) {
                    if($_SESSION['shopping_cart'][$keys]['productID'] === filter_input(INPUT_POST, 'productID')) {
                        $is_available++;
                        $_SESSION['shopping_cart'][$keys]['productQuantity'] = $_SESSION['shopping_cart'][$keys]['productQuantity'] + filter_input(INPUT_POST, 'productQuantity');
                    }
                }

                if ($is_available < 1) {
                    $item_array = [
                        'productID'         =>      filter_input(INPUT_POST, 'productID'),
                        'productName'       =>      filter_input(INPUT_POST, 'productName'),
                        'productPrice'      =>      filter_input(INPUT_POST, 'productPrice'),
                        'productQuantity'   =>      filter_input(INPUT_POST, 'productQuantity'),
                        'productImage'      =>      filter_input(INPUT_POST, 'productImage')
                    ];
                    $_SESSION['shopping_cart'][] = $item_array;
                }
            } else {
                $item_array = [
                    'productID'         =>      filter_input(INPUT_POST, 'productID'),
                    'productName'       =>      filter_input(INPUT_POST, 'productName'),
                    'productPrice'      =>      filter_input(INPUT_POST, 'productPrice'),
                    'productQuantity'   =>      filter_input(INPUT_POST, 'productQuantity'),
                    'productImage'      =>      filter_input(INPUT_POST, 'productImage')
                ];
                $_SESSION['shopping_cart'][] = $item_array;
            }
        }

        if(filter_input(INPUT_POST, 'action') === 'remove') {
            foreach($_SESSION['shopping_cart'] as $keys => $values) {
                if($values['productID'] === filter_input(INPUT_POST, 'productID')) {
                    unset($_SESSION['shopping_cart'][$keys]);
                }
            }
        }

        if(filter_input(INPUT_POST, 'action') === 'quantity_change') {
            foreach($_SESSION['shopping_cart'] as $keys => $values) {
                if($_SESSION['shopping_cart'][$keys]['productID'] === filter_input(INPUT_POST, 'productID')) {
                    $_SESSION['shopping_cart'][$keys]['productQuantity'] = filter_input(INPUT_POST, 'productQuantity');
                }
            }
        }

        $orderTable .= '
            <h3 class="cart-details__title">Your Cart</h3>
            <div class="items">
        ';

        if (!empty($_SESSION['shopping_cart'])) {
            $total = 0;

            foreach($_SESSION['shopping_cart'] as $keys => $values) {
                $orderTable .= '
                    <div class="item">
                        <div class="item__image">
                            <img src="' . $values['productImage'] . '" alt="Cart image">
                        </div>
                        <div class="item__details">
                            <h3 class="item__name">' . $values['productName'] . '</h3>
                            <input type="number" name="quantity[]" id="quantity' . $values['productID'] . '" value="' . $values['productQuantity'] . '" data-product-id="' . $values['productID'] . '" class="quantity">
                            <h3 class="item__price">' . $values['productPrice'] . '$</h3>
                            <h3 class="item__price">' . number_format($values['productQuantity'] * $values['productPrice'], 2) . '$</h3>
                            <button name="delete" class="item__delete" id="' . $values['productID'] . '">
                                <i class="fas fa-times-circle"></i>
                            </button>
                        </div>
                    </div>
                ';
                $total = $total + ($values['productQuantity'] * $values['productPrice']);
            }

            $orderTable .= '
                </div>
                <h3 class="cart-details__total">
                    Total:
                    <span class="cart-details__total cart-details__total--value">' . number_format($total, 2) . '$</span>
                </h3>
            ';
        }

        $orderTable .= '
            </div>
        ';

        $output = [
            'orderTable'        => $orderTable,
            'cartItem'          => count($_SESSION['shopping_cart'])
        ];

        echo json_encode($output);
    }
?>