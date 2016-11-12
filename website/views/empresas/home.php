<!--Start Form-->
<?php $attributes = array('id' => 'logout_form',
                        'class' => 'form-horizontal'); ?>
<?php echo 'Profissional: ' . $prof . ' ID: ' . $prof_id; ?>
<?php echo form_open('empresas/logout',$attributes); ?>
        <!--Submit Buttons-->
<?php $data = array("value" => "Logout",
                "name" => "submit",
                "class" => "btn btn-primary"); ?>
<?php echo form_submit($data); ?>
<?php echo form_close(); ?>

<!--
<?php echo $profissional->nome; ?> <br>
Currículo <?php echo $profissional->porcentagem_cadastro; ?> % Preenchido<br>
Última Atualização: <?php echo $profissional->data; ?>
-->

<?php echo form_open('empresas/gerenciar_vagas',$attributes); ?>
        <!--Submit Buttons-->
<?php $data = array("value" => "Gerenciar Vagas",
                "name" => "submit",
                "class" => "btn btn-primary"); ?>
<?php echo form_submit($data); ?>
<?php echo form_close(); ?>