

$(document).ready(function () {

    $("#registerForm").submit(function (e) {

        e.preventDefault();
        

        $("#messageBox").addClass("hidden");

        let formData = {

            fullname: $("#fullname").val(),

            role: $("#role").val(),

            // project_name: $("#project_name").val(),
            email: $("#email").val(),

            //project_code: $("#project_code").val(),

            password: $("#password").val(),

            // start_date: $("#start_date").val()
        };
        

        $.ajax({
            

            url: "registeruser_verifyapi.php",

            type: "POST",

            data: formData,

            dataType: "json",

            success: function (response) {

                if (response.status === true) {
                    

                    window.location.href =
                        response.location;

                } else {

                    $("#messageBox")
                        .removeClass("hidden")
                        .removeClass("bg-green-500")
                        .addClass("bg-red-500 text-white")
                        .text(response.message);
                }
            },

            error: function () {

                $("#messageBox")
                    .removeClass("hidden")
                    .addClass("bg-red-500 text-white")
                    .text("Server Error");
            }
        });

    });

});
function togglePassword() {

    let passwordField = document.getElementById("password");
    let eyeIcon = document.getElementById("eyeIcon");

    if (passwordField.type === "password") {

        passwordField.type = "text";

        eyeIcon.classList.remove("bi-eye-fill");
        eyeIcon.classList.add("bi-eye-slash-fill");

    } else {

        passwordField.type = "password";

        eyeIcon.classList.remove("bi-eye-slash-fill");
        eyeIcon.classList.add("bi-eye-fill");
    }
}