<style scoped>
    body {
        min-width: 1000px;
    }

    th {
        color: rgb(102,102,102);
        font-size: 12px;
        padding: 5px 10px 5px 4px;
    }

    tr:nth-child(odd), tr:nth-child(odd)>th{
        background-color: rgb(255,255,255);
    }

    tr:nth-child(even), tr:nth-child(even)>th{
        background-color: rgb(242,242,242);
    }

    table {
        display: inline-block;
        border-collapse:collapse;
        margin-top: 40px;

    }

    form {
        margin-right: 0;
    }

    .main {
        display: flex;
        justify-content:center;
        min-width: 1000px;
    }

    #refresh, #add {
        position: relative;
        margin-top: 20px;
        margin-right: 0;
        width: 48%;
        height: 80px;
    }

    #refresh {
        width: 48%;
    }

    .add {
        display: flex;
        justify-content:center;
        margin-right: 0;
        width: 100%;
    }

    #keyword {
        width: 300px;
        height: 30px;
        position: absolute;
        top: 0; left: 0; right: 0;
        margin: auto;
        border: rgb(102,102,102) solid 1px;
        border-radius: 3px;
    }

    #time {
        width: 50px;
        height: 18px;
        position: absolute;
        top: 0; left: 0; right: 0;
        margin: auto;
    }

    .button, #button {
        width: 60px;
        height: 30px;
        position: absolute;
        left: 0; bottom: 0; right: 0;
        margin: auto;
        border: rgb(102,102,102) solid 1px;
        border-radius: 3px;
        background-color: rgb(254,254,254);
    }

    .button {
        width: 120px;
        bottom: 6px;
    }

    .bigHead {
        font-size: 20px;
    }
</style>

<template>
    <div>
        <div class="add">
            <form action="" id="add">
                <input id="keyword" v-model="keyword" placeholder="请输入新敏感词 空格分离"/>
                <button id="button" type="button" v-on:click="addmgc()" >提交</button>
            </form>
            <form action="" id="refresh">
                <button class="button" type="button" v-on:click="getposts()" >获取{{ time }}天数据</button>
                <input id="time" type="number" v-model="time"/>
            </form>
        </div>
        <div class="main">
            <table>
                <thead>
                <tr>
                    <th class="bigHead">    </th>
                    <th class="bigHead">    </th>
                    <th class="bigHead">总计</th>
                    <th class="bigHead">    </th>
                    <th class="bigHead">    </th>
                    <th class="bigHead">招领</th>
                    <th class="bigHead">    </th>
                    <th class="bigHead">    </th>
                    <th class="bigHead">失物</th>
                    <th class="bigHead">    </th>
                </tr>
                </thead>
                <tr>
                    <th>    </th>
                    <th>合计</th>
                    <th>解决</th>
                    <th>校园卡数量</th>
                    <th>合计</th>
                    <th>解决</th>
                    <th>校园卡数量</th>
                    <th>合计</th>
                    <th>解决</th>
                    <th>校园卡数量</th>
                </tr>
                <tbody>
                    <tr v-for="post in posts">
                        <th>{{ post.address }}</th>
                        <th>{{ post.count }}</th>
                        <th>{{ post.solve }}</th>
                        <th>{{ post.stu_card }}</th>
                        <th>{{ post.found.count }}</th>
                        <th>{{ post.found.solve }}</th>
                        <th>{{ post.found.stu_card }}</th>
                        <th>{{ post.lost.count }}</th>
                        <th>{{ post.lost.solve }}</th>
                        <th>{{ post.lost.stu_card }}</th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                keyword: '',
                posts: [],
                time: 7
            }
        },
        methods: {
            addmgc() {
                let keyword = this.keyword.trim().split(/\s/);
                let r=confirm("你要添加的关键词如下：\n"+JSON.stringify(keyword)+"\n确定添加吗？");
                if (r === true){
                    window.axios.post(`https://found.sky31.com/add/mgc` , "keyword="+JSON.stringify(keyword)).then(({ data }) => {
                        if(data.code === 0) {
                                alert("成功");
                        } else {
                            alert('失败' + data.status + '\n' + data.data);
                        }

                    });
                }
            },
            getposts() {
                window.axios.get(`https://found.sky31.com/posts/info/`+this.time).then(({ data }) => {
                    let result = {};
                    result['总计'] = this.post_model('总计');
                    let type = ['found', 'lost'];

                    if(data.code !== 0) {
                        alert('错误！请联系管理员')
                    }

                    data = data.data;

                    for (let x of data) {
                        result['总计'].count += Number(x['count']);
                        result['总计'].solve += Number(x['solve']);
                        result['总计'].stu_card += Number(x['stu_card']);
                        result['总计'][type[x['type']]]['count'] += Number(x['count']);
                        result['总计'][type[x['type']]]['solve'] += Number(x['solve']);
                        result['总计'][type[x['type']]]['stu_card'] += Number(x['stu_card']);
                        if(result[x['address']] === undefined) {
                            result[x['address']] = this.post_model(x['address']);
                        }
                        result[x['address']]['count'] += Number(x['count']);
                        result[x['address']]['solve'] += Number(x['solve']);
                        result[x['address']]['stu_card'] += Number(x['stu_card']);
                        result[x['address']][type[x['type']]]['count'] = Number(x['count']);
                        result[x['address']][type[x['type']]]['solve'] = Number(x['solve']);
                        result[x['address']][type[x['type']]]['stu_card'] = Number(x['stu_card']);
                     }

                    let rel = [];
                    for (let x in result) {
                        rel.push(result[x]);
                    }

                    this.posts = rel.sort(function (a, b) {
                        return b.count - a.count;
                    });
                });
            },
            post_model(address) {
                return {
                    address: address, count: 0, solve: 0, stu_card: 0,
                    lost:{
                        count: 0, solve: 0, stu_card:0
                    },
                    found:{
                        count: 0, solve: 0, stu_card:0
                    }
                }
            }
        },
        created() {
            this.getposts();
        }
    }
</script>
