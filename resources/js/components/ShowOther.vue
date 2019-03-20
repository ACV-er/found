<template>
    <div>
        <div class="add">
            <form action="" id="add">
                <input id="keyword" placeholder="请输入新敏感词 空格分离"/>
                <button type="button" v-on:click="addmgc()" value="提交"/>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                keyword: '',
            }
        },
        methods: {
            addmgc() {
                let keyword = this.keyword.trim().split(/\s/);
            }
        }
    }
</script>

<style scoped>
    #add {
        position: relative;
        margin-top: 20px;
    }

    .add {
        display: flex;
        flex-direction: column;
        justify-content:center;
        min-width: 1000px;
    }

    #keyword {
        width: 300px;
        height: 30px;
        position: absolute;
        top: 0; left: 0; bottom: 0; right: 0;
        margin: auto;
    }
</style>
