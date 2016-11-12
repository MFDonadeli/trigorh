<!-- IDIOMA -->
<div id='lista_idioma'></div>

<button id='add_idioma'>Incluir Novo Idioma</button>

<form action='<?php echo base_url(); ?>candidatos/curriculo' id='form-conhecimento-idioma' method='post' accept-charset="utf-8">
    <input type="hidden" name="id_idioma" value="" id="id_idioma"  />
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