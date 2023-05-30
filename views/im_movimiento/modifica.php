<?php /** @var \tglobally\tg_imss\controllers\controlador_im_movimiento $controlador */ ?>

<form class="row g-3" method="post" action="<?php echo $controlador->link_modifica_bd; ?>">

    <?php echo $controlador->inputs->em_empleado_id; ?>
    <div class="control-group col-sm-2">
        <button id="editar_empleado" type="button" class="btn btn-secondary btn-guarda" disabled style="width: 100%;" data-toggle="modal" data-target="#editar_empleado_modal">Editar</button>
    </div>
    <?php echo $controlador->inputs->em_registro_patronal_id; ?>
    <?php echo $controlador->inputs->im_tipo_movimiento_id; ?>
    <?php echo $controlador->inputs->fecha; ?>
    <?php echo $controlador->inputs->factor_integracion; ?>
    <?php echo $controlador->inputs->salario_diario; ?>
    <?php echo $controlador->inputs->salario_diario_integrado; ?>
    <?php echo $controlador->inputs->salario_mixto; ?>
    <?php echo $controlador->inputs->salario_variable; ?>
    <?php echo $controlador->inputs->observaciones; ?>

    <div class="col-12 d-flex justify-content-end">
        <button type="submit" class="btn btn-primary" name="btn_action_next">Actualizar</button>
    </div>
</form>

<div id="editar_empleado_modal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">DATOS DEL EMPLEADO</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-lg-12">
                        <form id="em_empleado_update" class="form-additional">
                            <?php echo $controlador->inputs->nombre; ?>
                            <?php echo $controlador->inputs->ap; ?>
                            <?php echo $controlador->inputs->am; ?>
                            <?php echo $controlador->inputs->nss; ?>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" form="em_empleado_update" class="btn btn-info btn-guarda" name="btn_action_next" value="modifica">Actualiza</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
