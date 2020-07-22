export default {
    custName: $('input[name=name]'),
    custEmail: $('input[name=email]'),
    custPhone: $('input[name=phone]'),
    custAddress: $('input[name=address]'),
    custComment: $('.form-group__input--textarea'),
    inputFields: Array.from($('form input:not(.form-group__input--submit)'))
}