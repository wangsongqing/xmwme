<?php require(View . '/layout/admin.header.html'); ?>

<main>
    <!-- subnav start -->
    <?php require(View . '/layout/admin.subnav.html'); ?>
    <!-- subnav end -->

    <section class="content">
        <header class="content-header">订单管理</header>
        <div class="tab-wrap-1">
            <form name="searchForm" action="<?= Root ?>orders/index/" id="searchFormId" method="post">
                <input type="hidden" name="isSearch" value="1" />
                <ul class="clearfix tbox-sbox-list">
                    <li>
                        <label>订单号：</label>
                        <input type="text" autocomplete="off" name="orders_num" class="tbox30 tbox30-6" />
                    </li>
                    <li>
                        <input class="btn-2" type="submit" value="搜&nbsp;索">
                    </li>
                </ul>
            </form>
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
                            <th>用户ID</th>
                            <th>订单号</th>
                            <th>发货状态</th>
                            <th>商品名称</th>
                            <th>快递单号</th>
                            <th>商品类型</th>
                            <th>备注</th>
                            <th>创建时间</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result['record'] as $value) { ?>
                            <tr>
                                <td><?= $value['id'] ?></td>
                                <td><?= isset($value['user_id']) ? $value['user_id'] : '' ?></td>
                                <td><?= isset($value['orders_num']) ? $value['orders_num'] : '' ?></td>
                                <td><?= isset($value['is_get']) && $value['is_get'] == 1 ? '已发货' : '未发货' ?></td>
                                <td><?= isset($value['goods_name']) ? $value['goods_name'] : '' ?></td>
                                <td><?= isset($value['number']) ? $value['number'] : '' ?></td>
                                <td><?= isset($value['goods_type']) && $value['goods_type']=='1' ? '虚拟' : '实物' ?></td>
                                <td><?= isset($value['content']) ? $value['content'] : '' ?></td>
                                <td><?= isset($value['created']) ? date('Y-m-d H:i:s', $value['created']) : '' ?></td>
                                <td>
                                    <a href="<?php echo Root; ?>orders/edit/?id=<?= $value['id'] ?>">编辑</a>
                                    <a href="<?php echo Root; ?>orders/view/?id=<?= $value['id'] ?>">查看</a>
                                    <a href="<?php echo Root; ?>orders/delete/?id=<?= $value['id'] ?>">删除</a>
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


