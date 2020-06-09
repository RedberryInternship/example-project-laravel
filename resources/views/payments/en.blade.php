
<!-- saved from url=(0111)file:///Users/george/Downloads/ipay-2_0-All_w_cardholder_r/ipay-2_0-All_w_cardholder_r/dev/test/en/page-1a.html -->
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Input data and authentication</title><link rel="stylesheet" type="text/css" href="{{ asset('') . 'gc/en_files' }}/reset.css"><link rel="stylesheet" type="text/css" href="{{ asset('') . 'gc/en_files' }}/style.css"></head><body id="thebody"><div class="container"><div class="window"><div class="center"><img src="{{ asset('') . 'gc/en_files' }}/logo.gif" class="merchant-logo" alt="logo"></div><div class="order" id="purchase"><div><div class="title amount">Amount</div><div class="purchase-amount">$23.85</div></div><div id="pay-bonus-msg-wrapper"><div class="title amount">Points</div><div class="purchase-amount">11.93<img src="{{ asset('') . 'gc/en_files' }}/bonus-plus-logo.png" width="28" border="0" style="padding-left: 6px;"></div></div><div class="description"><div class="title desc">Description</div><div class="purchase-description">ISBN: 1445233434-45, 4457216346-42</div></div></div><script>
  var gamblingBinRanges ="";
  function chengeCVVDesc(num) {
  $el_cvv_desc = $('.cvc-info');
  if (num===4) {
  $el_cvv_desc.html('4 digits from<br> the front side<br> of the card');
  }
  else {
  $el_cvv_desc.html('3 digits from<br> revers side<br> of the card');
  }
  }
</script><div id="offer"><form id="payment-form" name="p.params" action="file:///Users/george/Downloads/ipay-2_0-All_w_cardholder_r/ipay-2_0-All_w_cardholder_r/dev/test/en/page-1b.html" method="POST" autocomplete="off" novalidate="novalidate"><div class="card"><div class="card-front"><div id="brand-logo" class="brand-logo"></div><div class="input-group"><span id="pan-input" class="form-input"><input id="pan_full" type="hidden" name="p.pan" value="" class="empty"><label for="pan" class="placeholder">Card Number</label><input id="pan" name="src.pan" class="empty" value="" autocomplete="off" required="required" data-rule-pan="true" data-rule-minlength="18" data-msg-required="Please, fill this field" data-msg-pan="Invalid card number" data-msg-pangambling="Restricted card for Gambling Payments" data-msg-minlength1="Please, enter" data-msg-minlength2="digits minimum" tabindex="1" maxlength="23" aria-required="true"><span class="error-container"></span></span></div><div class="input-group" id="expiry"><input type="hidden" name="p.expiry" value="" class="empty"><label class="input-label">Expiry date</label><span id="month-input" class="form-input"><label for="month" class="placeholder">Month</label><input id="month" name="p.expiry.month" maxlength="2" required="required" data-rule-min="1" data-rule-max="12" data-rule-minlength="2" data-msg="Number between 01 and 12" data-msg-minlength="Enter 2 digits" data-msg-required="Please, fill this field" data-next-elem="year" value="" tabindex="2" autocomplete="off" class="empty" aria-required="true"><span class="error-container"></span></span><span class="slash">/</span><span id="year-input" class="form-input"><label for="year" class="placeholder">Year</label><input id="year" name="p.expiry.year" maxlength="2" class="empty" required="required" data-rule-min="20" data-rule-minlength="2" data-msg="Card has expired" data-msg-minlength="Enter 2 digits" data-msg-required="Please, fill this field" data-next-elem="cvc" value="" tabindex="3" autocomplete="off" aria-required="true"><span class="error-container"></span></span><div class="cvc"><label class="cvc-info">3 digits from<br> revers side<br> of the card</label><span id="cvc-input" class="form-input"><label for="cvc" class="placeholder">CVV</label><input id="cvc" name="p.cvv2" maxlength="4" type="password" class="empty" required="required" data-rule-minlength="3" data-msg-minlength1="Enter" data-msg-minlength2="digits" data-msg-required="Please, fill this field" data-next-elem="name" value="" tabindex="4" autocomplete="off" aria-required="true"><span class="error-container"></span></span></div></div><div class="input-group" id="cardholder"><label class="input-label">Cardholder</label><span id="cardholdername-input" class="form-input"><label for="name" class="placeholder">Cardholder</label><input id="name" name="p.cardholder" maxlength="24" class="empty" required="required" data-msg-required="Please, fill this field" data-msg-chars="Only Latin characters are supported" value="" tabindex="5" aria-required="true"><span class="error-container"></span></span></div></div></div><div id="pay-bonus-wrapper" style="display: none;"><input type="hidden" name="p.paybonus" value="N"><input type="checkbox" id="payBonusPoints"><label for="payBonusPoints">Pay with <span class="bold">PLUS</span> points</label></div><script>
              var payBonusBinRanges = "22-33;4-5;6-7";
              
                  function listen(evnt, elem, func) {
                       if (elem.addEventListener)  // W3C DOM
                            elem.addEventListener(evnt, func, false);
                       else if (elem.attachEvent) { // IE DOM
                            var r = elem.attachEvent("on" + evnt, func);
                            return r;
                       }
                  }

                  listen("load", window, function () {
                      detectBonus();

                     listen("keyup", document.getElementsByName("src.pan")[0], function() {
                          detectBonus();
                     });
                     listen("input", document.getElementsByName("src.pan")[0], function() {
                          detectBonus();
                     });
                     listen("click", document.getElementById("payBonusPoints"), function() {
                           document.getElementsByName("p.paybonus")[0].value = this.checked ? "Y" : "N";
                           if (this.checked == true) {
                              $("#pay-bonus-msg-wrapper").show(300);
                          } else {
                              $("#pay-bonus-msg-wrapper").hide(200);
                          }
                     });
                   });


                  function detectBonus() {
                      var pan = document.getElementsByName("src.pan")[0].value.replace(/[^0-9]/g, '');
                          if(pan && validatePan(pan) &&  checkBonusBinRanges(pan)) {
                              //document.getElementById("pay-bonus-wrapper").style.display = "block";
                              $("#pay-bonus-wrapper").show(300);
                          } else {
                                //document.getElementById("pay-bonus-wrapper").style.display = "none";
                                $("#pay-bonus-wrapper").hide(200);
                                $("#pay-bonus-wrapper").hide();
                          }
                  }

                  function checkBonusBinRanges(value) {
                      var isValid = false;
                      var arrBinRanges = payBonusBinRanges.split(";");
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

                  function validatePan(value) {
                      var min = 13, max = 19, b, c, d, e;
                      for (d = +value[b = value.length - 1], e = 0; b--;)
                          c = +value[b], d += ++e % 2 ? 2 * c % 10 + (c > 4) : c;
                      return (value.length >= min && value.length <= max && !(d % 10));
                  }
              
          </script><div class="btn-group"><input class="btn btn-primary" type="submit" tabindex="8" value="Save"><input class="btn button_cancel" type="submit" name="p.cancel" value="Cancel" formnovalidate="formnovalidate" tabindex="9"></div></form></div></div></div><div class="footer"><p class="no-print">Data transfer over the Internet is secured by TLS protocol</p><p class="no-print">Data are protected according to international standard PCI DSS</p><div class="secure-logo no-print"><img src="{{ asset('') . 'gc/en_files' }}/secure-logo.png" alt="secure-logo"></div></div><script src="{{ asset('') . 'gc/en_files' }}/jquery-1.11.1.js"></script><script src="{{ asset('') . 'gc/en_files' }}/jquery.validate.js"></script><script src="{{ asset('') . 'gc/en_files' }}/jquery.mask.js"></script><script src="{{ asset('') . 'gc/en_files' }}/script.js"></script><div id="weava-permanent-marker" date="1591099227581"></div><div id="weava-ui-wrapper"><div class="weava-drop-area-wrapper"><div class="weava-drop-area"></div>
<div class="weava-drop-area-text">Drop here!</div></div></div></body></html>