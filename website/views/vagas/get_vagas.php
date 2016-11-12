<?php 
    foreach($dados as $dados_item)
    { ?>
        <p>
        <form action="<?php base_url()?>cadastra_vaga" id='form-vagas' method='post' accept-charset="utf-8">
        <input type='hidden' id='vaga' name='vaga' value='<?php echo $dados_item->idvaga; ?>'>
        <?php
        echo  $dados_item->codvaga;
        ?>

        <input type="submit" name="edit_vaga" value="Editar" class="edit_vaga"  />
        <button class='erase_vaga'>Apagar</button>
        </form> 
        </p>
<?php
    }
?> 