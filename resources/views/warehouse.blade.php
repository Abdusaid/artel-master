@extends('layouts.main')

@section('user', 'Складовщик')


@section('content')
    <router-view v-on:imported="addNews(1)" v-on:exported="addNews(2)"></router-view>
@endsection

@section('scripts')

    <script>

        var RemainderComponent = Vue.component('remainder', RemainderStore);
        var ImportComponent = Vue.component('import', ImportStore);
        var ExportComponent = Vue.component('export', ExportStore);
        Vue.component('reload', Reload);
        Vue.component('loading', Loading);

        const router = new VueRouter({
            base: '/warehouse',
            routes: [
                { path: '/', component: RemainderComponent, icon: 'storage', label: 'Остаток' },
                { path: '/import', component: ImportComponent, icon: 'cloud_download', label: 'Приход' },
                { path: '/export', component: ExportComponent, icon: 'cloud_upload', label: 'Расход' },
            ],
            mode: 'history'
        });

        axios.defaults.headers.common['Authorization'] = "Bearer " + "{!! $token !!}";

        var app = new Vue({
            el: "#my-app",
            router,
            data: {
                withSidebar: true,
                news: [0, 0, 0],
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
                    var index = to.path=='/' ? 0 : to.path=='/import' ? 1 : 2;
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

        });
    </script>
@endsection