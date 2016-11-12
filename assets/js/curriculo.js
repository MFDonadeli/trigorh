var base_url = 'http://localhost/~mfdonadeli/trigo/';

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
            url: base_url + "preenchimentos/dadospessoais",
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

//******* FORMAÇÃO ACADÊMICA
//**** Validação do preenchimenento e Limpeza do Formulário -->

    var ret_fa = false;

    function limpa_formacaoacademica()
    {
        $('#id_historicoacademico').val('');
        $('#formacao').val('-1');
        $('#curso').val('');
        $("input[name=situacao]").prop('checked', false);
        $('#inicio').val('');
        $('#final').val('');
        $('#instituicao').val('');
        $('#local').val('');  
    }

    $('#add_formacaoacademica').click(function(){
        limpa_formacaoacademica();  
    }); 

    $('#submit_ha').click(function() {
    var form_data = {
        id_historicoacademico: $('#id_historicoacademico').val(),
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
        url: base_url + "preenchimentos/historicoacademico",
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

    fill_lista_fa();

    return false;
});

//*****<!-- Preenche lista de Formações Acadêmicas -->
    function fill_lista_fa()
    {
        alert('oi');
        $.ajax({
        url: base_url + "preenchimentos/get_formacoes_academicas",
        type:       'POST',
        cache:      false,
        success: function(html){                    
            $('#lista_formacoes_academicas').html(html);
        }           
      });
    }

    $( document ).ready(function() {
        fill_lista_fa();
    });

//*******<!-- AutoComplete -->
//********<!-- http://stackoverflow.com/questions/3693560/how-do-i-pass-an-extra-parameter-to-jquery-autocomplete-field -->
    $("#curso").autocomplete({
        source: function(request, response) {
            $.getJSON(base_url + "preenchimentos/busca_curso", { formacao: $('#formacao').val(), curso: $('#curso').val()  }, 
                    response);
        },
        minLength: 3
    });

    $("#local").autocomplete({
        source: base_url + "preenchimentos/busca_local/?",
        minLength: 3
    });

//*******<!-- Botoes de Edicao -->
    $(document).on('click', '.edit', function() {
        
        limpa_formacaoacademica(); 

        var form_data = {
            idformacao_academica: this.parentElement.id
        };

        var resp = $.ajax({
        url: base_url + "preenchimentos/get_formacao_academica",
        type: 'POST',
        data: form_data,
        global: false,
        async:false,
        success: function(msg) { 
            var obj = $.parseJSON(msg);
            $('#formacao').val(obj.idformacao);  
            $('#curso').val(obj.curso);
            $("input[name=situacao][value=" + obj.situacao + "]").prop('checked', true);
            $('#inicio').val(obj.inicio);
            $('#final').val(obj.final);
            $('#instituicao').val(obj.nome_instituicao);
            $('#local').val(obj.local); 
            $('#id_historicoacademico').val(obj.idformacao_academica)        
        }
    }).responseText;

    alert(resp);
});

    $(document).on('click', '.erase', function() {
        var form_data = {
            idformacao_academica: this.parentElement.id
        };

        var resp = $.ajax({
        url: base_url + "preenchimentos/apaga_formacao_academica",
        type: 'POST',
        data: form_data,
        global: false,
        async:false,
        success: function(html) { 
            $('#lista_formacoes_academicas').html(html);           
        }
    }).responseText;

    alert(resp);
    fill_lista_fa();
});

//******* HISTÓRICO PROFISSIONAL
//*****<!-- Validação do preenchimenento e Limpeza do Formulário -->

    var ret = false;

    function limpa_historico_profissional()
    {
        $('#id_historicoprofissional').val('');
        $('#cargo').val('');
        $('#setor').val('-1');
        $('#nivel').val('-1');
        $('#jornada').val('-1');
        $('#tipo_contrato').val('-1');
        $("input[name=situacao_hp]").prop('checked', false);
        $('#inicio_hp').val('');
        $('#final_hp').val('');
        $('#empresa').val('');
        $('#local_hp').val('');
        $('#salario').val(''); 
        $("input[name=informar_salario]").prop('checked', false); 
        $('#atividades').val(''); 
        $("input[name=beneficio]").prop('checked', false);  
    }

    $('#add_historico_profissional').click(function(){
        limpa_historico_profissional(); 
    }); 

    $('#submit_hp').click(function() {
    var form_data = {
        id_historicoprofissional: $('#id_historicoprofissional').val(),
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
        informar_salario: $("input[name='informar_salario']:checked").val(),
        atividades: $('#atividades').val(),
        beneficio: $("input[name='beneficio']:checked").map(function() { return this.value; }).get()
    };

    var benef = $("input[name='beneficio']:checked").map(function() { return this.value; }).get();

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
        url: base_url + "preenchimentos/historicoprofissional",
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

    fill_lista_formacao_academica();

    return false;
});

//*****<!-- Autocomplete -->
    $("#local_hp").autocomplete({
        source: base_url + "preenchimentos/busca_local/?",
        minLength: 3
    });

//******<!-- Preenche lista de Histórico Profissional -->

    $( document ).ready(function() {
        fill_lista_formacao_academica();
    });

    function fill_lista_formacao_academica(){
      $.ajax({
        url: base_url + "preenchimentos/get_historicos_profissionais",
        type:       'POST',
        cache:      false,
        success: function(html){                    
            $('#lista_historico_profissional').html(html);
        }           
      });
   }

//***** Botoes de edicao
    $(document).on('click', '.edit_hp', function() {

        limpa_historico_profissional();

        var form_data = {
            id_historicoprofissional: this.parentElement.id
        };

        var resp = $.ajax({
        url: base_url + "preenchimentos/get_historico_profissional",
        type: 'POST',
        data: form_data,
        global: false,
        async:false,
        success: function(msg) { 
            var obj = $.parseJSON(msg);
            $('#id_historicoprofissional').val(obj.idhistorico_profissional);
            $('#cargo').val(obj.funcao);
            $('#setor').val(obj.idsetor);
            $('#nivel').val(obj.idnivel_hierarquico);
            $('#jornada').val(obj.idjornada);
            $('#tipo_contrato').val(obj.idtipo_contrato);
            $("input[name=situacao_hp][value=" + obj.situacao + "]").prop('checked', true);
            $('#inicio_hp').val(obj.inicio);
            $('#final_hp').val(obj.final);
            $('#empresa').val(obj.nome_empresa);
            $('#local_hp').val(obj.local);
            $('#salario').val(obj.ultimo_salario); 
            $("input[name=informar_salario][value=" + obj.informar + "]").prop('checked', true);
            $('#atividades').val(obj.atividades); 
            for (i = 0; i < obj.beneficios.length; i++) { 
                $("input[name=beneficio][value=" + obj.beneficios[i].idbeneficio + "]").prop('checked', true);
            }        
        }
    }).responseText;

    alert(resp);
});

    $(document).on('click', '.erase_hp', function() {
        var form_data = {
            id_historicoprofissional: this.parentElement.id
        };

        var resp = $.ajax({
        url: base_url + "preenchimentos/apaga_historico_profissional",
        type: 'POST',
        data: form_data,
        global: false,
        async:false,
        success: function(html) { 
            $('#lista_historico_profissional').html(html);           
        }
    }).responseText;

    alert(resp);

    fill_lista_formacao_academica();
});

//******* IDIOMA
//****<!-- Validação do preenchimento e limpeza do formulário -->
    var ret = false;

    function limpa_idioma()
    {
        $('#id_idioma').val('');
        $('#idioma').val('');
        $('#ididioma').val('');
        $('#nivel_idioma').val('1'); 
    }

    $(document).on('click', '#add_idioma', function() {
        limpa_idioma();
    }); 

    $('#submit_idioma').click(function() {
        var form_data = {
            id_idioma: $('#id_idioma').val(),
            idioma: $('#idioma').val(),
            nivel_idioma: $('#nivel_idioma').val(),
            ididioma: $('#ididioma').val()
        };

        $('#idioma_msg').html("");  

        var resp = $.ajax({
            url: $('#idioma_action').html(),
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
    fill_lista_idioma();

    return false;
});

//*****<!-- Autocomplete -->
     $(function() {
	    $("#idioma").autocomplete({
            source: function(request, response) {
					$.getJSON($('#idioma_autocomplete').html()+"?", {
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

//*****<!-- Preenche lista de Idiomas -->
    function fill_lista_idioma()
    {
        $.ajax({
        url: $('#idioma_list').html(),
        type:       'POST',
        cache:      false,
        success: function(html){                    
            $('#lista_idioma').html(html);
        }           
      });
    }

    $( document ).ready(function() {
        fill_lista_idioma();
    });

//****<!-- Botoes de edicao -->
    $(document).on('click', '.edit_idioma', function() {

        limpa_idioma();

        var form_data = {
            id_idioma: this.parentElement.id
        };

        var resp = $.ajax({
        url: $('#idioma_edit').html(),
        type: 'POST',
        data: form_data,
        global: false,
        async:false,
        success: function(msg) { 
            var obj = $.parseJSON(msg);
            $('#id_idioma').val(obj.id_idioma);
            $('#idioma').val(obj.conhecimento);
            $('#ididioma').val(obj.idconhecimento);
            $('#nivel_idioma').val(obj.nivel);       
        }
    }).responseText;

    alert(resp);
});

    $(document).on('click', '.erase_idioma', function() {

        var form_data = {
            id_idioma: this.parentElement.id
        };

        var resp = $.ajax({
        url: $('#idioma_erase').html(),
        type: 'POST',
        data: form_data,
        global: false,
        async:false,
        success: function(html) { 
            $('#lista_idioma').html(html);           
        }
    }).responseText;

    alert(resp);

    fill_lista_idioma();
});

//**** INFORMATICA
//****<!-- Validação do preenchimento e limpeza do formulário -->
    var ret = false;

    function limpa_informatica()
    {
        $('#id_informatica').val('');
        $('#informatica').val('');
        $('#idinformatica').val('');
        $('#qtd_tempo_informatica').val(''); 
    }

    $(document).on('click', '#add_informatica', function() {
        limpa_informatica();
    }); 

    $('#submit_informatica').click(function() {
        var form_data = {
            id_informatica: $('#id_informatica').val(),
            informatica: $('#informatica').val(),
            qtd_tempo_informatica: $('#qtd_tempo_informatica').val(),
            tempo_informatica: $('#tempo_informatica').val(),
            idinformatica: $('#idinformatica').val()
        };

        $('#informatica_msg').html(""); 
        $('#tempo_experiencia_msg').html("");  

        var resp = $.ajax({
            url: $('#informatica_action').html(),
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

    fill_lista_informatica();

    return false;
});

//****<!-- Autocomplete -->
     $(function() {
	    $("#informatica").autocomplete({
            source: function(request, response) {
					$.getJSON($('#informatica_autocomplete').html()+"/?", {
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

//****<!-- Preenche lista de Informática -->

    function fill_lista_informatica()
    {
        $.ajax({
        url: $('#informatica_list').html(),
        type:       'POST',
        cache:      false,
        success: function(html){                    
            $('#lista_informatica').html(html);
        }           
      });
    }

    $( document ).ready(function() {
        fill_lista_informatica();
    });

//****<!-- Botoes de edicao -->
    $(document).on('click', '.edit_informatica', function() {

        limpa_informatica();

        var form_data = {
            id_informatica: this.parentElement.id
        };

        var resp = $.ajax({
        url: $('#informatica_edit').html(),
        type: 'POST',
        data: form_data,
        global: false,
        async:false,
        success: function(msg) { 
            var obj = $.parseJSON(msg);
            $('#id_informatica').val(obj.id_informatica);
            $('#informatica').val(obj.conhecimento);
            $('#idinformatica').val(obj.idconhecimento);
            $('#qtd_tempo_informatica').val(obj.tempo_numero); 
            $('#tempo_informatica option[value="' + obj.tempo_grandeza + '"').prop("selected", true);
            //$('#tempo_informatica').val(obj.tempo_grandeza);     
        }
    }).responseText;

    alert(resp);
});

    $(document).on('click', '.erase_informatica', function() {

        var form_data = {
            id_informatica: this.parentElement.id
        };

        var resp = $.ajax({
        url: $('#informatica_erase').html(),
        type: 'POST',
        data: form_data,
        global: false,
        async:false,
        success: function(html) { 
            $('#lista_informatica').html(html);           
        }
    }).responseText;

    alert(resp);

    fill_lista_informatica();
});


//**** OBJETIVO
//****<!-- Validação do Preenchimento e limpeza do formulário -->

    function limpa_objetivo()
    {
        $('#cargo_objetivo').val('');
        $('#setor_objetivo').val('-1');
        $('#nivel_objetivo_1').val('-1');
        $('#nivel_objetivo_2').val('-1');
        $('#jornada_objetivo').val('-1');
        $('#tipo_contrato_objetivo').val('-1');
        $('#salario_menor_objetivo').val('');
        $('#salario_maior_objetivo').val('');
        $("input[name=exibe_salario_objetivo]").prop('checked', false);
    }

    $(document).on('click', '.add_objetivo', function() {
        limpa_objetivo();
    }); 

    $('#submit_objetivo').click(function() {
    var form_data = {
        id_objetivo: $('#id_objetivo').val(),
        cargo: $('#cargo_objetivo').val(),
        setor: $('#setor_objetivo').val(),
        nivel1: $('#nivel_objetivo_1').val(),
        nivel2: $('#nivel_objetivo_2').val(),
        jornada: $('#jornada_objetivo').val(),
        tipo_contrato: $('#tipo_contrato_objetivo').val(),
        salario_menor: $('#salario_menor_objetivo').val(),
        salario_maior: $('#salario_maior_objetivo').val(),
        exibe_salario: $("input[name='exibe_salario_objetivo']:checked").val()
    };

    $('#cargo_objetivo_msg').html(""); 
    $('#setor_objetivo_msg').html("");
    $('#nivel_objetivo_1_msg').html("");
    $('#jornada_objetivo_msg').html("");
    $('#tipo_contrato_objetivo_msg').html("");
    $('#salario_msg').html("");
      
    var resp = $.ajax({
        url: base_url + "preenchimentos/objetivo",
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

    fill_lista_objetivo();

    return false;
});

//****<!-- Preenche lista de Objetivos -->
    $( document ).ready(function() {
        fill_lista_objetivo();    
    });

    function fill_lista_objetivo()
    {
        $.ajax({
        url: base_url + "preenchimentos/get_objetivos",
        type:       'POST',
        cache:      false,
        success: function(html){                    
            $('#lista_objetivo').html(html);
        }           
      });
    }

//****<!-- Botoes de edicao -->
    $(document).on('click', '.edit_objetivo', function() {

        limpa_objetivo();

        var form_data = {
            idobjetivo: this.parentElement.id
        };

        var resp = $.ajax({
        url: base_url + "preenchimentos/get_objetivo",
        type: 'POST',
        data: form_data,
        global: false,
        async:false,
        success: function(msg) { 
            var obj = $.parseJSON(msg);
            $('#id_objetivo').val(obj.idobjetivo_profissional);
            $('#cargo_objetivo').val(obj.funcao);
            $('#setor_objetivo').val(obj.idsetor);
            $('#nivel_objetivo_1').val(obj.idnivel_hierarquico1);
            $('#nivel_objetivo_2').val(obj.idnivel_hierarquico2);
            $('#jornada_objetivo').val(obj.idjornada);
            $('#tipo_contrato_objetivo').val(obj.idtipo_contrato);
            $('#salario_menor_objetivo').val(obj.salario_menor);
            $('#salario_maior_objetivo').val(obj.salario_maior);
            $("input[name=exibe_salario_objetivo][value=" + obj.exibe_salario + "]").prop('checked', true);      
        }
    }).responseText;

    alert(resp);

    fill_lista_objetivo();
});

    $(document).on('click', '.erase_objetivo', function() {

        var form_data = {
            idobjetivo: this.parentElement.id
        };

        var resp = $.ajax({
        url: base_url + "preenchimentos/apaga_objetivo",
        type: 'POST',
        data: form_data,
        global: false,
        async:false,
        success: function(html) { 
            $('#lista_objetivo').html(html);           
        }
    }).responseText;

    alert(resp);

    fill_lista_objetivo();
});