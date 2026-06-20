$(document).ready(function(){

    $.ajax({

        url:"Authenticate_adminAPI.php",

        type:"POST",

        dataType:"json",

        success:function(response){

            if(response.status === false)
            {
                showMessage(
                    response.message,
                    false
                );

                setTimeout(function(){

                    window.location.href =
                    "adminLogin.html";

                },3000);

                return;
            }

            performLogout();
        },

        error:function(){

            showMessage(
                "Authentication Failed",
                false
            );

            setTimeout(function(){

                window.location.href =
                "adminLogin.html";

            },3000);
        }
    });

});

function performLogout()
{
    $.ajax({

        url:"admin_logout_API.php",

        type:"POST",

        dataType:"json",

        success:function(response){

            showMessage(
                response.message,
                response.status
            );

            setTimeout(function(){

                window.location.href =
                "adminLogin.html";

            },3000);
        },

        error:function(){

            showMessage(
                "Logout Failed",
                false
            );

            setTimeout(function(){

                window.location.href =
                "adminLogin.html";

            },3000);
        }
    });
}

function showMessage(message,status)
{
    $("#messageBox")
    .removeClass("hidden")
    .removeClass(
        "bg-red-500 bg-green-500"
    )
    .addClass(
        status
        ? "bg-green-500"
        : "bg-red-500"
    )
    .text(message);
    $("#statusText").text("Logout successfull");

$("#messageBox")
.removeClass("hidden")
.addClass("bg-green-600")
.text("Redirecting...");
}
