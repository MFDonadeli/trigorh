<!-- CONHECIMENTO INFORMÁTICA -->
<div id='lista_informatica'></div>

<button id='add_informatica'>Incluir Novo Conhecimento em Informática</button>
<form action='<?php echo base_url(); ?>candidatos/curriculo' id='form-conhecimento-informatica' method='post' accept-charset="utf-8">
    <input type="hidden" name="id_informatica" value="" id="id_informatica"  />
    <div id="informatica_msg"></div><label>Conhecimento:</label><input type='text' name='informatica' value='' id='informatica' /><br>  
    <input type="hidden" name="idinformatica" value="" id="idinformatica"  />
    <div id="tempo_experiencia_msg"></div><label>Tempo de Experiência</label>
        <input type='text' name='qtd_tempo_informatica' value='' id='qtd_tempo_informatica' />
        <select name='tempo_informatica' id='tempo_informatica'>
            <option value='Meses'>Meses</option>
            <option value='Anos'>Anos</option>
        </select> 
    <input type='checkbox' id='obrigatorio_informatica' name='obrigatorio_informatica' value='1'>Obrigatório<br>  
    <input type="submit" name="submit_informatica" value="Salvar" id="submit_informatica"  />   
</form>