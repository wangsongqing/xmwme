<?php require(View.'/layout/admin.header.html');?>
<script type="text/javascript" src="<?php echo Resource;?>js/My97DatePicker/WdatePicker.js"></script>

<main>
    <!-- subnav start -->
    <?php require(View.'/layout/admin.subnav.html');?>
    <!-- subnav end -->

    <section class="content">
        <header class="content-header">用户管理</header>
        <div class="tab-wrap-1">
            <form name="searchForm" action="<?=Root?>index/index/" id="searchFormId" method="post">
                <input type="hidden" name="isSearch" value="1" />
                <ul class="clearfix tbox-sbox-list">
                    <li>
                        <label>用户名：</label>
                        <input type="text" autocomplete="off" name="admin_name" class="tbox30 tbox30-6" />
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
                            <th>用户ID</th>
                            <th>手机号</th>
                            <th>登陆用户名</th>
                            <th>真实姓名</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($result['record'] as $value) {?>
                        <tr>
                            <td><?=$value['admin_id']?></td>
                            <td><?=isset($value['mobile']) ? $value['mobile'] : ''?></td>
                            <td><?=isset($value['admin_name']) ? $value['admin_name'] : ''?></td>
                            <td><?=isset($value['realname'])?$value['realname']:''?></td>
                            <td>
				<a href="<?php echo Root; ?>index/edit/?id=<?=$value['admin_id']?>">编辑</a>
				<a href="<?php echo Root; ?>index/view/?id=<?=$value['admin_id']?>">查看</a>
				<a href="<?php echo Root; ?>index/delete/?id=<?=$value['admin_id']?>">删除</a>
			    </td>
                        </tr>
                        <?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
        <nav class="pager-list-1">
            <?=pageBar($result['pageBar'], 4)?>
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

<?php require(View.'/layout/admin.footer.html');?>
