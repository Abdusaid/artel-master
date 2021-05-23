<!DOCTYPE html>
<html lang="en">

<head>
    <title>Artel</title>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="/images/artel_icon.png">
    <style>
        html, body, #my-app{
            height: 100%;
        }
    </style>
</head>

<body>
<div id="my-app">
    <router-view v-on:signed="signed" v-on:logout="logout" :user="user" v-on:notified="notify"></router-view>
</div>

    <script src="{{ asset("js/app.js") }}"></script>
    <script>
        const router = new VueRouter({
            routes: [
                { path: '/login', component: Login },
                { path: '/warehouse', component: Warehouseman, children:[
                    { path: '', url: '/warehouse', component: RemainderStore, icon: 'storage', label: 'Остаток' },
                    { path: 'import', url: '/warehouse/import', component: ImportStore, icon: 'cloud_download', label: 'Приход' },
                    { path: 'export', url: '/warehouse/export', component: ExportStore, icon: 'cloud_upload', label: 'Расход' },
                ]},
                { path: '/manager', component: Warehousemanager, children: [
                    { path: '', url: '/manager', component: Remainder, icon: 'storage', label: 'Остаток' },
                    { path: 'import', url: '/manager/import', component: Import, icon: 'cloud_download', label: 'Приход' },
                    { path: 'export', url: '/manager/export', component: Export, icon: 'cloud_upload', label: 'Расход' },
                ]},
                { path: '/director', component: Manager, children: [
                    { path: '', url: '/director', component: Remainder, icon: 'storage', label: 'Остаток' },
                    { path: 'import', url: '/director/import', component: Import, icon: 'cloud_download', label: 'Приход' },
                    { path: 'export', url: '/director/export', component: Export, icon: 'cloud_upload', label: 'Расход' },
                ] },
                { path: '/laboratorian', component: Laboratorian, children: [
                    { path: '', url: '/laboratorian', component: Notice, icon: 'storage', label: 'Извещение' },
                    { path: 'import', url: '/laboratorian/import', component: LabImport, icon: 'cloud_download', label: 'Приход' },
                ] },
                { path: '/requestor', component: Requestor },
                { path: '/admin', component: Admin, children: [
                    { path: 'raw', url: '/admin/raw', component: AdminRaw, icon: 'storage', label: 'Сырье'}
                ] },

                { path: '/rawman', component: Rawman, children: [
                    { path: '', url: '/rawman', component: MixtureRemainder, icon: 'storage', label: 'Остаток'}
                ] },
                { path: '*', redirect: '/login' }

            ],
            mode: 'history'
        });

        axios.interceptors.response.use(function (response) {
            return response;
        }, function (error) {
            if(error.response.status == 401 ||error.response.status == 403 ){
                router.push('/login');
            }
            return Promise.reject(error);
        });

        var app = new Vue({
            el: '#my-app',
            data: {
                user: {}
            },

            watch: {
                user(){
                }
            },

            methods: {
                signed: function(user){
                    console.log(user);
                    this.user = user;
                    axios.defaults.headers.common['Authorization'] = "Bearer " + this.user.api_token;
                    this.$cookie.set('user', JSON.stringify(this.user));
                    this.$router.push(this.user.url);
                },

                logout: function(){
                    var self = this;
                    axios.post('/api/logout')
                        .then(function(response){
                            self.$router.push('/login');
                        })
                        .catch(function(error){
                            console.log(error);
                        });
                },

                notify: function(message){
                    M.toast({ html: message, displayLength: 10000 });
                }
            },

            created: function(){
                this.user = JSON.parse(this.$cookie.get('user'));

                if(this.user != null)
                    axios.defaults.headers.common['Authorization'] = "Bearer " + this.user.api_token;
                else{
                    this.$router.push('/login');
                }
            },

            mounted: function(){

            },
            router
        });
    </script>

</body>

</html>

