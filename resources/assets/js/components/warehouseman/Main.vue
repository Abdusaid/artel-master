<template>
    <layout :with-sidebar="withSidebar" :news="news" :routes="routes" @logout="logout" :user="user">
        <router-view v-on:imported="addNews" v-on:exported="addNews" ></router-view>
    </layout>
</template>

<script>
    import Layout from "../layouts/Main.vue";

    export default {
        components: {
            'layout': Layout
        },

        props: {
            user: Object
        },
        data: function(){
            return {
                withSidebar: true,
                news: [0, 0, 0],
                sound: null,
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

        watch: {
            $route: function(to, from){
                var index = to.path=='/warehouse' ? 0 : to.path=='/warehouse/import' ? 1 : 2;
                this.news.splice(index, 1, 0);
            }
        },
        mounted: function(){
            //M.AutoInit();
            var instances = M.Dropdown.init(this.$refs.dropdownTrigger, {
                coverTrigger: false,
                alignment: 'right'
            });

            this.sound = new Audio('/audio/tone.mp3');
        }

    }



</script>