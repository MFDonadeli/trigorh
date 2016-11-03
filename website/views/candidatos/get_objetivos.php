
<div id='<?php echo $div_id ?>'>
    <?php 
        foreach($dados as $dados_item)
        { ?>
            <p id='id_<?php echo $dados_item->idobjetivo_profissional; ?>'>

            <?php
            echo  $dados_item->funcao;
            ?>

            <button class='edit_objetivo'>Editar</button>
            <button class='erase_objetivo'>Apagar</button>
            </p>
    <?php
        }
    ?>    
</div>



