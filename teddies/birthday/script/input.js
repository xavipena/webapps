function OTPInput() {

    const inputs = document.querySelectorAll('#otp > *[id]');
    for (let i = 0; i < inputs.length; i++) {
  
      inputs[i].addEventListener('keydown', function(event) {
        console.log("key:" + event.key + ", code:" + event.keyCode);
        if (event.key === "Backspace") {
          inputs[i].value = '';
          if (i !== 0)
            inputs[i - 1].focus();
        } else {
          if (i === inputs.length - 1 && inputs[i].value !== '') {
            return true;
          } else if (event.keyCode > 47 && event.keyCode < 58) {
            inputs[i].value = event.key;
            if (i !== inputs.length - 1)
              inputs[i + 1].focus();
            event.preventDefault();
          } else if (event.keyCode > 64 && event.keyCode < 91) {
            inputs[i].value = String.fromCharCode(event.keyCode);
            if (i !== inputs.length - 1)
              inputs[i + 1].focus();
            event.preventDefault();
          } // teclat numèric 96..105 
          else if (event.keyCode >= 96 && event.keyCode <= 105) {
            inputs[i].value = String.fromCharCode(event.keyCode - 48);
            if (i !== inputs.length - 1)
              inputs[i + 1].focus();
            event.preventDefault();}
        }
      });
    }
}

function ValidateOPT() {

  var fullcode = "";
  const inputs = document.getElementsByName('ibox');
  for (let i = 0; i < inputs.length; i++) {
    //console.log(i + ":" + inputs[i].value);
    fullcode += inputs[i].value;
  }
  console.log("*" + fullcode + "*");
  if (fullcode.trim() == "280125") {
    window.location.href = "inici.html";
    return;
  }
  alert("Codi incorrecte");
  window.location.href = "../stuff.php";
}