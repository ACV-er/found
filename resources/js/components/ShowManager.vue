<style scoped>
    .operate {
        display: inline-block;
        margin: 0 5px;
        font-size: 12px;
        color: rgb(0, 193, 222);
        cursor: pointer;
    }

    .operate:hover {
        color: rgb(0, 177, 205);
    }

    .operate:active {
        color: rgb(0, 104, 128);
    }

    th {
        color: rgb(102, 102, 102);
        font-size: 12px;
        padding: 5px 10px 5px 4px;
    }

    table {
        display: inline-block;
        margin-top: 40px;
    }

    .main {
        display: flex;
        justify-content: center;
        min-width: 1000px;
    }

    .add {
        position: fixed;
        top: 0; right: 0; bottom: 0; left: 0;
        display: flex;
        justify-content:center;
        background-color: rgba(0,0,0,.3);
        color: rgb(42,62,80);
    }

    .m-add {
        position: absolute;
        margin: auto;
        top: 0; right: 0; bottom: 0; left: 0;

        display: flex;
        flex-wrap: wrap;
        align-items: center;

        width: 500px;
        height: 300px;
        background-color: white;
        border-radius: 5px;
    }

    .from_button {
        width: 50px;
        height: 50px;
        border: 0;
        background-color: #06C;
        color: white;
        border-radius: 50px;
        margin:0 auto;
    }

    .input {
        display: block;
        width: 60%;
        height: 40px;
        border: #656565 solid 1px;
        border-radius: 5px;
        margin: 0 100px;
    }

    .button_box {
        width: 100%;
        display: flex;
        justify-content:center;
    }

    .add_button {
        display: flex;
        justify-content:center;
    }

    #add {
        width: 80px;
        height: 40px;
        border: 0px;
        border-radius: 5px;
        background-color: #0094fd;
    }

    .select {
        display: block;
        margin: 0 auto;
    }
</style>
<template>
    <div>
        <div class="add_button">
            <button id="add" v-on:click="addShow=true">添加管理员</button>
        </div>
        <div class="main">
            <table>
                <thead>
                <tr>
                    <th>id</th>
                    <th>账户</th>
                    <th>name</th>
                    <th>权限等级</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="manager in managers">
                    <th>{{ manager.id }}</th>
                    <th>{{ manager.stu_id }}</th>
                    <th>{{ manager.name }}</th>
                    <th>{{ power[manager.power] }}</th>
                    <th>
                        <div class="operate" v-on:click="deleteManager(manager.id)">删除</div>
                        |
                        <div class="operate" v-on:click="updateManagerShow(manager)">修改</div>
                    </th>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="add" v-if="addShow">
            <div class="m-add">
                <input class="input" v-model="add.stu_id" placeholder="请输入学号"/>

                <label class="select">权限等级
                    <select v-model="add.power">
                        <option value="1">高级管理员</option>
                        <option value="2">管理员</option>
                    </select>
                </label>
                <div class="button_box">
                    <button class="from_button" v-on:click="addShow=false">取消</button>
                    <button class="from_button" v-on:click="addManager()">确定</button>
                </div>
            </div>
        </div>
        <div class="update" v-if="updateShow">
            <div class="m-add">
                <input class="input" v-model="update.stu_id" placeholder="请输入学号"/>

                <label class="select">权限等级
                    <select v-model="update.power">
                        <option value="1">高级管理员</option>
                        <option value="2">管理员</option>
                    </select>
                </label>
                <div class="button_box">
                    <button class="from_button" v-on:click="updateShow=false">取消</button>
                    <button class="from_button" v-on:click="updateManager(update.id)">确定</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                managers: [],
                power: ['超级管理员', '高级管理员', '管理员'],
                addShow: false,
                add: {'stu_id':'',power:''},
                updateShow: false,
                update: {'stu_id':'',power:''}
            }
        },
        methods: {
            getManager() {
                let obj = this;
                let ajax = new XMLHttpRequest();
                ajax.onreadystatechange = function () {
                    if (ajax.readyState === 4 && ajax.status === 200) {
                        obj.managers = JSON.parse(ajax.responseText).data;
                    }
                };
                ajax.open("GET", "http://found.myweb.com/manager/list", true);//false同步    true异步
                ajax.send();
            },
            deleteManager(id) {
                let r = confirm("你确认删除么?");
                if (r === true) {
                    window.axios.get('http://found.myweb.com/manager/delete/' + id).then(({data}) => {
                        if (data.code === 0) {
                            this.getManager();
                            alert('成功');
                        } else if (data.code === 5) {
                            alert('权限不足');
                        } else {
                            alert(data.status + '\n' + data.data);
                        }
                    });
                }
            },
            addManager() {
                let data = 'stu_id='+this.add.stu_id+'&power='+this.add.power;
                console.log(data);
                window.axios.post(`http://found.myweb.com/manager/add`, data).then(({ data }) => {
                    if(data.code === 0) {
                        this.addShow=false;
                        this.getManager();
                    } else {
                        alert('失败' + data.status + '\n' + data.data);
                    }
                });
            },
            updateManager(id) {
                let data = 'stu_id='+this.update.stu_id+'&power='+this.update.power;
                window.axios.post(`http://found.myweb.com/manager/update/`+id, data).then(({ data }) => {
                    if(data.code === 0) {
                        this.updateShow=false;
                        this.getManager();
                    } else {
                        alert('失败' + data.status + '\n' + data.data);
                    }
                });
            },
            updateManagerShow(manager) {
                this.update = manager;
                this.updateShow = true;
            }
        },
        created: function () {
            this.getManager();
        }
    }
</script>
