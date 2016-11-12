<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">  
<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>

<p id='vaga' hidden><?php echo $vaga; ?></p>

<form action='<?php echo base_url(); ?>empresas/cadastra_vaga' id='form-dadosvaga' method='post' accept-charset="utf-8">
    <div id="titulo_msg"></div><label>Titulo:</label><input type='text' name='titulo' value="<?php echo $dados_vaga['titulo']; ?>" id='titulo' placeholder='Título da Vaga' /><br>
    <div id="cargo_msg"></div><label>Cargo:</label><input type='text' name='cargo' value="<?php echo $dados_vaga['cargo']; ?>" id='cargo' /><br>   
    <label>Quantidade:</label><input type='text' name='quantidade' value="<?php echo $dados_vaga['quantidade']; ?>" id='quantidade' /><br>
    <div id="setor_msg"></div><label>Setor:</label>
        <select name='setor' id='setor' />
        <?php foreach($setor as $setor_opt): ?>
            <option value='<?php echo($setor_opt->idsetor); ?>' <?php if($setor_opt->idsetor == $dados_vaga['idsetor']) echo 'selected' ?>><?php echo($setor_opt->setor); ?></option>
        <?php endforeach; ?>
        </select>
    <div id="nivel_msg"></div><label>Nível Hierárquico:</label>
        <select name='nivel' id='nivel'>
        <?php foreach($nivel_hierarquico as $nivel_hierarquico_opt): ?>
            <option value='<?php echo($nivel_hierarquico_opt->idnivel_hierarquico); ?>' <?php if($nivel_hierarquico_opt->idnivel_hierarquico == $dados_vaga['idnivel_hierarquico']) echo 'selected' ?>><?php echo($nivel_hierarquico_opt->nivel_hierarquico); ?></option>
        <?php endforeach; ?>
        </select>   
    <div id="jornada_msg"></div><label>Jornada:</label>
        <select name='jornada' id='jornada'>
        <?php foreach($jornada as $jornada_opt): ?>
            <option value='<?php echo($jornada_opt->idjornada); ?>' <?php if($jornada_opt->idjornada == $dados_vaga['idjornada']) echo 'selected' ?>><?php echo($jornada_opt->jornada); ?></option>
        <?php endforeach; ?>
        </select> 
    <div id="escala_msg"></div><label>Escala:</label>
        <select name='escala' id='escala'>
        <?php foreach($escala as $escala_opt): ?>
            <option value='<?php echo($escala_opt->idescala); ?>' <?php if($escala_opt->idescala == $dados_vaga['idescala']) echo 'selected' ?>><?php echo($escala_opt->escala); ?></option>
        <?php endforeach; ?>
        </select> 
    <div id="tipo_contrato_msg"></div><label>Tipo de Contrato:</label>
        <select name='tipo_contrato' id='tipo_contrato'>
        <?php foreach($tipo_contrato as $tipo_contrato_opt): ?>
            <option value='<?php echo($tipo_contrato_opt->idtipo_contrato); ?>' <?php if($tipo_contrato_opt->idtipo_contrato == $dados_vaga['idtipo_contrato']) echo 'selected' ?>><?php echo($tipo_contrato_opt->tipo_contrato); ?></option>
        <?php endforeach; ?>
        </select> 
    <div id="descricao_msg"></div><label>Descrição:</label><input type='text' name='descricao' value="<?php echo $dados_vaga['descricao']; ?>" id='descricao' /><br>
    <div id="cep_msg"></div><label>CEP da Vaga:</label><input type='text' name='cep' value="<?php echo $dados_vaga['cep_vaga']; ?>"id='cep' /><br> 
    <label>Endereço:</label><input type='text' name='endereco' value="<?php echo $dados_vaga['endereco']; ?>" id='endereco' /><br> 
    <label>Bairro:</label><input type='text' name='bairro' value="<?php echo $dados_vaga['bairro']; ?>" id='bairro' /><br> 
    <label>Cidade:</label><input type='text' name='cidade' value="<?php echo $dados_vaga['cidade']; ?>" id='cidade' /><br> 
    <div id="salario_msg"></div><label>Faixa Salarial:</label>
        <input type='text' name='salario_menor' value="<?php echo $dados_vaga['salario_menor']; ?>" id='salario_menor' /> até
        <input type='text' name='salario_maior' value="<?php echo $dados_vaga['salario_maior']; ?>" id='salario_maior' />
        <select name='periodicidade' id='periodicidade'>
            <option value='Periodicidade'>Periodiciade</option>
            <option value='Hora'>Por Hora</option>
            <option value='Diário'>Por Dia</option>
            <option value='Mensal'>Por Mês</option>
        </select>
        <br>
    <input type="checkbox" name="exibe_salario" id="exibe_salario" value="1">Exibir salário na descrição<br> 
    <input type="checkbox" name="ocultar_nome" id="ocultar_nome" value="1">Ocultar nome da empresa<br> 
    <label>Sexo</label>
        <?php foreach($sexo as $sexo_opt): ?>
        <input type='radio' id='sexo' name='sexo' value='<?php echo($sexo_opt->idsexo); ?>' <?php if($sexo_opt->idsexo == $dados_vaga['idsexo']) echo 'checked=true' ?>><?php echo($sexo_opt->sexo); ?><br>
        <?php endforeach; ?>
    <div id="idade_msg"></div><label>Faixa de Idade:</label>
        <input type='text' name='idade_menor' value="<?php echo $dados_vaga['idade_menor']; ?>" id='idade_menor' /> até <input type='text' name='idade_maior' value="<?php echo $dados_vaga['idade_maior']; ?>" id='idade_maior' /><br>
    <input type='checkbox' id='precisa_viajar' name='precisa_viajar' value='1' <?php if($dados_vaga['precisa_viajar']) echo 'checked=true'; ?>>Disponibilidade para viajar<br>
    <input type='checkbox' id='precisa_mudar' name='precisa_mudar' value='1' <?php if($dados_vaga['precisa_mudar']) echo 'checked=true'; ?>>Disponibilidade para mudar de cidade<br>
    <input type='checkbox' id='deficiente' name='deficiente' value='1' <?php if($dados_vaga['para_deficiente']) echo 'checked=true'; ?>>Vaga para deficiente<br>
    <input type='checkbox' id='sem_experiencia' name='sem_experiencia' value='1' <?php if($dados_vaga['necessita_experiencia']) echo 'checked=true'; ?>>Não precisa de experiência<br>
    <input type='checkbox' id='primeiro_emprego' name='primeiro_emprego' value='1' <?php if($dados_vaga['primeiro_emprego']) echo 'checked=true'; ?>>Para primeiro emprego<br>
    <input type='checkbox' id='trabalho_temporario' name='trabalho_temporario' value='1' <?php if($dados_vaga['temporario']) echo 'checked=true'; ?>>Trabalho temporário<br>
    <input type='checkbox' id='urgente' name='urgente' value='1' <?php if($dados_vaga['urgente']) echo 'checked=true'; ?>>Urgente<br>

    <label>Benefícios Oferecidos:</label>
        <?php 
        $i = 0;
        $chk = false;
        foreach($beneficio as $beneficio_opt): 
            if(isset($dados_vaga['beneficios'][$i]))
            {
                if($beneficio_opt->idbeneficio == $dados_vaga['beneficios'][$i]['idbeneficio'])
                {
                    $i++;
                    $chk = true;
                }
            }
        ?>
            
            <input type="checkbox" name="beneficio" id="beneficio" value='<?php echo($beneficio_opt->idbeneficio); ?>' <?php if($chk) echo 'checked=true'; ?>><?php echo($beneficio_opt->beneficio); ?><br>
        <?php 
            $chk = false;
        endforeach; ?>
    <input type="submit" name="submit_vaga" value="Salvar" id="submit_vaga"  />
</form>

<p id='curso_action' hidden><?php echo base_url(); ?>preenchimentos_vaga/curso/<?php echo $vaga; ?></p>
<p id='curso_autocomplete' hidden><?php echo base_url(); ?>preenchimentos_vaga/busca_curso/</p>
<p id='curso_list' hidden><?php echo base_url(); ?>preenchimentos_vaga/get_cursos/<?php echo $vaga; ?></p>
<p id='curso_edit' hidden><?php echo base_url(); ?>preenchimentos_vaga/get_curso/<?php echo $vaga; ?></p>
<p id='curso_erase' hidden><?php echo base_url(); ?>preenchimentos_vaga/apaga_curso/<?php echo $vaga; ?></p>

<div id='lista_curso'></div>

<button id='add_curso'>Incluir Curso</button>
<form action="" id='form-formacao' method='post' accept-charset="utf-8">
    <input type="hidden" id="id_curso" name='id_curso' />
    <div id="formacao_msg"></div><label>Formação</label>
        <select name='formacao' id='formacao'>
            <?php foreach($formacao as $formacao_opt): ?>
                <option value='<?php echo($formacao_opt->idformacao); ?>'><?php echo($formacao_opt->formacao); ?></option>
            <?php endforeach; ?>
        </select>  
    <div id="curso_msg"></div><label>Curso:</label><input type='text' name='curso' value='' id='curso' /><br> 
    <div id="situacao_msg"></div><input type='radio' id='situacao_curso' name='situacao_curso' value='0'>Cursando<br> 
    <input type='radio' id='situacao_curso' name='situacao_curso' value='1'>Concluído<br> 
    <input type='checkbox' id='obrigatorio_curso' name='obrigatorio_curso' value='1'>Obrigatório<br> 
    <input type="submit" name="submit_curso" value="Salvar" id="submit_curso"  /> 
</form>

<p id='idioma_action' hidden><?php echo base_url(); ?>preenchimentos_vaga/idioma/<?php echo $vaga; ?></p>
<p id='idioma_autocomplete' hidden><?php echo base_url(); ?>preenchimentos_vaga/busca_idioma/</p>
<p id='idioma_list' hidden><?php echo base_url(); ?>preenchimentos_vaga/get_idiomas/<?php echo $vaga; ?></p>
<p id='idioma_edit' hidden><?php echo base_url(); ?>preenchimentos_vaga/get_idioma/<?php echo $vaga; ?></p>
<p id='idioma_erase' hidden><?php echo base_url(); ?>preenchimentos_vaga/apaga_idioma/<?php echo $vaga; ?></p>
<?php $this->load->view('vagas/idioma'); ?>

<p id='informatica_action' hidden><?php echo base_url(); ?>preenchimentos_vaga/informatica/<?php echo $vaga; ?></p>
<p id='informatica_autocomplete' hidden><?php echo base_url(); ?>preenchimentos_vaga/busca_informatica/</p>
<p id='informatica_list' hidden><?php echo base_url(); ?>preenchimentos_vaga/get_informaticas/<?php echo $vaga; ?></p>
<p id='informatica_edit' hidden><?php echo base_url(); ?>preenchimentos_vaga/get_informatica/<?php echo $vaga; ?></p>
<p id='informatica_erase' hidden><?php echo base_url(); ?>preenchimentos_vaga/apaga_informatica/<?php echo $vaga; ?></p>
<?php $this->load->view('vagas/informatica'); ?>

<script src="<?php echo base_url(); ?>assets/js/cep.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vaga.js"></script>

