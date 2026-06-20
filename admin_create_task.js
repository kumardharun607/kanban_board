$(document).ready(function () {

    authenticateAdmin();

});

let adminDuplicateId = "";

function authenticateAdmin()
{
    $.ajax({

        url:"Authenticate_adminAPI.php",

        type:"POST",

        dataType:"json",

        success:function(response){

            if(response.status===false)
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

            getAdminDuplicateId();
        }
    });
}

function getAdminDuplicateId()
{
    $.ajax({

        url:
        "admin_request_regisr_id_api.php",

        type:"POST",

        dataType:"json",

        success:function(response){

            if(response.status===false)
            {
                showMessage(
                    response.message,
                    false
                );

                return;
            }

            adminDuplicateId =
            response.admin_duplicate_id;
        }
    });
}

$("#createTaskForm").submit(function(e){

    e.preventDefault();

    $.ajax({

        url:
        "admin_save_created_taskapi.php",

        type:"POST",

        data:{

            admin_duplicate_id:
            adminDuplicateId,

            assigned_id:
            $("#assigned_id").val(),

            project_code:
            $("#project_code").val(),

            task:
            $("#task").val(),

            suggestion:
            $("#suggestion").val()
        },

        dataType:"json",

        success:function(response){

            if(response.status===false)
            {
                showMessage(
                    response.message,
                    false
                );

                return;
            }

            showMessage(
                response.message,
                true
            );

            $("#createTaskForm")[0].reset();

            setTimeout(function(){

                window.location.href =
                "admindashboard.html";

            },1000);
        }
    });

});

function showMessage(
message,
success
)
{
    $("#messageBox")
    .removeClass("hidden")
    .removeClass(
        "bg-red-500 bg-green-500"
    )
    .addClass(
        success
        ?
        "bg-green-500"
        :
        "bg-red-500"
    )
    .text(message);

    setTimeout(function(){

        $("#messageBox")
        .addClass("hidden");

    },3000);
}
$(document).on("click", "#dashboardBtn", function () {

    window.location.href = "admindashboard.html";

});