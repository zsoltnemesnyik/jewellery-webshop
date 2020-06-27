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
    
    <!-- ICONS -->
    <script src="https://kit.fontawesome.com/e1de7d362c.js" crossorigin="anonymous"></script>

    <!-- SCRIPTS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    
    <!-- STYLESHEETS -->
    <link rel="stylesheet" href="css/style.css">
    
</head>
<body>
    <header class="header">
        <div class="header-top">
            <div class="container">
                <p class="header-top__promo-text">
                    100 day returns
                </p>
            </div>
        </div>
        <div class="header-bottom">
            <div class="container container--fluid">
                <div class="mobile-nav">
                    <span class="mobile-nav__icon"></span>
                </div>
                <div class="brand">
                    <img src="img/logo.png" alt="Logo" class="brand__logo"/>
                    <h1 class="brand__title">Jewellery Webshop</h1>
                </div>
                <nav class="navigation">
                    <ul class="navigation__menu">
                        <li class="navigation__item">
                            <a href="#" class="navigation__link">Lorem</a>
                        </li>
                        <li class="navigation__item">
                            <a href="#" class="navigation__link">Lorem</a>
                        </li>
                        <li class="navigation__item">
                            <a href="#" class="navigation__link">Lorem Ipsum</a>
                        </li>
                        <li class="navigation__item">
                            <a href="#" class="navigation__link">Lorem</a>
                        </li>
                        <li class="navigation__item">
                            <a href="#" class="navigation__link">Ipsum Dolor</a>
                        </li>
                        <li class="navigation__item">
                            <a href="#" class="navigation__link">Lorem</a>
                        </li>
                    </ul>
                </nav>
                <a href="#" class="header-bottom__icon">
                    <i class="fas fa-shopping-bag"></i>
                    <span class="badge"><?php if (isset($_SESSION['shopping_cart'])) {echo count($_SESSION['shopping_cart']);} else {echo '0';} ?></span>
                </a>
            </div>
        </div>
    </header>
    
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
                <div class="shopping-box" short-id="' + value['short_id'] + '"><div class="corner"><i class="fas fa-cart-plus"></i></div><img src="' + value.image_link_mb + '" alt="Jewellery image" class="shopping-box__image"><div class="box-details"><p class="box-details__title">' + title + '<span class="tooltiptext">' + value.title + '</span></p><div class="box-details-prices"><h6 class="box-details-prices__crossed">' + value.full_price + '</h6><h6 class="box-details-prices__price">' + value.best_price + '</h6></div></div></div>
                </div>

                <div class="popup-overlay"></div>
                <div class="shopping__popup"></div>

                <div class="cart">
                    <i class="fas fa-times cart__close-btn"></i>
                    <div class="cart-details">
                        <h3 class="cart-details__title">Your Cart</h3>
                        <form method="post" class="cart-details__form">
                            <div class="items">
                                <div class="item">
                                    <div class="item__image">
                                        <img src="./img/jewelry_1.png" alt="Cart image">
                                    </div>
                                    <div class="item__details">
                                        <h3 class="item__name">Product name</h3>
                                        <input type="number" name="quantity" class="item__quantity" min="1">
                                        <h3 class="item__price">400$</h3>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="item__image">
                                        <img src="./img/jewelry_1.png" alt="Cart image">
                                    </div>
                                    <div class="item__details">
                                        <h3 class="item__name">Product name</h3>
                                        <input type="number" name="quantity" class="item__quantity" min="1">
                                        <h3 class="item__price">400$</h3>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="item__image">
                                        <img src="./img/jewelry_1.png" alt="Cart image">
                                    </div>
                                    <div class="item__details">
                                        <h3 class="item__name">Product name</h3>
                                        <input type="number" name="quantity" class="item__quantity" min="1">
                                        <h3 class="item__price">400$</h3>
                                    </div>
                                </div>
                            </div>
                            <h3 class="cart-details__total">
                                Total:
                                <span class="cart-details__total cart-details__total--value">0.00$</span>
                            </h3>
                            <h3 class="cart-details__comment">Leave additional comment:</h3>
                            <textarea name="comment" rows="6" class="cart-details__comment-text"></textarea>
                            <button type="submit" class="cart-details__order-btn">Send Order</button>
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
    <script src="js/scripts.js"></script>
</body>
</html>