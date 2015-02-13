<?php
$this->pageTitle = Yii::app()->name . ' - Recover Password';
?>

<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'login-form',
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
    ));
    ?>
    <?php echo $msg;?>
    <?php if ($model->key):?>
    <div class="login_form">
        <div class="row">
            <table border="1">

                <tr>
                    <td colspan="2"><?php echo $this->translate($form->labelEx($model, 'password')); ?>
                        <?php echo $form->textField($model, 'password'); ?></td>
                </tr>
                <tr>
                    <td colspan="2"><?php echo $this->translate($form->labelEx($model, 'verpassword')); ?>
                        <?php echo $form->textField($model, 'verpassword'); ?></td>
                </tr>
                <tr>
                    <td width="300" style="white-space: nowrap">

                    </td>
                    <td align="right" width="100">
                        <?php echo $form->hiddenField($model, 'key'); ?>
                        <?php echo CHtml::submitButton('Edit Email', array('class' => "loginbutton")); ?>
                        
                    </td>
                <tr>
            </table>
        </div>
        <?php endif;?>

    </div>

    <?php $this->endWidget(); ?>
</div><!-- form -->

