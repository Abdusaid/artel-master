<template>
    <layout :with-sidebar="true" :news="news" :routes="routes" :user="user" @logout="logout">
        <router-view></router-view>
    </layout>
</template>


<script>

    import Layout from "../layouts/Main.vue";

    export default{
        components:{
            'layout': Layout
        },

        props: {
            user: Object
        },

        data(){
            return{
                news: [0, 0, 0],

            }
        },

        computed: {
            routes: function(){
                return this.$router.options.routes.find( route => this.$route.path.startsWith(route.path)).children;
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

            },

            logout: function(){
                this.$emit('logout');
            }
        }

    }
</script>