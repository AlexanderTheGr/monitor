<?php
$this->pageTitle = Yii::app()->name . ' - Send Recover Password';
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
    <?php if (!$msg):?>
    <div class="login_form">
        <div class="row">
            <table border="1">
                <tr>
                    <td colspan="2"><?php echo $this->translate($form->labelEx($model, 'email')); ?>
                        <?php echo $form->textField($model, 'email'); ?></td>
                </tr>
                <tr>
                    <td width="300" style="white-space: nowrap">

                    </td>
                    <td align="right" width="100">
                        <?php echo CHtml::submitButton('Send Email', array('class' => "loginbutton")); ?>
                        
                    </td>
                <tr>
            </table>
        </div>

    </div>
    <?php endif;?>

    <?php $this->endWidget(); ?>
</div><!-- form -->

