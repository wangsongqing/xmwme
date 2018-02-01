<?php require(View . '/layout/admin.header.html'); ?>
<!-- main start -->
<main>
     <!-- subnav start -->
    <?php require(View . '/layout/admin.subnav.html'); ?>
    <!-- subnav end -->
    <section class="content">
        <header class="content-header">修改banner</header>
        <fieldset class="form-list-32 form-list-32-1">
            <form action="/banner/edit/" method="post" id="servicecost" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?=isset($data['id'])?$data['id']:''?>">
                <ul>
                    <li>
                        <h6>活动：</h6>
                        <p>
                            <select name="activity_id" id="activity_id" class="sbox32" >
                                <option value="">请选择</option>
                                <option value="1" <?=isset($data['activity_id'])&&$data['activity_id']==1?'selected':''?>>玩了</option>
                            </select>
                        </p>
                    </li>
                    <li>
                        <h6>状态：</h6>
                        <p>
                            <select name="status" id="status" class="sbox32" >
                                <option value="">请选择</option>
                                <option value="1" <?=isset($data['status'])&&$data['status']==1?'selected':''?>>开启</option>
                                <option value="0" <?=isset($data['status'])&&$data['status']==0?'selected':''?>>关闭</option>
                            </select>
                        </p>
                    </li>
                    <li>
                        <h6>banner名称：</h6>
                        <p><input class="tbox30" type="text" value="<?=isset($data['banner_name'])?$data['banner_name']:''?>" name="banner_name" id="banner_name"></p>
                    </li>
                   
                    <li><h6>url：</h6>
                        <p><input class="tbox30" type="text" value="<?=isset($data['url'])?$data['url']:''?>" name="url" id="url"></p>
                    </li>
                    <li>
                        <h6>上传图片：</h6>
                        <aside>
                            <span class="sc_img01" id="upfilesDiv">
                                <input type="file" id="filename"  name="filename"  class="sc_file01" size="10"/>
                            </span>
                            <span class="imgjl" id="tis">图片支持jpg,png,jpeg格式</span>
                        </aside>
                    </li>
                    <li>
                        <img width="100" style="margin-left: 130px;" src="<?=isset($data['img_url'])?$data['img_url']:''?>">
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


