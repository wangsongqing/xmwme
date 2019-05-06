<?php foreach ($orders['record'] as $v) { ?>
    <li>
        <p>
            <span class="l">
                变现时间<br><time><?= date('Y-m-d H:i:s', $v['created']) ?></time>
            </span>
            <span class="c-red r">
                <?= $v['money'] ?> <br/>
                <time class="r"><?= $status[$v['status']] ?></time>
            </span>
        </p>
    </li>
<?php } ?>