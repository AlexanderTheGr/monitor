<?php
$this->pageTitle = Yii::app()->name . ' - Login';
?>

<!-- Form area -->
<div class="admin-form">
    <div class="container-fluid">

        <div class="row-fluid">
            <div class="span12">
                <!-- Widget starts -->
                <div class="widget">
                    <!-- Widget head -->
                    <div class="widget-head">
                        <i class="icon-lock"></i> Login 
                    </div>

                    <div class="widget-content">
                        <div class="padd">
                            <!-- Login form -->
                            <?php
                            $form = $this->beginWidget('CActiveForm', array(
                                'id' => 'login-form',
                                'enableClientValidation' => true,
                                'clientOptions' => array(
                                    'validateOnSubmit' => true,
                                ),
                            ));
                            ?>
                            <!-- Email -->
                            <div class="control-group">
                                <label class="control-label" for="inputEmail">Email</label>
                                <div class="controls">
                                    <?php echo $form->textField($model, 'email'); ?>
                                </div>
                            </div>
                            <!-- Password -->
                            <div class="control-group">
                                <label class="control-label" for="inputPassword">Password</label>
                                <div class="controls">
                                    <?php echo $form->passwordField($model, 'password'); ?>
                                </div>
                            </div>
                            <!-- Remember me checkbox and sign in button -->
                            <div class="control-group">
                                <div class="controls">
                                    <label class="checkbox">
                                        <input type="checkbox"> Remember me
                                    </label>
                                    <br>
                                    <button type="submit" class="btn">Sign in</button>
                                    <button type="reset" class="btn">Reset</button>
                                </div>
                            </div>
                            <?php $this->endWidget(); ?>

                        </div>
                        <div class="widget-foot">
                            <!-- Footer goes here -->
                        </div>
                    </div>
                </div>  
            </div>
        </div>
    </div> 
</div>

