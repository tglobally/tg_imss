<?php /** @var \tglobally\tg_imss\controllers\controlador_em_anticipo $controlador */ ?>

<form class="row g-3" method="post" action="<?php echo $controlador->link_alta_bd; ?>">

    <?php echo $controlador->inputs->em_empleado_id; ?>
    <?php echo $controlador->inputs->em_tipo_anticipo_id; ?>
    <?php echo $controlador->inputs->em_tipo_descuento_id; ?>
    <?php echo $controlador->inputs->descripcion; ?>
    <?php echo $controlador->inputs->monto; ?>
    <?php echo $controlador->inputs->n_pagos; ?>
    <?php echo $controlador->inputs->fecha_prestacion; ?>
    <?php echo $controlador->inputs->fecha_inicio_descuento; ?>
    <?php echo $controlador->inputs->comentarios; ?>

    <div class="col-12 d-flex justify-content-end">
        <button type="submit" class="btn btn-primary" name="btn_action_next">Registrar</button>
    </div>
</form>
