<?php
/**
 * 评论model
 */
class CommitModel extends modelMiddleware
{   
    /**
     *数据表key
     * @var type 
     */
    public $tableKey = 'commit';
    
    /**
     *数据表主键
     * @var type 
     */
    public $pK = 'id';
    /**
     *是否调用缓存
     * @var type 
     */
    public $cached = true;
    
    
    /**
     * 刷新mem缓存
     * @param  $id
     * @access public
     * @return void
     */
    public function commit_revision($id)
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
                "{blog_id:$blog_id}",
	    );
	}
	 $this->revision();
    }
}

