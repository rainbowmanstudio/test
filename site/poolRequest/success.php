<?php

use yii\helpers\Html;

?>
<div class="text-center">
    <h3><?= Yii::t('poolRequest', 'Generated codes sent to mail: {email}', ['email' => $email]);?><h3>

    <div>
        <?= Html::a(Yii::t('poolRequest', 'Generated codes'), $link) ?>
    </div>
</div>