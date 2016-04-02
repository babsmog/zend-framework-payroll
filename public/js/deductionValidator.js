var isValid = true;


function deductionValidateName(element){
  var value = element.value;
  var pattern = /^[A-Za-z]+(\s*|\s+[0-9]*|\s+[A-Za-z]*|[A-Za-z\s]*)$/;
  var name = element.name;

  element.style.backgroundColor = "white";
  var message = document.getElementById(name).innerHTML = "";

  if (value==""){
    element.style.backgroundColor = "pink";
    message = document.getElementById(name).innerHTML = "required field";
    isValid = false;
    return null;
  }
  if (!pattern.test(value.trim())){
    element.style.backgroundColor = "pink";
    message = document.getElementById(name).innerHTML = "invalid format";
    isValid = false;
    return null;
  }
  if (value.length>100){
    element.style.backgroundColor = "pink";
    message = document.getElementById(name).innerHTML = "name too long";
    isValid = false;
    return null;
  }
  isValid = true;
  return null;
}


function deductionValidatePercentage(element){
  var value = element.value;
  var pattern = /^[0-9]+(.[0-9]+|[0-9]*)$/;
  var name = element.name;
  var fixedAmountField = document.getElementsByName('fixed_amount')[0].value;

  element.style.backgroundColor = "white";

  var message = document.getElementById(name).innerHTML = "";
  var message = document.getElementById('fixed_amount').innerHTML = "";

  if (!value && !fixedAmountField){
    element.style.backgroundColor = "pink";
    message = document.getElementById(name).innerHTML = "Both deduction percentage and fixed amount cannot be empty at the same time.";
    message = document.getElementById('fixed_amount').innerHTML = "Both deduction percentage and fixed amount cannot be empty at the same time.";
    isValid = false;
    return null;
  }

  if (value && fixedAmountField){
    element.style.backgroundColor = "pink";
    message = document.getElementById(name).innerHTML = "Both deduction percentage and fixed amount cannot be entered at the same time.";
    message = document.getElementById('fixed_amount').innerHTML = "Both deduction percentage and fixed amount cannot be entered at the same time.";
    isValid = false;
    return null;
  }
  if (!pattern.test(value) && value){
    element.style.backgroundColor = "pink";
    message = document.getElementById(name).innerHTML = "must be a proper decimal value from 0 to 100 [54.3, 10, 99,100]";
    isValid = false;
    return null;
  }
  if ((value < 0 || value > 100) && value){
    element.style.backgroundColor = "pink";
    message = document.getElementById(name).innerHTML = "rate out of range [0-100]";
    isValid = false;
    return null;
  }
  isValid = true;
  return null;
}

function deductionValidateAmount(element){
  var value = element.value;
  var pattern = /^[0-9]+(.[0-9]+|[0-9]*)$/;
  var name = element.name;
  var percentageField = document.getElementsByName('deduction_percentage')[0].value;

  element.style.backgroundColor = "white";
  var message = document.getElementById(name).innerHTML = "";
  var message = document.getElementById('deduction_percentage').innerHTML = "";

  if (!value && !percentageField){
    element.style.backgroundColor = "pink";
    message = document.getElementById(name).innerHTML = "Both deduction percentage and fixed amount cannot be empty at the same time.";
    message = document.getElementById('deduction_percentage').innerHTML = "Both deduction percentage and fixed amount cannot be empty at the same time.";
    isValid = false;
    return null;
  }
  if (value && percentageField){
    element.style.backgroundColor = "pink";
    message = document.getElementById(name).innerHTML = "Both deduction percentage and fixed amount cannot be entered at the same time.";
    message = document.getElementById('deduction_percentage').innerHTML = "Both deduction percentage and fixed amount cannot be entered at the same time.";
    isValid = false;
    return null;
  }
  if (!pattern.test(value) && value){
    element.style.backgroundColor = "pink";
    message = document.getElementById(name).innerHTML = "must be a proper decimal value [54.3, 10, 999,100]";
    isValid = false;
    return null;
  }
  if ((value < 0 || value > 9999999.99) && value){
    element.style.backgroundColor = "pink";
    message = document.getElementById(name).innerHTML = "out of range [0-9999999.99]";
    isValid = false;
    return null;
  }
  isValid = true;
  return null;
}



function isFormValid(){
  if (isValid) {
    return true;
  }
  alert("Validation failed!");
  return false;
}
