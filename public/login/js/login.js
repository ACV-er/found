
var loginForm = new Vue({
	el:'#loginForm',
	data:{
		contact:false,
		id:'',
		psw:'',
		contact:''
	},
	methods:{
		JsonToString:function(FormData){
			var data = "";
			Object.keys(FormData).forEach(function(key){
		     	console.log(key,FormData[key]);
		     	data += key + '=' + FormData[key] + '&';
		     	//console.log(data);
			});
			data = data.substr(0,data.length-1);
			return data;
		},
		login:function(){
			if(this.id==""||this.psw=="")
			{
				mui.alert("请填写学号和密码!");
			}
			else if(this.contact == false)
			{
				mui.alert("请同意用户协议！");
			}
			else
			{
				var ajax = new XMLHttpRequest();
				var FormData = {
					stu_id:this.id,
					password:this.psw
				};
				var data = this.JsonToString(FormData);
				//console.log(data);
				ajax.onreadystatechange = function () {
					if (ajax.readyState == 4 && ajax.status == 200) {
						var result = JSON.parse(ajax.responseText);
						if(result.code == 2)
						{
							mui.alert("账号密码错误");
						}
						else if(result.code != 0)
						{
							mui.alert("未知错误，请联系拱拱管理员")
						}
						else
							window.location.href = '../index.html'
					}
				}
				ajax.withCredentials = true;
				ajax.open("POST", "https://found.sky31.com/login", true);//false同步    true异步
				ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				ajax.send(data);
			}
		},
//		test: function () {
//				var ajax = new XMLHttpRequest();
//				var data = "stu_id=201705550820&password=qq1246009411";
//				ajax.onreadystatechange = function () {
//					if (ajax.readyState == 4 && ajax.status == 200) {
//						console.log(ajax.responseText);
//					}
//				}
//				ajax.withCredentials = true;
//				ajax.open("get", "https://found.sky31.com/logintest", true);//false同步    true异步
//				ajax.withCredentials = true;
//				ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
//				ajax.send(data);
//		},
//
//		test2: function () {
//			var ajax = new XMLHttpRequest();
//			var data = "stu_id=201705550820&password=qq1246009411";
//			ajax.onreadystatechange = function () {
//				if (ajax.readyState == 4 && ajax.status == 200) {
//					console.log(ajax.responseText);
//				}
//			}
//			ajax.withCredentials = true;
//			ajax.open("get", "https://found.sky31.com/user/lost", true);//false同步    true异步
//			ajax.withCredentials = true;
//			ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
//			ajax.send(data);
//		}
	}
})
