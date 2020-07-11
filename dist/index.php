<?php require './includes/connection.php';?>

<!DOCTYPE html>
<html lang="en">
<head>
	<base href="./">
	<title>Jewelry Webshop</title>
	
	<?php require './includes/head.php';?>
</head>
<body>
	<?php include './includes/header.php';?>
	
	<main>
		<section class="shopping">
			<div class="container">
				<h2 class="section-title" id="sectitle">Best Sellers</h2>

				<div class="shopping-info">
					<p class="shopping-info__results-number"></p>
					<button class="shopping-info__resize">Resize</button>
				</div>
				<form class="shopping-form">
					<div class="wrapper wrapper--sort">
						<select data-native-menu="false" class="select-sort">
							<option value="default" selected="selected" class="select-sort__option">Sort by</option>
							<option value="asc" class="select-sort__option">Price Low to High</option>
							<option value="desc" class="select-sort__option">Price High to Low</option>
						</select>
					</div>
					<div class="wrapper wrapper--filter">
						<select data-native-menu="false" class="select-filter">
							<option value="default" selected="selected" class="select-filter__option">Filters</option>
							<option value="gold" class="select-filter__option">Gold</option>
							<option value="silver" class="select-filter__option">Silver</option>
						</select>
					</div>
				</form>
				
				<div class="shopping__items">
					<?php
						$result = mysqli_query($connection, "SELECT * FROM products ORDER BY product_id ASC");

						while ($row = mysqli_fetch_array($result)) {
					?>
					<div class="shopping-box">
						<?php 
							$productAvailability = true;
							if($row['product_availability'] == 0) {
								$productAvailability = false;
								echo '<div class="stock stock--error" data-stock="out of stock"></div>';
							} else if($row['product_availability'] > 0 && $row['product_availability'] <= 5) {
								echo '<div class="stock stock--warning" data-stock="low stock"></div>';
							}
						?>

						<?php if($productAvailability) {?>
							<div class="corner" id="<?php echo $row["product_id"];?>">
								<i class="fas fa-cart-plus"></i>
							</div>
						<?php
							}
						?>
						<img src="./img/<?php echo $row['product_image'];?>" id="image<?php echo $row['product_id']?>" alt="Jewellery image" class="shopping-box__image <?php if(!$productAvailability) {echo 'shopping-box__image--error';}?>">
						<div class="box-details">
							<p class="box-details__title">
								<?php echo substr($row['product_title'], 0, 40) . '...'?>
								<span class="tooltiptext"><?php echo $row['product_title']?></span>
							</p>
							<div class="box-details-specs">
								<h6 class="box-details-specs__availability"><?php echo $row['product_availability']?>pcs left</h6>
								<input type="number" id="quantity<?php echo $row['product_id']?>" class="box-details-specs__quantity" max="<?php echo $row['product_availability']?>" <?php if(!$productAvailability) {echo 'value="0" disabled';} else {echo 'min="1" value="1"';}?>>
								<h6 class="box-details-specs__price"><?php echo number_format($row['product_price-best'], 2)?>$</h6>
								<input type="hidden" name="hidden_name" id="name<?php echo $row['product_id']?>" value="<?php echo $row['product_title']?>">
								<input type="hidden" name="hidden_price" id="price<?php echo $row['product_id'];?>" value="<?php echo $row['product_price-best'];?>">
							</div>
						</div>
					</div>
					<?php
						}
					?>
				</div>

				<div class="popup-overlay"></div>
				<div class="shopping__popup"></div>
						
				<div class="cart">
					<i class="fas fa-times cart__close-btn"></i>
					<div class="cart-details">
                        <h3 class="cart-details__title">Your Cart</h3>
                        <div class="items">
                            <?php
                            if(!empty($_SESSION['shopping_cart'])) {
                                $total = 0;
                                foreach ($_SESSION['shopping_cart'] as $keys => $values) {
                            ?>
                            <div class="item">
                                <div class="item__image">
                                    <img src="<?php echo $values['productImage']?>" alt="Cart image">
                                </div>
                                <div class="item__details">
                                    <h3 class="item__name"><?php echo $values['productName'];?></h3>
                                    <input type="number" name="quantity[]" id="quantity<?php echo $values['productID'];?>" value="<?php echo $values['productQuantity'];?>" data-product-id="<?php echo $values['productID']?>" class="quantity" min="1" max="<?php echo $values['productMax']?>">
                                    <h3 class="item__price"><?php echo $values['productPrice'];?>$</h3>
                                    <h3 class="item__price"><?php echo number_format($values['productQuantity'] * $values['productPrice'], 2);?>$</h3>
                                	<button name="delete" class="item__delete" id="<?php echo $values['productID'];?>">
                                    <i class="fas fa-times-circle"></i>
                                </button>
                                </div>
                            </div>
                            <?php
                                $total = $total + ($values['productQuantity'] * $values['productPrice']);
                            	}
							}
                            ?>
                        </div>
                        <h3 class="cart-details__total">
                            Total:
                            <span class="cart-details__total cart-details__total--value"><?php if (isset($total)) {echo number_format($total, 2);} else {echo '0';}?>$</span>
                        </h3>
                        <?php
                        ?>
                        <form action="./pages/cart.php" method="post">
                            <h3 class="cart-details__comment">Leave additional comment:</h3>
                            <textarea name="comment" rows="3" class="cart-details__comment-text"></textarea>
                            <input type="submit" name="place_order" class="cart-details__order-btn" value="Send Order">
                        </form>
                    </div>
					<div class="cart-billing">
						<h3 class="cart-billing__title">Billing Informations</h3>
						<p class="cart-billing__text cart-billing__text--name">
							<span class="cart-billing__text cart-billing__text--highlighted">Name:</span>Text
						</p>
						<p class="cart-billing__text cart-billing__text--phone">
							<span class="cart-billing__text cart-billing__text--highlighted">Phone:</span>Text
						</p>
						<p class="cart-billing__text cart-billing__text--email">
							<span class="cart-billing__text cart-billing__text--highlighted">Email:</span>Text
						</p>
						<p class="cart-billing__text cart-billing__text--address">
							<span class="cart-billing__text cart-billing__text--highlighted">Address:</span>Text
						</p>
					</div>
				</div>
			</div>
		</section>
	</main>
	<?php include './includes/footer.php';?>
	<script src="js/scripts.js"></script>
</body>
</html>