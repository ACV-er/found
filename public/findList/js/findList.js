

var detail  = new Vue({
	el:'#detail',
	data:{
		detail:[]
	},
	methods:{
		getId:function(e){
			localStorage.setItem("id", e.target.dataset.id);
			localStorage.setItem("type", e.target.dataset.type);
			window.location.href="../findDetail/findDetail.html";
		}
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
				if(strs[i]==" "||strs[i]=="")
					strs.splice(i,1);
				
			} 
			localStorage.setItem('keyword',strs);
		}		
	})
}
window.onload = function(){
	search();
	var ajax = new XMLHttpRequest();
	ajax.onreadystatechange = function () {
		if (ajax.readyState == 4 && ajax.status == 200) {
			console.log(ajax.responseText);
			var result = JSON.parse(ajax.responseText).data;
			console.log(result);
			for(var i=0;i<result.length;i++)
			{
				if(result[i].img != null)
					result[i].img = "https://found.sky31.com/upload/laf/" + result[i].img;
				else
					result[i].img ='../img/yuhan.jpg';
				result[i].time = result[i].updated_at.substr(5,5);
				if(result[i].type == 1)
					detail.detail.push(result[i]);
			}
		}
	}
	ajax.withCredentials = true;
	ajax.open("GET", "https://found.sky31.com/laf", true);//false同步    true异步
	ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajax.send();
}                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     