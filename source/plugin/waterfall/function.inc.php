<?php

class waterFallPlugin{
	var $filterArray=array();
	var $orderByArray=array();
	function __construct()
	{
		global $_G;
		if (!isset($_G['cache']['plugin']['waterfall']))
		{
			loadcache('plugin');
		}
		
		if (isset($_G['setting']['recommendthread']['iconlevels']))
		{
			$this->filterArray['1']=array('name'=>lang('plugin/waterfall', 'love'),'sql'=>'recommends>='.$_G['setting']['recommendthread']['iconlevels']['0']);
		}
		if (isset($_G['setting']['heatthread']['iconlevels']))
		{
			$this->filterArray['2']=array('name'=>lang('plugin/waterfall', 'hot'),'sql'=>'heats>='.$_G['setting']['heatthread']['iconlevels']['0']);
		}
		$this->filterArray['3']=array('name'=>lang('plugin/waterfall', 'digest'),'sql'=>'digest in (1,2,3)');
		
		$this->orderByArray['1']=array('name'=>lang('plugin/waterfall','bydateline'),'sql'=>'order by dateline desc');
		$this->orderByArray['2']=array('name'=>lang('plugin/waterfall','bylastpost'),'sql'=>'order by lastpost desc');
		$this->orderByArray['3']=array('name'=>lang('plugin/waterfall','byviews'),'sql'=>'order by views desc');
		$this->orderByArray['4']=array('name'=>lang('plugin/waterfall','byreplies'),'sql'=>'order by replies desc');
		$this->orderByArray['5']=array('name'=>lang('plugin/waterfall','byrand'),'sql'=>'order by rand()');
		
		
	} 
	
	public function getForumIDlistString()
	{
		global $_G;
		$forumIDList=unserialize($_G['cache']['plugin']['waterfall']['forumidlist']);
		$forumIDListString=implode(",",$forumIDList);
		return $forumIDListString;
	}
	public function getWhereString($fidString,$filter)
	{
		global $_G;
		if (empty($fidString)) 
		{
			$fidString=$this->getForumIDlistString();	
		}
		if (empty($fidString)) return '';
		$whereString="where a.fid in (".$fidString.") and a.displayorder>=0";//displayorder>=0为了过滤掉回收站内的主题
		if ($_G['cache']['plugin']['waterfall']['onlypic']) $whereString=$whereString." and a.attachment=2";//有图主题
		if (!empty($filter)&&(array_key_exists($filter,$this->filterArray))) $whereString=$whereString." and ".$this->filterArray[$filter]['sql'];	
		return $whereString;
	}
	
	public function getOrderbyString($orderBy)
	{
		global $_G;
		if (empty($orderBy))
		{
			$orderBy=$_G['cache']['plugin']['waterfall']['defaultorderby'];	
		}
		if (array_key_exists($orderBy,$this->orderByArray)) return $this->orderByArray[$orderBy]['sql'];
		else return "";
	}	
}

?>