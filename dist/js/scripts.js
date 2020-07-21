import * as ui from './views/ui_control.js';

var Controller = (function($) {
    // Display the mobile nav icon and menu
    $('.mobile-nav').on('click', ui.toggleMobileNav);

    // Toggle view of items
    $('body').on('click', '.shopping-info__resize', ui.resizeItemDisplay);

    // Toggle shopping cart
    $('body').on('click', '.header__icon', ui.toggleShoppingCart);
    
    // add to cart
    $('.corner').on('click', function() {
        let productID = $(this).attr('id');
        let productName = $('#name' + productID).val();
        let productPrice = $('#price' + productID).val();
        let productQuantity = parseInt($('#quantity' + productID).val());
        let productAvailability = parseInt($('#availability' + productID).text());
        let productMax = $('#quantity' + productID).attr('max');
        let productImage = $('#image'+productID).attr('src');
        let action = 'add';
        
        if (productQuantity > productAvailability) {
            alert('Cannot buy more than what is available!')
        } else if(productQuantity == 0) {
            alert('Please select a number greater than ZERO!');
        } else {
            $.ajax({
                url: './includes/action.php',
                method:"POST",  
                dataType:"json",  
                data:{  
                    productID: productID,   
                    productName: productName,   
                    productPrice: productPrice,   
                    productQuantity: productQuantity,
                    productImage: productImage,
                    productMax: productMax,
                    productAvailability: productAvailability,
                    action: action  
                },  
                success:function(data) {
                    $('.cart-details__order').html(data['orderTable']);  
                    $('.badge').text(data['cartItem']);

                    $('.badge').addClass('added');
                    setTimeout(() => {
                        $('.badge').removeClass('added');
                    }, 750);
                }
            });
        }
    });

    // delete from cart
    $(document).on('click', '.item__delete', function() {
        let productID = $(this).attr('id');
        let productAvailability = parseInt($('#availability' + productID).text());
        let action = 'remove';

        if(confirm('Are you sure you really want to remove this product?')) {
            $.ajax({
                url: './includes/action.php',
                method:"POST",  
                dataType:"json",  
                data: {productID: productID, productAvailability: productAvailability, action: action},
                success:function(data) {
                    $('.cart-details__order').html(data['orderTable']);  
                    $('.badge').text(data['cartItem']);

                    $('.badge').addClass('added');
                    setTimeout(() => {
                        $('.badge').removeClass('added');
                    }, 1500);
                }
            })
        } else {
            return false
        }
    });

    $(document).on('keyup change click', '.quantity', function() {
        let productID = $(this).data('product-id');
        let productQuantity = $(this).val();
        let action = 'quantity_change';
        if (productQuantity != '') {
            $.ajax({
                url: './includes/action.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    productID: productID,
                    productQuantity: productQuantity,
                    action: action
                },
                success: function(data) {
                    $('.cart-details__order').html(data['orderTable']);
                }
            });
        }
    });

    $('#send').on('click', function() {
        let custName = $('input[name=name]').val();
        let custEmail = $('input[name=email]').val();
        let custPhone = $('input[name=phone]').val();
        let custAddress = $('input[name=address]').val();
        let custComment = $('.form-group__input--textarea').val();
        let action = 'send_order';

        if($('.items')[0].childElementCount !== 0) {
            if(confirm('Do you really want to send your order?')) {
                $.ajax({
                    url: './includes/send_order.php',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        custName: custName,
                        custPhone: custPhone,
                        custEmail: custEmail,
                        custAddress: custAddress,
                        custComment: custComment,
                        action: action
                    },
                    success: function(data) {
                        $.map(data['errors'], (key, value) => {
                            $(`.item__error[data-quantity-error="${value}"]`).toggleClass('display');
                            $(`.item__error[data-quantity-error="${value}"]`).text(`${key}`);
                        });
                        
                        if (data === 'order_success') {
                            window.location.href="../dist/pages/finish_order.php";
                        }
                    },
                    error: function(xhr, error, errorthrown) {
                        console.log(xhr);
                        console.log(error);
                        console.log(errorthrown);
                        console.log('hiba');
                    }
                });
            } else {
                return false
            }
        }
    });
    
}(jQuery));