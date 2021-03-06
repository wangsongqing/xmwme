<?php require(View . '/layout/admin.header.html'); ?>

<main>
    <!-- subnav start -->
    <?php require(View . '/layout/admin.subnav.html'); ?>
    <!-- subnav end -->

    <section class="content">
        <header class="content-header">博客管理</header>
        <div class="tab-wrap-1">
            <form name="searchForm" action="<?= Root ?>blog/index/" id="searchFormId" method="post">
                <input type="hidden" name="isSearch" value="1" />
                <ul class="clearfix tbox-sbox-list">
                    <li>
                        <label>博客标题：</label>
                        <input type="text" autocomplete="off" name="banner_name" class="tbox30 tbox30-6" />
                    </li>
                    <li>
                        <input class="btn-2" type="submit" value="搜&nbsp;索">
                    </li>
                </ul>
            </form>
            <div class="box">
                <span><a href="/blog/add/" class="btn-4 authapproverealname">新增博客</a> </span>
            </div>
            <div class="box">
                <table class="tab-list-1 tab-list-break">
                    <colgroup>
                        <col style="width:auto">
                        <col style="width:auto">
                        <col style="width:auto">
                        <col style="width:auto">
                        <col style="width:auto">
                        <col style="width:auto">
                        <col style="width:auto">
                        <col style="width:auto">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>标题</th>
                            <th>博客分类</th>
                            <th>博客摘要</th>
                            <th>创建时间</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['record'] as $value) { ?>
                            <tr>
                                <td><?= $value['id'] ?></td>
                                <td><?= isset($value['title']) ? $value['title'] : '' ?></td>
                                <td><?= isset($value['class']) ? $_class[$value['class']] : '' ?></td>
                                <td><?= isset($value['scontent'])? mb_substr($value['scontent'],0,30,'utf-8') : '' ?></td>
                                <td><?= isset($value['created']) ? date('Y-m-d H:i:s', $value['created']) : '' ?></td>
                                <td>
                                    <a href="<?php echo Root; ?>blog/edit/?id=<?= $value['id'] ?>">编辑</a>
                                    <a href="<?php echo Root; ?>blog/delete/?id=<?= $value['id'] ?>">删除</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <nav class="pager-list-1">
            <?= pageBar($result['pageBar'], 4) ?>
            <style>
                .pager-list-1 a, .pager-list-1 i {display: inline-block;}
                .pager-list-1 a{margin: 0 2px;text-align: center; width: 30px;color: #666;}
                .pager-list-1 a.on{background-color: #7faf2a;color: #fff;}
                .pager-list-1 a.updown{width: 43px;}
                .pager-list-1 select {border: 1px solid #ddd;color: #333;font-family: Microsoft YaHei;font-size: 12px;padding-left: 5px;height: 30px;}
            </style>
        </nav>

    </section>

</main>
<!-- main end -->

<?php require(View . '/layout/admin.footer.html'); ?>


