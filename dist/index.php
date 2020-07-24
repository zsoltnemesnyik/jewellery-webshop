<?php
	require './includes/connection.php';
?>

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
				<h2 class="section-title">Best Sellers</h2>
				<div class="shopping-form">
					<div class="wrapper wrapper--sort">
						<select id="selectSort">
							<option value="default" class="select-sort__option">Sort by</option>
							<option value="asc" class="select-sort__option">Price Low to High</option>
							<option value="desc" class="select-sort__option">Price High to Low</option>
						</select>
					</div>
					<div class="wrapper wrapper--filter">
						<select id="selectFilter">
							<option value="default" class="select-filter__option">Filters</option>
							<option value="gold" class="select-filter__option">Gold</option>
							<option value="silver" class="select-filter__option">Silver</option>
						</select>
					</div>
				</div>
				
				<?php
					$result = mysqli_query($connection, "SELECT * FROM products ORDER BY product_id ASC");
					echo '<p class="shopping__results-number">' . mysqli_num_rows($result) . ' product(s)</p>'
				?>
				<div class="shopping__items">
					<?php
						while ($row = mysqli_fetch_array($result)) {
					?>
					<div class="shopping-box visible">
						<?php 
							$productAvailability = true;
							if($row['product_availability'] <= 0) {
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
								<h6 class="box-details-specs__availability"><span class="box-details-specs__available-number" id="availability<?php echo $row['product_id']?>"><?php echo $row['product_availability']?></span>pcs left</h6>
								<?php if(!$productAvailability) {?>
									<input type="text" id="quantity<?php echo $row['product_id']?>" class="box-details-specs__quantity" <?php echo 'value="0" disabled';?>>
								<?php 
								} else {
								?>
								<input type="number" id="quantity<?php echo $row['product_id']?>" class="box-details-specs__quantity" max="<?php echo $row['product_availability']?>" <?php echo 'min="1" value="0"';?>>
								<?php } ?>
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

				<div class="cart-overlay"></div>						
				<div class="cart">
					<i class="fas fa-times cart__close-btn"></i>
					<div class="cart-details">
                        <h3 class="cart-details__title">Your Cart</h3>
						<div class="cart-details__order">
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
										<h3 class="item__error" data-quantity-error="<?php echo $values['productID'];?>">Error message</h3>
										<h3 class="item__name"><?php echo $values['productName'];?></h3>
										<input type="number" name="quantity[]" value="<?php echo $values['productQuantity'];?>" data-product-id="<?php echo $values['productID']?>" class="quantity" min="1" max="<?php echo $values['productMax']?>">
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
						</div>
                    </div>
					<div class="cart-billing">
						<h3 class="cart-billing__title">Billing Informations</h3>
						<form id="sendOrder" method="post">
							<div class="form-group">
								<label for="name" class="form-group__label">Name *</label>
								<span class="form-group__error">&nbsp;</span>
								<input type="text" name="name" id="name" class="form-group__input form-group__input--name" placeholder="Your name!">
							</div>
							<div class="form-group">
								<label for="email" class="form-group__label">Email *</label>
								<span class="form-group__error">&nbsp;</span>
								<input type="email" name="email" id="email" class="form-group__input form-group__input--email" placeholder="Your email!">
							</div>
							<div class="form-group">
								<label for="phone" class="form-group__label">Phone number *</label>
								<span class="form-group__error">&nbsp;</span>
								<input type="text" name="phone" id="phone" class="form-group__input form-group__input--phone" placeholder="Your phone number">
							</div>
							<div class="form-group">
								<label for="address" class="form-group__label">Address *</label>
								<span class="form-group__error">&nbsp;</span>
								<input type="text" name="address" id="address" class="form-group__input form-group__input--address" placeholder="Your address">
							</div>
							<div class="form-group form-group--comment">
								<label for="comment" class="form-group__label">Leave additional comment:</label>
								<textarea name="comment" id="comment" rows="3" class="form-group__input form-group__input--textarea"></textarea>
							</div>
							<div class="form-group form-group--submit">
								<input type="button" name="place_order" class="form-group__input form-group__input--submit" id="send" value="Send Order">
							</div>
                        </form>
					</div>
				</div>
			</div>
		</section>
	</main>
	<?php include './includes/footer.php';?>
	<script src="js/scripts.js" type="module"></script>
</body>
</html>