<!--Start Form-->
<?php $attributes = array('id' => 'logout_form',
                        'class' => 'form-horizontal'); ?>
<?php echo 'Profissional: ' . $prof . ' ID: ' . $prof_id; ?>
<?php echo form_open('candidatos/logout',$attributes); ?>
        <!--Submit Buttons-->
<?php $data = array("value" => "Logout",
                "name" => "submit",
                "class" => "btn btn-primary"); ?>
<?php echo form_submit($data); ?>
<?php echo form_close(); ?>