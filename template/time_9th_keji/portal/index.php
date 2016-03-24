<?php echo 'Theme by Time';exit;?>
<!--{template common/header}-->
<link rel="stylesheet" type="text/css" id="time_diy" href="template/time_9th_keji/src/portaldiy.css?{VERHASH}" />
<style id="diy_style" type="text/css"></style>
<script src="template/time_9th_keji/src/js/jquery.superslide.js" type="text/javascript"></script>

<div class="index-main lph-main cl">
    <!--[diy=diy_top]--><div id="diy_top" class="area"></div><!--[/diy]-->
    <div class="inner cl">
    <div class="index-left lph-left">
          <div class="idx-hotlists cl">
             <!--[diy=diy1]--><div id="diy1" class="area"></div><!--[/diy]-->
          </div>
          <script type="text/javascript">
	         jQuery.noConflict();
             jQuery(".m-slide").slide({ titCell:".tab li", mainCell:".img",effect:"fold", autoPlay:true});
          </script>
          <div class="idx-selLead cl">
          <!--[diy=diy3]--><div id="diy3" class="area"></div><!--[/diy]-->
      </div>
          <div class="lph-pageList index-pageList"> 
             <!--[diy=diy5]--><div id="diy5" class="area"></div><!--[/diy]-->
          </div>
        </div>
    <div class="lph-right">
          <!--[diy=diy6]--><div id="diy6" class="area"></div><!--[/diy]-->
          <div class="kuaixun right-box">
             <!--[diy=diy7]--><div id="diy7" class="area"></div><!--[/diy]-->
          </div>
          <!--[diy=diy8]--><div id="diy8" class="area"></div><!--[/diy]-->
          <div class="hotauthor right-box">
             <!--[diy=diy9]--><div id="diy9" class="area"></div><!--[/diy]-->
          </div>
          <div class="pbzttab right-box" id="pbzttab">
        <div class="pbz-hd cl"> <a class="cur" href="javascript:;">精彩文章</a> <a class="" href="javascript:;">最新专题</a> <i></i> </div>
        <div class="pbz-bd">
              <div class="bd comment">
            <!--[diy=diy10]--><div id="diy10" class="area"></div><!--[/diy]-->
          </div>
              <div class="bd zhuanti">
            <!--[diy=diy11]--><div id="diy11" class="area"></div><!--[/diy]-->
          </div>
            </div>
      </div>
          <div class="express right-box cl">
             <!--[diy=diy12]--><div id="diy12" class="area"></div><!--[/diy]-->
          </div>
        <div class="weixinewm right-box">
           <div class="we-img"><img src="template/time_9th_keji/src/lph-right-ewm.jpg"></div>
        </div>
        <!--[diy=diy13]--><div id="diy13" class="area"></div><!--[/diy]-->
        </div>
    </div>
    <div class="mainother cl">
       <!--[diy=diy_last]--><div id="diy_last" class="area"></div><!--[/diy]-->
    </div>
</div>
<script type="text/javascript">
jQuery.noConflict();
jQuery("#pbzttab .pbz-hd a").mouseover(function(){
  var num  = jQuery("#pbzttab .pbz-hd a").index(jQuery(this));
  var this1 = jQuery(this);
  jQuery("#pbzttab .pbz-bd .bd").css({"display":"none"});
  jQuery("#pbzttab .pbz-bd .bd").eq(num).css({"display":"block"});
  jQuery("#pbzttab .pbz-hd i").stop().animate({"left":0+num*139},{ easing: 'easeInExpo', duration: 200,complete:function(){
    jQuery("#pbzttab .pbz-hd a").removeClass("cur")
    this1.addClass("cur");
  }})
})
var yjkxFadeIn = function(){
    var list = jQuery("#pbzttab .pbz-bd .bd ul li"),
        length = list.length,
        speed = 550,
        time = 80,
        // timeadd = 10,
        maxCount = length-1,
        minCount = 0,
        curCount = minCount,
        initLeft = 20,
        initLeftInterval = 5,
        initOpcity = 0,
        endOpcity = 1;

    var fade = function(){
       // function xhFade(){
       //    setTimeout(function(){
       //        // list.eq(curCount).fadeIn(speed);
       //        list.eq(curCount).animate({"left":0,'opacity':1})
       //        curCount++;
       //        time += timeadd;
       //        if(curCount<=maxCount){
       //            xhFade();
       //        }
       //   },time)
       // }
       // xhFade();
       var sitv = setInterval(function(){
            if(curCount>maxCount){
              clearInterval(sitv);
            }
            list.eq(curCount).fadeIn(speed);
            curCount++;
       },time)
    }

    // var init = function(){
    //   for(var i = minCount; i<= maxCount; i++){
    //       list.eq(i).css({'left':initLeft+i*initLeftInterval,'opacity':initOpcity})
    //   }
    // }

    var star = function(){
      // init();
      fade();
    }

    star();
}
yjkxFadeIn();
</script>    
      
<!--{template common/footer}--> 
