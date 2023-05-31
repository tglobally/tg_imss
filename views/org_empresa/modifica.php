<?php /** @var \tglobally\tg_imss\controllers\controlador_com_cliente $controlador */ ?>

<form class="row g-3" method="post" action="">
    <?php echo $controlador->inputs->codigo; ?>
    <?php echo $controlador->inputs->rfc; ?>
    <?php echo $controlador->inputs->razon_social; ?>
    <?php echo $controlador->inputs->nombre_comercial; ?>

    <?php echo $controlador->inputs->email_sat; ?>
    <?php echo $controlador->inputs->fecha_inicio_operaciones; ?>
    <?php echo $controlador->inputs->fecha_ultimo_cambio_sat; ?>

    <?php echo $controlador->inputs->select->cat_sat_regimen_fiscal_id; ?>
    <?php echo $controlador->inputs->select->dp_pais_id; ?>
    <?php echo $controlador->inputs->select->dp_estado_id; ?>
    <?php echo $controlador->inputs->select->dp_municipio_id; ?>
    <?php echo $controlador->inputs->select->dp_cp_id; ?>
    <?php echo $controlador->inputs->select->dp_colonia_postal_id; ?>
    <?php echo $controlador->inputs->select->dp_calle_pertenece_id; ?>
    <?php echo $controlador->inputs->select->dp_calle_pertenece_entre1_id; ?>
    <?php echo $controlador->inputs->select->dp_calle_pertenece_entre2_id; ?>
    <?php echo $controlador->inputs->select->org_tipo_empresa_id; ?>

    <?php echo $controlador->inputs->exterior; ?>
    <?php echo $controlador->inputs->interior; ?>

    <?php echo $controlador->inputs->telefono_1; ?>
    <?php echo $controlador->inputs->telefono_2; ?>
    <?php echo $controlador->inputs->telefono_3; ?>

</form>
