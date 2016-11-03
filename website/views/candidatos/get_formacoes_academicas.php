
<div id='<?php echo $div_id ?>'>
    <?php 
        foreach($dados as $dados_item)
        { ?>
            <p id='id_<?php echo $dados_item->idformacao_academica; ?>'>

            <?php
            echo  $dados_item->formacao . ' ' . $dados_item->curso;
            ?>

            <button class='edit'>Editar</button>
            <button class='erase'>Apagar</button>
            </p>
    <?php
        }
    ?>    
</div>



