<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Artel</title>

    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- My own css -->
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    @yield('styles')

</head>

<body>
    <div id="my-app">
        <div v-if="withSidebar" class = "my-sidebar green lighten-4" v-cloak>

            <div class = "my-nav green lighten-2">
                <p>Artel</p>
            </div>

            <ul class="my-menu" v-cloak>
                <li v-for="(route, index) in $router.options.routes" class="my-menu-item">
                    <router-link :to="route.path" exact>
                        <i class="material-icons">@{{ route.icon }}</i> @{{ route.label }} <span v-if="news[index]>0" class="my-badge">@{{ news[index] }}</span>
                    </router-link>
                </li>
            </ul>

        </div>

        <div  id="my-body">
            <div class="my-nav green lighten-1">

                <p v-if="!withSidebar" class="left my-brand">
                    <a href="/request" class="brand-logo">Artel</a>
                </p>

                <!-- Dropdown Structure -->
                <ul id="dropdownUser" class="dropdown-content">
                    <li><a href="/login">Выйти</a></li>
                </ul>

                <p class="right my-username">
                    <a ref="dropdownTrigger" class="dropdown-trigger" href="#!" data-target="dropdownUser">@yield('user') <i class="material-icons">arrow_drop_down</i></a>
                </p>

            </div>
            <div id="my-content">
                @yield('content')
            </div>

        </div>
    </div>

    <script src="{{ asset("js/app.js") }}"></script>
    @yield('scripts')
</body>

</html>

