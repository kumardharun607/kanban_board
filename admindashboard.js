$(document).ready(function () {
  authenticateAdmin();
});

function authenticateAdmin() {
  $.ajax({
    url: "Authenticate_adminAPI.php",

    type: "POST",

    dataType: "json",

    success: function (response) {
      if (response.status === false) {
        window.location.href = "adminLogin.html";

        return;
      }

      loadTasks();
    },

    error: function () {
      window.location.href = "adminLogin.html";
    },
  });
}

function loadTasks() {
  $.ajax({
    url: "admindashboarddataAPI.php",

    type: "GET",

    dataType: "json",

    success: function (response) {
      if (response.status === false) {
        showMessage(response.message, false);

        setTimeout(function () {
          window.location.href = "adminLogin.html";
        }, 3000);

        return;
      }

      renderTasks(response.rows);
    },
  });
}

function renderTasks(rows) {
  let html = "";

  $.each(rows, function (index, row) {
    html += `

        <tr
        id="row_${row.task_id}"
        class="border-b border-slate-700">

        <td>

        ${row.task_id}

        </td>

        <td>

        ${row.created_id}

        <br>

        <button
        class="createdBtn bg-blue-500 px-2 py-1 rounded"
         data-created-id="${row.created_id}"
        data-task-id="${row.task_id}">

        More Info

        </button>

        <div
        id="created_${row.task_id}">
        </div>

        </td>

        <td>

        ${row.task_assigned_id}

        <br>

        <button
        class="assignedBtn bg-green-500 px-2 py-1 rounded"
         data-assigned-id="${row.task_assigned_id}"
data-task-id="${row.task_id}">

        More Info

        </button>

        <div
        id="assigned_${row.task_id}">
        </div>

        </td>

        <td>
        ${row.project_code}
        </td>

        <td>
        ${row.tasks}
        </td>

        <td>
        ${row.status}
        </td>

        <td>
        <div class="flex flex-col  items-center">

        <button
class="editBtn
bg-yellow-500
hover:bg-yellow-600
w-24
py-2
rounded-lg
font-semibold mt-3"

data-task-id="${row.task_id}"

data-created-id="${row.created_id}"

data-created-at="${row.task_created_at}"

data-assigned-id="${row.task_assigned_id}"

data-project-code="${row.project_code}"

data-task="${row.tasks}"

data-status="${row.status}">

Edit

</button>

        <br><br>

        <button
        class="deleteBtn
bg-red-500
hover:bg-red-600
w-24
py-2
rounded-lg
font-semibold"
        data-task-id="${row.task_id}">

        Delete

        </button>

        <br><br>

        <button
        class="suggestBtn
bg-purple-500
hover:bg-purple-600
w-24
py-2
rounded-lg
font-semibold mb-3"
        data-task-id="${row.task_id}">

        Suggest

        </button>
         
        </div>
        </td>
        

        </tr>

        `;
  });

  $("#taskTableBody").html(html);
}

$(document).on("click",".createdBtn",function(){

    let createdId =
$(this).data("created-id");

let taskId =
$(this).data("task-id");

let target =
$("#created_"+taskId);

    if(target.html().trim()!="")
    {
        target.slideUp(300,function(){

            $(this).html("").show();

        });

        return;
    }

    $.ajax({

        url:
        "adminfetching_created_detailsAPI.php",

        type:"POST",

        data:{
            created_id:createdId
        },

        dataType:"json",

        success:function(response){

            if(response.status)
            {
                target.hide().html(`

<div class="

rounded-2xl
p-4
mt-3
bg-purple-500/10
backdrop-blur-xl
border
border-purple-400/30
shadow-[0_0_25px_rgba(168,85,247,.7)]">

<p>
<b>Name:</b>
${response.created_details.fullname}
</p>

<p>
<b>Role:</b>
${response.created_details.role}
</p>

</div>

                `).slideDown(300);
            }
            else
            {
                target.html(response.message);
            }
        }
    });
});
//================================
$(document).on("click",".assignedBtn",function(){

    let assignedId =
$(this).data("assigned-id");

let taskId =
$(this).data("task-id");

let target =
$("#assigned_"+taskId);

    if(target.html().trim()!="")
    {
        target.slideUp(300,function(){

            $(this).html("").show();

        });

        return;
    }

    $.ajax({

        url:
        "adminfetching_assigned_detailsAPI.php",

        type:"POST",

        data:{
            assigned_id: assignedId,
        },

        dataType:"json",

        success:function(response){

            if(response.status)
            {
                target.hide().html(`

<div class="
rounded-2xl
p-4
mt-3
bg-purple-500/10
backdrop-blur-xl
border
border-purple-400/30
shadow-[0_0_25px_rgba(168,85,247,.7)]">

<p>
<b>Name:</b>
${response.assigned_details.fullname}
</p>

<p>
<b>Role:</b>
${response.assigned_details.role}
</p>

</div>

                `).slideDown(300);
            }
            else
            {
                target.html(response.message);
            }
        }
    });
});


$(document).on("click", ".editBtn", function () {
  $("#editModal").removeClass("hidden");

  $("#edit_task_id").val($(this).data("task-id"));

  $("#edit_created_id").val($(this).data("created-id"));

  $("#edit_created_at").val($(this).data("created-at"));

  $("#edit_assigned_id").val($(this).data("assigned-id"));

  $("#edit_project_code").val($(this).data("project-code"));

  $("#edit_tasks").val($(this).data("task"));

  $("#edit_status").val($(this).data("status"));
});
$(document).on("click","#closeModal",function(){

    $("#editModal").addClass("hidden");

});
$(document).on("submit", "#updateTaskForm", function (e) {

    e.preventDefault();

    console.log("Form Submitted");

    $.ajax({
        url: "admin_updatetasksApi.php",
        type: "POST",
        data: {
            task_id: $("#edit_task_id").val(),
            created_id: $("#edit_created_id").val(),
            task_assigned_id: $("#edit_assigned_id").val(),
            project_code: $("#edit_project_code").val(),
            tasks: $("#edit_tasks").val(),
            status: $("#edit_status").val()
        },
        dataType: "json",

        success: function(response){

            console.log(response);

            showMessage(
                response.message,
                response.status
            );

            if(response.status){

                $("#editModal").addClass("hidden");

                loadTasks();
            }
        },

        error:function(xhr){

            console.log(xhr.responseText);

            showMessage(
                "Server Error",
                false
            );
        }
    });

});
$(document).on("click","#closeModal",function(){

    $("#editModal").addClass("hidden");

});
$(document).on("click", ".deleteBtn", function () {
  let taskId = $(this).data("task-id");

  if (!confirm("Delete this task?")) {
    return;
  }

  $.ajax({
    url: "admin_deletetasksApi.php",

    type: "POST",

    data: {
      task_id: taskId,
    },

    dataType: "json",

    success: function (response) {
      showMessage(response.message, response.status);

      if (response.status) {
        $("#row_" + taskId).fadeOut(500, function () {
          $(this).remove();
        });
      }
    },
  });
});
$(document).on("click", ".suggestBtn", function () {
  let taskId = $(this).data("task-id");

  window.location.href = "adminsuggestion.html?task_id=" + taskId;
});

function showMessage(message, status) {
  $("#messageBox").removeClass("hidden");

  if (status) {
    $("#messageBox").removeClass("bg-red-500").addClass("bg-green-500");
  } else {
    $("#messageBox").removeClass("bg-green-500").addClass("bg-red-500");
  }

  $("#messageBox").text(message);

  setTimeout(function () {
    $("#messageBox").addClass("hidden");
  }, 3000);
}
$(document).on("click", "#createTaskBtn", function () {

    window.location.href = "admin_create_task.html";

});
$(document).on("click","#logoutBtn",function(){

    window.location.href =
    "admin_logout.html";

});

