var nav = new Vue({
	el:'#nav',
	data:{
		imgUrl:"",
		login:true,
		user:false
	}
})
var searchForm = new Vue({
	el:'#searchForm',
	data:{
		search:'',
		hotObject:['校园卡','身份证','手机','耳机','水杯','书包','钥匙','眼镜'],
		hotAddress:['一教','二教','三教','一田','二田','逸夫楼','经管楼','兴湘','学活','坑里','联建','金翰林','琴湖','南苑','北苑','北五','乐园','雅苑'],
		addr:false,
		obj:true,
		left:true
	},
	methods:{
		addInSearch:function(e){
			
			if(e.target.className == 'selectOption'){
				this.search += e.target.innerHTML + " ";
				e.target.className = 'selected';
				
			}
			else{
				e.target.className = 'selectOption';
				var pos = this.search.indexOf(e.target.innerHTML);
				this.search= this.search.split(e.target.innerHTML).join("");
			
				
			}
		},
		AddressTab:function(){
			//this.hotAddress = ['一教','二教','三教','一田','二田','逸夫楼','经管楼','兴湘','学活','坑里','联建','金翰林','琴湖','南苑','北苑','北五食堂','乐园食堂'];
			this.obj = false;
			this.addr = true;
			this.left = false;
		
		},
		ObjectTab:function(){
			//this.hotObject = ['校园卡','身份证','手机','耳机','水杯','书包','钥匙','眼镜']
			this.left = true;
			this.addr = false;
			this.obj = true;
		},
	}
})
var find = new Vue({
	el:'#find',
	data:{
		findTitle:[]
	},
	methods:{
		getId:function(e){
			setCookie("id",e.target.dataset.id);
	
			window.location.href="./findDetail/findDetail.html";
		},
		getType:function(e){
			setCookie("type",e.target.dataset.type);
			window.location.href="./list/list.html";

		}
	}
	
})
var get = new Vue({
	el:'#get',
	data:{
		getTitle:[]
	},
	methods:{
		getId:function(e){
			setCookie("id",e.target.dataset.id);
			window.location.href="./findDetail/findDetail.html";
		},
		getType:function(e){
			setCookie("type",e.target.dataset.type);
			window.location.href="./list/list.html";
			
		}
	}
})
function laf(){
	var ajax = new XMLHttpRequest();
	ajax.onreadystatechange = function () {
		if (ajax.readyState == 4 && ajax.status == 200) {
			//console.log(ajax.responseText);
			var result = JSON.parse(ajax.responseText).data.laf;
			var nickname = JSON.parse(ajax.responseText).data.nickname;
			var a=0,b=0;
			for(var i=0;i<result.length;i++)
			{
				if(result[i].img != null)
					result[i].img = "https://found.sky31.com/upload/laf/" + result[i].img;
				else 
					result[i].img = "./img/yuhan.jpg";
				
				result[i].nickname = nickname[result[i].user_id]; 
				result[i].time = result[i].updated_at.substr(5,5);
				if(result[i].type==1&&a<8){
					find.findTitle.push(result[i]);
					a++;
				}
				else if(result[i].type==0&&b<8){
					get.getTitle.push(result[i]);
					b++;
				}
				else{
					break;
				}
					
			}
			
		}
	}
	ajax.withCredentials = true;
	ajax.open("GET", "https://found.sky31.com/laf", true);//false同步    true异步
	ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajax.send();
}

	
function search(){
	var search = document.querySelector('#search');
	var searchIcon = document.querySelector('#search-icon');
	search.addEventListener('keypress',function(e){
		if(e.keyCode == 13)
		{
			var content = searchForm.search;
			strs=content.split(" "); //字符分割
			
			for (i=0;i<strs.length ;i++ ) 
			{ 
				if(strs[i]==" "||strs[i]=="")
					strs.splice(i,1);
				
			} 
			setCookie('keyword',strs);
			setCookie('type',2);
			window.location.href = "./list/list.html";
		}		
	})
	searchIcon.addEventListener('click',function(e){
		var content = searchForm.search;
		strs=content.split(" "); //字符分割
		
		for (i=0;i<strs.length ;i++ ) 
		{ 
			if(strs[i]==" "||strs[i]=="")
				strs.splice(i,1);
			
		} 
		setCookie('keyword',strs);
		setCookie('type',2);
		window.location.href = "./list/list.html";	
	})
}

function checkStage(){
	var str ="GongGong Webview";
	//console.log(navigator.userAgent);
	if(navigator.userAgent.indexOf(str) != -1)
	{
		document.querySelector("header").style.display = 'none'
	}
	var sUserAgent = navigator.userAgent.toLowerCase();
    var bIsIpad = sUserAgent.match(/ipad/i) == "ipad";
    var bIsIphoneOs = sUserAgent.match(/iphone os/i) == "iphone os";
    var bIsMidp = sUserAgent.match(/midp/i) == "midp";
    var bIsUc7 = sUserAgent.match(/rv:1.2.3.4/i) == "rv:1.2.3.4";
    var bIsUc = sUserAgent.match(/ucweb/i) == "ucweb";
    var bIsAndroid = sUserAgent.match(/android/i) == "android";
    var bIsCE = sUserAgent.match(/windows ce/i) == "windows ce";
	var bIsWM = sUserAgent.match(/windows mobile/i) == "windows mobile";
	if (!(bIsIpad || bIsIphoneOs || bIsMidp || bIsUc7 || bIsUc || bIsAndroid || bIsCE || bIsWM)) {
	  
      mui.alert("pc端使用体验较差,部分功能无法使用.建议使用移动端");
	}

}
window.onload = function(){
	checkStage();
	search();
	var random = Math.round(Math.random()*100);
	var ajax = new XMLHttpRequest();
	ajax.onreadystatechange = function () {
		if (ajax.readyState == 4 && ajax.status == 200) {
			//console.log(ajax.responseText);
			var result = JSON.parse(ajax.responseText);
			
			if(result.code == 6)
			{
				mui.alert("请先登录");
			
			}
			else{
				nav.imgUrl = "background-image:url('https://found.sky31.com/upload/avatar/" + result.data.avatar + '?a='+ random + "');";;
				nav.login = false;
				nav.user = true;
				
			}
		}
	}
	ajax.withCredentials = true;
	ajax.open("GET", "https://found.sky31.com/user/info", true);//false同步    true异步
	ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajax.send();
	laf();
}
