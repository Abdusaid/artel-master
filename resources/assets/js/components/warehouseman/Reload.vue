<template>
    <div class="reload waves-effect waves-light" @click="reload">
        <i class="material-icons">refresh</i>
        <p>Перезагрузить</p>
    </div>
</template>

<style scoped>
    .reload{
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #bbb;
        cursor: pointer;
        font-weight: bold;
    }

    .reload i{
        font-size: 75px;
    }

    .reload p{
        margin: 0;
    }


</style>
<script>
    export default {
        methods: {
            reload: function(){
                this.$emit('reload');
            }
        }
    }
</script>