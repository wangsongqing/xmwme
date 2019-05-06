<?php require(View.'/layout/admin.header.html');?>
<script src="<?=Resource?>js/user.js"></script>

<main>
    <!-- subnav start -->
    <?php require(View.'/layout/admin.subnav.html');?>
    <!-- subnav end -->

    <section class="content">
        <header class="content-header">添加用户信息</header>

        <fieldset class="form-list-32">
            <h3 class="operate-title-1"><i></i>添加用户信息</h3>
            <form name="editForm" action="<?=Root?>index/add/" id="editFormId" method="post" autocomplete="off">
                <ul>
                    <li>
                        <h6>当前登录用户：</h6>
                        <p><?=$user['admin_name']?><input type="hidden" name="uid" value="<?=$user['admin_id']?>"><input id="account" type="hidden" name="account" value="<?=$user['admin_name']?>"></p>
                    </li>
                    <li><h6>登录名：</h6><aside><input type="text" name="admin_name" class="tbox30 tbox30-6"/></aside></li>
                    <li><h6>手机号：</h6><aside><input name="mobile" maxlength="11" class="tbox30 tbox30-6" type="text"></aside></li>
		    <li><h6>真实名字：</h6><aside><input name="realname" class="tbox30 tbox30-6" type="text" ></aside></li>
                   <li><h6>密码：</h6><aside><input name="password" class="tbox30 tbox30-6" type="text" ></aside></li>
                    <li class="agent-subbtn-wrap mt30px">
                        <h6>&nbsp;</h6><aside><input class="btn-2" type="submit" value="提交"></aside>
                    </li>
                </ul>

            </form>
        </fieldset>

    </section>

</main>
<!-- main end -->

<?require(View.'/layout/admin.footer.html');?>
