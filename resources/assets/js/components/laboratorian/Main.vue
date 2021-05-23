<template>
    <layout :with-sidebar="withSidebar" :news="news" :routes="routes" @logout="logout" :user="user">
        <router-view v-on:notified="addNews" v-on:imported="addNews" ></router-view>
    </layout>

</template>

<style scoped>

    .table-wrapper{
        padding: 5px;
        box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 3px 1px -2px rgba(0, 0, 0, 0.12), 0 1px 5px 0 rgba(0, 0, 0, 0.2);;
    }

    table th, table td{
        padding: 5px;
    }
    .my-pagination{
        text-align: center;
    }

    .my-pagination li.active{
        background-color: #4caf50;
    }

    .disabled{
        pointer-events: none;
    }

    .highlight tr:hover{
        cursor: pointer;
        filter: brightness(95%);
    }

</style>

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
                news: [0, 0],
                sound: null
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
            addNews(index, message, data){
                if(index == 1){
                    if(this.currentRoute == 0){
                        var num = this.news[1] + 1;
                        this.news.splice(1, 1, num);
                    }else if(this.currentRoute == 1 && data.import.type==1 && data.import.isNew){
                        var num = this.news[0] + 1;
                        this.news.splice(0, 1, num);
                    }
                    this.sound.play();
                    this.$emit('notified', message);
                }
            },

            logout: function(){
                this.$emit('logout');
            }
        },

        watch: {
            $route: function(to, from){
                var index = to.path=='/laboratorian' ? 0 : 1;
                this.news.splice(index, 1, 0);
            }
        },

        mounted: function(){
            var instances = M.Dropdown.init(this.$refs.dropdownTrigger, {
                coverTrigger: false,
                alignment: 'right'
            });

            this.sound = new Audio('/audio/tone.mp3');
        }
    }
</script>