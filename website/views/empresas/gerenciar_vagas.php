<form action="<?php base_url()?>cadastra_vaga" id='form-inclui-vaga' method='post' accept-charset="utf-8">
    <input type='submit' id='add_vaga' value='Incluir Nova Vaga' />
</form>

<div id='lista_vagas_empresa'></div>  

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">  
<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>

<script>

    //*****<!-- Preenche lista de Idiomas -->
    function fill_lista_vagas()
    {
        $.ajax({
            url: '<?php echo base_url(); ?>empresas/get_vagas',
            type:       'POST',
            cache:      false,
            success: function(html){                    
                $('#lista_vagas_empresa').html(html);
            }           
      });
    }

    $( document ).ready(function() {
        fill_lista_vagas();
    });

    $(document).on('click', '.erase_vaga', function() {
        var form_data = {
            idvaga: this.parentElement.vaga.value
        };

        var resp = $.ajax({
            url: '<?php echo base_url(); ?>empresas/apaga_vaga',
            type: 'POST',
            data: form_data,
            global: false,
            async:false,
            success: function(html) { 
                $('#lista_vagas_empresa').html(html);           
            }
        }).responseText;

        alert(resp);
        fill_lista_vagas();
        return false;
    });
</script>

