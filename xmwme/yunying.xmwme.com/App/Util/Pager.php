<?php
class Pager
{
	public function getBar($total, $pageSize)
	{
		$result_num = $total_num = array();
		$curPage = isset($_REQUEST['page']) ? (int)$_REQUEST['page'] : 1;
		$curPage = $curPage > 0 ? $curPage : 1;//当前页码

		$pageNum = $total/$pageSize;
		$num_count = is_int($pageNum)?$pageNum:(intval($pageNum)+1);//总页数
		$url = $listStr = $listStr = $result['jump']= '';
		if ($_REQUEST)
		{
			foreach($_REQUEST as $k => $v)
			{
				if ($k == 'page') continue;
				if (isset($_COOKIE[$k]) && $_COOKIE[$k]==$v) continue;
				if (is_array($v)) $v = implode(",",$v);
				$url .= urlencode($k).'='.urlencode($v).'&';
			}
			$url = '?'.$url.'page=';
		}
		else
		{
			$url = '?page=';
		}
		
		if($pageNum > 100) {
			$pageNum = 100;
			$initNum = $curPage;
		}

		for ($i=$initNum; $i < $pageNum + 1; $i++)
		{
			$total_num[$i] = $url.$i;
			$select = $curPage == $i ? ' selected' : '';
			$result['jump'].= "<option value=$i$select>$i</option>";

			if ($i < ($curPage + 4) && $i > ($curPage - 4))
			{
				$result_num[$i] = $url.$i;
				$listStr .= '<a href="'.$result_num[$i].'">'.$i."</a> ";
			}
		}
		$prevPage = $curPage > 1 ? $curPage - 1 : $curPage;
		$nextPage = $curPage < $num_count ? $curPage + 1 : $num_count;
		$pageInfo = array(
			'cur_page'	=> $curPage,
			'num_array'	=> $result_num,
			'total_num'	=> $total_num,
			'num_count'	=> $num_count,
			'prev_page'	=> $url.$prevPage,
			'next_page'	=> $url.$nextPage,
			);
		
		//构造分页html
		$navi = '<a href="'.$pageInfo['prev_page'].'">上一页</a>'.$pageInfo['cur_page']."/".$pageInfo['num_count'].'<a href="'.$pageInfo['next_page'].'">下一页</a> ';
		$curPage - 3 > 1 && $listStr = "...".$listStr;
		$curPage + 3 < $num_count && $listStr .= "...";
		$navi .= $listStr;

		$pageInfo['jump'] = '
						<script language="JavaScript" type="text/JavaScript">
						function page_jump(targ,selObj,restore)
						{
							eval(targ+".location=\''.$url.'"+selObj.options[selObj.selectedIndex].value+"\'");
							if (restore) selObj.selectedIndex=0;
						}
						</script>
						<select name=nump onchange="page_jump(\'this\',this,0)">'.$result['jump'].'</select>
										';
		$pageInfo['navi']   = $navi;
		$pageInfo['numnav'] = $listStr;
		return $pageInfo;
	}
}
?>