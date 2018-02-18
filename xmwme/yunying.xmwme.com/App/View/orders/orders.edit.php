<?php require(View . '/layout/admin.header.html'); ?>
<!-- main start -->
<main>
     <!-- subnav start -->
    <?php require(View . '/layout/admin.subnav.html'); ?>
    <!-- subnav end -->
    <section class="content">
        <header class="content-header">编辑订单</header>
        <fieldset class="form-list-32 form-list-32-1">
            <form action="/orders/edit/" method="post" id="servicecost" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?=isset($id)?$id:0?>">
                <ul>
                    <li>
                        <h6>用户手机号</h6>
                        <p><input class="tbox30" type="text" disabled="disabled" value="<?=isset($data['phone'])?$data['phone']:''?>"></p>
                    </li>
                    <li>
                        <h6>订单号：</h6>
                        <p><input class="tbox30" type="text" disabled="disabled" value="<?=isset($data['orders_num'])?$data['orders_num']:''?>"></p>
                    </li>
                    <li>
                        <h6>商品名称：</h6>
                        <p><input class="tbox30" type="text" disabled="disabled" value="<?=isset($data['goods_name'])?$data['goods_name']:''?>"></p>
                    </li>
                    <li>
                        <h6>商品类型：</h6>
                        <p><input class="tbox30" type="text" disabled="disabled" value="<?=isset($data['goods_type'])&&$data['goods_type']==1?'虚拟':'实物'?>"></p>
                    </li>
                    <li>
                        <h6>发货状态：</h6>
                        <p>
                            <select name="is_get" id="is_get" class="sbox32" >
                                <option value="1" <?=isset($data['is_get'])&&$data['is_get']==1?'selected':''?>>发货</option>
                                <option value="0" <?=isset($data['is_get'])&&$data['is_get']==0?'selected':''?>>未发货</option>
                            </select>
                        </p>
                    </li>
                    <?php if(isset($data['goods_type']) && $data['goods_type']==2){ ?>
                    <li>
                        <h6>快递单号：</h6>
                        <p><input class="tbox30" type="text" name="number" value="<?=isset($data['number'])?$data['number']:''?>"></p>
                    </li>
                    <?php } ?>
                    <li><h6>备注：</h6><aside><textarea style="width: 400px;height:200px;" id="content" name="content"><?=isset($data['content'])?$data['content']:''?></textarea></aside></li>
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


