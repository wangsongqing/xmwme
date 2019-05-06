<?php
/**
 +------------------------------------------------------------------------------
 * Run Framework 数据分页
 +------------------------------------------------------------------------------
 * @author  Jimmy Wang <jimmywsq@163.com>
 * @version 1.0
 +------------------------------------------------------------------------------
 */
class Page
{	
	public function get($total, $pageRows = 20, $point = 10, $style = 'on')
	{	    var_dump($point);exit;
		$prior = ceil($point/2) + 1;
		$back  = ceil($point/2);
		$url   = $temp = '';
		$num   = ceil($total/$pageRows);
		$page  = isset($_GET['page']) ?(int)$_GET['page'] : 0;
		if( $page <= 0)   $page = 1;
		if( $page > $num) $page = $num;
		$self = $_SERVER['REQUEST_URI'];
		if(substr($self,-1,1)!='/' && !strpos($self,'page'))
		{
			if (strpos($self,'.html')) {
				$self = substr($self, 0, strpos($self, '.html')+5);
			} else {
				$self = substr($self, 0, strpos($self, '?'));
			}
		}else{
			if (strpos($self,'.html')) {
				$self = substr($self, 0, strpos($self, '.html')+5);
			} else {
				$self='';
			}
		}

		if($_REQUEST)
		{
			foreach($_REQUEST as $k => $v)
			{
				if($k == 'mod' || $k == 'action' || $k == 'page') continue;
				if(isset($_COOKIE[$k]) && $_COOKIE[$k] == $v) continue;
				if(is_array($v)) $v = implode(",",$v);
				$url .= urlencode($k).'='.urlencode($v).'&';
			}
			$url = strpos($self,'.html') ? $self.'?'.$url.'page=' : $self.'./?'.$url.'page=';
		}
		else
		{
			$url = strpos($self,'.html') ? $self.'?'.$url.'page=' : $self.'./?'.$url.'page=';

		}
		$result['current']	  = $page;
		$result['first']	  = ($page>1 ? $url.'1' : 'javascript:;');
		$result['pre']		  = ($page-1>0 ? $url.($page-1) : 'javascript:;');
		$result['next']		  = ($page+1<=$num ? $url.($page+1) : 'javascript:;');
		$result['last']		  = ($num>1 && $page<$num ? $url.$num : 'javascript:;');
		$result['recordNum']  = $total;
		$result['pageNum']    = $num;
		$result['jump']       = '';

		$jumper = $listStr = '';

		if ($page <= $back && ($page + $point-1 <=$num) )
		{
			for($j=1; $j<=$point; $j++)
			{
				$link = $j == $page ? '<a href="javascript:;" class="'.$style.'">'.$j."</a> " : '<a href="'.$url.$j.'">'.$j."</a>";
				$listStr .= $link;
			}
		}
		else
		{
			for($i=1; $i<=$num; $i++)
			{
				if ($i < ($page + $back) && $i > ($page - $prior))
				{
					$point = $i == $page ? '<a href="javascript:;" class="'.$style.'">'.$i."</a>" : '<a href="'.$url.$i.'">'.$i."</a>";
					$listStr .= $point;
				}
			}
		}
		
		$pageNum  = $num;
		$initNum = 1;
		if($num > 100) {
			if($page < 50)  $pageNum = (100 - $page) > $total ? $total - $page : $page + (100 - $page); 
			else $pageNum = ($total - $page) > 50 ? ($page-1) + 50 : $total - $page; 
			$initNum = ($page - 50) > 0 ? $page - 50 : 1;
		}

		for($i=$initNum; $i<=$pageNum; $i++)
		{
			$page == $i ? $select=' selected' : $select='';
			$jumper.= "<option value=$i$select>$i</option>";
		}
		$result['point']= $listStr;
		$result['jump']	= '
						<script language="JavaScript" type="text/JavaScript">
						function page_jump(targ,selObj,restore)
						{
							eval(targ+".location=\''.$url.'"+selObj.options[selObj.selectedIndex].value+"\'");
							if (restore) selObj.selectedIndex=0;
						}
						</script>
						<select name=nump onchange="page_jump(\'this\',this,0)">'.$jumper.'</select>
										';
		return $result;
	}
}
?>