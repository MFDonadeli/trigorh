
<div id='<?php echo $div_id ?>'>
    <?php 
        foreach($dados as $dados_item)
        { ?>
            <p id='id_<?php echo $dados_item->idhistorico_profissional; ?>'>

            <?php
            echo  $dados_item->nome_empresa . ' ' . $dados_item->funcao;
            ?>

            <button class='edit_hp'>Editar</button>
            <button class='erase_hp'>Apagar</button>
            </p>
    <?php
        }
    ?>    
</div>



