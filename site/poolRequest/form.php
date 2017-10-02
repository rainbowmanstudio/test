<?php

/* @var $model \app\models\PoolRequest\Form */

use yii\helpers\Html;
use \kartik\widgets\ActiveForm;
use \kartik\widgets\ActiveField;

$form = ActiveForm::begin([
    'id' => 'login-form',
    'options' => ['class' => 'form-horizontal'],
]);
?>
<?= $form->field($model, 'quantity', ['hintType' => ActiveField::HINT_SPECIAL]) ?>
<?= $form->field($model, 'length', ['hintType' => ActiveField::HINT_SPECIAL]) ?>
<?= $form->field($model, 'onlyLatin')->checkBox() ?>
<?= $form->field($model, 'onlyDigits')->checkBox() ?>
<?= $form->field($model, 'useDigits')->checkBox() ?>
<?= $form->field($model, 'useRegister')->checkBox() ?>
<?= $form->field($model, 'useCaps')->checkBox() ?>
<?= $form->field($model, 'useCyrillic')->checkBox() ?>
<?= $form->field($model, 'onlyCyrillic')->checkBox() ?>
<?= $form->field($model, 'useUniqueCyrillic')->checkBox() ?>
<?= $form->field($model, 'prefix', ['hintType' => ActiveField::HINT_SPECIAL]) ?>
<?= $form->field($model, 'countPrefix')->checkBox() ?>
<?= $form->field($model, 'exclude', ['hintType' => ActiveField::HINT_SPECIAL]) ?>
<?= $form->field($model, 'email', ['hintType' => ActiveField::HINT_SPECIAL]) ?>

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton(Yii::t('poolRequest', 'Get codes'), ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
<?php ActiveForm::end() ?>