$(document).ready(function () {

    authenticateAdmin();

});

function authenticateAdmin()
{
    $.ajax({

        url: "Authenticate_adminAPI.php",

        type: "POST",

        dataType: "json",

        success: function (response) {

            if(response.status === false)
            {
                showMessage(
                    response.message,
                    false
                );

                setTimeout(function(){

                    window.location.href =
                    "admindashboard.html";

                },3000);

                return;
            }

            loadTaskDetails();
        },

        error: function(){

            window.location.href =
            "adminLogin.html";
        }
    });
}

function loadTaskDetails()
{
    let params =
    new URLSearchParams(
        window.location.search
    );

    let task_id =
    params.get("task_id");

    if(!task_id)
    {
        showMessage(
            "Task Id Missing",
            false
        );

        return;
    }

    $("#submitSuggestion")
    .attr(
        "task_id",
        task_id
    );

    $.ajax({

        url:
        "admin_retrive_suggestionAPI.php",

        type:"POST",

        data:{
            task_id:task_id
        },

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
                    "admindashboard.html";

                },3000);

                return;
            }

            let task =
            response.task;

            $("#created_at")
            .text(task.task_created_at);

            $("#created_id")
            .text(task.created_id);

            $("#project_code")
            .text(task.project_code);

            $("#status")
            .text(task.status);

            $("#task_text")
            .text(task.tasks);

            $("#oldSuggestion")
            .val(
                task.admin_suggestion ??
                ""
            );
        }
    });
}

$(document).on(
"click",
"#submitSuggestion",
function(){

    let task_id =
    $(this).attr("task_id");

    let suggestion =
    $("#newSuggestion").val();

    $.ajax({

        url:
        "updatesuggestion_by_adminAPI.php",

        type:"POST",

        data:{
            task_id:task_id,
            suggestion:suggestion
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

            $("#oldSuggestion")
            .val(
                 response.updated_suggestion
            );

            $("#newSuggestion")
            .val("");
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
        success ?
        "bg-green-500" :
        "bg-red-500"
    )
    .text(message);

    setTimeout(function(){

        $("#messageBox")
        .addClass("hidden");

    },3000);
}