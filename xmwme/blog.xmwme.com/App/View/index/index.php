<?php include View.'/public/header.php'; ?>
        <!-- main container -->
        <div class="container" style="margin-left: 340px;">
            <?php foreach($data as $value){ ?>
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