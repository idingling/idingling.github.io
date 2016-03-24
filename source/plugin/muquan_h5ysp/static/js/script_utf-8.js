function h5ysp_btn(cmd) {
	checkFocus();
	showEditorMenu_h5ysp(cmd);
	return;
}

function showEditorMenu_h5ysp(tag, params) {
	var sel, selection;
	var str = '', strdialog = 0, stitle = '';
	var ctrlid = editorid + (params ? '_cst' + params + '_' : '_') + tag;
	var opentag = '[' + tag + ']';
	var closetag = '[/' + tag + ']';
	var menu = $(ctrlid + '_menu');
	var pos = [0, 0];
	var menuwidth = 270;
	var menupos = '43!';
	var menutype = 'menu';

	if(BROWSER.ie) {
		sel = wysiwyg ? editdoc.selection.createRange() : document.selection.createRange();
		pos = getCaret();
	}
	selection = sel ? (wysiwyg ? sel.htmlText : sel.text) : getSel();

	if(menu) {
		if($(ctrlid).getAttribute('menupos') !== null) {
			menupos = $(ctrlid).getAttribute('menupos');
		}
		if($(ctrlid).getAttribute('menuwidth') !== null) {
			menu.style.width = $(ctrlid).getAttribute('menuwidth') + 'px';
		}
		if(menupos == '00') {
			menu.className = 'fwinmask';
			if($(editorid + '_' + tag + '_menu').style.visibility == 'hidden') {
				$(editorid + '_' + tag + '_menu').style.visibility = 'visible';
			} else {
				showMenu({'ctrlid':ctrlid,'mtype':'win','evt':'click','pos':menupos,'timeout':250,'duration':3,'drag':ctrlid + '_ctrl'});
			}
		} else {
			showMenu({'ctrlid':ctrlid,'evt':'click','pos':menupos,'timeout':250,'duration':in_array(tag, ['fontname', 'fontsize', 'sml']) ? 2 : 3,'drag':1});
		}


	}
	else{
		 switch(tag) {
			case 'btn_h5ysp_mp3':
			    stitle='HTML5音乐播放器';
				str = '<p class="pbn">请输入音乐文件地址:</p><p class="pbn"><input type="text" id="' + ctrlid + '_param_1" class="px" value="" style="width: 220px;" /></p><p class="pbn"><label for="' + ctrlid + '_param_2"><input type="checkbox" id="' + ctrlid + '_param_2" class="pc" value="1"/> 是否自动播放</label></p><p class="xg2 pbn">支持mp3、ogg、wav 等HTML5音频格式；<br />示例: http://server/muquan.mp3</p>';
				break;
			case 'btn_h5ysp_mp4':
			    stitle='HTML5视频播放器';
				str = '<p class="pbn">请输入视频文件地址:</p><p class="pbn"><input type="text" id="' + ctrlid + '_param_1" class="px" value="" style="width: 220px;" /></p><p class="pbn">宽: <input id="' + ctrlid + '_param_2" size="5" value="" class="px" /> &nbsp; 高: <input id="' + ctrlid + '_param_3" size="5" value="" class="px" /></p><p class="xg2 pbn">支持mp4、ogg、webm等HTML5视频格式<br />示例: http://server/weigou.mp4</p>';
				break;
	        default:
				for(i in EXTRAFUNC['showEditorMenu']) {
					EXTRASELECTION= selection;
					try {
						eval('str = ' + EXTRAFUNC['showEditorMenu'][i] + '(\'' + tag + '\', 1)');
					} catch(e) {}
				}
				if(!str) {
					str = '';
					var first = $(ctrlid + '_param_1').value;
					if($(ctrlid + '_param_2')) var second = $(ctrlid + '_param_2').value;
					if($(ctrlid + '_param_3')) var third = $(ctrlid + '_param_3').value;
					if((params == 1 && first) || (params == 2 && first && (haveSel || second)) || (params == 3 && first && second && (haveSel || third))) {
						if(params == 1) {
							str = first;
						} else if(params == 2) {
							str = haveSel ? selection : second;
							opentag = '[' + tag + '=' + first + ']';
						} else {
							str = haveSel ? selection : third;
							opentag = '[' + tag + '=' + first + ',' + second + ']';
						}
						insertText((opentag + str + closetag), strlen(opentag), strlen(closetag), true, sel);
					}
				}
				break;
		 }
    menupos = '00';
	menutype = 'win';
	var menu = document.createElement('div');
	menu.id = ctrlid + '_menu';
	menu.style.display = 'none';
	menu.className = 'p_pof upf';
	menu.style.width = menuwidth + 'px';
	if(menupos == '00') {
			menu.className = 'fwinmask';
			s = '<table width="100%" cellpadding="0" cellspacing="0" class="fwin"><tr><td class="t_l"></td><td class="t_c"></td><td class="t_r"></td></tr><tr><td class="m_l">&nbsp;&nbsp;</td><td class="m_c">'
				+ '<h3 class="flb"><em>' + stitle + '</em><span><a onclick="hideMenu(\'\', \'win\');return false;" class="flbc" href="javascript:;">关闭</a></span></h3><div class="c">' + str + '</div>'
				+ '<p class="o pns"><button type="submit" id="' + ctrlid + '_submit" class="pn pnc"><strong>提交</strong></button></p>'
				+ '</td><td class="m_r"></td></tr><tr><td class="b_l"></td><td class="b_c"></td><td class="b_r"></td></tr></table>';
		} else {
			s = '<div class="p_opt cl"><span class="y" style="margin:-10px -10px 0 0"><a onclick="hideMenu();return false;" class="flbc" href="javascript:;">关闭</a></span><div>' + str + '</div><div class="pns mtn"><button type="submit" id="' + ctrlid + '_submit" class="pn pnc"><strong>提交</strong></button></div></div>';
		}
		menu.innerHTML = s;
	$(editorid + '_editortoolbar').appendChild(menu);
	showMenu({'ctrlid':ctrlid,'mtype':menutype,'evt':'click','duration':3,'cache':0,'drag':1,'pos':menupos});
	}

	try {
		if($(ctrlid + '_param_1')) {
			$(ctrlid + '_param_1').focus();
		}
	} catch(e) {}
	var objs = menu.getElementsByTagName('*');
	for(var i = 0; i < objs.length; i++) {
		_attachEvent(objs[i], 'keydown', function(e) {
			e = e ? e : event;
			obj = BROWSER.ie ? event.srcElement : e.target;
			if((obj.type == 'text' && e.keyCode == 13) || (obj.type == 'textarea' && e.ctrlKey && e.keyCode == 13)) {
				if($(ctrlid + '_submit') && tag != 'image') $(ctrlid + '_submit').click();
				doane(e);
			} else if(e.keyCode == 27) {
				hideMenu();
				doane(e);
			}
		});
	}
	if($(ctrlid + '_submit')) $(ctrlid + '_submit').onclick = function() {
		checkFocus();
		if(BROWSER.ie && wysiwyg) {
			setCaret(pos[0]);
		}
		switch(tag) {
			case 'btn_h5ysp_mp3':
			    var auto = $(ctrlid + '_param_2').checked ? '=1' : '';
				insertText('[h5audio' + auto +']' + $(ctrlid + '_param_1').value + '[/h5audio]', 7, 8, false, sel);
				break;
			case 'btn_h5ysp_mp4':
			    if($(ctrlid + '_param_2').value && $(ctrlid + '_param_3').value) {
					insertText('[h5mp4=' + parseInt($(ctrlid + '_param_2').value) + ',' + parseInt($(ctrlid + '_param_3').value) + ']' + squarestrip($(ctrlid + '_param_1').value) + '[/h5mp4]', 7, 8, false, sel);
				} else {
					insertText('[h5mp4]' + squarestrip($(ctrlid + '_param_1').value) + '[/h5mp4]', 7, 8, false, sel);
				}
				break;
	        default:
				for(i in EXTRAFUNC['showEditorMenu']) {
					EXTRASELECTION= selection;
					try {
						eval('str = ' + EXTRAFUNC['showEditorMenu'][i] + '(\'' + tag + '\', 1)');
					} catch(e) {}
				}
				if(!str) {
					str = '';
					var first = $(ctrlid + '_param_1').value;
					if($(ctrlid + '_param_2')) var second = $(ctrlid + '_param_2').value;
					if($(ctrlid + '_param_3')) var third = $(ctrlid + '_param_3').value;
					if((params == 1 && first) || (params == 2 && first && (haveSel || second)) || (params == 3 && first && second && (haveSel || third))) {
						if(params == 1) {
							str = first;
						} else if(params == 2) {
							str = haveSel ? selection : second;
							opentag = '[' + tag + '=' + first + ']';
						} else {
							str = haveSel ? selection : third;
							opentag = '[' + tag + '=' + first + ',' + second + ']';
						}
						insertText((opentag + str + closetag), strlen(opentag), strlen(closetag), true, sel);
					}
				}
				break;
		 }
		hideMenu('', 'win'); 
		hideMenu();
	};
}