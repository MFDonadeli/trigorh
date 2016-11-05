<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">  
<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>


<form action='<?php echo base_url(); ?>candidatos/curriculo' id='form-dadospessoais' method='post' accept-charset="utf-8">
    <input type="hidden" name="msg_dadospessoais" value="" id="msg_dadospessoais"  />
        
    <div id="nome_msg"></div><label>Nome Completo:</label><input type='text' name='nome' value='<?php echo $dados_pessoais->nome; ?>' id='nome' placeholder='Nome Completo' /><br>
    <div id="cpf_msg"></div><label>CPF:</label><input type='text' name='cpf' value='<?php echo $dados_pessoais->cpf; ?>' id='cpf' /><br>
    <div id="celular_msg"></div><label>Celular:</label><input type='text' name='celular' value='<?php echo $dados_pessoais->celular; ?>' id='celular' /><br>
    <label>Observação:</label><input type='text' name='observacao_celular' value='<?php echo $dados_pessoais->observacao_celular; ?>' id='observacao_celular' /><br>
    <label>Fixo/Recado:</label><input type='text' name='telefone_fixo' value='<?php echo $dados_pessoais->telefone_fixo; ?>' id='telefone_fixo' /><br>
    <label>Observação:</label><input type='text' name='observacao_telefone_fixo' value='<?php echo $dados_pessoais->observacao_telefone_fixo; ?>' id='observacao_telefone_fixo' /><br>
    <div id="data_nascimento_msg"></div><label>Data de Nascimento:</label><input name="data_nascimento" type="text" id="data_nascimento" value='<?php echo $dados_pessoais->data_nascimento; ?>'/><br>
    <div id="sexo_msg"></div><label>Sexo</label>
        <?php foreach($sexo as $sexo_opt): ?>
        <input type='radio' id='sexo' name='sexo' value='<?php echo($sexo_opt->idsexo); ?>' <?php if($sexo_opt->idsexo == $dados_pessoais->idsexo) echo 'checked=true' ?>><?php echo($sexo_opt->sexo); ?><br>
        <?php endforeach; ?>
    <div id="estado_civil_msg"></div><label>Estado Civil</label>
        <select name='estado_civil' id='estado_civil'>
            <?php foreach($estado_civil as $estado_civil_opt): ?>
            <option value='<?php echo($estado_civil_opt->idestado_civil); ?>' <?php if($estado_civil_opt->idestado_civil == $dados_pessoais->idestado_civil) echo 'selected=selected' ?>><?php echo($estado_civil_opt->estado_civil); ?></option>
            <?php endforeach; ?>
        </select>
    <div id="cep_msg"></div><label>CEP:</label><input type='text' name='cep' value='<?php echo $dados_pessoais->cep; ?>' id='cep' /><br>
    <div id="endereco_msg"></div><label>Endereço:</label><input type='text' name='endereco' value='<?php echo $dados_pessoais->endereco; ?>' id='endereco' /><br>
    <label>Numero:</label><input type='text' name='numero' value='<?php echo $dados_pessoais->numero; ?>' id='numero' /><br>
    <label>Complemento:</label><input type='text' name='complemento' value='<?php echo $dados_pessoais->complemento; ?>' id='complemento' /><br>
    <label>Bairro:</label><input type='text' name='bairro' value='<?php echo $dados_pessoais->bairro; ?>' id='bairro' /><br>
    <div id="cidade_msg"></div><label>Cidade/Estado:</label>
        <input type="hidden" name="id_cidade" value='<?php echo($dados_pessoais->idcidade); ?>' id="id_cidade"  />
        <input type='text' name='cidade' value='<?php echo($dados_pessoais->localizacao); ?>' id='cidade' /><br>
    <input type='checkbox' id='pode_viajar' name='pode_viajar' value='1' <?php if($dados_pessoais->pode_viajar) echo 'checked=true'; ?>>Disponibilidade para viajar?<br>
    <input type='checkbox' id='pode_mudar' name='pode_mudar' value='1' <?php if($dados_pessoais->pode_mudar) echo 'checked=true'; ?>>Disponibilidade para mudar de cidade?<br>
    <input type='checkbox' id='deficiente' name='deficiente' value='1' <?php if($dados_pessoais->deficiente) echo 'checked=true'; ?>>Portador de deficiência?<br>
    <label>Observacoes:</label>
    <textarea name="observacoes_dadospessoais" cols="28" rows="5" max_length="512" id="observacoes_dadospessoais" value='<?php echo $dados_pessoais->observacoes; ?>'></textarea><br>
    <input type="submit" name="submit" value="Salvar" id="submit"  />
</form>

<div id='lista_formacoes_academicas'></div>

<button id='add_formacaoacademica'>Incluir Nova Formação Acadêmica</button>

<form action='<?php echo base_url(); ?>candidatos/curriculo' id='form-historicoacademico' method='post' accept-charset="utf-8">
    <input type="hidden" name="id_historicoacademico" value="" id="id_historicoacademico"  />
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


<div id='lista_historico_profissional'></div>

<button id='add_historico_profissional'>Incluir Nova Experiência Profissional</button>

<form action='<?php echo base_url(); ?>candidatos/curriculo' id='form-historicoprofissional' method='post' accept-charset="utf-8">
    <input type="hidden" name="id_historicoprofissional" value="" id="id_historicoprofissional"  />
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
    <div id="atividades_msg"></div><label>Principais Atividades:</label>
    <textarea name="atividades" cols="28" rows="5" max_length="200" id="atividades"></textarea><br>
    <span style="font-size: 9px;"><span id="chars_extra">200</span> caracteres restantes</span><br>
    <label>Benefícios:</label>
        <?php foreach($beneficio as $beneficio_opt): ?>
            <input type="checkbox" name="beneficio" id="beneficio" value='<?php echo($beneficio_opt->idbeneficio); ?>'><?php echo($beneficio_opt->beneficio); ?><br>
        <?php endforeach; ?>
    <input type="submit" name="submit_hp" value="Salvar" id="submit_hp"  />
</form>


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
    <input type="submit" name="submit_informatica" value="Salvar" id="submit_informatica"  />   
</form>

<!-- OBJETIVO -->
<div id='lista_objetivo'></div>

<form action='<?php echo base_url(); ?>candidatos/curriculo' id='form-objetivo' method='post' accept-charset="utf-8">
    <input type="hidden" name="id_objetivo" value="" id="id_objetivo"  />
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
    <input type="checkbox" name="exibe_salario_objetivo" id="exibe_salario_objetivo" value="1">Exibir salário no currículo?<br>
    <input type="submit" name="submit_objetivo" value="Salvar" id="submit_objetivo"  />
</form>


<script src="<?php echo base_url(); ?>assets/js/cep.js"></script>
<script src="<?php echo base_url(); ?>assets/js/curriculo.js"></script>
