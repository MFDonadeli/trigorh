
<div id='<?php echo $div_id ?>'>
    <?php 
        $subtitulo = '';
        foreach($dados as $dados_item)
        { ?>
            <p id='id_<?php echo $dados_item->idconhecimento_profissional; ?>'>

            <?php
            if($subtitulo != $dados_item->subtipo_conhecimento)
            {
                $subtitulo = $dados_item->subtipo_conhecimento;
                echo "<strong>" . $subtitulo . "</strong><br>";
            }
            ?>
            


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



