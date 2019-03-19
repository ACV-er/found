<style scoped>
    .operate {
        display: inline-block;
        margin: 0 5px;
        font-size: 12px;
        color: rgb(0,193,222);
        cursor:pointer;
    }

    .operate:hover {
        color: rgb(0, 177, 205);
    }

    .operate:active {
        color: rgb(0, 104, 128);
    }

    th {
        color: rgb(102,102,102);
        font-size: 12px;
        padding: 5px 10px 5px 4px;
    }

    table {
        display: inline-block;
        margin-top: 40px;
    }

    .main {
        display: flex;
        justify-content:center;
        min-width: 1000px;
    }

    .title {
        max-width: 100px;
    }

    .description {
        max-width: 200px;
    }

    .title, .description {
        white-space:nowrap;
        text-overflow:ellipsis;
        overflow:hidden;
    }

    .allInfo {
        position: fixed;
        top: 0; right: 0; bottom: 0; left: 0;
        display: flex;
        justify-content:center;
        background-color: rgba(0,0,0,.3);
    }

    .m-allInfo {
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
        border: 0px;
        background-color: #06C;
        color: white;
        border-radius: 50px;
        margin:0 auto;
    }

    .title_input {
        height: 3em;
    }

    .description_input {
        height: 5em;
    }

    .title_input, .description_input {
        width: 60%;
        margin:0 auto;
    }

    .button_box {
        width: 100%;
        display: flex;
        justify-content:center;
    }

    #search {
        position: relative;
        margin-top: 20px;
    }

    .search {
        display: flex;
        flex-direction: column;
        justify-content:center;
        min-width: 1000px;
    }

    #keyword {
        width: 220px;
        height: 30px;
        position: absolute;
        top: 0; left: 0; bottom: 0; right: 0;
        margin: auto;
    }
</style>
<template>
    <div>
        <div class="search">
            <form action="" id="search" v-on:submit.prevent="search()">
                <input id="keyword" type="search" name="keyword" v-model="keyword" placeholder="请输入查找关键字 id, nickname">
            </form>
        </div>
        <div class="main">
            <table>
                <thead>
                    <tr>
                        <th>id</th>
                        <th>类型</th>
                        <th class="title">标题</th>
                        <th class="description">描述</th>
                        <th>校园卡</th>
                        <th>地点</th>
                        <th>日期</th>
                        <th>标记</th>
                        <th>最后更新时间</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="post in posts">
                        <th>{{ post.id }}</th>
                        <th v-if="post.type === 1">失物</th>
                        <th v-else>招领</th>
                        <th class="title">{{ post.title }}</th>
                        <th class="description">{{ post.description }}</th>
                        <th v-if="post.stu_card === 1">是</th>
                        <th v-else>否</th>
                        <th>{{ post.address }}</th>
                        <th>{{ post.date }}</th>
                        <th v-if="post.mark === 1">是</th>
                        <th v-else>否</th>
                        <th>{{ post.updated_at }}</th>
                        <th>
                            <div class="operate" v-on:click="deletePost(post.id)">删除</div> |
                            <div class="operate" v-on:click="getAllInfo(post.id)">修改</div> |
                            <div class="operate" v-on:click="getUploader(post.user_id)">转到发布者</div>
                        </th>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="allInfo" v-if="allInfoShow">
            <div class="m-allInfo">
                <textarea class="title_input" v-model="allInfo.title">{{ allInfo.title }}</textarea>
                <textarea class="description_input" v-model="allInfo.description">{{ allInfo.description }}</textarea>
                <div class="button_box">
                    <button class ="from_button" v-on:click="allInfo={};allInfoShow=false;">取消</button>
                    <button class ="from_button" v-on:click="updateInfo()">确定</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        data() {
            return {
                keyword: '',
                posts: [],
                allInfoShow: false,
                allInfo:{}
            }
        },
        methods: {
            getPost() {
                let obj = this;
                let ajax = new XMLHttpRequest();
                ajax.onreadystatechange = function () {
                    if (ajax.readyState == 4 && ajax.status == 200) {
                        obj.posts = JSON.parse(ajax.responseText).data;
                    }
                };
                ajax.open("GET", "http://found.myweb.com/laf", true);//false同步    true异步
                ajax.send();
                return true;
            },
            search(){
                let keyword = this.keyword.trim().split(/\s/);
                window.axios.post(`http://found.myweb.com/search` , "keyword="+JSON.stringify(keyword)).then(({ data }) => {
                    if(data.code === 0) {
                        if( data.data.length ===  0) {
                            alert("没有相关发布");
                        }
                        this.posts=data.data;
                    } else {
                        alert('失败' + data.status + '\n' + data.data);
                    }

                });
            },
            getAllInfo(id) {
                window.axios.get('http://found.myweb.com/laf/' + id).then(({ data }) => {
                    this.allInfo = data.data;
                    this.allInfoShow = true;
                });
            },
            updateInfo() {
                let data = 'title='+this.allInfo.title+'&description='+this.allInfo.description;
                window.axios.post(`http://found.myweb.com/manager/post/update/` + this.allInfo.id, data).then(({ data }) => {
                    if(data.code === 0) {
                        this.allInfo={};
                        this.allInfoShow=false;
                        this.getPost();
                    } else {
                        alert('失败' + data.status + '\n' + data.data);
                    }
                });
            },
            deletePost(id) {
                let r=confirm("你确认删除么?");
                if (r === true){
                    window.axios.get('http://found.myweb.com/manager/post/delete/' + id).then(({ data }) => {
                        if(data.code === 0) {
                            this.getPost();
                            alert('成功');
                        } else {
                            alert(data.status + '\n' + data.data);
                        }
                    });
                }
            },
            getUploader(id) {
                alert('开发中,发布者id：' + id + ',请去用户搜索该用户!')
            }
        },
        created: function () {
            this.getPost();
        }
    }
</script>
