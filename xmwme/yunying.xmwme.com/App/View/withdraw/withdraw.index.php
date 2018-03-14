<?php require(View . '/layout/admin.header.html'); ?>

<main>
    <!-- subnav start -->
    <?php require(View . '/layout/admin.subnav.html'); ?>
    <!-- subnav end -->

    <section class="content">
        <header class="content-header">提现审核管理</header>
        <div class="tab-wrap-1">
            <form name="searchForm" action="<?= Root ?>orders/index/" id="searchFormId" method="post">
                <input type="hidden" name="isSearch" value="1" />
                <ul class="clearfix tbox-sbox-list">
                    <li>
                        <label>提现用户：</label>
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
                            <th>提现ID</th>
                            <th>用户ID</th>
                            <th>提现订单号</th>
                            <th>提现金额</th>
                            <th>提现状态</th>
                            <th>发起时间</th>
                            <th>到账时间</th>
                            <th>审核人</th>
                            <th>审核时间</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result['record'] as $value) { ?>
                            <tr>
                                <td><?= $value['id'] ?></td>
                                <td><?= isset($value['user_id']) ? $value['user_id'] : '' ?></td>
                                <td><?= isset($value['orders']) ? $value['orders'] : '' ?></td>
                                <td><?= isset($value['money']) ? $value['money'] : '' ?></td>
                                <td><?= isset($value['status']) ? $status[$value['status']] : '' ?></td>
                                <td><?= isset($value['created']) ? date('Y-m-d H:i:s', $value['created']) : '' ?></td>
                                <td><?= isset($value['start_time']) && !empty($value['start_time']) ? date('Y-m-d H:i:s', $value['start_time']) : '' ?></td>
                                <td><?= isset($value['admin_id']) && !empty($value['admin_id']) ? $value['admin_id'] : '' ?></td>
                                <td><?= isset($value['sd_created']) && !empty($value['sd_created']) ? date('Y-m-d H:i:s', $value['sd_created']) : '' ?></td>
                                <td>
                                    <a href="<?php echo Root; ?>withdraw/audit/?id=<?= $value['id'] ?>">审核</a>
                                    <a href="<?php echo Root; ?>withdraw/view/?id=<?= $value['id'] ?>">查看</a>
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


