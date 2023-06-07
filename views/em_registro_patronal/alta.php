<?php /** @var \tglobally\tg_imss\controllers\controlador_em_registro_patronal $controlador */ ?>

<form class="row g-3" method="post" action="<?php echo $controlador->link_alta_bd; ?>">

    <?php echo $controlador->inputs->fc_csd_id; ?>
    <?php echo $controlador->inputs->em_clase_riesgo_id; ?>
    <?php echo $controlador->inputs->cat_sat_isn_id; ?>
    <?php echo $controlador->inputs->descripcion; ?>

    <div class="col-12 d-flex justify-content-end">
        <button type="submit" class="btn btn-primary" name="btn_action_next">Registrar</button>
    </div>
</form>

