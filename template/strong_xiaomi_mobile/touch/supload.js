var JSLOADED=[];function s_setid(A){return !A?null:document.getElementById(A)}function uploadWindow(C,B,A){var A=isUndefined(A)?"image":A;UPLOADWINRECALL=B;$("#weipost_sortoptions").load("./forum.php?mod=misc&action=upload&fid="+C+"&type="+A+"mobile=2")}function hideWindow(A){$("#"+A).remove()}function showError(B){var A=/<script[^\>]*?>([^\x00]*?)<\/script>/ig;B=B.replace(A,"");if(B!==""){showDialog(B,"alert","´íÎóÐÅÏ¢",null,true,null,"","","",3)}}function updatetradeattach(A,B,C){s_setid("tradeaid").value=A;s_setid("tradeattach_image").innerHTML='<img src="'+C+"/"+B+'" class="spimg" />';ATTACHORIMAGE=1}function updateactivityattach(A,B,C){s_setid("activityaid").value=A;s_setid("activityattach_image").innerHTML='<img src="'+C+"/"+B+'" class="spimg" />';ATTACHORIMAGE=1}function updatesortattach(B,C,D,A){s_setid("sortaid_"+A).value=B;s_setid("sortattachurl_"+A).value=D+"/"+C;s_setid("sortattach_image_"+A).innerHTML='<img src="'+D+"/"+C+'" class="spimg" />';ATTACHORIMAGE=1}function uploadWindowstart(){alert();s_setid("uploadwindowing").style.visibility="visible"}function uploadWindowload(){s_setid("uploadwindowing").style.visibility="hidden";var B=s_setid("uploadattachframe").contentWindow.document.body.innerHTML;if(B==""){return}var C=B.split("|");if(C[0]=="DISCUZUPLOAD"&&C[2]==0){UPLOADWINRECALL(C[3],C[5],C[6]);hideWindow("sortupfile")}else{var A="";if(C[7]=="ban"){A="(附件类型被禁止)"}else{if(C[7]=="perday"){A="(不能超过 "+C[8]+" 字节)"}else{if(C[7]>0){A="(不能超过 "+C[7]+" 字节)"}}}showError(STATUSMSG[C[2]]+A)}if(s_setid("attachlimitnotice")){ajaxget("forum.php?mod=ajax&action=updateattachlimit&fid="+fid,"attachlimitnotice")}}var showDialogST=null;function showDialog(msg,mode,t,func,cover,funccancel,leftmsg,confirmtxt,canceltxt,closetime,locationtime){clearTimeout(showDialogST);cover=isUndefined(cover)?(mode=="info"?0:1):cover;leftmsg=isUndefined(leftmsg)?"":leftmsg;mode=in_array(mode,["confirm","notice","info","right"])?mode:"alert";var menuid="fwin_dialog";var menuObj=s_setid(menuid);var showconfirm=1;confirmtxtdefault="È·¶¨";closetime=isUndefined(closetime)?"":closetime;closefunc=function(){if(typeof func=="function"){func()}else{eval(func)}hideMenu(menuid,"dialog")};if(closetime){showPrompt(null,null,"<i>"+msg+"</i>",closetime*1000,"popuptext");return}locationtime=isUndefined(locationtime)?"":locationtime;if(locationtime){leftmsg=locationtime+" ÃëºóÒ³ÃæÌø×ª";showDialogST=setTimeout(closefunc,locationtime*1000);showconfirm=0}confirmtxt=confirmtxt?confirmtxt:confirmtxtdefault;canceltxt=canceltxt?canceltxt:"È¡Ïû";if(menuObj){hideMenu("fwin_dialog","dialog")}menuObj=document.createElement("div");menuObj.style.display="none";menuObj.className="fwinmask";menuObj.id=menuid;s_setid("append_parent").appendChild(menuObj);var hidedom="";if(!BROWSER.ie){hidedom='<style type="text/css">object{visibility:hidden;}</style>'}var s=hidedom+'<table cellpadding="0" cellspacing="0" class="fwin"><tr><td class="t_l"></td><td class="t_c"></td><td class="t_r"></td></tr><tr><td class="m_l">&nbsp;&nbsp;</td><td class="m_c"><h3 class="flb"><em>';s+=t?t:"ÌáÊ¾ÐÅÏ¢";s+='</em><span><a href="javascript:;" id="fwin_dialog_close" class="flbc" onclick="hideMenu(\''+menuid+"', 'dialog')\" title=\"¹Ø±Õ\">¹Ø±Õ</a></span></h3>";if(mode=="info"){s+=msg?msg:""}else{s+='<div class="c altw"><div class="'+(mode=="alert"?"alert_error":(mode=="right"?"alert_right":"alert_info"))+'"><p>'+msg+"</p></div></div>";s+='<p class="o pns">'+(leftmsg?'<span class="z xg1">'+leftmsg+"</span>":"")+(showconfirm?'<button id="fwin_dialog_submit" value="true" class="pn pnc"><strong>'+confirmtxt+"</strong></button>":"");s+=mode=="confirm"?'<button id="fwin_dialog_cancel" value="true" class="pn" onclick="hideMenu(\''+menuid+"', 'dialog')\"><strong>"+canceltxt+"</strong></button>":"";s+="</p>"}s+='</td><td class="m_r"></td></tr><tr><td class="b_l"></td><td class="b_c"></td><td class="b_r"></td></tr></table>';menuObj.innerHTML=s;if(s_setid("fwin_dialog_submit")){s_setid("fwin_dialog_submit").onclick=function(){if(typeof func=="function"){func()}else{eval(func)}hideMenu(menuid,"dialog")}}if(s_setid("fwin_dialog_cancel")){s_setid("fwin_dialog_cancel").onclick=function(){if(typeof funccancel=="function"){funccancel()}else{eval(funccancel)}hideMenu(menuid,"dialog")};s_setid("fwin_dialog_close").onclick=s_setid("fwin_dialog_cancel").onclick}showMenu({"mtype":"dialog","menuid":menuid,"duration":3,"pos":"00","zindex":JSMENU["zIndex"]["dialog"],"cache":0,"cover":cover});try{if(s_setid("fwin_dialog_submit")){s_setid("fwin_dialog_submit").focus()}}catch(e){}};