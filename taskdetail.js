$(document).ready(function () {

    $.ajax({

        url: "Authenticate_userAPI.php",

        type: "POST",

        dataType: "json",

        success: function (response) {

            if (!response.status) {

                window.location.href =
                    "userlogin.html";
            }
        },

        error: function () {

            window.location.href =
                "userlogin.html";
        }
    });

    const urlParams =
        new URLSearchParams(
            window.location.search
        );

    const taskId =
        urlParams.get("task_id");

    if (!taskId) {

        $("#messageBox")
            .removeClass("hidden")
            .text("Task Id Missing");

        return;
    }

    $.ajax({

        url: "retrivetaskdetails.php",

        type: "POST",

        data: {
            task_id: taskId
        },

        dataType: "json",

        success: function (response) {

            if (!response.status) {

                $("#messageBox")
                    .removeClass("hidden")
                    .text(response.message);

                return;
            }

            $("#welcomeUser")
                .text(response.assign_name);

            $("#taskId")
                .text(taskId);

            $("#projectCode")
                .text(response.project_code);

            $("#createdAt")
                .text(response.created_at);

            $("#createdId")
                .text(response.created_Id);

            $("#createdName")
                .text(response.created_name);

            $("#createdRole")
                .text(response.created_role);

            $("#assignName")
                .text(response.assign_name);

            $("#assignRole")
                .text(response.assign_role);

            $("#suggestion")
                .text(response.suggestion ??
                    "No Suggestion");
        },

        error: function () {

            $("#messageBox")
                .removeClass("hidden")
                .text("Server Error");
        }
    });

    $("#showCreatorBtn").click(function () {

        $("#creatorBox")
            .toggleClass("hidden");
    });

    $("#backBtn").click(function () {

        window.location.href =
            "userdashboard.html";
    });

    $("#logoutBtn").click(function () {

        window.location.href =
            "userlogout.html";
    });
    

});