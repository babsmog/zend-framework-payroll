var isValid = new Array(false, false, false,false,false); ;


function ageValidator(element){
  var value = element.value;
  var pattern = /^[0-9]+$/;
  var name = element.name;

  element.style.backgroundColor = "white";
  var message = document.getElementById(name).innerHTML = "";

  if (value==""){
    element.style.backgroundColor = "pink";
    message = document.getElementById(name).innerHTML = "required field";
    isValid[2] = false;
    return null;
  }
  if (!pattern.test(value)){
    element.style.backgroundColor = "pink";
    message = document.getElementById(name).innerHTML = "age must be numeric only";
    isValid[2] = false;
    return null;
  }
  if (value < 18 || value > 70){
    element.style.backgroundColor = "pink";
    message = document.getElementById(name).innerHTML = "age out of range [18-70]";
    isValid[2] = false;
    return null;
  }
  isValid[2] = true;
  return null;
}


function nameValidator(element){
  var value = element.value;
  var name = element.name;

  element.style.backgroundColor = "white";
  var message = document.getElementById(name).innerHTML = "";

  if (name==="fname")
  {
    var pattern = /^[A-Za-z]+$/;
    if (value===""){
      element.style.backgroundColor = "pink";
      message = document.getElementById(name).innerHTML = "required field";
      isValid[0] = false;
      return null;
    }
    if (!pattern.test(value)){
      element.style.backgroundColor = "pink";
      message = document.getElementById(name).innerHTML = "name must contain valid characters from your language";
      isValid[0] = false;
      return null;
    }
    if (value.length>30){
      element.style.backgroundColor = "pink";
      message = document.getElementById(name).innerHTML = "name must be at most 30 characters long";
      isValid[0] = false;
      return null;
    }
    isValid[0] = true;
    return null;
  }

  if (name==="lname")
  {
    var pattern = /^[A-Za-z]+$/;
    if (value===""){
      element.style.backgroundColor = "pink";
      message = document.getElementById(name).innerHTML = "required field";
      isValid[1] = false;
      return null;
    }
    if (!pattern.test(value)){
      element.style.backgroundColor = "pink";
      message = document.getElementById(name).innerHTML = "name must contain valid characters from your language";
      isValid[1] = false;
      return null;
    }
    if (value.length>30){
      element.style.backgroundColor = "pink";
      message = document.getElementById(name).innerHTML = "name must be at most 30 characters long";
      isValid[1] = false;
      return null;
    }
    isValid[1] = true;
    return null;
  }


  if (name==="street_name")
  {
    var pattern = /^([0-9]+|[0-9]+\/[0-9]+)\s+[A-Za-z]+[A-Za-z\s.]*$/;
    if (value===""){
      element.style.backgroundColor = "pink";
      message = document.getElementById(name).innerHTML = "required field";
      isValid[3] = false;
      return null;
    }
    if (!pattern.test(value)){
      element.style.backgroundColor = "pink";
      message = document.getElementById(name).innerHTML = "format: [street number] [street name]";
      isValid[3] = false;
      return null;
    }
    if (value.length>100){
      element.style.backgroundColor = "pink";
      message = document.getElementById(name).innerHTML = "should be at most 100 characters long";
      isValid[3] = false;
      return null;
    }
    isValid[3] = true;
    return null;
  }


  if (name==="community")
  {
    var pattern = /^[A-Za-z]+[\sA-Za-z0-9]*$/;
    if (value==""){
      element.style.backgroundColor = "pink";
      message = document.getElementById(name).innerHTML = "required field";
      isValid[4] = false;
      return null;
    }
    if (!pattern.test(value)){
      element.style.backgroundColor = "pink";
      message = document.getElementById(name).innerHTML = "invalid format";
      isValid[4] = false;
      return null;
    }
    if (value.length>100){
      element.style.backgroundColor = "pink";
      message = document.getElementById(name).innerHTML = "should be at most 100 characters long";
      isValid[4] = false;
      return null;
    }
    isValid[4] = true;
    return null;
  }
}



function isFormValid(){
  for (i = 0; i < isValid.length; i++) {
    if (isValid[i]==false){
      alert("Validation failed!");
      return false;
    }
  }
  return true;
}
