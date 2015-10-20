console.log("Home.js has been loaded");
console.log(myip);

function isValidEmailAddress(emailAddress) {
    var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
    return pattern.test(emailAddress);
};

$(document).ready(function () {

    $("#loginform").keyup(function (e) {
      if (e.keyCode == 13) {
      $("#login").click();
      }
  });

  $("#regform").keyup(function (e) {
    if (e.keyCode == 13) {
    $("#register").click();
    }
});

  $("#login").click(function(evt){
    evt.preventDefault();
    $('input[type="text"]').css("background","rgb(30,30,30)");
    $('input[type="password"]').css("background","rgb(30,30,30)");
    var email = $("#email").val();
    var password = $("#password").val();
    // Checking for blank fields.
      if( email =='' && password ==''){
      $('input[type="text"]').css("background","rgb(202,69,112)");
      $('input[type="text"]').attr("placeholder", "Email ?");
      $('input[type="text"]').focus();
      $('input[type="password"]').css("background","rgb(202,69,112)");
      $('input[type="password"]').attr("placeholder", "Password ?");
      }else if( email =='')
      {
        $('input[type="text"]').css("background","rgb(202,69,112)");
        $('input[type="text"]').attr("placeholder", "Email ?");
        $('input[type="text"]').focus();
      }else if(!isValidEmailAddress(email))
      {
        $('input[type="text"]').val('');
        $('input[type="text"]').css("background","rgb(202,69,112)");
        $('input[type="text"]').attr("placeholder", "Invalid E-mail ID");
        $('input[type="text"]').focus();
      }
      else if( password =='')
      {
        $('input[type="password"]').css("background","rgb(202,69,112)");
        $('input[type="password"]').attr("placeholder", "Password ?");
        $('input[type="password"]').focus();
      }else {
  console.log("done");

  $.ajax({
    type: "POST",
    url: '/authentication',
    data:{
      email: email,
      password: password
    },
    success: function(data){
      var loginCode = JSON.parse(data);
      // console.log(loginCode);
      if(loginCode.code == '200')
      {
      window.location.href = "/dashboard";
      }else if(loginCode.code == '198')
      {
            $('#registerD').hide();
            $('#forgotD').hide();
            $('#tryagain').show();
      }
     console.log(loginCode);
      //alert('success'+JSON.stringify(data));
    },
    error: function(data){
      alert('failure'+JSON.stringify(data));
    }
  });


}
});


$("#register").click(function(evt){
  evt.preventDefault();
  $('input[type="text"]').css("background","rgb(30,30,30)");
  $('input[type="password"]').css("background","rgb(30,30,30)");
  var email = $("#regemail").val();
  var password = $("#regpassword").val();

  // Checking for blank fields.
    if( email =='' && password ==''){
    $('input[type="text"]').css("background","rgb(202,69,112)");
    $('input[type="text"]').attr("placeholder", "Email ?");
    $('input[type="text"]').focus();
    $('input[type="password"]').css("background","rgb(202,69,112)");
    $('input[type="password"]').attr("placeholder", "Password ?");
    }else if( email =='')
    {
      $('input[type="text"]').css("background","rgb(202,69,112)");
      $('input[type="text"]').attr("placeholder", "Email ?");
      $('input[type="text"]').focus();
    }else if(!isValidEmailAddress(email))
    {
      $('input[type="text"]').val('');
      $('input[type="text"]').css("background","rgb(202,69,112)");
      $('input[type="text"]').attr("placeholder", "Invalid E-mail ID");
      $('input[type="text"]').focus();
    }
    else if( password =='')
    {
      $('input[type="password"]').css("background","rgb(202,69,112)");
      $('input[type="password"]').attr("placeholder", "Password ?");
      $('input[type="password"]').focus();
    }else if(password.length < 6 )
    {
      $('input[type="password"]').val('');
      $('input[type="password"]').css("background","rgb(202,69,112)");
      $('input[type="password"]').attr("placeholder", "Minimum 6 letters");
      $('input[type="password"]').focus();
    }
    else {
console.log("done");

$.ajax({
  type: "POST",
  url: '/registration',
  data:{
    email: email,
    password: password,
    ipaddress: myip
  },
  success: function(data){
    var registerCode = JSON.parse(data);
    console.log(registerCode);
    if(registerCode.code == '197')
    {
      $('input[type="text"]').css("background","rgb(202,69,112)");
      $('input[type="text"]').attr("placeholder", "Email already exits");
      $('input[type="text"]').focus();
      $('#alreadyregisterD').hide();
      $('#forgotP').hide();
      $('#alreadyexits').show();
    }else if(registerCode.code == '200')
    {
      $('#alreadyexits').hide();
      $('#register').hide();
      $('#alreadyregisterD').hide();
      $('#forgotP').hide();
      $('#registerDone').show();
      $('#verifyEmail').show();
    }
  //  console.log(loginCode);
    //alert('success'+JSON.stringify(data));
  },
  error: function(data){
    alert('failure'+JSON.stringify(data));
  }
});


}
});




});
