var isValid = true;


function rateValidator(element){
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
  if (!pattern.test(value)){
    element.style.backgroundColor = "pink";
    message = document.getElementById(name).innerHTML = "rate must be a proper decimal value [10, 1.0, 0.10]";
    isValid = false;
    return null;
  }
  if (value < 0 || value > 9999999.99){
    element.style.backgroundColor = "pink";
    message = document.getElementById(name).innerHTML = "rate out of range [0-9999999.99]";
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
