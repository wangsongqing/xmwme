<?php require(View . '/layout/admin.header.html'); ?>
<!-- main start -->
<main>
     <!-- subnav start -->
    <?php require(View . '/layout/admin.subnav.html'); ?>
    <!-- subnav end -->
    <section class="content">
        <header class="content-header">编辑商品</header>
        <fieldset class="form-list-32 form-list-32-1">
            <form action="/goods/edit/" method="post" id="servicecost" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?=isset($id)?$id:0?>">
                <ul>
                    <li>
                        <h6>商品名称：</h6>
                        <p><input class="tbox30" type="text" value="<?=isset($data['goods_name'])?$data['goods_name']:''?>" name="goods_name" id="goods_name"></p>
                    </li>
                    <li>
                        <h6>商品库存：</h6>
                        <p><input class="tbox30" type="text" value="<?=isset($data['store'])?$data['store']:''?>" name="store" id="store"></p>
                    </li>
                    <li>
                        <h6>状态：</h6>
                        <p>
                            <select name="status" id="status" class="sbox32" >
                                <option value="1" <?=isset($data['status'])&&$data['status']==1?'selected':''?>>上架</option>
                                <option value="0" <?=isset($data['status'])&&$data['status']==0?'selected':''?>>下架</option>
                            </select>
                        </p>
                    </li>
                     <li>
                        <h6>商品类型：</h6>
                        <p>
                            <select name="goods_type" id="goods_type" class="sbox32" >
                                <option value="1" <?=isset($data['goods_type'])&&$data['goods_type']==1?'selected':''?>>虚拟</option>
                                <option value="2" <?=isset($data['goods_type'])&&$data['goods_type']==2?'selected':''?>>实物</option>
                            </select>
                        </p>
                    </li>
                    <li>
                        <h6>所需积分：</h6>
                        <p><input class="tbox30" type="text" value="<?=isset($data['credit'])?$data['credit']:''?>" name="credit" id="credit"></p>
                    </li>
                    <li>
                        <h6>商品图片：</h6>
                        <aside>
                            <span class="sc_img01" id="upfilesDiv">
                                <input type="file" id="filename"  name="filename"  class="sc_file01" size="10"/>
                            </span>
                            <span class="imgjl" id="tis">图片支持jpg,png,jpeg格式</span>
                        </aside>
                    </li>
                    <li>
                        <img width="100" style="margin-left: 130px;" src="<?=isset($data['list_pic'])?$data['list_pic']:''?>">
                    </li>
                    <li><h6>商品详情：</h6><aside><textarea id="content" name="content"><?=isset($data['content'])?$data['content']:''?></textarea></aside></li>
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


