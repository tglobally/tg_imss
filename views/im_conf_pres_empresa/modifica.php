<?php /** @var \tglobally\tg_imss\controllers\controlador_im_conf_pres_empresa $controlador */ ?>

<form class="row g-3" method="post" action="<?php echo $controlador->link_modifica_bd; ?>">

    <?php echo $controlador->inputs->codigo; ?>
    <?php echo $controlador->inputs->descripcion; ?>
    <?php echo $controlador->inputs->org_empresa_id; ?>
    <?php echo $controlador->inputs->im_conf_prestaciones_id; ?>

    <div class="col-12 d-flex justify-content-end">
        <button type="submit" class="btn btn-primary" name="btn_action_next">Actualizar</button>
    </div>
</form>