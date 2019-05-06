<?php require(View . '/layout/admin.header.html'); ?>
<!-- main start -->
<main>
     <!-- subnav start -->
    <?php require(View . '/layout/admin.subnav.html'); ?>
    <!-- subnav end -->
    <section class="content">
        <header class="content-header">编辑活动</header>
        <fieldset class="form-list-32 form-list-32-1">
            <form action="/activity/add/" method="post" id="servicecost" enctype="multipart/form-data">
                <ul>
                    <li>
                        <h6>活动唯一key：</h6>
                        <p><input class="tbox30" type="text" name="akey" id="akey"></p>
                    </li>
                    <li>
                        <h6>活动名称：</h6>
                        <p><input class="tbox30" type="text" name="activity_name" id="activity_name"></p>
                    </li>
                    <li>
                        <h6>开始时间</h6>
                        <p><input class="tbox30" type="text"  name="start_time" id="start_time"></p>
                    </li>
                    <li>
                        <h6>结束时间</h6>
                        <p><input class="tbox30" type="text"  name="end_time" id="end_time"></p>
                    </li>
                    <li>
                        <h6>状态：</h6>
                        <p>
                            <select name="status" id="status" class="sbox32" >
                                <option value="">请选择</option>
                                <option value="1">开启</option>
                                <option value="0">关闭</option>
                            </select>
                        </p>
                    </li>
                   
                    <li><h6>活动链接：</h6>
                        <p><input class="tbox30" type="text" value="" name="url" id="url"></p>
                    </li>
                    <li>
                        <h6>活动图片：</h6>
                        <aside>
                            <span class="sc_img01" id="upfilesDiv">
                                <input type="file" id="filename"  name="filename"  class="sc_file01" size="10"/>
                            </span>
                            <span class="imgjl" id="tis">图片支持jpg,png,jpeg格式</span>
                        </aside>
                    </li>
<!--                    <li><h6>描述：</h6><aside><textarea id="remark" name="remark"></textarea></aside></li>-->
                    <li><h6>&nbsp;</h6><input class="btn-1" type="submit" value="提交"></li>
                </ul>
            </form>
        </fieldset>
    </section>
</main>
<!-- main end -->

<!--footer 开始-->
<?php require(View . '/layout/admin.footer.html'); ?>
<!-- footer end -->


