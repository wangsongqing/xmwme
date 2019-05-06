<?php require(View.'/layout/admin.header.html');?>
<script src="<?=Resource?>js/user.js"></script>

<main>
    <!-- subnav start -->
    <?php require(View.'/layout/admin.subnav.html');?>
    <!-- subnav end -->

    <section class="content">
        <header class="content-header">上传图片</header>

        <fieldset class="form-list-32">
            <h3 class="operate-title-1"><i></i>修改用户信息</h3>
            <form name="editForm" enctype="multipart/form-data" action="<?=Root?>index/upload/" id="editFormId" method="post" autocomplete="off">
                <input type="file" name='filename'/>
		<input type="hidden" name="isPost" value="1">
		<input type='submit'>
            </form>
        </fieldset>

    </section>

</main>
<!-- main end -->

<?php require(View.'/layout/admin.footer.html');?>
