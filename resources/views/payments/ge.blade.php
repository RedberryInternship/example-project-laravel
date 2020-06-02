
<!-- saved from url=(0111)file:///Users/george/Downloads/ipay-2_0-All_w_cardholder_r/ipay-2_0-All_w_cardholder_r/dev/test/ge/page-1a.html -->
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta name="viewport" content="width=device-width, initial-scale=1"><title>მონაცემების შეყვანა და ავტორიზაცია</title><link rel="stylesheet" type="text/css" href="{{ asset('') . 'gc/ge_files' }}/reset.css"><link rel="stylesheet" type="text/css" href="{{ asset('') . 'gc/ge_files' }}/style.css"></head><body id="thebody"><div class="container"><div class="window"><div class="center"><img src="{{ asset('') . 'gc/ge_files' }}/logo.gif" class="merchant-logo" alt="logo"></div><div class="order" id="purchase"><div><div class="title amount">თანხა</div><div class="purchase-amount">$23.85</div></div><div id="pay-bonus-msg-wrapper"><div class="title amount">ქულა</div><div class="purchase-amount">11.93<img src="{{ asset('') . 'gc/ge_files' }}/bonus-plus-logo.png" width="28" border="0" style="padding-left: 6px;"></div></div><div class="description"><div class="title desc">აღწერა</div><div class="purchase-description">ISBN: 1445233434-45, 4457216346-42</div></div></div><script>
  var gamblingBinRanges ="";
  function chengeCVVDesc(num) {
  $el_cvv_desc = $('.cvc-info');
  if (num===4) {
  $el_cvv_desc.html('ბარათის წინა<br> მხარეს მოცემული<br> 4 ციფრი');
  }
  else {
  $el_cvv_desc.html('ბარათის უკანა<br> მხარეს მოცემული<br> 3 ციფრი');
  }
  }
</script><div id="offer"><form id="payment-form" name="p.params" action="file:///Users/george/Downloads/ipay-2_0-All_w_cardholder_r/ipay-2_0-All_w_cardholder_r/dev/test/ge/page-1b.html" method="POST" autocomplete="off" novalidate="novalidate"><div class="card"><div class="card-front"><div id="brand-logo" class="brand-logo"></div><div class="input-group"><span id="pan-input" class="form-input"><input id="pan_full" type="hidden" name="p.pan" value="" class="empty"><label for="pan" class="placeholder">ბარათის ნომერი</label><input id="pan" name="src.pan" class="empty" value="" autocomplete="off" required="required" data-rule-pan="true" data-rule-minlength="18" data-msg-required="გთხოვთ, შეავსოთ აღნიშნული ველი" data-msg-pan="ბარათის ნომერი არასწორია" data-msg-pangambling="ბარათი შეზღუდულია აზარტულ თამაშებში გადახდებისათვის" data-msg-minlength1="გთხოვთ, შეიყვანოთ მინიმუმ" data-msg-minlength2="ციფრი" tabindex="1" maxlength="23" aria-required="true"><span class="error-container"></span></span></div><div class="input-group" id="expiry"><input type="hidden" name="p.expiry" value="" class="empty"><label class="input-label">მოქმედების ვადა</label><span id="month-input" class="form-input"><label for="month" class="placeholder">თვე</label><input id="month" name="p.expiry.month" maxlength="2" required="required" data-rule-min="1" data-rule-max="12" data-rule-minlength="2" data-msg="მნიშვნელობა უნდა იყოს 01-დან 12-მდე." data-msg-minlength="შეიყვანეთ 2 ციფრი" data-msg-required="გთხოვთ, შეავსოთ აღნიშნული ველი" data-next-elem="year" value="" tabindex="2" autocomplete="off" class="empty" aria-required="true"><span class="error-container"></span></span><span class="slash">/</span><span id="year-input" class="form-input"><label for="year" class="placeholder">წელი</label><input id="year" name="p.expiry.year" maxlength="2" class="empty" required="required" data-rule-min="20" data-rule-minlength="2" data-msg="ბარათის ვადა ამოწურულია" data-msg-minlength="შეიყვანეთ 2 ციფრი" data-msg-required="გთხოვთ, შეავსოთ აღნიშნული ველი" data-next-elem="cvc" value="" tabindex="3" autocomplete="off" aria-required="true"><span class="error-container"></span></span><div class="cvc"><label class="cvc-info">ბარათის უკანა<br> მხარეს მოცემული<br> 3 ციფრი</label><span id="cvc-input" class="form-input"><label for="cvc" class="placeholder">CVV</label><input id="cvc" name="p.cvv2" maxlength="4" type="password" class="empty" required="required" data-rule-minlength="3" data-msg-minlength1="შეიყვანეთ" data-msg-minlength2="ციფრი" data-msg-required="გთხოვთ, შეავსოთ აღნიშნული ველი" data-next-elem="name" value="" tabindex="4" autocomplete="off" aria-required="true"><span class="error-container"></span></span></div></div><div class="input-group" id="cardholder"><label class="input-label">მფლობელი</label><span id="cardholdername-input" class="form-input"><label for="name" class="placeholder">მფლობელი</label><input id="name" name="p.cardholder" maxlength="24" class="empty" required="required" data-msg-required="გთხოვთ, შეავსოთ აღნიშნული ველი" data-msg-chars="აღნიშნული ველის შევსება უნდა მოხდეს ლათინურად" value="" tabindex="5" aria-required="true"><span class="error-container"></span></span></div></div></div><div id="pay-bonus-wrapper" style="display: none;"><input type="hidden" name="p.paybonus" value="N"><input type="checkbox" id="payBonusPoints"><label for="payBonusPoints"><span class="bold">PLUS</span> ქულებით გადახდა</label></div><script>
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
              
          </script><div class="btn-group"><input class="btn btn-primary" type="submit" tabindex="8" value="შენახვა"><input class="btn button_cancel" type="submit" name="p.cancel" value="გაუქმება" formnovalidate="formnovalidate" tabindex="9"></div></form></div></div></div><div class="footer"><p class="no-print">მონაცემების გადაცემის უსაფრთხოება ინტერნეტ არხის საშუალებით უზრუნველყოფილია TLS პროტოკოლით</p><p class="no-print">მონაცემები დაცულია საერთაშორისო სტანდარტის PCI DSS-ის მიხედვით</p><div class="secure-logo no-print"><img src="{{ asset('') . 'gc/ge_files' }}/secure-logo.png" alt="secure-logo"></div></div><script src="{{ asset('') . 'gc/ge_files' }}/jquery-1.11.1.js"></script><script src="{{ asset('') . 'gc/ge_files' }}/jquery.validate.js"></script><script src="{{ asset('') . 'gc/ge_files' }}/jquery.mask.js"></script><script src="{{ asset('') . 'gc/ge_files' }}/script.js"></script><div id="weava-permanent-marker" date="1591099289481"></div><div id="weava-ui-wrapper"><div class="weava-drop-area-wrapper"><div class="weava-drop-area"></div>
<div class="weava-drop-area-text">Drop here!</div></div></div></body></html>