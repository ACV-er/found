var head = new Vue({
	el:'#head',
	data:{
		address:['金翰林','琴湖','北苑','南苑','逸夫楼','一教','三教']
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
     	}	
	}
})

