<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">  
<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>

<form action='<?php echo base_url(); ?>candidatos/curriculo' id='form-dadospessoais' method='post' accept-charset="utf-8">
    <input type="hidden" name="msg_dadospessoais" value="" id="msg_dadospessoais"  />
        
    <div id="nome_msg"></div><label>Nome Completo:</label><input type='text' name='nome' value='' id='nome' placeholder='Nome Completo' /><br>
    <div id="cpf_msg"></div><label>CPF:</label><input type='text' name='cpf' value='<?php echo $cpf; ?>' id='cpf' /><br>
    <div id="celular_msg"></div><label>Celular:</label><input type='text' name='celular' value='' id='celular' /><br>
    <label>Observação:</label><input type='text' name='observacao_celular' value='' id='observacao_celular' /><br>
    <label>Fixo/Recado:</label><input type='text' name='telefone_fixo' value='' id='telefone_fixo' /><br>
    <label>Observação:</label><input type='text' name='observacao_telefone_fixo' value='' id='observacao_telefone_fixo' /><br>
    <div id="data_nascimento_msg"></div><label>Data de Nascimento:</label><input name="data_nascimento" type="text" id="data_nascimento"/><br>
    <div id="sexo_msg"></div><label>Sexo</label>
        <?php foreach($sexo as $sexo_opt): ?>
        <input type='radio' id='sexo' name='sexo' value='<?php echo($sexo_opt->idsexo); ?>'><?php echo($sexo_opt->sexo); ?><br>
        <?php endforeach; ?>
    <div id="estado_civil_msg"></div><label>Estado Civil</label>
        <select name='estado_civil' id='estado_civil'>
            <?php foreach($estado_civil as $estado_civil_opt): ?>
            <option value='<?php echo($estado_civil_opt->idestado_civil); ?>'><?php echo($estado_civil_opt->estado_civil); ?></option>
            <?php endforeach; ?>
        </select>
    <div id="cep_msg"></div><label>CEP:</label><input type='text' name='cep' value='' id='cep' /><br>
    <div id="endereco_msg"></div><label>Endereço:</label><input type='text' name='endereco' value='' id='endereco' /><br>
    <label>Numero:</label><input type='text' name='numero' value='' id='numero' /><br>
    <label>Complemento:</label><input type='text' name='complemento' value='' id='complemento' /><br>
    <label>Bairro:</label><input type='text' name='bairro' value='' id='bairro' /><br>
    <div id="cidade_msg"></div><label>Cidade/Estado:</label>
        <input type="hidden" name="id_cidade" value="" id="id_cidade"  />
        <input type='text' name='cidade' value='' id='cidade' /><br>
    <input type='checkbox' id='pode_viajar' name='pode_viajar' value='1'>Disponibilidade para viajar?<br>
    <input type='checkbox' id='pode_mudar' name='pode_mudar' value='1'>Disponibilidade para mudar de cidade?<br>
    <input type='checkbox' id='deficiente' name='deficiente' value='1'>Portador de deficiência?<br>
    <label>Observacoes:</label>
    <textarea name="observacoes_dadospessoais" cols="28" rows="5" max_length="512" id="observacoes_dadospessoais"></textarea><br>
    <input type="submit" name="submit" value="Salvar" id="submit"  />
</form>

<script type='text/javascript'>
    var ret = false;

    $('#submit').click(function() {
    var form_data = {
        nome: $('#nome').val(),
        cpf: $('#cpf').val(),
        celular: $('#celular').val(),
        observacao_celular: $('#observacao_celular').val(),
        telefone_fixo: $('#telefone_fixo').val(),
        observacao_telefone_fixo: $('#observacao_telefone_fixo').val(),
        data_nascimento: $('#data_nascimento').val(),
        estado_civil: $('#estado_civil').val(),
        sexo: $("input[name='sexo']:checked").val(),
        cep: $('#cep').val(),
        endereco: $('#endereco').val(),
        bairro: $('#bairro').val(),
        cidade: $('#cidade').val(),
        id_cidade: $('#id_cidade').val(),
        numero: $('#numero').val(),
        complemento: $('#complemento').val(),
        pode_viajar: $('#pode_viajar').val(),
        pode_mudar: $('#pode_mudar').val(),
        deficiente: $('#deficiente').val(),
        observacoes_dadospessoais: $('#observacoes_dadospessoais').val()
    };

    $('#nome_msg').html("");  
    $('#cpf_msg').html("");
    $('#celular_msg').html('');
    $('#data_nascimento_msg').html('');
    $('#sexo_msg').html('');
    $('#estado_civil_msg').html('');
    $('#cep_msg').html('');
    $('#endereco_msg').html('');
    $('#cidade_msg').html('');
    $('#msg_dadospessoais').val(''); 

    var resp = $.ajax({
        url: "<?php echo site_url('preenchimentos/dadospessoais'); ?>",
        type: 'POST',
        data: form_data,
        global: false,
        async:false,
        success: function(msg) { 
            var obj = $.parseJSON(msg);
            $('#nome_msg').html(obj.nome);  
            $('#cpf_msg').html(obj.cpf);
            $('#celular_msg').html(obj.celular);
            $('#data_nascimento_msg').html(obj.data_nascimento);
            $('#sexo_msg').html(obj.sexo);
            $('#estado_civil_msg').html(obj.estado_civil);
            $('#cidade_msg').html(obj.cidade); 
            $('#endereco_msg').html(obj.endereco); 
            $('#cep_msg').html(obj.cep);  
            $('#msg_dadospessoais').val(obj.msg);        
        }
    }).responseText;

    alert(resp);

    return false;
});
</script>

<script type="text/javascript" >

        $(document).ready(function() 
        {
            function limpa_formulário_cep() 
            {
                // Limpa valores do formulário de cep.
                $("#endereco").val("");
                $("#bairro").val("");
                $("#cidade").val("");
            }
            
            //Quando o campo cep perde o foco.
            $("#cep").blur(function()
            {
                //Nova variável "cep" somente com dígitos.
                var cep = $(this).val().replace(/\D/g, '');

                //Verifica se campo cep possui valor informado.
                if (cep != "") 
                {

                    //Expressão regular para validar o CEP.
                    var validacep = /^[0-9]{8}$/;

                    //Valida o formato do CEP.
                    if(validacep.test(cep)) 
                    {
                        //Preenche os campos com "..." enquanto consulta webservice.
                        $("#endereco").val("...");
                        $("#bairro").val("...");
                        $("#cidade").val("...");
                        $("#id_cidade").val("...");

                        //Consulta o webservice viacep.com.br/
                        $.getJSON("//viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) 
                        {
                            if (!("erro" in dados)) 
                            {
                                //Atualiza os campos com os valores da consulta.
                                $("#endereco").val(dados.logradouro);
                                $("#bairro").val(dados.bairro);
                                $("#cidade").val(dados.localidade + "-" + dados.uf);
                                $("#id_cidade").val(dados.ibge);
                            } //end if.
                            else 
                            {
                                //CEP pesquisado não foi encontrado.
                                limpa_formulário_cep();
                                alert("CEP não encontrado.");
                            }
                        });
                    } //end if.
                    else 
                    {
                        //cep é inválido.
                        limpa_formulário_cep();
                        alert("Formato de CEP inválido.");
                    }
                } //end if.
                else 
                {
                    //cep sem valor, limpa formulário.
                    limpa_formulário_cep();
                }
            });
        });

    </script>

<form action='<?php echo base_url(); ?>candidatos/curriculo' id='form-historicoacademico' method='post' accept-charset="utf-8">
    <div id="formacao_msg"></div><label>Formação</label>
        <select name='formacao' id='formacao'>
            <?php foreach($formacao as $formacao_opt): ?>
                <option value='<?php echo($formacao_opt->idformacao); ?>'><?php echo($formacao_opt->formacao); ?></option>
            <?php endforeach; ?>
        </select> 
    <div id="curso_msg"></div><label>Curso:</label><input type='text' name='curso' value='' id='curso' /><br>  
    <div id="situacao_msg"></div><label>Situação:</label>
        <input type="radio" name="situacao" id="situacao" value="0">Cursando
        <input type="radio" name="situacao"  id="situacao" value="1">Concluído
        <input type="radio" name="situacao"  id="situacao" value="2">Trancado<br>
    <div id="inicio_msg"></div><label>Início:</label><input type='text' name='inicio' value='' id='inicio' /><br>
    <div id="final_msg"></div><label>Término:</label><input type='text' name='final' value='' id='final' /><br>
    <div id="instituicao_msg"></div><label>Instituição:</label><input type='text' name='instituicao' value='' id='instituicao' /><br>  
    <div id="local_msg"></div><label>Local:</label><input type='text' name='local' value='' id='local' /><br> 
    <input type="submit" name="submit_ha" value="Salvar" id="submit_ha"  />
</form>

<script type='text/javascript'>
    var ret = false;

    $('#submit_ha').click(function() {
    var form_data = {
        formacao: $('#formacao').val(),
        curso: $('#curso').val(),
        situacao: $("input[name='situacao']:checked").val(),
        inicio: $('#inicio').val(),
        final: $('#final').val(),
        instituicao: $('#instituicao').val(),
        local: $('#local').val()
    };

    $('#formacao_msg').html("");  
    $('#curso_msg').html("");
    $('#situacao_msg').html('');
    $('#inicio_msg').html('');
    $('#final_msg').html('');
    $('#instituicao_msg').html('');
    $('#local_msg').html('');

    var resp = $.ajax({
        url: "<?php echo site_url('preenchimentos/historicoacademico'); ?>",
        type: 'POST',
        data: form_data,
        global: false,
        async:false,
        success: function(msg) { 
            var obj = $.parseJSON(msg);
            $('#formacao_msg').html(obj.formacao);  
            $('#curso_msg').html(obj.curso);
            $('#situacao_msg').html(obj.situacao);
            $('#inicio_msg').html(obj.inicio);
            $('#final_msg').html(obj.final);
            $('#instituicao_msg').html(obj.instituicao);
            $('#local_msg').html(obj.local);         
        }
    }).responseText;

    alert(resp);

    return false;
});
</script>

<!-- http://stackoverflow.com/questions/3693560/how-do-i-pass-an-extra-parameter-to-jquery-autocomplete-field -->
<script>
    $("#curso").autocomplete({
        source: function(request, response) {
            $.getJSON("<?php echo base_url(); ?>preenchimentos/busca_curso", { formacao: $('#formacao').val(), curso: $('#curso').val()  }, 
                    response);
        },
        minLength: 3
    });
</script>

<script>
    $("#local").autocomplete({
        source: "<?php echo base_url(); ?>preenchimentos/busca_local/?",
        minLength: 3
    });
</script>

<form action='<?php echo base_url(); ?>candidatos/curriculo' id='form-historicoprofissional' method='post' accept-charset="utf-8">
    <div id="cargo_msg"></div><label>Cargo:</label><input type='text' name='cargo' value='' id='cargo' /><br>    
    <div id="setor_msg"></div><label>Setor:</label>
        <select name='setor' id='setor' />
        <?php foreach($setor as $setor_opt): ?>
            <option value='<?php echo($setor_opt->idsetor); ?>'><?php echo($setor_opt->setor); ?></option>
        <?php endforeach; ?>
        </select>
    <div id="nivel_msg"></div><label>Nível Hierárquico:</label>
        <select name='nivel' id='nivel'>
        <?php foreach($nivel_hierarquico as $nivel_hierarquico_opt): ?>
            <option value='<?php echo($nivel_hierarquico_opt->idnivel_hierarquico); ?>'><?php echo($nivel_hierarquico_opt->nivel_hierarquico); ?></option>
        <?php endforeach; ?>
        </select>   
    <div id="jornada_msg"></div><label>Jornada:</label>
        <select name='jornada' id='jornada'>
        <?php foreach($jornada as $jornada_opt): ?>
            <option value='<?php echo($jornada_opt->idjornada); ?>'><?php echo($jornada_opt->jornada); ?></option>
        <?php endforeach; ?>
        </select> 
    <div id="tipo_contrato_msg"></div><label>Tipo de Contrato:</label>
        <select name='tipo_contrato' id='tipo_contrato'>
        <?php foreach($tipo_contrato as $tipo_contrato_opt): ?>
            <option value='<?php echo($tipo_contrato_opt->idtipo_contrato); ?>'><?php echo($tipo_contrato_opt->tipo_contrato); ?></option>
        <?php endforeach; ?>
        </select> 
    <label>Situação:</label>
        <input type="checkbox" name="situacao_hp" id="situacao_hp" value="0">Atual<br>
    <div id="inicio_msg_hp"></div><label>Início:</label><input type='text' name='inicio_hp' value='' id='inicio_hp' /><br>
    <div id="final_msg_hp"></div><label>Desligamento:</label><input type='text' name='final_hp' value='' id='final_hp' /><br>
    <div id="empresa_msg_hp"></div><label>Empresa:</label><input type='text' name='empresa' value='' id='empresa' /><br>  
    <div id="local_msg_hp"></div><label>Local:</label><input type='text' name='local_hp' value='' id='local_hp' /><br> 
    <div id="salario_msg"></div><label>Último Salário:</label><input type='text' name='salario' value='' id='salario' /><br>
    <input type='checkbox' id='informar_salario' name='informar_salario' value='1'>Informar?<br>
    <div id="atividades_msg"><label>Principais Atividades:</label>
    <textarea name="atividades" cols="28" rows="5" max_length="200" id="atividades"></textarea><br>
    <span style="font-size: 9px;"><span id="chars_extra">200</span> caracteres restantes</span><br>
    <label>Benefícios:</label>
        <?php foreach($beneficio as $beneficio_opt): ?>
            <input type="checkbox" name="beneficio" id="beneficio" value='<?php echo($beneficio_opt->idbeneficio); ?>'><?php echo($beneficio_opt->beneficio); ?><br>
        <?php endforeach; ?>
    <input type="submit" name="submit_hp" value="Salvar" id="submit_hp"  />
</form>

<script type='text/javascript'>
    var ret = false;

    $('#submit_hp').click(function() {
    var form_data = {
        cargo: $('#cargo').val(),
        setor: $('#setor').val(),
        nivel: $('#nivel').val(),
        jornada: $('#jornada').val(),
        tipo_contrato: $('#tipo_contrato').val(),
        situacao: $("input[name='situacao_hp']:checked").val(),
        inicio: $('#inicio_hp').val(),
        final: $('#final_hp').val(),
        empresa: $('#empresa').val(),
        local: $('#local_hp').val(),
        salario: $('#salario').val(),
        informar_salario: $('#informar_salario').val(),
        atividades: $('#atividades').val(),
        beneficio: $("input[name='beneficio']:checked").map(function() { return this.value; }).get()
    };

    var benef = $("input[name='beneficio']:checked").map(function() { return this.value; }).get();
    alert(benef);

    $('#cargo_msg').html("");  
    $('#setor_msg').html("");
    $('#nivel_msg').html("");
    $('#jornada_msg').html("");
    $('#tipo_contrato_msg').html("");
    $('#inicio_hp_msg').html('');
    $('#final_hp_msg').html('');
    $('#empresa_msg_hp').html('');
    $('#local_msg_hp').html('');
    $('#atividades_msg_hp').html('');

    var resp = $.ajax({
        url: "<?php echo site_url('preenchimentos/historicoprofissional'); ?>",
        type: 'POST',
        data: form_data,
        global: false,
        async:false,
        success: function(msg) { 
            var obj = $.parseJSON(msg);
            $('#cargo_msg').html(obj.cargo);  
            $('#setor_msg').html(obj.setor);
            $('#nivel_msg').html(obj.nivel);
            $('#jornada_msg').html(obj.jornada);
            $('#tipo_contrato_msg').html(obj.tipo_contrato);
            $('#inicio_msg').html(obj.inicio);
            $('#final_msg').html(obj.final);
            $('#empresa_msg_hp').html(obj.empresa);
            $('#local_msg_hp').html(obj.local);
            $('#atividades_msg_hp').html(obj.atividades);       
        }
    }).responseText;

    alert(resp);

    return false;
});
</script>

<script>
    $("#local_hp").autocomplete({
        source: "<?php echo base_url(); ?>preenchimentos/busca_local/?",
        minLength: 3
    });
</script>

<form action='<?php echo base_url(); ?>candidatos/curriculo' id='form-conhecimento-idioma' method='post' accept-charset="utf-8">
    <div id="idioma_msg"></div><label>Idioma:</label><input type='text' name='idioma' value='' id='idioma' /><br>  
    <input type="hidden" name="ididioma" value="" id="ididioma"  />
    <div id="nivel_idioma_msg"></div><label>Nível</label>
        <select name='nivel_idioma' id='nivel_idioma'>
            <?php foreach($nivel_idioma as $nivel_idioma_opt): ?>
                <option value='<?php echo($nivel_idioma_opt->idnivel_idioma); ?>'><?php echo($nivel_idioma_opt->nivel_idioma); ?></option>
            <?php endforeach; ?>
        </select>  
    <input type="submit" name="submit_idioma" value="Salvar" id="submit_idioma"  /> 
</form>

<script type='text/javascript'>
    var ret = false;

    $('#submit_idioma').click(function() {
    var form_data = {
        idioma: $('#idioma').val(),
        nivel_idioma: $('#nivel_idioma').val(),
        ididioma: $('#ididioma').val()
    };

    $('#idioma_msg').html("");  

    var resp = $.ajax({
        url: "<?php echo site_url('preenchimentos/idioma'); ?>",
        type: 'POST',
        data: form_data,
        global: false,
        async:false,
        success: function(msg) { 
            var obj = $.parseJSON(msg);
            $('#idioma_msg').html(obj.idioma);        
        }
    }).responseText;

    alert(resp);

    return false;
});
</script>

<script>
     $(function() {
	    $("#idioma").autocomplete({
            source: function(request, response) {
					$.getJSON("<?php echo base_url(); ?>preenchimentos/busca_idioma/? ?>", {
						term: request.term
					}, function(data){
              response($.map(data, function(value,key) {
                return { label:value.label , value: value.value }
              }));
            });
				},
        minLength: 3,
				focus: function(event, ui) {
					// prevent autocomplete from updating the textbox
					event.preventDefault();
					// manually update the textbox
					$(this).val(ui.item.label);
				},
				select: function(event, ui) {
					// prevent autocomplete from updating the textbox
					event.preventDefault();
					// manually update the textbox and hidden field
					$(this).val(ui.item.label);
					$("#ididioma").val(ui.item.value);
				}
			});
		});
</script>

<form action='<?php echo base_url(); ?>candidatos/curriculo' id='form-conhecimento-informatica' method='post' accept-charset="utf-8">
    <div id="informatica_msg"></div><label>Conhecimento:</label><input type='text' name='informatica' value='' id='informatica' /><br>  
    <input type="hidden" name="idinformatica" value="" id="idinformatica"  />
    <div id="tempo_experiencia_msg"></div><label>Tempo de Experiência</label>
        <input type='text' name='qtd_tempo_informatica' value='' id='qtd_tempo_informatica' />
        <select name='tempo_informatica' id='tempo_informatica'>
            <option value='Meses'>Meses</option>
            <option value='Anos'>Anos</option>
        </select> 
    <input type="submit" name="submit_informatica" value="Salvar" id="submit_informatica"  />   
</form>

<script type='text/javascript'>
    var ret = false;

    $('#submit_informatica').click(function() {
    var form_data = {
        informatica: $('#informatica').val(),
        qtd_tempo_informatica: $('#qtd_tempo_informatica').val(),
        tempo_informatica: $('#tempo_informatica').val(),
        idinformatica: $('#idinformatica').val()
    };

    $('#informatica_msg').html(""); 
    $('#tempo_experiencia_msg').html("");  

    var resp = $.ajax({
        url: "<?php echo site_url('preenchimentos/informatica'); ?>",
        type: 'POST',
        data: form_data,
        global: false,
        async:false,
        success: function(msg) { 
            var obj = $.parseJSON(msg);
            $('#informatica_msg').html(obj.informatica); 
            $('#tempo_experiencia_msg').html(obj.tempo_informatica);        
        }
    }).responseText;

    alert(resp);

    return false;
});
</script>

<script>
     $(function() {
	    $("#informatica").autocomplete({
            source: function(request, response) {
					$.getJSON("<?php echo base_url(); ?>preenchimentos/busca_informatica/? ?>", {
						term: request.term
					}, function(data){
              response($.map(data, function(value,key) {
                return { label:value.label , value: value.value }
              }));
            });
				},
        minLength: 3,
				focus: function(event, ui) {
					// prevent autocomplete from updating the textbox
					event.preventDefault();
					// manually update the textbox
					$(this).val(ui.item.label);
				},
				select: function(event, ui) {
					// prevent autocomplete from updating the textbox
					event.preventDefault();
					// manually update the textbox and hidden field
					$(this).val(ui.item.label);
					$("#idinformatica").val(ui.item.value);
				}
			});
		});
</script>

<form action='<?php echo base_url(); ?>candidatos/curriculo' id='form-objetivo' method='post' accept-charset="utf-8">
    <div id="cargo_objetivo_msg"></div><label>Cargo:</label><input type='text' name='cargo_objetivo' value='' id='cargo_objetivo' /><br>    
    <div id="setor_objetivo_msg"></div><label>Setor:</label><label>Setor:</label>
        <select name='setor_objetivo' id='setor_objetivo' />
        <?php foreach($setor as $setor_opt): ?>
            <option value='<?php echo($setor_opt->idsetor); ?>'><?php echo($setor_opt->setor); ?></option>
        <?php endforeach; ?>
        </select>
    <div id="nivel_objetivo_1_msg"></div><label>Nível Hierárquico:</label>
        <select name='nivel_objetivo_1' id='nivel_objetivo_1'>
        <?php foreach($nivel_hierarquico as $nivel_hierarquico_opt): ?>
            <option value='<?php echo($nivel_hierarquico_opt->idnivel_hierarquico); ?>'><?php echo($nivel_hierarquico_opt->nivel_hierarquico); ?></option>
        <?php endforeach; ?>
        </select>  
    <label>Nível Hierárquico:</label>
        <select name='nivel_objetivo_2' id='nivel_objetivo_2'>
        <?php foreach($nivel_hierarquico as $nivel_hierarquico_opt): ?>
            <option value='<?php echo($nivel_hierarquico_opt->idnivel_hierarquico); ?>'><?php echo($nivel_hierarquico_opt->nivel_hierarquico); ?></option>
        <?php endforeach; ?>
        </select>   
    <div id="jornada_objetivo_msg"></div><label>Jornada:</label>
        <select name='jornada_objetivo' id='jornada_objetivo'>
            <?php foreach($jornada as $jornada_opt): ?>
                <option value='<?php echo($jornada_opt->idjornada); ?>'><?php echo($jornada_opt->jornada); ?></option>
            <?php endforeach; ?>
        </select> 
    <div id="tipo_contrato_objetivo_msg"></div><label>Tipo de Contrato:</label>
        <select name='tipo_contrato_objetivo' id='tipo_contrato_objetivo'>
        <?php foreach($tipo_contrato as $tipo_contrato_opt): ?>
            <option value='<?php echo($tipo_contrato_opt->idtipo_contrato); ?>'><?php echo($tipo_contrato_opt->tipo_contrato); ?></option>
        <?php endforeach; ?>
        </select> 
    <div id="salario_msg"></div><label>Faixa Salarial:</label>
        <input type='text' name='salario_menor_objetivo' value='' id='salario_menor_objetivo' /> até <input type='text' name='salario_maior_objetivo' value='' id='salario_maior_objetivo' /><br>
    <input type="checkbox" name="exibe_salario_objetivo" id="exibe_salario_objetivo" value="0">Exibir salário no currículo?<br>
    <input type="submit" name="submit_objetivo" value="Salvar" id="submit_objetivo"  />
</form>

<script type='text/javascript'>
    var ret = false;

    $('#submit_objetivo').click(function() {
    var form_data = {
        cargo: $('#cargo_objetivo').val(),
        setor: $('#setor_objetivo').val(),
        nivel1: $('#nivel_objetivo_1').val(),
        nivel2: $('#nivel_objetivo_2').val(),
        jornada: $('#jornada_objetivo').val(),
        tipo_contrato: $('#tipo_contrato_objetivo').val(),
        salario_menor: $('#salario_menor_objetivo').val(),
        salario_maior: $('#salario_maior_objetivo').val(),
        exibe_salario: $("input[name='exibe_salario_objetivo']:checked").val(),
    };

    $('#cargo_objetivo_msg').html(""); 
    $('#setor_objetivo_msg').html("");
    $('#nivel_objetivo_1_msg').html("");
    $('#jornada_objetivo_msg').html("");
    $('#tipo_contrato_objetivo_msg').html("");
    $('#salario_msg').html("");
      
    var resp = $.ajax({
        url: "<?php echo site_url('preenchimentos/objetivo'); ?>",
        type: 'POST',
        data: form_data,
        global: false,
        async:false,
        success: function(msg) { 
            var obj = $.parseJSON(msg);
            $('#cargo_objetivo_msg').html(obj.cargo); 
            $('#setor_objetivo_msg').html(obj.setor);
            $('#nivel_objetivo_1_msg').html(obj.nivel);
            $('#jornada_objetivo_msg').html(obj.jornada);
            $('#tipo_contrato_objetivo_msg').html(obj.tipo_contrato);
            $('#salario_msg').html(obj.salario);      
        }
    }).responseText;

    alert(resp);

    return false;
});
</script>