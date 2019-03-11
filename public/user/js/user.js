var head = new Vue({
	el:'#head',
	data:{
		imgUrl:'',
		id:'20170509xxxx',
		nickname:'可爱的昊昊',
	}
})
function next(){
	var ajax = new XMLHttpRequest();
	ajax.onreadystatechange = function () {
		if (ajax.readyState == 4 && ajax.status == 200) {
		
			var result = JSON.parse(ajax.responseText).data;
			console.log(result);
			for(var i=0;i<result.length;i++)
			{
				if(result[i].img != null)
					result[i].img = "https://found.sky31.com/upload/laf/" + result[i].img;
				else
					result[i].img ='../img/yuhan.jpg';
				result[i].time = result[i].updated_at.substr(5,5);
				detail.detail.push(result[i]);
			}
			
		}
	}
	ajax.withCredentials = true;
	ajax.open("GET", "https://found.sky31.com/user/laf", true);//false同步    true异步
	ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajax.send();
}
window.onload = function(){
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
				head.id = result.data.stu_id;
		
				head.nickname = result.data.nickname;
				head.imgUrl = 'https://found.sky31.com/upload/avatar/' + result.data.avatar;
			}
		}
	}
	ajax.withCredentials = true;
	ajax.open("GET", "https://found.sky31.com/user/info", true);//false同步    true异步
	ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajax.send();
	next();
}

var detail  = new Vue({
	el:'#mainPage',
	data:{
		detail:[]
	},
	methods:{
		getId:function(e){
			localStorage.setItem("id", e.target.dataset.id);
			localStorage.setItem("type", e.target.dataset.type);
			window.location.href="../getDetail/getDetail.html";
		}
	}
})


