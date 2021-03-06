export const datePickerConfig = {
  autoClose: true,
  showClearBtn: true,
  format: 'yyyy-mm-d',
  firstDay: 1,
};

if(locale === 'ka') {
  datePickerConfig.i18n = {
    cancel: 'უკან',
    done: 'ოკ',
    clear: 'გასუფთავება',
    monthsShort: [ 'იან', 'თებ', 'მარ', 'აპრ', 'მაი', 'ივნ', 'ივლ', 'აგვ', 'სექ', 'ოქტ', 'ნოე', 'დეკ'],
    months: [ 'იანვარი', 'თებერვალი', 'მარტი', 'აპრილი', 'მაისი', 'ივნისი', 'ივლისი', 'აგვისტო', 'სექტემბერი', 'ოქტომბერი', 'ნოემბერი', 'დეკემბერი'],
    previousMonth: 'წინა თვე',
    nextMonth: 'მომდევნო თვე',
    weekdays: ['კვირა', 'ორშაბათი', 'სამშაბათი', 'ოთხშაბათი, ხუთშაბათი', 'პარასკევი', 'შაბათი'],
    weekdaysShort: ['კვ', 'ორშ', 'სამ', 'ოთხ, ხუთ', 'პარ', 'შაბ'],
    weekdaysAbbrev: ['კ', 'ო', 'ს', 'ო', 'ხ', 'პ', 'შ'],
  };
}