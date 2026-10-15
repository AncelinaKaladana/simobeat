<?php $f = take_flash();
if ($f): ?><div class="alert <?= e($f["type"]) ?>"><?= e($f["message"]) ?></div><?php endif ?>