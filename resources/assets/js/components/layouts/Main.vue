
<template>
    <div class="layout">
        <div v-if="withSidebar" class = "my-sidebar green lighten-4" v-cloak>

            <div class = "my-nav green lighten-2">
                <p>Artel</p>
            </div>

            <ul class="my-menu" v-cloak>
                <li v-for="(route, index) in routes" class="my-menu-item">
                    <router-link :to="route.url" exact>
                        <i class="material-icons">{{ route.icon }}</i> {{ route.label }} <span v-if="news[index]>0" class="my-badge">{{ news[index] }}</span>
                    </router-link>
                </li>
            </ul>

        </div>

        <div  id="my-body">
            <div class="my-nav green lighten-1">

                <p v-if="!withSidebar" class="left my-brand">
                    <a :href="$route.path" class="brand-logo">Artel</a>
                </p>

                <!-- Dropdown Structure -->
                <ul id="dropdownUser" class="dropdown-content">
                    <li>
                        <a href="#" @click="logout">Выйти</a>
                    </li>
                </ul>

                <p class="right my-username">
                    <a ref="dropdownTrigger" class="dropdown-trigger" href="#!" data-target="dropdownUser">{{ user.role }} <i class="material-icons">arrow_drop_down</i></a>
                </p>

            </div>
            <div id="my-content">
                <slot></slot>
            </div>

        </div>
    </div>
</template>

<script>
    export default {
        props: {
            withSidebar: Boolean,
            news: Array,
            routes: Array,
            user: Object
        },

        methods: {
            logout: function(){
                this.$emit('logout');
            }
        },
        mounted: function(){
            var dropdown = M.Dropdown.init(this.$refs.dropdownTrigger, {
                coverTrigger: false,
                alignment: 'right'
            });
        }
    }
</script>

<style scoped>
    .layout{
        height: 100%;
    }
    *{
        box-sizing: border-box;
    }

    [v-cloak]{
        display: none;
    }

    #my-body{
        height: 100%;
        overflow: hidden;
    }


    .my-sidebar{
        padding: 0;
        width: 15%;
        height: 100%;
        float: left;
    }


    #my-content{
        padding: 0 20px 0 20px;
        height: calc(100% - 60px);
        overflow-y: scroll;
    }

    #my-content>div{
        margin-top: 24px;
    }


    .my-menu-item i{
        vertical-align: middle;
    }

    .my-menu-item:hover{
        background-color: #81C784;
        cursor: pointer;
    }
    .my-menu-item{
        padding: 0px;
        overflow: hidden;
        text-align: center;
        font-size: large;
        position: relative;
    }

    .my-menu-item a{
        display: block;
        padding: 5%;
        color: inherit;
    }
    .router-link-active{
        background-color: #a5d6a7;
    }

    .my-nav .left {
        margin-left: 20px;
        font-size: 200%;
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

