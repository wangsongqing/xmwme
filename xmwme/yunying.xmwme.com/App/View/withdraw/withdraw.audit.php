<?php require(View . '/layout/admin.header.html'); ?>
<!-- main start -->
<main>
     <!-- subnav start -->
    <?php require(View . '/layout/admin.subnav.html'); ?>
    <!-- subnav end -->
    <section class="content">
        <header class="content-header">审核提现</header>
        <fieldset class="form-list-32 form-list-32-1">
            <form action="/withdraw/audit/" method="post" id="servicecost" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?=isset($id)?$id:0?>">
                <ul>
                    <li>
                        <h6>提现订单号</h6>
                        <p><input class="tbox30" type="text" disabled="disabled" value="<?=isset($data['orders'])?$data['orders']:''?>"></p>
                    </li>
                    <li>
                        <h6>提现金额：</h6>
                        <p><input class="tbox30" type="text" disabled="disabled" value="<?=isset($data['money'])?$data['money']:''?>"></p>
                    </li>
                    <li>
                        <h6>发起时间：</h6>
                        <p><input class="tbox30" type="text" disabled="disabled" value="<?=isset($data['created'])?date('Y-m-d H:i:s',$data['created']):''?>"></p>
                    </li>
                    <li>
                        <h6>到账时间：</h6>
                        <p><?=isset($data['start_time'])&&!empty($data['start_time'])?date('Y-m-d H:i:s',$data['start_time']):''?></p>
                    </li>
                    <li>
                        <h6>当前状态：</h6>
                        <p><?=isset($data['status'])?$status[$data['status']]:''?></p>
                    </li>
                    <li>
                        <h6>提现状态：</h6>
                        <p>
                            <?php if(isset($data['status'])&&$data['status']==2){ ?>
                                 <select name="status" id="status" class="sbox32" >
                                    <option value="4">提现到账</option>
                                </select>
                            <?php }else{ ?>
                                <?php if(isset($data['status']) && $data['status']==4){ ?>
                                已经到账
                                <?php }else{ ?>
                                <select name="status" id="status" class="sbox32" >
                                    <option value="2" <?=isset($data['status'])&&$data['status']==2?'selected':''?>>审核通过</option>
                                    <option value="3" <?=isset($data['status'])&&$data['status']==3?'selected':''?>>审核不通过</option>
                                </select>
                                <?php } ?>
                            <?php } ?>
                        </p>
                    </li>
                    <li><h6>备注：</h6><aside><textarea style="width: 400px;height:200px;" id="content" name="content"><?=isset($data['content'])?$data['content']:''?></textarea></aside></li>
                    <?php if(isset($data['status']) && $data['status']!=4){ ?>
                    <li><h6>&nbsp;</h6><input class="btn-1" type="submit" value="提交"></li>
                    <?php } ?>
                </ul>
            </form>
        </fieldset>
    </section>
</main>
<!-- main end -->

<!--footer 开始-->
<?php require(View . '/layout/admin.footer.html'); ?>
<!-- footer end -->


