<template>
    <div class="warehousemanager">
        <div id="slide-out" class="sidenav my-sidebar" ref="sidenav">
            <div class = "my-nav green lighten-2">
                <p>Artel</p>
            </div>
            <ul v-cloak>
                <li v-for="(route, index) in routes" class="my-sidebar-item">
                    <router-link :to="route.url" exact class="sidenav-close">
                        <i class="material-icons">{{ route.icon }}</i> {{ route.label }}
                        <span v-if="news[index]>0" class="my-badge">{{ news[index] }}</span>
                    </router-link>
                </li>
            </ul>
        </div>
        <div  id="my-body">
            <div class="my-nav green lighten-1">
                <p class="left">
                    <a href="#" data-target="slide-out" class="sidenav-trigger">
                        <i class="material-icons">menu</i>
                        <span v-cloak v-if="notifications>0" class="notification">{{ notifications }}</span>
                    </a>
                </p>
                <p class="left my-brand">
                    <a href="/manager" class="brand-logo">Artel</a>
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
                <router-view :firms="firms" v-on:imported="addNews" v-on:exported="addNews" :sound="sound"></router-view>
            </div>

        </div>
    </div>
</template>
<script>
    import { firms } from '../mixins';

    export default{
        mixins: [ firms ],
        props: {
            user: Object
        },
        data: function(){
            return {
                news: [0, 0, 0],
                sound: null
            }
        },

        computed: {
            notifications: function(){
                return this.news.reduce(function(a,b){ return a+b;});
            },
            routes: function(){
                var index = this.$route.path.indexOf('/', 1);
                var root = index!=-1 ? this.$route.path.slice(0, index) : this.$route.path;
                return this.$router.options.routes.find( route => route.path==root).children;
            },

            currentRoute: function(){
                return this.routes.findIndex( item => this.$route.path==item.url);
            }
        },

        watch: {
            $route: function(to, from){
                var index = to.path==this.user.url ? 0 : to.path==this.user.url+"/import" ? 1 : 2;
                this.news.splice(index, 1, 0);
            }
        },

        methods: {
            addNews(index, message){
                if(index != this.currentRoute){
                    var num = this.news[index]+1;
                    this.news.splice(index, 1, num);
                }
                this.sound.play();
                this.$emit('notified', message);
            },

            logout: function(){
                this.$emit('logout');
            }
        },
        created: function(){
            this.loadFirms();
        },

        mounted: function(){
            var dropdown = M.Dropdown.init(this.$refs.dropdownTrigger, {
                coverTrigger: false,
                alignment: 'right'
            });
            var sidenav = M.Sidenav.init(this.$refs.sidenav);

            this.sound = new Audio('/audio/tone.mp3');
        }

    }
</script>

<style scoped>
    [v-cloak]{
        display: none;
    }
    .warehousemanager{
        height: 100%;
    }

    .router-link-active{
        background-color: #a5d6a7;
    }

    #nprogress .bar {
        height: 3px;
    }

    #my-body{
        height: 100%;
        overflow: hidden;
    }

    #my-content{
        padding: 0 24px 0 24px;
        height: calc(100% - 60px);
        overflow-y: scroll;
    }

    #my-content > div{
        margin-top: 24px;
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

    .table-rawHistory td, .table-rawHistory th{
        padding: 8px;
    }

    .table-rawHistory tbody tr:last-child{
        border-bottom: none;
    }

    .table-rawHistory{
        border: 1px solid lightgrey;
        margin-top: 10px;
    }
    .sidenav-trigger{
        position: relative;
    }

    .my-sidebar-item{
        position: relative;
    }
    .notification {
        position: absolute;
        top: 0;
        right: -10px;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background-color: #2196f3;
        font-size: 12px;
        color: white;
        vertical-align: middle;
        text-align: center;
    }

    .my-badge {
        position: absolute;
        right: 5%;
        top: 50%;
        transform: translateY(-50%);
        width: 18px;
        height: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background-color: #2196f3;
        font-size: 10px;
        color: white;
        vertical-align: middle;
        text-align: center;
    }
</style>

