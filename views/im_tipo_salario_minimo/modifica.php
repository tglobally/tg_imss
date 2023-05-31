<?php /** @var \tglobally\tg_imss\controllers\controlador_im_tipo_salario_minimo $controlador */ ?>

<form class="row g-3" method="post" action="<?php echo $controlador->link_modifica_bd; ?>">

    <?php echo $controlador->inputs->id; ?>
    <?php echo $controlador->inputs->codigo; ?>
    <?php echo $controlador->inputs->codigo_bis; ?>
    <?php echo $controlador->inputs->descripcion; ?>
    <?php echo $controlador->inputs->descripcion_select; ?>
    <?php echo $controlador->inputs->alias; ?>

    <div class="col-12 d-flex justify-content-end">
        <button type="submit" class="btn btn-primary" name="btn_action_next">Actualizar</button>
    </div>
</form>