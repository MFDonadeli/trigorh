<!DOCTYPE html>
<html>
<body>
    <ul>
        <li><a href="<?php echo base_url(); ?>Candidatos">Candidatos</a></li>
        <li><a href="<?php echo base_url(); ?>Empresas">Empresas</a></li>
        <li><a href="<?php echo base_url(); ?>Blog">Blog</a></li>
        <li><a href="<?php echo base_url(); ?>Cursos">Cursos</a></li>
    </ul>

    <?php if($this->session->flashdata('login_failed')) : ?>
        <?php echo '<p class="text-error">' .$this->session->flashdata('login_failed') . '</p>'; ?>
    <?php endif; ?>

    <?php $this->load->view('empresas/login'); ?>

    <?php $attributes = array('id' => 'form-cadastro',
                          'class' => 'form-horizontal'); ?>
    <?php echo form_open('empresas/cadastrar',$attributes); ?>
         <!--Submit Buttons-->
    <?php $data = array("value" => "Incluir Vaga",
                    "name" => "submit",
                    "class" => "btn btn-primary"); ?>
    <?php echo form_submit($data); ?>
    <?php echo form_close(); ?>

</body>
</html>