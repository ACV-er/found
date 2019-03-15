
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
		}
	},
	methods:{
		mark:function(){
			var ajax = new XMLHttpRequest();
			ajax.onreadystatechange = function () {
				if (ajax.readyState == 4 && ajax.status == 200) {
				
					var result = JSON.parse(ajax.responseText).data;
					
					if(result.code == 6)
					{
						mui.alert("请先登录",function(){
							window.location.href = "../login/login.html"
						});
						
					}
					else if(result.code == 0){
						mui.alert("标记成功",function(){
							window.history.go(-1);
						});
					}
					else{
						mui.alert("标记失败，请联系管理员");
					}
				}
			}
			ajax.withCredentials = true;
			ajax.open("GET", "https://found.sky31.com/mark/"+ localStorage.getItem("id"), true);//false同步    true异步
			ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			ajax.send();
		}
	}
		
})
window.onload = function(){
	var ajax = new XMLHttpRequest();
	ajax.onreadystatechange = function () {
		if (ajax.readyState == 4 && ajax.status == 200) {
		
			var result = JSON.parse(ajax.responseText);
			
			if(result.code == 6)
			{
				mui.alert("请先登录");
				window.location.href = "../login/login.html"
			}
			else{
				result = result.data;
				
				if(result.img!=null)
					result.img ='https://found.sky31.com/upload/laf/' + result.img;
				else
					result.img ='../img/yuhan.jpg'
				detail.information = result;
			}
		}
	}
	ajax.withCredentials = true;
	ajax.open("GET", "https://found.sky31.com/laf/"+ localStorage.getItem("id"), true);//false同步    true异步
	ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajax.send();
}