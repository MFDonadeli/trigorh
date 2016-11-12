
<div id='<?php echo $div_id ?>'>
    <?php 
        foreach($dados as $dados_item)
        { ?>
            <p id='id_<?php echo $dados_item->idconhecimento_vaga; ?>'>

            <?php
            echo  $dados_item->conhecimento . ' ' . $dados_item->nivel_idioma;
            ?>

            <button class='edit_idioma'>Editar</button>
            <button class='erase_idioma'>Apagar</button>
            </p>
    <?php
        }
    ?>    
</div>



