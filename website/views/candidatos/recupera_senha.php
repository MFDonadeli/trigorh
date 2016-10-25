<html>
    <body>
        Digite e-mail e cpf. Enviaremos um link para redefinição de senha para o endereço de email cadastrado
        <?php $attributes = array('id' => 'form-login',
                          'class' => 'form-horizontal'); ?>
        <?php echo form_open('candidatos/recupera_senha',$attributes); ?>

        <?php
            $data = array(
                'type'  => 'hidden',
                'name'  => 'msg',
                'id'    => 'msg'
            );

            echo form_input($data);
        ?>

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
                email: $('#email').val(),
                cpf: $('#cpf').val(),
            };

            $('#email_msg').html(""); 
            $('#cpf_msg').html("");
            
            var resp = $.ajax({
                url: "<?php echo site_url('candidatos/valida_recupera_senha'); ?>",
                type: 'POST',
                data: form_data,
                global: false,
                async:false,
                success: function(msg) { 
                    var obj = $.parseJSON(msg);
                    $('#email_msg').html(obj.email);
                    $('#cpf_msg').html(obj.cpf);  
                    $('#msg').val(obj.msg);        
                }
            }).responseText;

            if($('#msg').val() == '')
                ret = true;
            return aaa();
            //return false;
        });
        </script>

        </script>    
    </body>
</html>


