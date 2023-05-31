<?php /** @var \tglobally\tg_imss\controllers\controlador_im_salario_minimo $controlador */ ?>

<form class="row g-3" method="post" action="<?php echo $controlador->link_modifica_bd; ?>">

    <?php echo $controlador->inputs->id; ?>
    <?php echo $controlador->inputs->codigo; ?>
    <?php echo $controlador->inputs->codigo_bis; ?>
    <?php echo $controlador->inputs->descripcion; ?>
    <?php echo $controlador->inputs->select->im_tipo_salario_minimo_id; ?>
    <?php echo $controlador->inputs->select->dp_cp_id; ?>
    <?php echo $controlador->inputs->fecha_inicio; ?>
    <?php echo $controlador->inputs->fecha_fin; ?>
    <?php echo $controlador->inputs->monto; ?>

    <div class="col-12 d-flex justify-content-end">
        <button type="submit" class="btn btn-primary" name="btn_action_next">Actualizar</button>
    </div>
</form>
