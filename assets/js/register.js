$(document).ready(function(){
    $("#hideLogin").click(function(){
        $("#loginForm").hide();
        $("#registerForm").show();
    });

    $("#hideRegister").click(function(){
        $("#registerForm").hide();
        $("#loginForm").show();
    });
});
