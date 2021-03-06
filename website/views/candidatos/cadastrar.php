<html>
    <body>
        <?php $attributes = array('id' => 'form-cadastrar-profissional',
                          'class' => 'form-horizontal'); ?>
        <?php echo form_open('candidatos/curriculo',$attributes); ?>

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
        <div id="nome_msg"></div>
        <?php echo form_label('Nome Completo:'); ?>
        <?php
        $data = array(
                    'name'        => 'nome',
                    'placeholder' => 'Nome Completo', 
                    'style'       => 'width:90%',
                    'id'          => 'nome',
                    'value'       => set_value('nome')
                    );
        ?>
        <?php echo form_input($data); ?>
        </p>

        <!--Field: Email-->
        <p>
        <div id="email_msg"></div>
        <?php echo form_label('E-mail:'); ?>
        <?php
        $data = array(
                    'name'        => 'email',
                    'placeholder' => 'Endereço de e-mail', 
                    'style'       => 'width:90%',
                    'id'          => 'email',
                    'value'       => set_value('email')
                    );
        ?>
        <?php echo form_input($data); ?>
        </p>

        <!--Field: CPF-->
        <p>
        <div id="cpf_msg"></div>
        <?php echo form_label('CPF:'); ?>
        <?php
        $data = array(
                    'name'        => 'cpf',
                    'placeholder' => 'CPF', 
                    'style'       => 'width:90%',
                    'id'          => 'cpf',
                    'value'       => set_value('cpf')
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

        <!--Field: Confirma Password-->
        <p>
        <div id="confirma_senha_msg"></div>
        <?php echo form_label('Confirme a senha:'); ?>
        <?php
        $data = array(
                    'name'        => 'confirma_senha',
                    'placeholder' => 'Confirme sua senha',
                    'style'       => 'width:90%',
                    'id'          => 'confirma_senha',
                    'value'       => set_value('confirma_senha')
                    );
        ?>
        <?php echo form_password($data); ?>
        </p>

        <p>
            <!--Submit Buttons-->
            <?php $data = array("content" => "Confirmar",
                            "name" => "confirmar",
                            "class" => "btn btn-primary",
                            "id" => "confirmar"); ?>
            <?php echo form_button($data); ?>
        </p>
        <?php echo form_close(); ?>

        <script type='text/javascript' src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
        <script type='text/javascript'>
            var ret = false;

            function aaa()
            {
                if(ret)
                    return true;
                else
                    return false;
            }

            $('#confirmar').click(function() {
            var form_data = {
                nome: $('#nome').val(),
                email: $('#email').val(),
                cpf: $('#cpf').val(),
                senha: $('#senha').val(),
                confirma_senha: $('#confirma_senha').val()   
            };

            $('#nome_msg').html(""); 
            $('#email_msg').html(""); 
            $('#cpf_msg').html("");
            $('#senha_msg').html("");
            $('#confirma_senha_msg').html("");

            var resp = $.ajax({
                url: "<?php echo site_url('candidatos/valida_cadastrar'); ?>",
                type: 'POST',
                data: form_data,
                global: false,
                async:false,
                success: function(msg) { 
                    var obj = $.parseJSON(msg);
                    $('#nome_msg').html(obj.nome);  
                    $('#email_msg').html(obj.email);
                    $('#cpf_msg').html(obj.cpf);  
                    $('#senha_msg').html(obj.senha);
                    $('#confirma_senha_msg').html(obj.confirma_senha);  
                    $('#msg').val(obj.msg);        
                }
            }).responseText;

            if($('#msg').val() == '')
                $('#form-cadastrar-profissional').submit();
        });
        </script>

        </script>    
    </body>
</html>


