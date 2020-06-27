"use strict";
var domController = (function($) {
    // Display the mobile nav icon and menu
    $('.mobile-nav').on('click', function() {
        $(this).toggleClass('crossed');
        $('.navigation').toggleClass('open');
    });

    // Resize Shopping items view
    $('body').on('click', '.shopping-info__resize', function() {
        $('.shopping__items').toggleClass('resized');
    });

    // Toggle shopping-cart on click
    $('body').on('click', '.header-bottom__icon', function() {
        $('.cart').toggleClass('visible');

        $('.cart__close-btn').on('click', function() {
            $('.cart').removeClass('visible');
        })
    });

    // Close description on click
    $('body').on('click', function(e) {
        if (e.target.closest('.shopping__popup-close') || e.target.closest('.popup-overlay')) {
            $('.popup-overlay').removeClass('visible');
        }
    });

    return {
        // Empty the shown items
        emptyItems: function() {
            $('.shopping__items').html('');
        },
        // Display the items, description, results
        displayItemsResultsDescriptions: function(data, titleLimit=35) {
            // Display the items
            $.each(data, function(key, value) {
                let title = (value.title.length < titleLimit) ? value.title : value.title.substring(0,titleLimit-1) + '...';
                let dataItem = '<div class="shopping-box" short-id="' + value['short_id'] + '"><div class="corner"><i class="fas fa-cart-plus"></i></div><img src="' + value.image_link_mb + '" alt="Jewellery image" class="shopping-box__image"><div class="box-details"><p class="box-details__title">' + title + '<span class="tooltiptext">' + value.title + '</span></p><div class="box-details-prices"><h6 class="box-details-prices__crossed">' + value.full_price + '</h6><h6 class="box-details-prices__price">' + value.best_price + '</h6></div></div></div>';
                $('.shopping__items').append(dataItem);

                // Display item description on click
                $('body').on('click', '.shopping-box__image', function(e) {
                    $('.popup-overlay').addClass('visible');
                    let shortID = $(this.parentElement).attr('short-id');
        
                    if (value['short_id'] == shortID) {
                        let descItem = '<a class="shopping__popup-close"><i class="fas fa-times"></i></a><p class="shopping__popup-text">' + value.description + '</p><a href="' + value.link_mb + '" class="shopping__popup-btn" target="_blank">Buy Now</a>';
                        
                        $('.shopping__popup').html(descItem);
                    }
                });
            });

            // Display the number of items
            $('.shopping-info__results-number').html(data.length + ' results');
        },
        // Toggle selected attribute of clicked el
        toggleSelAttr: function(selNode, param) {
            $(selNode).removeAttr('selected');
            let selectedNode = $(selNode + '[value=' + param + ']').attr('selected', 'selected');

            return selectedNode.val()
        }
    }
}(jQuery));

var calcController = (function($) {
    // Dynamic compare function
    function dynamicSort(property, order) {
        var sort_order = 1;
        
        if(order === "desc"){
            sort_order = -1;
        }

        return function (a, b){
            // a should come before b in the sorted order
            if(a[property] < b[property]){
                return -1 * sort_order;
                // a should come after b in the sorted order
            }else if(a[property] > b[property]){
                return 1 * sort_order;
                // a and b are the same
            }else{
                return 0 * sort_order;
            }
        }
    }

    return {
        // Sort function
        sortArray: function(arr, prop, ord) {
            return Array.prototype.slice.call(arr).sort(dynamicSort(prop, ord))
        },
        // Filter the given array by value and string
        filterArray: function(arr, searchedString, valueProperty) {
            arr = $.map(arr, function(el) {
                if (el[valueProperty].toLowerCase().indexOf(searchedString) >= 0) {
                    return el
                }
            });

            return arr
        },
        // Initialize variables
        init: function() {
            let state = {
                order: 'default',
                material: 'default',
                modArr: [],
                sorted: false,
                filtered: false
            }

            return state
        },
        stripTags: function strip_html_tags(str)
        {
           if ((str===null) || (str===''))
               return '';
          else
           str = str.toString();
          return str.replace(/<[^>]*>/g, '');
        }
    }
}(jQuery));

var Controller = (function($, domCtrl, calcCtrl) {
    let state, selectedSortOption, selectedFilterOption, items;

        // 1.) Initialization
            // Empty the items list in the DOM
            domCtrl.emptyItems();

            // 2.) Init state variable to default values
            state = calcCtrl.init();
        
        // 2.) Make default request
        $.getJSON('https://cors-anywhere.herokuapp.com/https://www.nemesnyikzsolt.hu/products.json', function(items){
            // security check if item's data is in correct format and doesn't contain harmful code
            items = $.map(items, function(el) {
                if (!isNaN(el.best_price) && !isNaN(el.full_price) && typeof el.description == 'string' && typeof el.title == 'string') {
                    Object.keys(el).forEach((key) => {
                        el[key] = calcCtrl.stripTags(el[key]);
                });

                    $.each(el, function(val) {
                        el.best_price = parseFloat(el[val]);
                    })

                    return el
                }
            });

            // Check if page is refreshed
            if (sessionStorage.length != 0) {
                state.modArr = JSON.parse(sessionStorage.getItem('state')).modArr;
                state.order = JSON.parse(sessionStorage.getItem('state')).order;
                state.material = JSON.parse(sessionStorage.getItem('state')).material;
                state.filtered = JSON.parse(sessionStorage.getItem('state')).filtered;
                state.sorted = JSON.parse(sessionStorage.getItem('state')).sorted;
                
                if (state.order != 'default') {
                    domCtrl.toggleSelAttr('.select-sort__option', state.order);
                }
    
                if (state.material != 'default') {
                    domCtrl.toggleSelAttr('.select-filter__option', state.material);
                }

                domCtrl.displayItemsResultsDescriptions(state.modArr);
            } else {
                domCtrl.displayItemsResultsDescriptions(items);
            }
            
            // Sort the items on click, according to selected option's value
            $('.select-sort').on('change', function() {
                // 1.) Toggle selected attribute on click
                selectedSortOption = domCtrl.toggleSelAttr('.select-sort__option', $(this).val());

                // 2.) Check if the sort option is set to default and if the items are filtered
                if (selectedSortOption == 'default') {
                    state.filtered != false ? state.modArr =  calcCtrl.filterArray(items, state.material, 'material') : state.modArr = items;

                // Check if the items are already filtered
                } else if (state.filtered != false) {
                    // Sort the items
                    state.modArr = calcCtrl.sortArray(state.modArr, 'best_price', selectedSortOption);

                } else {
                    state.modArr = calcCtrl.sortArray(items, 'best_price', selectedSortOption);
                }
                
                // 3.) Empty the itemlist and display the sorted items
                domCtrl.emptyItems();
                domCtrl.displayItemsResultsDescriptions(state.modArr);

                //. 4) Update the state object
                state = {
                    order: selectedSortOption,
                    material: state.material,
                    modArr: state.modArr,
                    sorted: selectedSortOption == 'default' ? false : true,
                    filtered: state.filtered
                }

                // 6.) Push the state into sessionStorage
                sessionStorage.setItem('state', JSON.stringify(state));
            });

            // Filter the items on click, according to selected option's value
            $('.select-filter').on('change', function() {
                // 1.) Toggle selected attribute on click
                selectedFilterOption = domCtrl.toggleSelAttr('.select-filter__option', $(this).val());

                // 2.) Check if the filter option is set to default and the array is sorted
                if (selectedFilterOption == 'default') {
                    state.sorted != false ? state.modArr = calcCtrl.sortArray(items, 'best_price', state.order) : state.modArr = items;

                // Check if the items are already sorted
                } else if (state.sorted != false) {
                    // Calculate the sorted items
                    // Filter the sorted items
                    state.modArr = calcCtrl.filterArray(calcCtrl.sortArray(items, 'best_price', state.order), selectedFilterOption, 'material');

                } else {
                    state.modArr = calcCtrl.filterArray(items, selectedFilterOption, 'material');
                }

                // 3.) Empty the itemlist and display the sorted items
                domCtrl.emptyItems();
                domCtrl.displayItemsResultsDescriptions(state.modArr);

                //. 4) Update the state object
                state = {
                    order: state.order,
                    material: selectedFilterOption,
                    modArr: state.modArr,
                    sorted: state.sorted,
                    filtered: selectedFilterOption == 'default' ? false : true
                }

                // 5.) Push the state into sessionStorage
                sessionStorage.setItem('state', JSON.stringify(state));
            })
        });
}(jQuery, domController, calcController));