    //request
  var request = {
    login: false
  };

//iframes
  var iframe = {
    login: document.getElementById('login_iframe')
  };

//forms
  var login_form = document.getElementById('login_form');

//fields
  //login_form fields
    var login_form_input = {
      username: document.getElementById('login_form_username_input'),
      password: document.getElementById('login_form_password_input'),
      submitbtn: '#login_form_submit_btn'
    };

canCloseModal = true;
//variable declaration end.......................................................

login_form.onsubmit = function(){

  if(hasSpace(login_form_input.username.value))
  {
    createmessage(3, 'That is an invalid <b>Username.</b>', true);
    return false;
  }
  if(isWhitespace(login_form_input.password.value))
  {
    createmessage(3, 'You entered a wrong <b>Password.</b>', true);
    return false;
  }
  request.login = true;
  $(login_form_input.submitbtn).button('loading');
  canCloseModal = false;
  return true;
};

iframe.login.onload = function()
{
  var server_message = iframe.login.contentDocument.body.innerHTML;
  if(request.login)
  {
    if(server_message == "admintype")
    {
      window.location = "index.php";
    }
    else if(server_message == "cswdtype")
    {
      window.location = "index.php";
    }
    else if(server_message == "cdrrmotype")
    {
      window.location = "index.php";
    }
    else if(server_message == "brgytype")
    {
      window.location = "index.php";
    }
    else if(server_message == "deactivated")
    {
      createmessage(3, 'Your account is currently deactivated', true);
    }
    else if(server_message == "error")
    {
      createmessage(3, 'Wrong Username or Password', true);
    }
    else if(!isWhitespace(GetSuccessMsg(server_message)))
    {
      createmessage(1, GetSuccessMsg(server_message), true);
    }
    else if(!isWhitespace(GetWarningMsg(server_message)))
    {
      createmessage(2, GetWarningMsg(server_message), true);
    }
    else if(!isWhitespace(GetErrorMsg(server_message)))
    {
      createmessage(3, GetErrorMsg(server_message), true);
    }
    else if(!isWhitespace(GetServerMsg(server_message)))
    {
      createmessage(4, GetServerMsg(server_message), true);
    }
    else
    {
      createmessage(3, '<b>Oh Snap!</b> There is a problem with the server or your connection.', true);
    }

    request.login = false;
    $(login_form_input.submitbtn).button('reset');
    canCloseModal = true;
    iframe.login.src = "";
  }

};

$('#myModal').on('hide.bs.modal', function (e) {
  return canCloseModal;
});


// JavaScript Document
function isWhitespace(text){
    return (text.trim().length == 0);
  }

function hasSpace(text) {
    return (text.split(' ').length > 1);
  }

  function hasvalueof(text, gg) {
    return (text.split(gg).length > 1);
  }

  function pausecomp(millis)
  {
    var date = new Date();
    var curDate = null;

    do { curDate = new Date(); }
    while(curDate-date < millis);
  }

  function pohibitClosing(id, arguu)
  {
    $('#' + id).on('hide.bs.modal', function (e) {
      return arguu;
    });
  }

  function haswrongspaces(txt) {
    var z = false;
    var x = txt.split(' ');
    var y = x.length;
    for(var i = 0; i < y; i++ )
    {
      if(x[i].length < 1)
      {
        z = true;
      }
    }
    return z;
  }

  function invalidNaming(txt) {
    return (haswrongspaces(txt) || isWhitespace(txt));
  }

  function isbelow(num, txt) {
    return txt.length < num;
  }

  function setSpaceToZero(the_input) {
    if(isWhitespace(the_input.value))
    {
      the_input.value = 0;
    }
  }

  function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  }

  var navbarHide = function() {
    "use strict";
  $( document ).ready(function() {
     if ($("nav").offset().top > 0) {
      $("#mainNav").removeClass("navbarTransparent");
     }
    else{
      $("#mainNav").addClass("navbarTransparent");
    }
    });
  };
  
  navbarHide();
  $(window).scroll(navbarHide);
  
  
  