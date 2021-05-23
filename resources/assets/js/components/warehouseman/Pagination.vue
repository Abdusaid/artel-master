<template>
    <ul class="pagination my-pagination">
        <li :class="value==1 ? 'disabled' : 'waves-effect'" @click="changePage(value-1)"><a href="#"><i class="material-icons">chevron_left</i></a></li>
        <li v-for="page in pages" v-show="isVisible(page)" :class="value==page ? 'active' : 'waves-effect'" @click="changePage(page)"><a href="#">{{ page }}</a></li>
        <li :class="value==pages ? 'disabled' : 'waves-effect'" @click="changePage(value+1)"><a href="#"><i class="material-icons">chevron_right</i></a></li>
    </ul>
</template>

<style scoped>
    .my-pagination{
        text-align: center;
    }

    .my-pagination li.active{
        background-color: #4caf50;
    }

    .disabled{
        pointer-events: none;
    }
</style>
<script>
    export default {
        props: {
            pages: Number,
            value: Number
        },
        data: function(){
            return {

            }
        },

        methods: {
            changePage: function(page){
                this.$emit("input", page)
            },

            isVisible: function(page){
                var left = this.value - (this.value<this.pages-2 ? 2 : this.value-this.pages+4);
                var right = this.value + (this.value>2 ? 2 : 5-this.value);
                return page>=left && page<=right;
            }
        }
    }
</script>