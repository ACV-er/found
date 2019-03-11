

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
			qq:'1110011001',
			wx:'1110011001',
			nickname:'肖声昊',
			'class':''
		}
	},

		
})
window.onload = function(){
	var ajax = new XMLHttpRequest();
	console.log("!!!")
	ajax.onreadystatechange = function () {
		if (ajax.readyState == 4 && ajax.status == 200) {
		
			var result = JSON.parse(ajax.responseText).data;
			
			if(result.code == 6)
			{
				mui.alert("请先登录");
				window.location.href = "../login/login.html"
			}
			else{
				console.log(result);
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