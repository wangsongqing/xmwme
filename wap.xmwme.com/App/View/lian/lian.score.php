<div class="outer">
    <div class="inner">
        <div class="hd">
            <?php if(isset($is_floop) && $is_floop==1){ ?>
            <i class="icon icon-1"></i>
            <?php } ?>
            <i class="icon icon-text"></i>
            <span class="text"><?=isset($data['credit'])?$data['credit']:'0'?></span>
        </div>
        <div class="datas">
            <ul>
                <li>
                    <div class="line"></div>
                    <p><?=isset($data['get_credit'])?$data['get_credit']:'0'?></p>红包
                </li>
            </ul>
        </div>
    </div>
</div>
<div class="btns">
    <?php if($is_login>0){?>
        <a href="<?=Root?>my/index/" class="btn-view"></a>
    <?php }else{ ?>
        <a href="<?=Root?>login/index/" class="btn-view"></a>
    <?php } ?>
    <a href="javascript:;" class="btn-replay" id="btn_replay"></a>
</div>
