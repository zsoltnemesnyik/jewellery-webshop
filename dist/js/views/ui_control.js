export const toggleMobileNav = () => {
    $(this).toggleClass('crossed');
    $('.navigation').toggleClass('open');
};

export const toggleShoppingCart = () => {
    $('.cart').toggleClass('visible'); 
    $('.cart-overlay').toggleClass('visible'); 
    
    $('.cart__close-btn, .cart-overlay').on('click', function() {
        $('.cart').removeClass('visible');
        $('.cart-overlay').removeClass('visible'); 
    });
}

export const toggleSelectOption = (nodes, selNode) => {
    nodes.removeAttr('selected');
    selNode.attr('selected', 'selected');

    return selNode.val()
}