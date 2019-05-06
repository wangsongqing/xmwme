<?php 
$title = '松松的博客 | 技术&amp;思想&amp;生活';
$scontent = 'PHP|Mysql|Linux|Nginx|Memcache|Redis';
?>
<?php include View.'/public/header.php'; ?>
        <!-- main container -->
        <div class="container" style="margin-left: 340px;" id="main_gift_list">
            <?php foreach($data['record'] as $value){ ?>
            <div class="article-list">
                <article class="article">
                    <h1>
                        <a href="" title="<?=$value['title']?>" alt="<?=$value['title']?>"><?=$value['title']?></a>
                    </h1>
                    <div class="content">
                       <?=$value['scontent']?>
                    </div>
                    <div class="article-info">
                        <i class="fa fa-calendar"></i> <?=date('Y-m-d',$value['created'])?> &nbsp; <i class="fa fa-map-marker"></i> 
                        <a href="" rel="category tag"><?=$class[$value['class']]?></a>                
                    </div>
                    <div class="readmore">
                        <a href="<?=Root?>index/detail/?id=<?=$value['id']?>" title="<?=$value['title']?>" alt="<?=$value['title']?>">+ 阅读全文</a>
                    </div>            
                </article>
            </div>
            <?php } ?>
<!--            <div class="article-list">
                <article class="article">
                    <h1>
                        <a href="" title="如何利用showdoc自动生成数据字典" alt="如何利用showdoc自动生成数据字典">如何利用showdoc自动生成数据字典</a>
                    </h1>
                    <div class="content">
                        <p>好的数据字典文档能够清晰地反映出数据库的结构以及相关释义，方便技术人员查阅。我们很容易使用showdoc来生成展示数据库结构的字典文档，并且能根据数据库结构的变动来自动修改文档，省去人工编辑的麻烦。</p>
                    </div>
                    <div class="article-info">
                        <i class="fa fa-calendar"></i> 2018-04-21 &nbsp; <i class="fa fa-map-marker"></i> 
                        <a href="" rel="category tag">开源资源</a>,<a href="" rel="category tag">编程技术</a>                
                    </div>
                    <div class="readmore">
                        <a href="" title="如何利用showdoc自动生成数据字典" alt="如何利用showdoc自动生成数据字典">+ 阅读全文</a>
                    </div>            
                </article>
            </div>-->
        <div class="clear"></div>
    </div>
    <?php include View.'/public/footer.php'; ?>
<script>  
    var Page = 2;
    var range = 10; //距下边界长度/单位px
    var totalheight = 0;
    var canRequest = true;
   
    var _date = '<?=$_date?>';
    main =  $("#main_gift_list"); //主体元素
    var str = '<div class="loading-more"><i></i>正在加载…</div>';
    $(window).scroll(function(event){//滚动加载数据  
           var wScrollY = window.scrollY; // 当前滚动条位置    
           var wInnerH = window.innerHeight; // 设备窗口的高度（不会变）    
           var bScrollH = document.body.scrollHeight; // 滚动条总高度        
           if (wScrollY + wInnerH >= bScrollH) {  
                $('.loading-more').remove();
                main.append(str);
                canRequest = false;
                 $.ajax({
                    type: "get",
                    url : "/index/blogdate/",
                    async:false,
                    dataType:'json',
                    data: 'ajax=1&page=' + Page + '&date=' + _date,
                    success: function(result){
                      $('.loading-more').remove();
                        canRequest = true;
                        if(result.err == '1' && result.data!="") {//查询结果有数据
                          main.append(result.data);
                          Page++;
                        }else if(result.err == '0' && result.data ==""){//查询结果无数据
                            canRequest = false;
                        }
                    }
                });
            }
    });  
</script>