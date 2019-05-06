<?php require(View.'/layout/admin.header.html');?>
<main>
    <!-- subnav start -->
    <?php require(View.'/layout/admin.subnav.html');?>
    <!-- subnav end -->

    <section class="content">
        <header class="content-header">查看</header>

        <fieldset class="form-list-32">
            <h3 class="operate-title-1"><i></i>查看</h3>
                <input type="hidden" name="step" value="2" />
                <ul>
                    <li>
                        <h6>真实姓名：</h6>
                        <p><?=$result['realname']?><input id="account" type="hidden" name="account" value="<?=$result['realname']?>"></p>
                    </li>
                    <li>
			<h6>登录名：</h6>
			<p><?=$result['admin_name']?></p>
		    </li>
                    <li>
			<h6>手机号码：</h6>
			<p><?=$result['mobile']?></p>
		    </li>
                </ul>
        </fieldset>

    </section>

</main>
<!-- main end -->

<?require(View.'/layout/admin.footer.html');?>
