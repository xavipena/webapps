function goNext() {

  console.log("goNext");
  window.location.href = "  check.php";
}

// Focus on password

$('#password').focusin(function(){
  $('form').addClass('up')
});
$('#password').focusout(function(){
  $('form').removeClass('up')
});

// Panda Eye move

$(document).on( "mousemove", function( event ) {
  var dw = $(document).width() / 15;
  var dh = $(document).height() / 15;
  var x = event.pageX/ dw;
  var y = event.pageY/ dh;
  $('.eye-ball').css({
    width : x,
    height : y
  });
});

// validation

function doSubmit() {

  var usr = document.getElementById('username').value;
  var pas = document.getElementById('password').value;
  var ok = usr == "moma" && pas == "moisy"
  if (ok) { 

    document.getElementById('amsg').innerHTML = "Correcte!!";
    form = document.getElementById('panda');
    form.classList.add('right-entry');
    setTimeout(function(){ 
      console.log("goNext");
      goNext();
    },2000 );
  }
  else {

    form = document.getElementById('panda');
    form.classList.add('wrong-entry');
    setTimeout(function(){ 
      form.classList.remove('wrong-entry');
    },3000 );
  }  
}

$('._btn').click(function(){

  var usr = document.getElementById('username').value;
  var pas = document.getElementById('password').value;
  var ok = usr == "moma" && pas == "moisy"
  if (ok) { 
    goNext();
    /*
    document.getElementById('amsg').innerHTML = "Correcte!!";
    $('form').addClass('right-entry');
    setTimeout(function(){ 
      goNext();
    },2000 );
    */
  }
  else {
    $('form').addClass('wrong-entry');
    setTimeout(function(){ 
      $('form').removeClass('wrong-entry');
    },3000 );
  }  
});