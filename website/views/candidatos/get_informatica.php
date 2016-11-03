
<div id='<?php echo $div_id ?>'>
    <?php 
        foreach($dados as $dados_item)
        { ?>
            <p id='id_<?php echo $dados_item->idconhecimento_profissional; ?>'>

            <?php
            echo  $dados_item->conhecimento;
            ?>

            <button class='edit_informatica'>Editar</button>
            <button class='erase_informatica'>Apagar</button>
            </p>
    <?php
        }
    ?>    
</div>



