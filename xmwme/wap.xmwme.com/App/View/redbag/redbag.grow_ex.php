<?php foreach ($credit_log['record'] as $v) { ?>
    <li>
        <span class="l"><i class="icon-time"></i><?= date('Y-m-d H:i:s', $v['created']) ?></span>
        <?php if (isset($v['goods_name']) && !empty($v['goods_name'])) { ?>
            <span class="l">，兑换<?= $v['goods_name'] ?>获得</span>
        <?php } ?>
        <span class="c-red r">-<?= $v['credit'] ?>积分</span>
    </li>
<?php } ?>
