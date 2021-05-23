<template>
    <div class="tabs-wrapper">
        <div class="my-left" @click="scroll(-1)"><a href="#"><i class="material-icons">chevron_left</i></a></div>
        <div class="my-right" @click="scroll(1)"><a href="#"><i class="material-icons">chevron_right</i></a></div>
        <ul class="tabs" ref="tabFirms" v-cloak>
            <li v-if="all" class="tab" @click="choose(0)"><a href="#firm">Все</a></li>
            <li class="tab" v-for="(firm, index) in firms" @click="choose(firm.id)" ><a :href="'#firm'+index">{{ firm.name }}</a></li>
        </ul>
    </div>
</template>

<style>
    .tabs::-webkit-scrollbar{
        display: none;
    }

    .tabs-wrapper {
        position: relative;
    }

    .tabs {
        width: 90%;
    }
    .tabs .tab a{
        color: #4caf5080;
    }

    .tabs .tab a:hover, .tabs .tab a.active{
        color: #4caf50;
    }

    .tabs .indicator{
        background-color: #4caf50;
    }

    .tabs .tab a:focus, .tabs .tab a:focus.active{
        background-color: #4caf5020;
    }

    .my-left, .my-right{
        position: absolute;
        top: 0;
        bottom: 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
        z-index: 100;
        cursor: pointer;
    }

    .my-left i, .my-right i{
        font-size: 200%;
    }

    .my-right{
        right: 0;
    }


</style>
<script>
    export default {
        props: {
            firms: Array,
            value: Number,
            all: {
                type: Boolean,
                default: false
            }
        },

        data: function(){
            return {
                tab: null
            }
        },

        methods: {
            choose: function(firm_id){
                this.$emit('input', firm_id);
            },

            scroll: function(side){
                if(side>0){
                    this.$refs.tabFirms.scrollLeft += 50;
                }else{
                    this.$refs.tabFirms.scrollLeft -= 50;
                }
            },

            initTabs: function(){
                this.$emit('input', this.all ? 0 : this.firms[0].id);
                this.$nextTick(function(){
                    this.tab = M.Tabs.init(this.$refs.tabFirms);
                });
            }
        },

        watch: {
            firms: function(){
                this.initTabs();
            }
        },

        mounted: function(){
            if(this.firms.length > 0)
                this.initTabs();
        }
    }
</script>