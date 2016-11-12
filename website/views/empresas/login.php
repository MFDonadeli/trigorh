<?php $attributes = array('id' => 'form-login',
                          'class' => 'form-horizontal'); ?>
<?php echo form_open('empresas/login',$attributes); ?>

<?php
    $data = array(
        'type'  => 'hidden',
        'name'  => 'msg',
        'id'    => 'msg'
);

echo form_input($data);
?>

<!--Field: Username-->
<p>
<div id="email_msg"></div>
<?php echo form_label('Usuário:'); ?>
<?php
$data = array(
              'name'        => 'email',
              'placeholder' => 'E-mail', 
              'style'       => 'width:90%',
              'id'          => 'email',
              'value'       => set_value('email')
            );
?>
<?php echo form_input($data); ?>
</p>

<!--Field: Password-->
<p>
<div id="senha_msg"></div>
<?php echo form_label('Senha:'); ?>
<?php
$data = array(
              'name'        => 'senha',
              'placeholder' => 'Senha',
              'style'       => 'width:90%',
              'id'          => 'senha',
              'value'       => set_value('senha')
            );
?>
<?php echo form_password($data); ?>
</p>

<p>
    <!--Submit Buttons-->
    <?php $data = array("content" => "Entrar",
                    "name" => "entrar",
                    "class" => "btn btn-primary",
                    "id" => "entrar"); ?>
    <?php echo form_button($data); ?>
</p>
<?php echo form_close(); ?>

<a href='<?php echo base_url()?>empresas/recupera_senha'>Esqueci minha senha</a>

<script type='text/javascript' src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
<script type='text/javascript'>
    var ret = false;

    $('#entrar').click(function() {
    var form_data = {
        email: $('#email').val(),
        senha: $('#senha').val()   
    };

    $('#email_msg').html("");  
    $('#senha_msg').html("");
    $('#senha_msg').val('');

    var resp = $.ajax({
        url: "<?php echo site_url('empresas/login'); ?>",
        type: 'POST',
        data: form_data,
        global: false,
        async:false,
        success: function(msg) { 
            var obj = $.parseJSON(msg);
            $('#email_msg').html(obj.email);  
            $('#senha_msg').html(obj.senha);   
            $('#msg').val(obj.msg);        
        }
    }).responseText;

    if($('#msg').val() == '')
        $('#form-login').submit();
    return aaa();
    //return false;
});
</script>


