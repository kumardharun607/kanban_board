$(document).ready(function () {

    $("#adminRegisterForm").submit(function (e) {

        e.preventDefault();

        let formData = {

            admin_name: $("#admin_name").val(),

            admin_email: $("#admin_email").val(),

            admin_password: $("#admin_password").val(),

            secret_code: $("#secret_code").val()
        };

        $.ajax({

            url: "admin_registerverifyApi.php",

            type: "POST",

            data: formData,

            dataType: "json",

            success: function (response) {

                $("#messageBox")
                    .removeClass("hidden");

                if (response.status) {

                    $("#messageBox")
                        .removeClass("bg-red-500")
                        .addClass("bg-green-500 text-white")
                        .text(response.message);

                    setTimeout(function () {

                        window.location.href =
                            response.location;

                    }, 3000);

                } else {

                    $("#messageBox")
                        .removeClass("bg-green-500")
                        .addClass("bg-red-500 text-white")
                        .text(response.message);
                }
            }
        });

    });

});

function togglePassword() {

    let password =
        document.getElementById("admin_password");

    let eye =
        document.getElementById("eyeIcon");

    if (password.type === "password") {

        password.type = "text";

        eye.classList.remove("bi-eye-fill");

        eye.classList.add("bi-eye-slash-fill");

    } else {

        password.type = "password";

        eye.classList.remove("bi-eye-slash-fill");

        eye.classList.add("bi-eye-fill");
    }
}