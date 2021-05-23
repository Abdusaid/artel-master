<template>
    <div class="admin">
        <div id="slide-out" class="sidenav my-sidebar" ref="sidenav">
            <div class = "my-nav green lighten-2">
                <p>Admin Panel</p>
            </div>
            <ul v-cloak>
                <li v-for="(route, index) in routes" class="my-sidebar-item">
                    <router-link :to="route.url" exact class="sidenav-close">
                        <i class="material-icons">{{ route.icon }}</i> {{ route.label }}
                    </router-link>
                </li>
            </ul>
        </div>
        <div  id="my-body">
            <div class="my-nav green lighten-1">
                <p class="left">
                    <a href="#" data-target="slide-out" class="sidenav-trigger">
                        <i class="material-icons">menu</i>
                    </a>
                </p>
                <p class="left my-brand">
                    <a href="/admin" class="brand-logo">Admin Panel</a>
                </p>

                <!-- Dropdown Structure -->
                <ul id="dropdownUser" class="dropdown-content">
                    <li><a href="#" @click="logout">Выйти</a></li>
                </ul>

                <p class="right my-username">
                    <a ref="dropdownTrigger" class="dropdown-trigger" href="#!" data-target="dropdownUser">{{ user.role }} <i class="material-icons">arrow_drop_down</i></a>
                </p>

            </div>
            <div id="my-content">
                <router-view></router-view>
            </div>

        </div>
    </div>
</template>

<style scoped>
    .admin, #my-body{
        height: 100%;
    }

    #my-content{
        height: calc(100% - 60px);
        padding: 20px;
    }

    .my-nav .left{
        margin-left: 20px;
        font-size: 200%;
    }

    .my-nav .sidenav-trigger i{
        font-size: 150%;
    }

    .sidenav{
        background-color: #c8e6c9;
        width: 250px;
    }

    .router-link-active{
        background-color: #a5d6a7;
    }
</style>

<script>

    export default{

        props: {
            user: Object
        },

        computed: {
            routes: function(){
                var index = this.$route.path.indexOf('/', 1);
                var root = index!=-1 ? this.$route.path.slice(0, index) : this.$route.path;
                return this.$router.options.routes.find( route => route.path==root).children;
            }
        },

        methods:{
            logout(){
                this.$emit('logout');
            }
        },

        mounted(){
            var dropdown = M.Dropdown.init(this.$refs.dropdownTrigger, {
                coverTrigger: false,
                alignment: 'right'
            });
            var sidenav = M.Sidenav.init(this.$refs.sidenav);
        }
    }
</script>