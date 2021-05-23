<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Warehousemanager</title>

    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- My own css -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">

</head>

<body>

<div id="my-app">
    <div class="background">

    </div>
    <div class="login-panel">
        <div class="login-tittle">
            Artel
        </div>

        <div class="login-form">
            <form action="#">
                <div class="input-field col s12">
                    <i class="material-icons prefix">person</i>
                    <input type="text" id="login" v-model="login"/>
                    <label for="login">Логин</label>
                </div>

                <div class="input-field col s12">
                    <i class="material-icons prefix">lock</i>
                    <input type="password" id="password" v-model="password"/>
                    <label for="password">Пароль</label>
                </div>

                <div class="input-field">
                    <button class="btn waves-effect waves-light">Войти</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset("js/app.js") }}"></script>

<script>
    var app = new Vue({
        el: "#my-app",
        data: {
            login: '',
            password: ''
        },

        methods: {
            login: function(){
                var self = this;
                axios.post('/login', {
                    'username': self.login,
                    'password': self.password
                }).then(function(response){

                }).catch(function(){

                });
            }
        },
        mounted: function(){
            M.AutoInit();
        }
    });
</script>
</body>

</html>