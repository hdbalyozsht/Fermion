var Login = function () {

    var handleLogin = function () {
        $('.login-form').validate({
            errorElement: 'label', //default input error message container
            errorClass: 'help-inline', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                username: {
                    required: true
                },
                password: {
                    required: true
                },
                remember: {
                    required: false
                }
            },

            messages: {
                username: {
                    required: "Kullanıcı adı gerekli."
                },
                password: {
                    required: "Şifre Gerekli."
                }
            },

            invalidHandler: function (event, validator) { //display error alert on form submit
                document.getElementById("errormsg").innerHTML = "Lütfen tüm alanları doldurun.";
                $('.alert-error', $('.login-form')).show();
            },

            highlight: function (element) { // hightlight error inputs
                $(element)
                    .closest('.control-group').addClass('error'); // set error class to the control group
            },

            success: function (label) {
                label.closest('.control-group').removeClass('error');
                label.remove();
            },

            errorPlacement: function (error, element) {
                error.addClass('help-small no-left-padding').insertAfter(element.closest('.input-icon'));
            },

            submitHandler: function (form) {
                //form.submit();
                var u = document.getElementById("username").value;
                var p = document.getElementById("password").value;

                document.getElementById("loginbtn").style.display = "none";
                var ajax = new XMLHttpRequest();
                ajax.onreadystatechange = function () {
                    if (ajax.readyState == 4 && ajax.status == 200) {
                        if (ajax.responseText.indexOf("login_failed")==0) {
                            console.debug(ajax.responseText);
                            document.getElementById("errormsg").innerHTML = "Kullanıcı Adı veya Şifre yanlış.";
                            $('.alert-error', $('.login-form')).show();
                            document.getElementById("loginbtn").style.display = "block";
                        } else {
                            window.location = "success_login_redirect.php";
                        }
                    } else {
                        document.getElementById("loginbtn").style.display = "block";
                    }
                };
                ajax.open("POST", "login.php", true);
                ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                ajax.send("u=" + u + "&p=" + p);
            }
        });

        $('.login-form input').keypress(function (e) {
            if (e.which == 13) {
                if ($('.login-form').validate().form()) {
                    //$('.login-form').submit();
                }
                return false;
            }
        });
    };

    return {
        //main function to initiate the module
        init: function () {

            handleLogin();

            $.backstretch([
                "back.jpg"
            ], {
                fade: 0,
                duration: 60*60*1000
            });

        }

    };

}();