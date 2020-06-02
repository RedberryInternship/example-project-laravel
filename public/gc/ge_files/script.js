$(document).ready(function() {
    var $offer = $('#offer'),
        $offerForm = $('form', $offer),
        $pan = $('[name="src.pan"]', $offer),
        $csc = $('[name="p.cvv2"]', $offer);

    $('#month').mask('99');
    $('#year').mask('99');
    $csc.mask('0000');
    $pan.on('keypress', checkchar);
    $('#pan_masked').on('input paste', detectBrand);
    $pan.on('input paste', detectBrand).mask('0000 0000 0000 0000 000');
    $('input').on('input', emptyMarker).trigger('input');
    $('input').on('keyup', jump);

    $('#year').attr('data-rule-min', String(new Date().getFullYear()).substr(2, 2));
    $('#month, #year').on('input', calcExpiry).trigger('input');

    function detectBrand() {
        var val = $(this).val().replace(/[^0-9]/g, ''),
            val = $.trim(val).replace(" ", "");

        document.forms['p.params'].elements['p.pan'].value = val;

        $el = $('#brand-logo');
        $el_cvv = $('.cvc .placeholder');
        $el_cvv_desc = $('.cvc-info span');
        $el_cvv_input = $('#cvc');
        $el.removeClass('visa mastercard maestro mir amex unknown');
        if (val) {
            var d1 = parseInt(val[0], 10),
                d2 = val.length >= 2 ? parseInt(val.substr(0, 2), 10) : 0,
                d6 = val.length >= 6 ? parseInt(val.substr(0, 6), 10) : 0,
                d9 = val.length >= 9 ? parseInt(val.substr(0, 9), 10) : 0;

            if (d1 == 4) {
                $el.addClass('visa');
                $(this).attr('data-rule-minlength', '16');
                $el_cvv.html('CVV2');
                $el_cvv_input.attr('maxlength', '3');
                chengeCVVDesc(3);
            } else if ((51 <= d2 && d2 <= 55) || (222100 <= d6 && d6 <= 272099)) {
                $el.addClass('mastercard');
                $(this).attr('data-rule-minlength', '18');
                $el_cvv.html('CVC2');
                $el_cvv_input.attr('maxlength', '3');
                chengeCVVDesc(3);
            } else if (d1 == 6 || d2 == 50 || (56 <= d2 && d2 <= 59)) {
                $el.addClass('maestro');
                $(this).attr('data-rule-minlength', '18');
                $el_cvv.html('CVC2');
                $el_cvv_input.attr('maxlength', '3');
                chengeCVVDesc(3);
            } else if ((375121000 <= d9 && d9 <= 375121999) || (379897000 <= d9 && d9 <= 379897999)) {
                $el.addClass('amex');
                $(this).attr('data-rule-minlength', '18');
                $el_cvv.html('CSC');
                $el_cvv_input.attr('maxlength', '4');
                $el_cvv_input.attr('data-rule-minlength', '4');
                chengeCVVDesc(4);
            } else {
                $el.addClass('unknown');
                $(this).attr('data-rule-minlength', '18');
                $el_cvv.html('CVV');
                $el_cvv_input.attr('maxlength', '3');
                chengeCVVDesc(3);
            }
        }
    }

    function checkchar(e) {
        var symbol = (e.which) ? e.which : e.keyCode;
        if ((symbol < 48 || symbol > 57) && (symbol != 35 && symbol != 36 && symbol != 37 && symbol != 39 && symbol != 45 && symbol != 46 && symbol != 8 && symbol != 9 && symbol != 118)) return false;
    }

    function emptyMarker() {
        var $el = $(this);
        $el.val() ? $el.removeClass('empty') : $el.addClass('empty');
    }

    function calcExpiry() {
        var exp = document.forms['p.params'].elements['p.expiry'];
        var eMonth = document.forms['p.params'].elements['p.expiry.month'];
        var eYear = document.forms['p.params'].elements['p.expiry.year'];
        exp.value = eYear.value.concat(eMonth.value);
    }

    function jump(){
        var max = $(this).attr('maxlength');
        var next = $(this).attr('data-next-elem');
        if (next && $(this).val().length >= max) {
            $('#' + next).focus();
        }
    }

    function validatePan(value) {
        var min = 13, max = 19, b, c, d, e;
        for (d = +value[b = value.length - 1], e = 0; b--;)
            c = +value[b], d += ++e % 2 ? 2 * c % 10 + (c > 4) : c;
        return (value.length >= min && value.length <= max && !(d % 10));
    }
    function checkGamblingBinRanges(value, BinRanges) {
        var isValid = false;
        var arrBinRanges = BinRanges.split(";");
        for(var i=0; i<arrBinRanges.length; i++) {
            var range = arrBinRanges[i].split("-");
            var valueForEqual = parseInt(value.substring(0, range[0].length));
            if(valueForEqual >= +range[0] && valueForEqual <= +range[1]) {
                isValid = true;
                break;
            }
        }
        return isValid;
    }

    jQuery.validator.addMethod('pan', function (value, el) {
        var number = $(el).cleanVal();
        var min = 12, max = 19, b, c, d, e;

        for (d = +number[b = number.length - 1], e = 0; b--;)
            c = +number[b], d += ++e % 2 ? 2 * c % 10 + (c > 4) : c;

        return !!(number.length >= min && number.length <= max && !(d % 10));
    });

    jQuery.validator.addMethod('pangambling', function (value, el) {
        if (gamblingBinRanges) {
            var number = $(el).cleanVal();
            if(number && validatePan(number) &&  checkGamblingBinRanges(number, gamblingBinRanges)) {
                //Gambling supported
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    });

    jQuery.validator.addMethod('expiry', function (value) {
        if (/\d\d\/\d\d/.test(value)) {
            var parts = value.split('/'),
                m = parseInt(parts[0], 10),
                y = 2000 + parseInt(parts[1], 10),
                cur = new Date(),
                curM = cur.getMonth() + 1,
                curY = cur.getFullYear();

            return m > 0 && m <= 12 && (y > curY || (y == curY && m >= curM));
        }
        return false;
    });

    $.validator.addMethod(
        "cardholder_chars",
        function(value, element, regexp) {
            var check = false;
            return this.optional(element) || regexp.test(value);
        },
        $('#name').attr('data-msg-chars')
    );

    $offerForm.validate({
        rules: {
            "p.pan": {
                required: true,
                pan: true
            },
            "src.pan": {
                required: true,
                pan: true,
                minlength: function () {
                    var pan = $('[name="p.pan"]').val(),
                        d1 = pan.length >= 1 ? parseInt(pan.substr(0, 1), 10) : 0
                    return (d1 == 4)? 16 : 18;
                },
                pangambling: true
            },
            "p.expiry": {
                required: true,
                expiry: true

            },
            "p.cvv2": {
                required: function () {
                    var pan = $("#pan_full").val(),
                        d1 = pan[0],
                        d2 = pan[1];
                    return d1 != '5' || (d2 > 0 && d2 < 6);
                },
                digits: true,
                minlength: function () {
                    var pan = $('[name="p.pan"]').val(),
                        d9 = pan.length >= 9 ? parseInt(pan.substr(0, 9), 10) : 0
                    return ((375121000 <= d9 && d9 <= 375121999) || (379897000 <= d9 && d9 <= 379897999))? 4 : 3;
                }
            },
            "p.cardholder": {
                maxlength: 24,
                cardholder_chars: /^[a-zA-Z0-9\s\/\.\,#\&\-\_\(\)\+\@\"\<\>:]*$/
            }
        },
        messages: {
            "p.cvv2": {
                minlength: jQuery.validator.format($('#cvc').attr('data-msg-minlength1')+" {0} "+$('#cvc').attr('data-msg-minlength2'))
            },
            "src.pan": {
                minlength: function() {
                    return jQuery.validator.format($('[name="src.pan"]').attr('data-msg-minlength1') + " {0} " + $('[name="src.pan"]').attr('data-msg-minlength2'), $('[name="src.pan"]').attr('data-rule-minlength')-3)
                }
            }
        },

        errorPlacement: function (error, element) {
            error.appendTo(element.next('.error-container'));
        },
        submitHandler: function (form) {
            $("#pan").val("");
            $("#pan_masked").val("");
            form.submit();
        }
    });

});