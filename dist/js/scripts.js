import elements from './views/base.js';
import * as ui_control from './views/ui_control.js';
import * as formcheck from './modules/FormCheck.js';

var Controller = (function($) {
    // Display the mobile nav icon and menu
    $('.mobile-nav').on('click', ui_control.toggleMobileNav);

    // Toggle shopping cart
    $('body').on('click', '.header__icon', ui_control.toggleShoppingCart);
    
    // add to cart
    $('body').on('click', '.corner', function() {
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
        let action = 'send_order';

        if($('.items')[0].childElementCount !== 0 && confirm('Do you really want to send your order?')) {
            $(elements.inputFields).each((_key, value) => {
                if($(value).val() === '') {
                    formcheck.fieldValidate($(value), 'Field can\'t be empty', 'form-group__error display');
                } else {
                    formcheck.fieldValidate($(value), '&nbsp', 'form-group__error');
                }
            });
            if (true) {
                
                $.ajax({
                    url: './includes/send_order.php',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        custName: elements.custName.val(),
                        custPhone: elements.custPhone.val(),
                        custEmail: elements.custEmail.val(),
                        custAddress: elements.custAddress.val(),
                        custComment: elements.custComment.val(),
                        action: action
                    },
                    success: function(data) {
                        $.map(data['errors'], (key, value) => {
                            $(`.item__error[data-quantity-error="${value}"]`).toggleClass('display');
                            $(`.item__error[data-quantity-error="${value}"]`).text(`${key}`);
                        });
                        
                        if (data === 'order_success') {
                            window.location.href="../pages/finish_order.php";
                        }
                    }
                });
            }
        }
    });

    // Change order of elements
    $('#selectSort').on('change', (e) => {
        let sortOption = ui_control.toggleSelectOption($('.select-sort__option'), $(e.target));
          let filterOption = $('#selectFilter').val();
  
          $.ajax({
              beforeSend: function() {
                  $('.shopping-box').toggleClass('visible');
              },
              url: './includes/items.php',
              method: 'POST',
              dataType: 'json',
              data: {
                  sort: sortOption,
                  filter: filterOption
              },
              success: function(data) {
                  $('.shopping__items').html(data['shoppingBox']);
                  $('.shopping__results-number').text(data['numResults'] + ' product(s)');
                  setTimeout(() => {
                      $('.shopping-box').toggleClass('visible');
                  });
              }
          });
      });
      $('#selectFilter').on('change', (e) => {
          let filterOption = ui_control.toggleSelectOption($('.select-filter__option'), $(e.target));
          let sortOption = $('#selectSort').val();
          
          $.ajax({
              beforeSend: function() {
                  $('.shopping-box').toggleClass('visible');
              },
              url: './includes/items.php',
              method: 'POST',
              dataType: 'json',
              data: {
                  sort: sortOption,
                  filter: filterOption
              },
              success: function(data) {
                  $('.shopping__items').html(data['shoppingBox']);
                  $('.shopping__results-number').text(data['numResults'] + ' product(s)');
                  setTimeout(() => {
                      $('.shopping-box').toggleClass('visible');
                  });
              }
          });
      });
}(jQuery));