<?php
if (!defined('IN_DISCUZ'))
{
    exit('2015112921KktVY1229Y');
}
class plugin_localurl
{
    public function viewthread_postbottom_output()
    {
        global $postlist,$threadsortshow;
        
        foreach ($postlist as $id => &$post){
        	
            $post['message'] = $this->url_replace($post['message']);
            
            $post['signature'] = $this->url_replace($post['signature']);
         
        }
        
        foreach ($threadsortshow['optionlist'] as $k => &$v) {    
        	        
            $v['value'] = $this->url_replace($v['value']);
            
        } 
           
        unset($post);
        
        return array();
        
    }

    function url_replace($content)
    {

        $search = "/\<a href\=\"(\w+)?\:\/\/(.+?)\"/ie";
        
        $replace = '$this->iflocal(\'\\2\',\'\\1\')';   
        
        $content = preg_replace($search, $replace, $content);
        
        return $content;
    }

    function iflocal($url,$type='http')
    {       
        global $_G;
        $var = $_G['cache']['plugin']['localurl'];
        
        if(!in_array($_G['fid'],unserialize($var['forums']))||!in_array($_G['groupid'],unserialize($var['groups']))){
        	
        	return "<a href=\"$type://$url\"";
        
        }
        
        $white = $var['white'];
        
        $whites = explode("\r\n", $white);
        
        $siteurl =  trim(str_replace('http://', '', $_G['siteurl']),'/');
        
        $whites[] = $siteurl;
        
        $domain = explode('/', $url);
        
        $domain = $domain[0];
        
        $status = 0;
        
        if($type=='http'||$type=='https'){
        	
	        foreach ($whites as $w){
	        	
	        	if(strstr($w,"*.")) {
	        	
	        		if(strstr($domain,str_replace("*.","",$w))) {
	        			$status =1;
	        			break;
	        		}
	        	}
	        	elseif($domain == $w){
	        		$status = 1;
	        		break;
	        	}
	        
	        }
        }
        
        if($status == 1)
        {
            return "<a href=\"$type://$url\"";
        }
        else{
        	if($var['encode']=='base64')  $url = strtr(base64_encode($type.'://'.$url), '+/=', '-_,');
        	
        	elseif($var['encode']=='urlencode') $url = urlencode($type.'://'.$url);
            
            @include_once('rewrite.php');
            
            if($var['rewrite']==1) $url = rewriteurl($url);
            
            else{
        		if((float)trim($_G['setting']['version'],'Xx')>=3) $url="plugin.php?id=localurl&url=".$url;
            
            	else $url="plugin.php?id=localurl:localurl&url=".$url;
            }
            
			return "<a rel=\"nofollow\" href=\"".$url."\"";
            
            
        }
        
    }
    
}

class plugin_localurl_portal extends plugin_localurl
{
	function view_article_content_output()
	{
		global $content;
		$content['content'] = $this->url_replace($content['content']);
		return array();
	}
}

class plugin_localurl_home extends plugin_localurl
{
	function space_blog_op_extra_output()
	{
		global $blog;
		$blog['message'] = $this->url_replace($blog['message']);
		return array();
	}

	function space_blog_comment_bottom_output()
	{
		global $list;
		foreach ($list as &$post)
		{
			$post['message'] = $this->url_replace($post['message']);
		}
		unset($post);
		return array();
	}
	function space_album_pic_bottom_output()
	{
		$this->space_blog_comment_bottom_output();
	}
	function space_wall_face_extra_output()
	{
		$this->space_blog_comment_bottom_output();
	}

	function follow_top_output()
	{
		global $list;
		foreach ($list['content'] as &$post)
		{
			$post['content'] = $this->url_replace($post['content']);
		}
		unset($post);
		return array();
	}
}
class plugin_localurl_group extends plugin_localurl{
}

class plugin_localurl_forum extends plugin_localurl{	
}


?>