function formatReal(value) {

  var decimalPoints = 2;
  var decimalSeparator = ',';
  var thousandSeparator = '.';

  var totalValue = parseInt(value * (Math.pow(10, decimalPoints)));
  var entires = parseInt(parseInt(value * (Math.pow(10, decimalPoints))) / parseFloat(Math.pow(10, decimalPoints)));
  var cents = parseInt(parseInt(value * (Math.pow(10, decimalPoints))) % parseFloat(Math.pow(10, decimalPoints)));

  if(cents % 10 == 0 && cents + "".length < 2) {
    cents = cents + "0";
  } else if(cents < 10) {
    cents = "0" + cents;
  }

  var thousands = parseInt(entires / 1000);
  entires = entires % 1000;

  var returnValue = "";

  if(thousands > 0) {
    returnValue = thousands + "" + thousandSeparator + "" + returnValue
    if(entires == 0){
      entires = "000";
    } else if(entires < 10) {
      entires = "00" + entires;
    } else if(entires < 100) {
      entires = "0" + entires;
    }
  }

  returnValue += entires + "" + decimalSeparator + "" + cents;

  return returnValue;
}
