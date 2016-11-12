
<div id='<?php echo $div_id ?>'>
    <?php 
        foreach($dados as $dados_item)
        { ?>
            <p id='id_<?php echo $dados_item->idformacao_vaga; ?>'>

            <?php
            echo  $dados_item->curso . ' - ';
            echo ($dados_item->situacao == 0) ? 'Cursando' : 'Concluído';
            ?>

            <button class='edit_curso'>Editar</button>
            <button class='erase_curso'>Apagar</button>
            </p>
    <?php
        }
    ?>    
</div>



