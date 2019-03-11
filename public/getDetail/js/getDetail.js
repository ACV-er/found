
var user_id;
var detail  = new Vue({
	el:'#detail',
	data:{
		information:{
			title:'',
			description:'',
			img:'../img/yuhan.jpg',
			date:'',
			updated_at:'',
			address:'',
			phone:'',
			qq:'',
			wx:'',
			nickname:'',
			'class':''
		},
	},
	methods:{
			finish:function(){
				var ajax = new XMLHttpRequest();
				ajax.onreadystatechange = function () {
					if (ajax.readyState == 4 && ajax.status == 200) {
							var result = JSON.parse(ajax.responseText).data;
							console.log(result);
							mui.alert("完璧归赵~皆大欢喜",function(){
								window.history.go(-1);
							})
							
					}
				}
				ajax.withCredentials = true;
				ajax.open("GET", "https://found.sky31.com/finish/"+ localStorage.getItem("id"), true);//false同步    true异步
				ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				ajax.send();
			},
			delete:function(){
				
			},
			change:function(){
				window.location.href="../update/update.html"
			}
		}
		
})
function getuser(){
	var ajax = new XMLHttpRequest();
	ajax.onreadystatechange = function () {
		if (ajax.readyState == 4 && ajax.status == 200) {
			console.log(ajax.responseText);
			var result = JSON.parse(ajax.responseText);
			if(result.code == 6)
			{
				mui.alert("请先登录");
			
			}
			else{
				getLaf(result.data.user_id);
			}
		}
	}
	ajax.withCredentials = true;
	ajax.open("GET", "https://found.sky31.com/user/info", true);//false同步    true异步
	ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajax.send();
	
}
function getLaf(id){
	var ajax = new XMLHttpRequest();
	console.log("!!!")
	ajax.onreadystatechange = function () {
		if (ajax.readyState == 4 && ajax.status == 200) {
		
			var result = JSON.parse(ajax.responseText).data;
			console.log(id);
			console.log(result.user_id);
			if(id != result.user_id)
			{
				mui.alert("非法访问！",function(){
					window.location.href = "../index.html";
				});
				return;
			}
			if(result.img!=null)
				result.img ='https://found.sky31.com/upload/laf/' + result.img;
			else
				result.img ='../img/yuhan.jpg'
			detail.information = result;
		}
	}
	ajax.withCredentials = true;
	ajax.open("GET", "https://found.sky31.com/laf/"+ localStorage.getItem("id"), true);//false同步    true异步
	ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajax.send();
}
window.onload = function(){
	getuser();
	
}