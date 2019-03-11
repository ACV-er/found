

var detail  = new Vue({
	el:'#detail',
	data:{
		detail:[]
	},
	methods:{
		getId:function(e){
			localStorage.setItem("id", e.target.dataset.id);
	
			window.location.href="../findDetail/findDetail.html";
		}
	}
});
var searchForm = new Vue({
	el:'#searchForm',
	data:{
		search:'',
	},
	methods:{

	}
})
function search(){
	var search = document.querySelector('#search');
	
	search.addEventListener('keypress',function(e){
		if(e.keyCode == 13)
		{
			var content = search.value;
			strs=content.split(" "); //字符分割 
			for (i=0;i<strs.length ;i++ ) 
			{ 
				console.log(strs[i]); //分割后的字符输出 
			}
			localStorage.setItem('keyword',strs);
			localStorage.setItem('type',2);
			Ajaxsearch(); 
		}		
	})
}
function Ajaxsearch(){
	var str_arr = new Array();
	str_arr = localStorage.getItem('keyword').split(',');
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
	
	console.log(data);
	ajax.onreadystatechange = function () {
		if (ajax.readyState == 4 && ajax.status == 200) {
			var result = JSON.parse(ajax.responseText);
			console.log(result);
			if(result.code == 0)
			{
				result = result.data;
				for(var i=0;i<result.length;i++)
				{
					if(result[i].img != null)
						result[i].img = "https://found.sky31.com/upload/laf/" + result[i].img;
					else
						result[i].img ='../img/yuhan.jpg';
					detail.detail.push(result[i]);
				}
			}
		}
	}
	ajax.withCredentials = true;
	ajax.open("POST", "https://found.sky31.com/search", true);//false同步    true异步
	ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajax.send(data);
}
window.onload = function(){
	search();
	var ajax = new XMLHttpRequest();
	ajax.onreadystatechange = function () {
		if (ajax.readyState == 4 && ajax.status == 200) {
			console.log(ajax.responseText);
			var result = JSON.parse(ajax.responseText).data;
			console.log(result);
			if(localStorage.getItem('type') == 2)
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
					
					if(localStorage.getItem('type') == 1  && result[i].type == 1)
						detail.detail.push(result[i]);
					else if(localStorage.getItem('type')== 0  && result[i].type == 0)
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