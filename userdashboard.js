$(document).ready(function () {

    authenticateUser();

});


function showMessage(message, type = "error") {

    let messageBox = $("#messageBox");

    messageBox
        .removeClass(
            "hidden bg-red-500 bg-green-500"
        )
        .addClass(
            type === "success"
                ? "bg-green-500"
                : "bg-red-500"
        )
        .text(message);

    setTimeout(function () {

        messageBox.addClass("hidden");

    }, 3000);

}


/* =========================
   AUTHENTICATE USER
========================= */

function authenticateUser() {

    $.ajax({

        url: "Authenticate_userAPI.php",

        type: "POST",

        dataType: "json",

        success: function (response) {

            if (!response.status) {

                window.location.href =
                    "userlogin.html";

                return;
            }

            loadDashboardData();

        },

        error: function () {

            window.location.href =
                "userlogin.html";

        }

    });

}


/* =========================
   LOAD DASHBOARD
========================= */

function loadDashboardData() {

    $.ajax({

        url: "userdashboarddataApi.php",

        type: "GET",

        dataType: "json",

        success: function (response) {

            if (!response.status) {

                showMessage(
                    response.message
                );

                return;
            }

            $("#userName")
                .text(response.name);

            $("#assignTaskBtn")
                .attr(
                    "data-assignid",
                    response.register_id
                );

            renderTasks(
                response.task
            );

        },

        error: function () {

            showMessage(
                "Unable To Load Dashboard"
            );

        }

    });

}


/* =========================
   RENDER TASKS
========================= */

function renderTasks(tasks) {

    $("#todoContainer").html("");

    $("#doingContainer").html("");

    $("#doneContainer").html("");

    $.each(tasks, function (index, task) {

        let card =
            createTaskCard(task);

        if (task.status === "To Do") {

            $("#todoContainer")
                .append(card);

        }

        else if (
            task.status === "Doing"
        ) {

            $("#doingContainer")
                .append(card);

        }

        else {

            $("#doneContainer")
                .append(card);

        }

    });

}


/* =========================
   TASK CARD
========================= */

function createTaskCard(task) {

    return `

    <div
        class="task-card
        bg-slate-800/80
        border
        border-slate-700
        rounded-xl
        p-4
        shadow-lg">

        <h3
            class="font-semibold
            text-base
            sm:text-lg
            break-words">

            ${task.tasks}

        </h3>

        <p
            class="text-xs
            sm:text-sm
            text-gray-400
            mt-2
            break-words">

            ${task.admin_suggestion ??
                "No Suggestion"}

        </p>

        <div
            class="flex
            flex-col
            sm:flex-row
            gap-2
            mt-4">

            <button

                class="detailBtn
                w-full
                sm:w-auto
                bg-blue-600
                hover:bg-blue-700
                px-3
                py-2
                rounded-lg
                text-sm"

                data-taskid=
                "${task.task_id}">

                More Details

            </button>

            <select

                class="statusSelect
                w-full
                sm:flex-1
                bg-slate-900
                border
                border-slate-700
                rounded-lg
                px-2
                py-2
                text-sm"

                data-taskid=
                "${task.task_id}">

                <option
                    value="To Do"
                    ${task.status === "To Do"
                        ? "selected"
                        : ""}>

                    To Do

                </option>

                <option
                    value="Doing"
                    ${task.status === "Doing"
                        ? "selected"
                        : ""}>

                    Doing

                </option>

                <option
                    value="Done"
                    ${task.status === "Done"
                        ? "selected"
                        : ""}>

                    Done

                </option>

            </select>

        </div>

    </div>

    `;

}


/* =========================
   MORE DETAILS
========================= */

$(document).on(
    "click",
    ".detailBtn",
    function () {

        let taskId =
            $(this)
                .data("taskid");

        window.location.href =
            "taskdetail.html?task_id="
            + taskId;

    }
);


/* =========================
   UPDATE STATUS
========================= */

$(document).on(
    "change",
    ".statusSelect",
    function () {

        let taskId =
            $(this)
                .data("taskid");

        let status =
            $(this)
                .val();

        $.ajax({

            url:
                "updateStatus.php",

            type:
                "POST",

            data: {

                task_id:
                    taskId,

                status:
                    status

            },

            dataType:
                "json",

            success:
                function (
                    response
                ) {

                    if (
                        response.status
                    ) {

                        showMessage(
                            "Task Updated",
                            "success"
                        );

                        loadDashboardData();

                    }

                },

            error:
                function () {

                    showMessage(
                        "Status Update Failed"
                    );

                }

        });

    }
);


/* =========================
   CREATE TASK
========================= */

$(document).on(
    "click",
    "#assignTaskBtn",
    function () {

        let assignId =
            $(this)
                .attr(
                    "data-assignid"
                );

        let projectCode =
            $("#project_code")
                .val()
                .trim();

        let taskName =
            $("#task_name")
                .val()
                .trim();

        if (
            projectCode === "" ||
            taskName === ""
        ) {

            showMessage(
                "Fill All Fields"
            );

            return;

        }

        $.ajax({

            url:
                "usercreateTask.php",

            type:
                "POST",

            data: {

                project_code:
                    projectCode,

                task:
                    taskName,

                assign_id:
                    assignId

            },

            dataType:
                "json",

            success:
                function (
                    response
                ) {

                    if (
                        response.status
                    ) {

                        $("#project_code")
                            .val("");

                        $("#task_name")
                            .val("");

                        showMessage(
                            "Task Created Successfully",
                            "success"
                        );

                        loadDashboardData();

                    }

                    else {

                        showMessage(
                            response.message
                        );

                    }

                },

            error:
                function () {

                    showMessage(
                        "Task Creation Failed"
                    );

                }

        });

    }
);


/* =========================
   LOGOUT
========================= */

$(document).on(
    "click",
    "#logoutBtn",
    function () {

        window.location.href =
            "userlogout.html";

    }
);