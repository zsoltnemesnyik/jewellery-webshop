import * as ui from './views/ui_control.js';

// var calcController = (function($) {
//     // Dynamic compare function
//     function dynamicSort(property, order) {
//         var sort_order = 1;
        
//         if(order === "desc"){
//             sort_order = -1;
//         }

//         return function (a, b){
//             // a should come before b in the sorted order
//             if(a[property] < b[property]){
//                 return -1 * sort_order;
//                 // a should come after b in the sorted order
//             }else if(a[property] > b[property]){
//                 return 1 * sort_order;
//                 // a and b are the same
//             }else{
//                 return 0 * sort_order;
//             }
//         }
//     }

//     return {
//         // Sort function
//         sortArray: function(arr, prop, ord) {
//             return Array.prototype.slice.call(arr).sort(dynamicSort(prop, ord))
//         },
//         // Filter the given array by value and string
//         filterArray: function(arr, searchedString, valueProperty) {
//             arr = $.map(arr, function(el) {
//                 if (el[valueProperty].toLowerCase().indexOf(searchedString) >= 0) {
//                     return el
//                 }
//             });

//             return arr
//         },
//         // Initialize variables
//         init: function() {
//             let state = {
//                 order: 'default',
//                 material: 'default',
//                 modArr: [],
//                 sorted: false,
//                 filtered: false
//             }

//             return state
//         },
//         stripTags: function strip_html_tags(str)
//         {
//            if ((str===null) || (str===''))
//                return '';
//           else
//            str = str.toString();
//           return str.replace(/<[^>]*>/g, '');
//         }
//     }
// }(jQuery));

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
        let comment = $('.cart-details__comment-text').val();
        let action = 'send_order';

        if($('.items')[0].childElementCount !== 0) {
            if(confirm('Do you really want to send your order?')) {
                $.ajax({
                    url: './includes/send_order.php',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        comment: comment,
                        action: action
                    },
                    success: function(data) {
                        console.log(data);
                        $.map(data['errors'], (key, value) => {
                            $(`.item__error[data-quantity-error="${value}"]`).toggleClass('display');
                            $(`.item__error[data-quantity-error="${value}"]`).text(`${key}`);
                        });
                        
                        if (data === 'order_success') {
                            window.location.href="../dist/pages/finish_order.php";
                        }
                    },
                    error: function(data) {
                        console.log(data);
                        console.log('hiba');
                    }
                });
            } else {
                return false
            }
        }
    });
    
}(jQuery));
//     // let state, selectedSortOption, selectedFilterOption, items;

//         // // 1.) Initialization
//         //     // Empty the items list in the DOM
//         //     domCtrl.emptyItems();

//         //     // 2.) Init state variable to default values
//         //     // state = calcCtrl.init();
        
//         // // 2.) Make default request
//         // $.getJSON('https://cors-anywhere.herokuapp.com/https://www.nemesnyikzsolt.hu/products.json', function(items){
//         //     // security check if item's data is in correct format and doesn't contain harmful code
//         //     items = $.map(items, function(el) {
//         //         if (!isNaN(el.best_price) && !isNaN(el.full_price) && typeof el.description == 'string' && typeof el.title == 'string') {
//         //             Object.keys(el).forEach((key) => {
//         //                 el[key] = calcCtrl.stripTags(el[key]);
//         //         });

//         //             $.each(el, function(val) {
//         //                 el.best_price = parseFloat(el[val]);
//         //             })

//         //             return el
//         //         }
//         //     });

//         //     // Check if page is refreshed
//         //     if (sessionStorage.length != 0) {
//         //         state.modArr = JSON.parse(sessionStorage.getItem('state')).modArr;
//         //         state.order = JSON.parse(sessionStorage.getItem('state')).order;
//         //         state.material = JSON.parse(sessionStorage.getItem('state')).material;
//         //         state.filtered = JSON.parse(sessionStorage.getItem('state')).filtered;
//         //         state.sorted = JSON.parse(sessionStorage.getItem('state')).sorted;
                
//         //         if (state.order != 'default') {
//         //             domCtrl.toggleSelAttr('.select-sort__option', state.order);
//         //         }
    
//         //         if (state.material != 'default') {
//         //             domCtrl.toggleSelAttr('.select-filter__option', state.material);
//         //         }

//         //         // domCtrl.displayItemsResultsDescriptions(state.modArr);
//         //     } else {
//         //         // domCtrl.displayItemsResultsDescriptions(items);
//         //     }
            
//         //     // Sort the items on click, according to selected option's value
//         //     $('.select-sort').on('change', function() {
//         //         // 1.) Toggle selected attribute on click
//         //         selectedSortOption = domCtrl.toggleSelAttr('.select-sort__option', $(this).val());

//         //         // 2.) Check if the sort option is set to default and if the items are filtered
//         //         if (selectedSortOption == 'default') {
//         //             state.filtered != false ? state.modArr =  calcCtrl.filterArray(items, state.material, 'material') : state.modArr = items;

//         //         // Check if the items are already filtered
//         //         } else if (state.filtered != false) {
//         //             // Sort the items
//         //             state.modArr = calcCtrl.sortArray(state.modArr, 'best_price', selectedSortOption);

//         //         } else {
//         //             state.modArr = calcCtrl.sortArray(items, 'best_price', selectedSortOption);
//         //         }
                
//         //         // 3.) Empty the itemlist and display the sorted items
//         //         domCtrl.emptyItems();
//         //         domCtrl.displayItemsResultsDescriptions(state.modArr);

//         //         //. 4) Update the state object
//         //         state = {
//         //             order: selectedSortOption,
//         //             material: state.material,
//         //             modArr: state.modArr,
//         //             sorted: selectedSortOption == 'default' ? false : true,
//         //             filtered: state.filtered
//         //         }

//         //         // 6.) Push the state into sessionStorage
//         //         sessionStorage.setItem('state', JSON.stringify(state));
//         //     });

//         //     // Filter the items on click, according to selected option's value
//         //     $('.select-filter').on('change', function() {
//         //         // 1.) Toggle selected attribute on click
//         //         selectedFilterOption = domCtrl.toggleSelAttr('.select-filter__option', $(this).val());

//         //         // 2.) Check if the filter option is set to default and the array is sorted
//         //         if (selectedFilterOption == 'default') {
//         //             state.sorted != false ? state.modArr = calcCtrl.sortArray(items, 'best_price', state.order) : state.modArr = items;

//         //         // Check if the items are already sorted
//         //         } else if (state.sorted != false) {
//         //             // Calculate the sorted items
//         //             // Filter the sorted items
//         //             state.modArr = calcCtrl.filterArray(calcCtrl.sortArray(items, 'best_price', state.order), selectedFilterOption, 'material');

//         //         } else {
//         //             state.modArr = calcCtrl.filterArray(items, selectedFilterOption, 'material');
//         //         }

//         //         // 3.) Empty the itemlist and display the sorted items
//         //         domCtrl.emptyItems();
//         //         domCtrl.displayItemsResultsDescriptions(state.modArr);

//         //         //. 4) Update the state object
//         //         state = {
//         //             order: state.order,
//         //             material: selectedFilterOption,
//         //             modArr: state.modArr,
//         //             sorted: state.sorted,
//         //             filtered: selectedFilterOption == 'default' ? false : true
//         //         }

//         //         // 5.) Push the state into sessionStorage
//         //         sessionStorage.setItem('state', JSON.stringify(state));
//         //     })
//         // });