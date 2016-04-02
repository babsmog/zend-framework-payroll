var isValid = true;


function workDoneValidator(element){
  var value = element.value;
  var pattern = /^[0-9]+(.[0-9]+|[0-9]*)$/;
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
    message = document.getElementById(name).innerHTML = "hours worked must be a proper decimal value [10, 1.0, 0.10]";
    isValid = false;
    return null;
  }
  if (value < 0 || value > 999.99){
    element.style.backgroundColor = "pink";
    message = document.getElementById(name).innerHTML = "out of range [0-999.99]";
    isValid = false;
    return null;
  }
  isValid = true;
  return null;
}

function workDoneDateValidator(element){
  var value = element.value;
  var pattern = /^[1-9][0-9][0-9][0-9]\-([0-9][0-9]|[0-9])\-([0-9][0-9]|[0-9])$/;
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
    message = document.getElementById(name).innerHTML = "invalid format. Should be [yyyy-mm-dd]";
    isValid = false;
    return null;
  }
  if ((new Date(value))=='Invalid Date'){
    element.style.backgroundColor = "pink";
    message = document.getElementById(name).innerHTML = "invalid date";
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
