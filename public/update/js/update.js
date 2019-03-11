var head = new Vue({
	el:'#find',
	data:{
		address:"",
		addressAll:['金翰林','琴湖','北苑','南苑','逸夫楼','一教','三教'],
		nickname:'',
		Class:'',
		phone:'',
		qq:'',
		weixin:'',
		type:'',
		stu_card:'',
		description:'',
		title:'',
		date:'',
		src:'../img/yuhan.jpg'
	},
	methods:{
		upload:function(c,d){
		    "use strict";
		    var $c = document.querySelector(c),
		        $d = document.querySelector(d),
		        file = $c.files[0],
		        reader = new FileReader();
		    	reader.readAsDataURL(file);
		    	reader.onload = function(e){
		        $d.setAttribute("src", e.target.result);
		    };
     	},
     	judge:function(data){
     		//必填项
     		if(data.type==="")
     		{
     			mui.alert("请选择发布类型");
     			return false;
     		}
     		if(data.stu_card === "")
     		{
     			mui.alert("请选择是丢失校园卡还是其他");
     			return false;
     		}
     		if(data.title==""||data.title.length>100)
     		{
     			mui.alert("标题必填并且长度不超过100字");
     			return false;
     		}
     		if(data.description==""||data.description.length>200)
     		{
     			mui.alert("描述必填并且长度不超过200字");
     			return false;
     		}
     		
     		var patt = /^[\d]{4}-[\d]{2}-[\d]{2}$/;
     		if(data.date==""||patt.test(data.date) == false)
     		{
     			mui.alert("请完整填写日期");
     			return false;
     		}
     		return true;
     		
     	},
     	JsonToString:function(FormData){
			var data = "";
			Object.keys(FormData).forEach(function(key){
		     	//console.log(key,FormData[key]);
		     	data += key + '=' + FormData[key] + '&';
		     	//console.log(data);
			});
			data = data.substr(0,data.length-1);
			return data;
		},
		getCouponSelected:function(){
            //获取选中的优惠券
            console.log(this.address)
        },
		submit:function(){
			var find = document.querySelector('#find');
			var Formdata = {
				type:this.type,
				title:this.title,
				description:this.description,
				stu_card:this.stu_card,
				address:this.address,
				date:this.date,
				img:document.querySelector('#fileBtn').files[0]
			}
			console.log(Formdata);
			
			var data = new FormData(find);
			data.append('date', this.date);
			data.append('address', this.address);
			data.append('title', this.title);
			if(this.judge(Formdata) == true)
				this.Ajax(data);

		},
		Ajax:function(data){
			var ajax = new XMLHttpRequest();
			ajax.onreadystatechange = function () {
			if (ajax.readyState == 4 && ajax.status == 200) {
				var result = JSON.parse(ajax.responseText);
				console.log(result);
				console.log(head.type);
				if(result.code == 0&&this.type == 1)
				{
					localStorage.setItem('type',1)
					window.location.href = "../list/list.html"
				}
					
				else if(result.code == 0&&this.type == 0)
				{
					localStorage.setItem('type',0)
					window.location.href = "../list/list.html"
				}
				else if(result.code == 9||result.code==10)
				{
					mui.alert("服务器正忙，请等10秒再提交");
				}
				else{
					mui.alert("发布失败，请联系管理员");
				}
			}
			}
			ajax.withCredentials = true;
			ajax.open("POST", "https://found.sky31.com/update/"+localStorage.getItem('id'), true);//false同步    true异步
			//ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			ajax.send(data);
		}
	}
})
function loadForm(){
	var ajax = new XMLHttpRequest();
	ajax.onreadystatechange = function () {
		if (ajax.readyState == 4 && ajax.status == 200) {
			console.log(ajax.responseText);
			var result = JSON.parse(ajax.responseText);
			result = result.data;
			console.log(result);
			head.date = result.date;
			head.type = result.type;
			head.stu_card = result.stu_card;
			head.addrss = result.address;
			head.title = result.title;
			head.description = result.description;
			head.src='https://found.sky31.com/upload/laf/' + result.img;
		}
	}
	ajax.withCredentials = true;
	ajax.open("GET", "https://found.sky31.com/laf/"+localStorage.getItem('id'), true);//false同步    true异步
	ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajax.send();
}
window.onload = function(){
	head.address = head.addressAll[0];
	if(localStorage.getItem('id')==undefined||localStorage.getItem('id')==null)
	{
			mui.alert("访问路径错误");
			window.location.href = "../index.html"
	}
	var ajax = new XMLHttpRequest();
	ajax.onreadystatechange = function () {
		if (ajax.readyState == 4 && ajax.status == 200) {
			console.log(ajax.responseText);
			var result = JSON.parse(ajax.responseText);
			
			if(result.code == 6)
			{
				mui.alert("请先登录");
				window.location.href = "../login/login.html"
			}
			else{
				result = result.data;
				head.nickname = result.nickname;
				head.Class = result['class'];
				head.qq = result.qq;
				head.phone = result.phone;
				head.weixin = result.wx;
			}
		}
	}
	ajax.withCredentials = true;
	ajax.open("GET", "https://found.sky31.com/user/info", true);//false同步    true异步
	ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajax.send();
	loadForm();
}
