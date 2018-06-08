<?php foreach ($data['record'] as $value) { ?>
    <div class="article-list">
        <article class="article">
            <h1>
                <a href="" title="<?= $value['title'] ?>" alt="<?= $value['title'] ?>"><?= $value['title'] ?></a>
            </h1>
            <div class="content">
                <?= $value['scontent'] ?>
            </div>
            <div class="article-info">
                <i class="fa fa-calendar"></i> <?= date('Y-m-d', $value['created']) ?> &nbsp; <i class="fa fa-map-marker"></i> 
                <a href="" rel="category tag"><?= $class[$value['class']] ?></a>                
            </div>
            <div class="readmore">
                <a href="<?= Root ?>index/detail/?id=<?= $value['id'] ?>" title="<?= $value['title'] ?>" alt="<?= $value['title'] ?>">+ 阅读全文</a>
            </div>            
        </article>
    </div>
<?php } ?>