export const toggleMobileNav = () => {
    $(this).toggleClass('crossed');
    $('.navigation').toggleClass('open');
};

export const resizeItemDisplay = () => {
    $('.shopping__items').toggleClass('resized');
}

export const toggleShoppingCart = () => {
    $('.cart').toggleClass('visible');    

    $('.cart__close-btn').on('click', function() {
        $('.cart').removeClass('visible');
    });
}