

var detail  = new Vue({
	el:'#detail',
	data:{
		detail:[]
	},
	methods:{
		getId:function(e){
			setCookie("id", e.target.dataset.id);
			window.location.href="../findDetail/findDetail.html";
		}
	}
});
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

function search(){
	var search = document.querySelector('#search');
	var searchIcon = document.querySelector('#search-icon');
	search.addEventListener('keypress',function(e){
		if(e.keyCode == 13)
		{
			detail.detail = [];
			var content = search.value;
			strs=content.split(" "); //字符分割 
			for (i=0;i<strs.length ;i++ ) 
			{ 
				//console.log(strs[i]); //分割后的字符输出 
			}
			setCookie('keyword',strs);
			setCookie('type',2);
			Ajaxsearch(); 
		}		
	})
	searchIcon.addEventListener('click',function(e){
		detail.detail = [];
		var content = searchForm.search;
		strs=content.split(" "); //字符分割
		
		for (i=0;i<strs.length ;i++ ) 
		{ 
			if(strs[i]==" "||strs[i]=="")
				strs.splice(i,1);
			
		} 
		setCookie('keyword',strs);
		setCookie('type',2);
		Ajaxsearch();
	})
}
function Ajaxsearch(){
	var str_arr = new Array();
	str_arr = getCookie('keyword').split(',');
	searchForm.search = ""
	for(var i = 0 ; i<str_arr.length;i++)
	{
		if(i == str_arr.length - 1)
			searchForm.search += str_arr[i];
		else
			searchForm.search += str_arr[i]+" ";
	}
	var ajax = new XMLHttpRequest();
	var data = "keyword=" + JSON.stringify(str_arr);
	
	//console.log(data);
	ajax.onreadystatechange = function () {
		if (ajax.readyState == 4 && ajax.status == 200) {
			var result = JSON.parse(ajax.responseText);
			var nickname = JSON.parse(ajax.responseText).data.nickname;
			//console.log(result);
			if(result.code == 0)
			{
				result = result.data.laf;
				for(var i=0;i<result.length;i++)
				{
					if(result[i].img != null)
						result[i].img = "https://found.sky31.com/upload/laf/" + result[i].img;
					else
						result[i].img ='../img/yuhan.jpg';
					
					result[i].nickname = nickname[result[i].user_id]; 
					detail.detail.push(result[i]);
				}
				//console.log(detail.detail);
			}
		}
	}
	ajax.withCredentials = true;
	ajax.open("POST", "https://found.sky31.com/search", true);//false同步    true异步
	ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajax.send(data);
}

window.onload = function(){
    checkStage();
	search();
	var ajax = new XMLHttpRequest();
	ajax.onreadystatechange = function () {
		if (ajax.readyState == 4 && ajax.status == 200) {
			//console.log(ajax.responseText);
			var result = JSON.parse(ajax.responseText).data.laf;
			var nickname = JSON.parse(ajax.responseText).data.nickname;
			//console.log(result);
			if(getCookie('type') == 2)
			{
				Ajaxsearch();
			}
			else
			{
				for(var i=0;i<result.length;i++)
				{
					if(result[i].img != null)
						result[i].img = "https://found.sky31.com/upload/laf/" + result[i].img;
					else
						result[i].img ='../img/yuhan.jpg';
					result[i].time = result[i].updated_at.substr(5,5);

					result[i].nickname = nickname[result[i].user_id]; 
					//console.log(result[i].nickname)
					if(getCookie('type') == 1  && result[i].type == 1)
						detail.detail.push(result[i]);
					else if(getCookie('type')== 0  && result[i].type == 0)
						detail.detail.push(result[i]);
					
				}
			
			}
			
		}
	}
	ajax.withCredentials = true;
	ajax.open("GET", "https://found.sky31.com/laf", true);//false同步    true异步
	ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajax.send();
}                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     