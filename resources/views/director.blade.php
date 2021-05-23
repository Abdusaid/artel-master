<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Direktor</title>

    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <script>window.Laravel = {csrfToken: '{{ csrf_token() }}'}</script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- My own css -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/warehousemanager.css') }}">

</head>

<body>

    <div id="my-app">
        <div id="slide-out" class="sidenav my-sidebar">
            <div class = "my-nav green lighten-2">
                <p>Artel</p>
            </div>
            <ul v-cloak>
                <li v-for="route in $router.options.routes"><router-link :to="route.path" exact class="sidenav-close"><i class="material-icons">@{{ route.icon }}</i> @{{ route.label }}</router-link></li>
            </ul>
        </div>
        <div  id="my-body">
            <div class="my-nav green lighten-1">
                <p class="left"><a href="#" data-target="slide-out" class="sidenav-trigger"><i class="material-icons">menu</i></a></p>
                <p class="left my-brand">
                    <a href="/manager" class="brand-logo">Artel</a>
                </p>

                <!-- Dropdown Structure -->
                <ul id="dropdownUser" class="dropdown-content">
                    <li><a href="#!">Выйти</a></li>
                </ul>

                <p class="right my-username">
                    <a class="dropdown-trigger" href="#!" data-target="dropdownUser">Зав. складом <i class="material-icons">arrow_drop_down</i></a>
                </p>

            </div>
            <div id="my-content">
                <router-view></router-view>
            </div>

        </div>
    </div>

    <script src="{{ asset("js/app.js") }}"></script>

    <script>

        // var StatisticsComponent = Vue.component('statistics', Statistics);
        // var ImportComponent = Vue.component('import', Import);
        // var ExportComponent = Vue.component('export', Export);
        var RemainderComponent = Vue.component('remainder', Remainder);
        var ImportComponent = Vue.component('import', Import);
        var ExportComponent = Vue.component('export', Export);

        const router = new VueRouter({
            base: '/director',
            routes: [
                { path: '/', component: RemainderComponent, icon: 'storage', label: 'Остаток' },
                { path: '/import', component: ImportComponent, icon: 'cloud_download', label: 'Приход' },
                { path: '/export', component: ExportComponent, icon: 'cloud_upload', label: 'Расход' },
            ],
            mode: 'history'
        });
        NProgress.configure({ easing: 'ease', speed: 1000});
        router.beforeResolve((to, from, next) => {
            if(to.path){
                NProgress.start();
            }
            next();
        });
        router.afterEach((to, from) => {
            NProgress.done();
        });

        var app = new Vue({
            el: "#my-app",
            router,
            data: {


            },

            mounted: function(){
                M.AutoInit();
                var elems = document.querySelectorAll('.dropdown-trigger');
                var instances = M.Dropdown.init(elems, {
                    coverTrigger: false,
                    alignment: 'right'
                });
            }

        });
    </script>
</body>

</html>
