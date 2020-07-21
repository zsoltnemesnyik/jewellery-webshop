export const toggleMobileNav = () => {
    $(this).toggleClass('crossed');
    $('.navigation').toggleClass('open');
};

export const resizeItemDisplay = () => {
    $('.shopping__items').toggleClass('resized');
}

export const toggleShoppingCart = () => {
    $('.cart').toggleClass('visible'); 
    $('.cart-overlay').toggleClass('visible'); 
    
    $('.cart__close-btn, .cart-overlay').on('click', function() {
        $('.cart').removeClass('visible');
        $('.cart-overlay').removeClass('visible'); 
    });
}