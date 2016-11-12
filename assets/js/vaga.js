var base_url = 'http://localhost/~mfdonadeli/trigo/';

$('#submit_vaga').click(function() {
        var form_data = {
            vaga: $('#vaga').html(),
            titulo: $('#titulo').val(),
            cargo: $('#cargo').val(),
            quantidade: $('#quantidade').val(),
            setor: $('#setor').val(),
            nivel: $('#nivel').val(),
            jornada: $('#jornada').val(),
            escala: $('#escala').val(),
            tipo_contrato: $('#tipo_contrato').val(),
            descricao: $('#descricao').val(),
            cep: $('#cep').val(),
            endereco: $('#endereco').val(),
            bairro: $('#bairro').val(),
            cidade: $('#cidade').val(),
            salario_menor: $('#salario_menor').val(),
            salario_maior: $('#salario_maior').val(),
            periodicidade: $('#periodicidade').val(),
            exibe_salario: $('#exibe_salario').val(),
            idade_menor: $('#idade_menor').val(),
            idade_maior: $('#idade_maior').val(),
            precisa_viajar: $("input[name='precisa_viajar']:checked").val(),
            precisa_mudar: $("input[name='precisa_mudar']:checked").val(),
            deficiente: $("input[name='deficiente']:checked").val(),
            sem_experiencia: $("input[name='sem_experiencia']:checked").val(),
            primeiro_emprego: $("input[name='primeiro_emprego']:checked").val(),
            trabalho_temporario: $("input[name='trabalho_temporario']:checked").val(),
            urgente: $("input[name='urgente']:checked").val(),
            beneficio: $("input[name='beneficio']:checked").map(function() { return this.value; }).get()
        };

        $('#titulo_msg').html("");  
        $('#cargo_msg').html("");
        $('#setor_msg').html('');
        $('#nivel_msg').html('');
        $('#jornada_msg').html('');
        $('#tipo_contrato_msg').html('');
        $('#descricao_msg').html('');
        $('#cep_msg').html(''); 

        var resp = $.ajax({
            url: base_url + "preenchimentos_vaga/dados_vaga",
            type: 'POST',
            data: form_data,
            global: false,
            async:false,
            success: function(msg) { 
                var obj = $.parseJSON(msg);
                $('#titulo_msg').html(obj.titulo);  
                $('#cargo_msg').html(obj.cargo);
                $('#setor_msg').html(obj.setor);
                $('#nivel_msg').html(obj.nivel);
                $('#jornada_msg').html(obj.jornada);
                $('#tipo_contrato_msg').html(obj.tipo_contrato);
                $('#descricao_msg').html(obj.descricao); 
                $('#cep_msg').html(obj.cep);       
            }
    }).responseText;

    alert(resp);

    return false;
});

//***** CURSOS

    function limpa_curso()
    {
        $('#id_curso').val('');
        $('#formacao').val('-1');
        $('#curso').val('');
        $("input[name=situacao_curso]").prop('checked', false); 
        $("input[name=obrigatorio_curso]").prop('checked', false); 
    }

    $('#add_curso').click(function(){
        limpa_curso();  
    }); 

    $('#submit_curso').click(function() {
        var form_data = {
            id_curso: $('#id_curso').val(),
            formacao: $('#formacao').val(),
            curso: $('#curso').val(),
            situacao: $("input[name='situacao_curso']:checked").val(),
            obrigatorio: $("input[name='obrigatorio_curso']:checked").val()
        };

        $('#formacao_msg').html("");  
        $('#curso_msg').html("");
        $('#situacao_msg').html('');
        
        var resp = $.ajax({
            url: $('#curso_action').html(),
            type: 'POST',
            data: form_data,
            global: false,
            async:false,
            success: function(msg) { 
                var obj = $.parseJSON(msg);
                $('#formacao_msg').html(obj.formacao);  
                $('#curso_msg').html(obj.curso);
                $('#situacao_msg').html(obj.situacao);        
            }
    }).responseText;

    alert(resp);

    fill_lista_cursos();

    return false;
});

//*****<!-- Preenche lista de Cursos -->
    function fill_lista_cursos()
    {
        alert('oi');
        $.ajax({
        url: $('#curso_list').html(),
        type:       'POST',
        cache:      false,
        success: function(html){                    
            $('#lista_curso').html(html);
        }           
      });
    }

    $( document ).ready(function() {
        fill_lista_cursos();
    });

//*******<!-- AutoComplete -->
//********<!-- http://stackoverflow.com/questions/3693560/how-do-i-pass-an-extra-parameter-to-jquery-autocomplete-field -->
    $("#curso").autocomplete({
        source: function(request, response) {
            $.getJSON($('#curso_autocomplete').html(), { formacao: $('#formacao').val(), curso: $('#curso').val()  }, 
                    response);
        },
        minLength: 3
    });

//*******<!-- Botoes de Edicao -->
    $(document).on('click', '.edit_curso', function() {
        
        limpa_curso(); 

        var form_data = {
            id_curso: this.parentElement.id
        };

        var resp = $.ajax({
        url: $('#curso_edit').html(),
        type: 'POST',
        data: form_data,
        global: false,
        async:false,
        success: function(msg) { 
            var obj = $.parseJSON(msg);
            $('#formacao').val(obj.idformacao);  
            $('#curso').val(obj.curso);
            $("input[name=situacao_curso][value=" + obj.situacao + "]").prop('checked', true);
            $("input[name=obrigatorio_curso][value=" + obj.obrigatorio + "]").prop('checked', true);
            $('#id_curso').val(obj.idformacao_vaga)        
        }
    }).responseText;

    alert(resp);
});

    $(document).on('click', '.erase_curso', function() {
        var form_data = {
            idformacao_vaga: this.parentElement.id
        };

        var resp = $.ajax({
        url: $('#curso_erase').html(),
        type: 'POST',
        data: form_data,
        global: false,
        async:false,
        success: function(html) { 
            $('#lista_curso').html(html);           
        }
    }).responseText;

    alert(resp);
    fill_lista_cursos();
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
        $("input[name=obrigatorio_idioma]").prop('checked', false); 
    }

    $(document).on('click', '#add_idioma', function() {
        limpa_idioma();
    }); 

    $('#submit_idioma').click(function() {
        var form_data = {
            id_idioma: $('#id_idioma').val(),
            idioma: $('#idioma').val(),
            nivel_idioma: $('#nivel_idioma').val(),
            ididioma: $('#ididioma').val(),
            obrigatorio: $("input[name='obrigatorio_idioma']:checked").val()
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
            $("input[name=obrigatorio_idioma][value=" + obj.obrigatorio + "]").prop('checked', true);      
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
        $("input[name=obrigatorio_informatica]").prop('checked', false); 
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
            idinformatica: $('#idinformatica').val(),
            obrigatorio: $("input[name='obrigatorio_informatica']:checked").val()
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
            $("input[name=obrigatorio_informatica][value=" + obj.obrigatorio + "]").prop('checked', true);
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