<?php
    require './connection.php';

    if (filter_input(INPUT_POST, 'sort')) {
        $sortOrder = filter_input(INPUT_POST, 'sort');
        $result = mysqli_query($connection, "SELECT * FROM products ORDER BY `products`.`product_price-best` $sortOrder");
    }
    
    if (filter_input(INPUT_POST, 'filter')) {
        $filterMaterial = filter_input(INPUT_POST, 'filter');
        $result = mysqli_query($connection, "SELECT * FROM products WHERE product_material='$filterMaterial'");
    }

        $shoppingBox = '';
        while ($row = mysqli_fetch_array($result)) {
            $productAvailability = true;
            $shoppingBox .= '
            <div class="shopping-box">';
                if($row['product_availability'] <= 0) {
                    $productAvailability = false;
                    $shoppingBox .= '<div class="stock stock--error" data-stock="out of stock"></div>';
                } else if($row['product_availability'] > 0 && $row['product_availability'] <= 5) {
                    $shoppingBox .= '<div class="stock stock--warning" data-stock="low stock"></div>';
                }

                if($productAvailability) {
                    $shoppingBox .= '
                    <div class="corner" id="' .$row["product_id"]. '">
                        <i class="fas fa-cart-plus"></i>
                    </div>';
                }

                $shoppingBox .= '
                    <img src="./img/' .$row['product_image']. '" id="image' .$row['product_id']. '" alt="Jewellery image" class="shopping-box__image';
                        if(!$productAvailability) {$shoppingBox .= ' shopping-box__image--error';}
                    $shoppingBox .= '
                    ">

                    <div class="box-details">
                        <p class="box-details__title">'
                            .substr($row['product_title'], 0, 40).
                            '<span class="tooltiptext">' .$row['product_title']. '</span>
                        </p>
                        <div class="box-details-specs">
                            <h6 class="box-details-specs__availability">
                                <span class="box-details-specs__available-number" id="availability' .$row['product_id']. '">' 
                                    .$row['product_availability']. 
                                '</span>pcs left
                            </h6>
                    ';
                        
                    if(!$productAvailability) {
                        $shoppingBox .= '
                        <input type="text" id="quantity' .$row['product_id']. '" class="box-details-specs__quantity" value="0" disabled>';    
                    } else {
                        $shoppingBox .= '
                        <input type="number" id="quantity' .$row['product_id']. '" class="box-details-specs__quantity" max="' .$row['product_availability']. '" min="1" value="0">';
                    }

                    $shoppingBox .= '
                        <h6 class="box-details-specs__price">' .number_format($row['product_price-best'], 2). '$</h6>
                        <input type="hidden" name="hidden_name" id="name' .$row['product_id']. '" value="' .$row['product_title']. '">
                        <input type="hidden" name="hidden_price" id="price' .$row['product_id']. '" value="' .$row['product_price-best']. '">
                        </div>
                    </div>
            </div>';
        }
        echo json_encode($shoppingBox);
?>