var isValid = true;


function locationValidator(element){
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



function isFormValid(){
  if (isValid) {
    return true;
  }
  alert("Validation failed!");
  return false;
}
