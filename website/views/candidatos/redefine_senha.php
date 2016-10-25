<html>
    <body>
        <?php if($no_data) : ?>
            Requisição não encontrada
        <?php else : ?>
        <?php $attributes = array('id' => 'form-login',
                          'class' => 'form-horizontal'); ?>
        <?php echo form_open('candidatos/redefine_senha',$attributes); ?>

        <?php
            $data = array(
                'type'  => 'hidden',
                'name'  => 'msg',
                'id'    => 'msg'
            );

            echo form_input($data);
        ?>

        <?php
            $data = array(
                'type'  => 'hidden',
                'name'  => 'id',
                'id'    => 'id',
                'value' => $id
            );

            echo form_input($data);
        ?>

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
            <?php $data = array("value" => "Confirmar",
                            "name" => "submit",
                            "class" => "btn btn-primary",
                            "id" => "submit"); ?>
            <?php echo form_submit($data); ?>
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

            $('#submit').click(function() {
            var form_data = {
                senha: $('#senha').val(),
                confirma_senha: $('#confirma_senha').val() ,
                id: $('#id').val()
            };

            $('#senha_msg').html("");
            $('#confirma_senha_msg').html("");

            var resp = $.ajax({
                url: "<?php echo site_url('candidatos/valida_redefine_senha'); ?>",
                type: 'POST',
                data: form_data,
                global: false,
                async:false,
                success: function(msg) { 
                    var obj = $.parseJSON(msg);
                    $('#senha_msg').html(obj.senha);
                    $('#confirma_senha_msg').html(obj.confirma_senha);  
                    $('#msg').val(obj.msg);        
                }
            }).responseText;

            if($('#msg').val() == '')
                ret = true;
            return aaa();
            //return false;
        });
        </script>

        <?php endif; ?>   
    </body>
</html>


