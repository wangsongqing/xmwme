<?php foreach ($credit_log['record'] as $v) { ?>
    <li>
        <span class="l"><i class="icon-time"></i><?= date('Y-m-d H:i:s', $v['created']) ?></span>
        <?php if (isset($v['activity_name']) && !empty($v['activity_name'])) { ?>
            <span class="l">，通过<?= $v['activity_name'] ?>获得</span>
        <?php } ?>
        <span class="c-red r">+<?= $v['credit'] ?>积分</span>
    </li>
<?php } ?>
