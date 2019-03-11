var sortForm = new Vue({
	el:'#sortForm',
	data:{
		thingButtons:['校园卡','手机','耳机','其他'],
		thingSelect:'',
		addressButtons:['金翰林','琴湖','南苑','逸夫楼','北苑','二田','联建','坑里'],
		addressSelect:[],
		year:'',
		month:'',
		day:'',
		mo:[],
		d:[],
		y:[]
	},
	methods:{
		clearAddress:function(){
			this.addressSelect = [];
		},
		clearThing:function(){
			this.thingSelect = '';
		},
		clearTime:function(){
			this.year = '';
			this.month = '';
			this.day = '';
		},
		tload:function(){
			var date = new Date();
			this.y.push(date.getFullYear()); 
			this.y.push(date.getFullYear()+1); 
			for(i=0;i<12;i++)
			{
				this.mo.push(i+1);
			}
			for(i=0;i<31;i++)
			{
				this.d.push(i+1);
			}
			this.year = this.y[0];
			this.month = this.mo[0];
			this.day = this.d[0];
		},
		getCouponSelected:function(){
            //获取选中的优惠券
            console.log(this.couponSelected)
       },
		Submit:function(){
			
			
			
		}
	}
})
window.onload = sortForm.tload();

