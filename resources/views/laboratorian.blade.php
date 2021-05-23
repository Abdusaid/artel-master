@extends('layouts.main')

@section('user', 'Лаборант')

@section('content')
<router-view v-on:notified="addNews(0)" v-on:imported="addNews(1)"></router-view>
@endsection

@section('scripts')
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

        var NoticeComponent = Vue.component('notice', Notice);
        var ImportComponent = Vue.component('import', LabImport);
        const router = new VueRouter({
            base: '/laboratorian',
            routes: [
                { path: '/', component: NoticeComponent, icon: 'storage', label: 'Извещение' },
                { path: '/import', component: ImportComponent, icon: 'cloud_download', label: 'Приход' },
            ],
            mode: 'history'
        });
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
                withSidebar: true,
                news: [0, 0],
                sound: null
            },

            methods: {
                addNews(index){
                    var num = this.news[index]+1;
                    this.news.splice(index, 1, num);
                    this.sound.play();
                }
            },

            watch: {
                $route: function(to, from){
                    var index = to.path=='/' ? 0 : 1;
                    this.news.splice(index, 1, 0);
                }
            },

            mounted: function(){
                M.AutoInit();
                var instances = M.Dropdown.init(this.$refs.dropdownTrigger, {
                    coverTrigger: false,
                    alignment: 'right'
                });

                this.sound = new Audio('/audio/tone.mp3');
            }

        });
    </script>
    @endsection
