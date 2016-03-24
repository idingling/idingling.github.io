
var http_host = 'http://'+window.location.host;
//RAF 支持~
	requestAnimationFrame = window.requestAnimationFrame ||
	    window.mozRequestAnimationFrame ||
	    window.webkitRequestAnimationFrame ||
	    window.msRequestAnimationFrame ||
	    window.oRequestAnimationFrame ||
	    function(callback) {return setTimeout(callback, 1000 / 60); };		

	if (!window.requestAnimationFrame) {
		var lastTime = 0;
		requestAnimationFrame = function(callback, element) {
			var currTime = new Date().getTime();
			var timeToCall = Math.max(0, 16 - (currTime - lastTime));
			var id = window.setTimeout(function() { callback(currTime + timeToCall); },
			  timeToCall);
			lastTime = currTime + timeToCall;
			return id;
		};
	}
	cancelAnimationFrame = window.cancelAnimationFrame ||
	    window.mozCancelAnimationFrame ||
	    window.webkitCancelAnimationFrame ||
	    window.msCancelAnimationFrame ||
	    window.oCancelAnimationFrame ||
	    function(callback) {return clearTimeout(callback, 1000 / 60); };
	
var Util=function(){
	this.config();
	this.init();
}

Util.prototype={
		constructor:Util,
		config:function(){
			//this.j_info=".J_info";
			this.j_intro=jq(".J_intro");
			//this.j_linescroll=jq(".J_linescroll");
			//this.j_mifens=jq(".J_mifens");
		},
		init:function(){
			this.subEvent();
			this.publishEvent();
		},
		//发布
		publish: function(){
			var callback, list, i, length, args = [].slice.call(arguments,0), ev = args.shift();
			if(!(callback = this._callback) || !(list = this._callback[ev])){
				return this;
			}
			for(i = 0, length = list.length; i < length; i++){
				list[i].apply(this, args);
			}
			return this;
		},
		subEvent:function(){
			var _this=this;
			//this.subscribe("picshowbot",_this.picShowBot);
			//this.subscribe("pichidbot",_this.picHidBot);
			//_this.subscribe("rewardscroll",_this.rewardScroll);
			//this.subscribe("randomthree",_this.randomThree);
		},
		publishEvent:function(){
			var _this=this;
			//鼠标悬浮图片之上，
			var beau_intro=_this.j_intro;

			beau_intro.mouseover(function(){
				var j_if=jq(this).siblings(".J_info");
				_this.picShowBot(j_if);
			});
			//鼠标离开
			beau_intro.mouseout(function(){
				var j_if=jq(this).siblings(".J_info");
				_this.picHidBot(j_if);
			});
			//经验名单滚动
			//_this.publish("rewardscroll");

			//中奖米粉随机更换
			_this.randomThree();
		
		},

		//中奖米粉，随机换三个
		//要求，数量必须12个以上.并且是3的倍数
		randomThree:function(){
			var tmpl = document.getElementById('mifen-tmpl').innerHTML;
			var doTtmpl = doT.template(tmpl);
			var _this=this;
			var j_mifens=_this.j_mifens;
			var length=mifens.data.length;
			
			if(length===0){
				return;
			}else{
				mifens.end=length;
			}

			var init=function(){
				
				var val=doTtmpl(mifens);

				document.getElementById('J_mifens').innerHTML = val;

			}
			init();

			
			var oridata=mifens.data;
			var flag=8;

			var tmpl2= document.getElementById('mifen2-tmpl').innerHTML;
			
			var doTtmpl2 = doT.template(tmpl2);
			var loop=function(){

				var a=(Math.round(Math.random()*9)-1)<0 ? 0:(Math.round(Math.random()*9)-1);
				var b=(Math.round(Math.random()*9)-1)<0 ? 0:(Math.round(Math.random()*9)-1);
				var c=(Math.round(Math.random()*9)-1)<0 ? 0:(Math.round(Math.random()*9)-1);
				if(a==b||b==c||a==c){
					return;
				}
				var i=flag+1;
				
				var j=flag+2;
				var k=flag+3;
				
				var newdata=[];
				var fade=function(x,y){
						
					jq("#mifen"+x).fadeOut(1000,function(){
						var newmifen={
							start:y,
							end:y+1,
							data:oridata
						};
						var val=doTtmpl2(newmifen);
						jq(this).html(val);
						jq(this).hide();
						jq(this).fadeIn(1000);
					});
				};
				if(i>length||i==length){
					for(var u=0;u<9;u++){
						fade(u,u);
					}
					flag=8;
				}else{	
					fade(a,i);
					fade(b,j);
					fade(c,k);
					flag=flag+3;
				}
			}
			setInterval(loop,1500);
		},
		//经验名单滚动
		rewardScroll:function(){

			var tmpl = document.getElementById('reward-tmpl').innerHTML;
			
			var doTtmpl = doT.template(tmpl);
			
			var _this=this;

			var j_lineart=_this.j_linescroll;

			var lineArray=_this.lineArr;

  			var flag=6;
  			var lenFlag=1;
		
  			var lineLength=rewardlist.arr.length;
  			if(lineLength===0){
				return;
			}
			
  			var interLine=function(){
  				if(flag==lineLength){
  					flag=0;

  				}
  				rewardlist.start=flag;
  				rewardlist.end=flag+1;
  				var len=-36*lenFlag;
  				
  				j_lineart.append(doTtmpl(rewardlist));

  				j_lineart.animate({top:len+"px"},500);	
  				flag++;
  				lenFlag++;
  			}
  			setInterval(interLine,2000);
  			var initline=function(){
  				var lineHtml=doTtmpl(rewardlist);
  				j_lineart.html(lineHtml);
  			}
  			initline();
		},

		//图片滑落
		picHidBot:function(j_if){
			j_if.stop(true,true).animate({bottom: '-161px'}, "slow");
		},
		//图片从底部滑出
		picShowBot:function(j_if){
			j_if.stop(true,true).delay(300).animate({bottom: '0px'}, 400);
			  			// .animate({bottom: '-30px'}, 170)
			  			// .animate({bottom: '0px'}, 140)
			  			// .animate({bottom: '-15px'}, 130)
			  			// .animate({bottom: '0px'}, 120);
		}
}

jq(function(){
	new Util();
});