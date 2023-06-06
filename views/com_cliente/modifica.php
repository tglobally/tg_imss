<?php /** @var \tglobally\tg_imss\controllers\controlador_com_cliente $controlador */ ?>

<form class="row g-3" method="post" action="">

    <?php echo $controlador->inputs->com_tipo_cliente_id; ?>
    <?php echo $controlador->inputs->codigo; ?>
    <?php echo $controlador->inputs->razon_social; ?>
    <?php echo $controlador->inputs->rfc; ?>
    <?php echo $controlador->inputs->telefono; ?>
    <?php echo $controlador->inputs->cat_sat_regimen_fiscal_id; ?>
    <?php echo $controlador->inputs->dp_pais_id; ?>
    <?php echo $controlador->inputs->dp_estado_id; ?>
    <?php echo $controlador->inputs->dp_municipio_id; ?>
    <?php echo $controlador->inputs->dp_cp_id; ?>
    <?php echo $controlador->inputs->dp_colonia_postal_id; ?>
    <?php echo $controlador->inputs->dp_calle_pertenece_id; ?>

    <?php echo $controlador->inputs->numero_exterior; ?>
    <?php echo $controlador->inputs->numero_interior; ?>

    <?php echo $controlador->inputs->cat_sat_uso_cfdi_id; ?>
    <?php echo $controlador->inputs->cat_sat_metodo_pago_id; ?>
    <?php echo $controlador->inputs->cat_sat_forma_pago_id; ?>
    <?php echo $controlador->inputs->cat_sat_tipo_de_comprobante_id; ?>
    <?php echo $controlador->inputs->cat_sat_moneda_id; ?>
</form>
