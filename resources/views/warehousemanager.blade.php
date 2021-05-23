<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Warehousemanager</title>

    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- My own css -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/warehousemanager.css') }}">

</head>
<style>
    [v-cloak]{
        display: none;
    }
</style>
<body>

    <div id="my-app">
        <div id="slide-out" class="sidenav my-sidebar" ref="sidenav">
            <div class = "my-nav green lighten-2">
                <p>Artel</p>
            </div>
            <ul v-cloak>
                <li v-for="(route, index) in $router.options.routes" class="my-sidebar-item">
                    <router-link :to="route.path" exact class="sidenav-close">
                        <i class="material-icons">@{{ route.icon }}</i> @{{ route.label }}
                        <span v-if="news[index]>0" class="my-badge">@{{ news[index] }}
                    </router-link>
                </li>
            </ul>
        </div>
        <div  id="my-body">
            <div class="my-nav green lighten-1">
                <p class="left">
                    <a href="#" data-target="slide-out" class="sidenav-trigger">
                        <i class="material-icons">menu</i>
                        <span v-cloak v-if="notifications>0" class="notification">@{{ notifications }}</span>
                    </a>
                </p>
                <p class="left my-brand">
                    <a href="/manager" class="brand-logo">Artel</a>
                </p>

                <!-- Dropdown Structure -->
                <ul id="dropdownUser" class="dropdown-content">
                    <li><a href="/login">Выйти</a></li>
                </ul>

                <p class="right my-username">
                    <a ref="dropdownTrigger" class="dropdown-trigger" href="#!" data-target="dropdownUser">Зав. складом <i class="material-icons">arrow_drop_down</i></a>
                </p>

            </div>
            <div id="my-content">
                <router-view :firms="firms" v-on:imported="addNews(1)" v-on:exported="addNews(2)" :sound="sound"></router-view>
            </div>

        </div>
    </div>

    <script src="{{ asset("js/app.js") }}"></script>

    <script>

        var RemainderComponent = Vue.component('remainder', Remainder);
        var ImportComponent = Vue.component('import', Import);
        var ExportComponent = Vue.component('export', Export);


        const router = new VueRouter({
            base: '/manager',
            routes: [
                { path: '/', component: RemainderComponent, icon: 'storage', label: 'Остаток' },
                { path: '/import', component: ImportComponent, icon: 'cloud_download', label: 'Приход' },
                { path: '/export', component: ExportComponent, icon: 'cloud_upload', label: 'Расход' },
            ],
            mode: 'history'
        });
        router.beforeResolve((to, from, next) => {
            if(to.path){
                NProgress.set(0.3);
            }
            next();
        });
        router.afterEach((to, from) => {
            //NProgress.done();
        });

        var app = new Vue({
            el: "#my-app",
            mixins: [ firms ],
            data: {
                news: [0, 0, 0],
                sound: null
            },

            computed: {
                notifications: function(){
                    return this.news.reduce(function(a,b){ return a+b;});
                }
            },

            watch: {
                $route: function(to, from){
                    var index = to.path=='/warehouse' ? 0 : to.path=='/warehouse/import' ? 1 : 2;
                    this.news.splice(index, 1, 0);
                }
            },

            methods: {
                addNews(index){
                    var num = this.news[index]+1;
                    this.news.splice(index, 1, num);
                    this.sound.play();
                }
            },
            created: function(){
                this.loadFirms();
            },

            mounted: function(){
                //M.AutoInit();
                var dropdown = M.Dropdown.init(this.$refs.dropdownTrigger, {
                    coverTrigger: false,
                    alignment: 'right'
                });
                var sidenav = M.Sidenav.init(this.$refs.sidenav);

                this.sound = new Audio('/audio/tone.mp3');
            },
            router

        });
    </script>
</body>

</html>