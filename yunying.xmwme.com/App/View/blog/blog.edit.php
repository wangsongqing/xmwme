<?php require(View . '/layout/admin.header.html'); ?>
<script type="text/javascript" charset="utf-8" src="<?=Resource?>UEditor/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="<?=Resource?>UEditor/ueditor.all.min.js"> </script>
    <!--建议手动加在语言，避免在ie下有时因为加载语言失败导致编辑器加载失败-->
    <!--这里加载的语言文件会覆盖你在配置项目里添加的语言类型，比如你在配置项目里配置的是英文，这里加载的中文，那最后就是中文-->
    <script type="text/javascript" charset="utf-8" src="<?=Resource?>UEditor/lang/zh-cn/zh-cn.js"></script>
<!-- main start -->
<main>
     <!-- subnav start -->
    <?php require(View . '/layout/admin.subnav.html'); ?>
    <!-- subnav end -->
    <section class="content">
        <header class="content-header">编辑博客</header>
        <fieldset class="form-list-32 form-list-32-1">
            <form action="/blog/edit/" method="post" id="servicecost" enctype="multipart/form-data">
                <ul>
                    <input type="hidden" name="id" value="<?=isset($id)?$id:0?>">
                    <li>
                        <h6>博客标题：</h6>
                        <p><input class="tbox30" type="text" value="<?=isset($data['title'])?$data['title']:''?>" name="title" id="title"></p>
                    </li>
                     <li>
                        <h6>博客类型：</h6>
                        <p>
                            <select name="class" id="status" class="sbox32" >
                                <option value="">请选择</option>
                                <?php foreach($type as $k=>$v){ ?>
                                <option value="<?=isset($k)?$k:''?>" <?php if(isset($data['class']) && $data['class']==$k){echo 'selected';} ?>><?=isset($v)?$v:''?></option>
                                <?php } ?>
                            </select>
                        </p>
                    </li>
                    <li>
                        <h6>博客摘要：</h6>
                        <textarea style="width:500px;height:170px;" name="scontent"><?=isset($data['scontent'])?$data['scontent']:''?></textarea>
                    </li>
                    <li><h6>博客内容：</h6><aside><textarea id="editor" style="width:800px;height:300px;" name="content"><?=isset($data['content'])?$data['content']:''?></textarea></aside></li>
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
<script>
    var ue = UE.getEditor('editor');
</script>


