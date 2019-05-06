<?php

class BlogModel extends modelMiddleware
{
    /**
     * 数据表key
     */
    public $tableKey = 'blog';
    public  $cached  = false;
    /**
     * 数据表主键Id名称
     */
    public $pK = 'id';//主键
    
    /**
     * 刷新mem缓存
     * @param  $id
     * @access public
     * @return void
     */
    public function blog_revision($id)
    {
        $sql    = sprintf("select * from %s where `id` = '$id'", $this->getTable($this->tableKey,0) );
        $member = $this->getRow($sql);
	if (empty($member)){
	    $this->revisionKey = array("{all:all}");
	}else{
	    extract($member);
	    //为数据查询key
	    $this->revisionKey = array(
		"{all:all}",
		"{id:$id}",
	    );
	}
	 $this->revision();
    }
}

