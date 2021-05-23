<template>
    <div class="row">
        <div class="col s7">
            <index @choose="choose" ref="rawsList"></index>
        </div>
        <div class="col s5">
            <crud ref="crudCard" @stored="stored" @updated="updated" @deleted="deleted"></crud>
        </div>
    </div>
</template>

<style scoped>
    .row, .row .col{
        height: 100%;
    }
</style>
<script>

    import RawCreate from "../admin/RawCreateUpdate.vue";
    import RawList from "../admin/RawList.vue";

    export default{
        components: {
            'index': RawList,
            'crud' : RawCreate
        },

        data(){
            return{
                tempRaw: null
            }
        },

        methods: {
            choose(raw){
                this.tempRaw = raw;
                this.$refs.crudCard.setRaw(raw);
            },

            stored(raw){
                this.$refs.rawsList.add(raw);
            },

            updated(raw){
                this.tempRaw.name = raw.name;
                this.tempRaw.color_id = raw.color_id;
                this.tempRaw.color_name = raw.color_name;
                this.tempRaw.parent_id = raw.parent_id;
                this.tempRaw.parent_name = raw.parent_name;
                this.tempRaw.firms = raw.firms;
                this.tempRaw.firm_names = raw.firm_names;
                this.tempRaw.is_pervichka = raw.is_pervichka;
                this.tempRaw.is_vtorichka = raw.is_vtorichka;
                this.tempRaw.is_granula = raw.is_granula;

            },

            deleted(){
                this.$refs.rawsList.deleteRaw(this.tempRaw);
            }
        }
    }
</script>