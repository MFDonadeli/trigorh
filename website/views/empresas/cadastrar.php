<html>
    <body>
        <?php $attributes = array('id' => 'form-cadastrar-empresa',
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
        <?php echo form_label('Nome da Empresa:'); ?>
        <?php
        $data = array(
                    'name'        => 'nome',
                    'placeholder' => 'Nome da Empresa', 
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

        <!--Field: Razão Social da Empresa-->
        <p>
        <div id="razao_social_msg"></div>
        <?php echo form_label('Razão Social:'); ?>
        <?php
        $data = array(
                    'name'        => 'razao_social',
                    'placeholder' => 'Razão Social', 
                    'style'       => 'width:90%',
                    'id'          => 'razao_social',
                    'value'       => set_value('razao_social')
                    );
        ?>
        <?php echo form_input($data); ?>
        </p>

        <!--Field: CNPJ-->
        <p>
        <div id="cnpj_msg"></div>
        <?php echo form_label('CNPJ:'); ?>
        <?php
        $data = array(
                    'name'        => 'cnpj',
                    'placeholder' => 'CNPJ', 
                    'style'       => 'width:90%',
                    'id'          => 'cnpj',
                    'value'       => set_value('cnpj')
                    );
        ?>
        <?php echo form_input($data); ?>
        </p>

        <p>
        <div id="setor_msg"></div><label>Setor Principal da Empresa:</label>
        <select name='setor' id='setor' />
        <?php foreach($setor as $setor_opt): ?>
            <option value='<?php echo($setor_opt->idsetor); ?>'><?php echo($setor_opt->setor); ?></option>
        <?php endforeach; ?>
        </select>
        </p>


        <div id="nome_responsavel_msg"></div><label>Nome do Responsável:</label><input type='text' name='nome_responsavel' value='' id='nome_responsavel' placeholder='Nome do Responsável' /><br>
        <div id="telefone_msg"></div><label>Telefone de Contato:</label><input type='text' name='telefone' value='' id='telefone' /><br>
        <label>Observação:</label><input type='text' name='observacao_telefone' value='' id='observacao_telefone_fixo' /><br>
    

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
                razao_social: $('#razao_social').val(),
                cnpj: $('#cnpj').val(),
                setor: $('#setor').val(),
                nome: $('#nome').val(),
                telefone: $('#telefone').val(),
                observacao_telefone: $('#observacao_telefone').val,
                senha: $('#senha').val(),
                confirma_senha: $('#confirma_senha').val()   
            };

            $('#nome_msg').html(""); 
            $('#email_msg').html(""); 
            $('#razao_social_msg').html(""); 
            $('#cnpj_msg').html("");
            $('#setor_msg').html("");
            $('#nome_responsavel_msg').html("");
            $('#telefone_msg').html("");
            $('#senha_msg').html("");
            $('#confirma_senha_msg').html("");

            var resp = $.ajax({
                url: "<?php echo site_url('empresas/valida_cadastrar'); ?>",
                type: 'POST',
                data: form_data,
                global: false,
                async:false,
                success: function(msg) { 
                    var obj = $.parseJSON(msg);
                    $('#nome_msg').html(obj.nome);  
                    $('#email_msg').html(obj.email);
                    $('#razao_social_msg').html(obj.razao_social);
                    $('#cnpj_msg').html(obj.cnpj);  
                    $('#setor_msg').html(obj.setor);
                    $('#nome_responsavel_msg').html(obj.nome_responsavel);
                    $('#telefone_msg').html(obj.telefone);
                    $('#senha_msg').html(obj.senha);
                    $('#confirma_senha_msg').html(obj.confirma_senha);  
                    $('#msg').val(obj.msg);        
                }
            }).responseText;

            alert(resp);

            if($('#msg').val() == '')
                $('#form-cadastrar-empresa').submit();
        });
        </script>

        </script>    
    </body>
</html>


