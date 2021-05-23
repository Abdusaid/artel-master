<template>
    <div class="login">
        <div class="background">

        </div>
        <div class="login-panel">
            <div v-show="loading" class="progress">
                <div class="indeterminate"></div>
            </div>
            <div class="login-tittle">
                Artel
            </div>
            <p v-show="wrongCredentials">Неправильный логин или пароль</p>
            <div class="login-form">
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
                    <button class="btn waves-effect waves-light" :class=" { 'disabled' : loading } " @click="signin">Войти</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function(){
            return {
                login: '',
                password: '',
                wrongCredentials: false,
                loading: false
            }
        },

        methods: {
            signin: function(){
                var self = this;
                self.loading = true;
                self.wrongCredentials = false;
                axios.post('/api/login', {
                    'username': self.login,
                    'password': self.password
                }).then(function(response){
                    self.loading = false;
                    self.$emit('signed', response.data.data);
                }).catch(function(error){
                    self.loading = false;
                    self.wrongCredentials = true;
                    console.log(error.response);
                });
            }
        },
        mounted: function(){
            //M.AutoInit();
        }
    }
</script>

<style scoped>
    .login{
        position: relative;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }


    .login-panel{
        position: relative;
        width: 40%;
        box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 3px 1px -2px rgba(0, 0, 0, 0.12), 0 1px 5px 0 rgba(0, 0, 0, 0.2);
        padding: 20px 30px 10px 30px;
        text-align: center;
        color: #9e9e9e;
        background-color: white;
        z-index: 100;
    }

    .login-panel p {
        color: red;
    }

    .progress {
        position: absolute;
        right: 0;
        left: 0;
        top: 0;
        margin: 0;
    }
    .login-tittle{
        color: #26a69a;
        font-size: 28px;
        font-weight: bold;
        font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;
        -webkit-font-smoothing: antialiased;
    }

    .background{
        position: absolute;
        width: 100%;
        height: 100%;
        background-image: url("/images/background.png");
        background-size: 100%;


        -webkit-filter: blur(25px);
        -moz-filter: blur(25px);
        -o-filter: blur(25px);
        -ms-filter: blur(25px);
        filter: blur(25px);
    }
</style>