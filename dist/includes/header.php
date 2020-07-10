<header class="header">
    <div class="container container--fluid">
        <div class="mobile-nav">
            <span class="mobile-nav__icon"></span>
        </div>
        <div class="brand">
            <img src="img/logo.png" alt="Logo" class="brand__logo"/>
            <h1 class="brand__title">Jewelry Webshop</h1>
        </div>
        <nav class="navigation">
            <ul class="navigation__menu">
                <li class="navigation__item">
                    <a href="index.php" class="navigation__link">Home</a>
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
            </ul>
        </nav>
        <a href="#" class="header__icon">
            <i class="fas fa-shopping-bag"></i>
            <span class="badge"><?php if (isset($_SESSION['shopping_cart'])) {echo count($_SESSION['shopping_cart']);} else {echo '0';} ?></span>
        </a>
    </div>
</header>