$(function (){

    /**
     * Авторизация
     */

    $('.login-btn').click(function (e){
        e.preventDefault();

        $('input').removeClass('error');

        let login = $('input[name="login"]').val(),
            password = $('input[name="password"]').val();

        $.ajax({
            url: "Ajax.php",
            type: "POST",
            dataType: "json",
            data: {
                login: login,
                password: password
            },
            success: function (data){

                if(data.status){
                    document.location.href = "main.php";
                } else {

                    if(data.type === 1) {
                        data.fields.forEach(function (field) {
                            $(`input[name="${field}"]`).addClass('error');
                        });
                    }

                    $('.msg').removeClass("none").text(data.message);
                }

            }
        });
    });

    /**
     * Регистрация
     */

    $(".register-btn").click(function (e){
       e.preventDefault();

        let login = $("#login").val(),
            email = $("#email").val(),
            password = $("#password").val(),
            passwordConf = $("#password_confirm").val(),
            name = $("#username").val();

        $('input').removeClass('error');

        $.ajax({

            url: "Ajax.php",
            type: "POST",
            dataType: "json",
            data: {
                login: login,
                email: email,
                password: password,
                password_confirm: passwordConf,
                username: name
            },
            success: function (data){
                if(data.status){
                    document.location.href = "index.php";
                } else {
                    if(data.type === 1){
                        data.fields.forEach(function (field){
                            $(`input[name="${field}"]`).addClass('error');
                        });
                    }

                    $('.msg').removeClass("none").text(data.message);
                }

            }

        });
    });
})