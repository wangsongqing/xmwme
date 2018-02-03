<?php require(View . '/layout/admin.header.html'); ?>
<!-- main start -->
<main>
     <!-- subnav start -->
    <?php require(View . '/layout/admin.subnav.html'); ?>
    <!-- subnav end -->
    <section class="content">
        <header class="content-header">编辑活动</header>
        <fieldset class="form-list-32 form-list-32-1">
            <form action="/activity/edit/" method="post" id="servicecost" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?=isset($data['id'])?$data['id']:''?>">
                <ul>
                    <li>
                        <h6>活动唯一key：</h6>
                        <p><input class="tbox30" readonly="readonly" type="text" value="<?=isset($data['key'])?$data['key']:''?>" name="key" id="key"></p>
                    </li>
                    <li>
                        <h6>活动：</h6>
                        <p><input class="tbox30" type="text" value="<?=isset($data['activity_name'])?$data['activity_name']:''?>" name="activity_name" id="activity_name"></p>
                    </li>
                    <li>
                        <h6>开始时间</h6>
                        <p><input class="tbox30" type="text" value="<?=isset($data['start_time'])?date('Y-m-d',$data['start_time']):''?>" name="start_time" id="start_time"></p>
                    </li>
                    <li>
                        <h6>结束时间</h6>
                        <p><input class="tbox30" type="text" value="<?=isset($data['end_time'])?date('Y-m-d',$data['end_time']):''?>" name="end_time" id="end_time"></p>
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
                   
                    <li><h6>活动链接：</h6>
                        <p><input class="tbox30" type="text" value="<?=isset($data['url'])?$data['url']:''?>" name="url" id="url"></p>
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


