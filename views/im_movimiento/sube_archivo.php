<?php /** @var \tglobally\tg_imss\controllers\controlador_im_movimiento $controlador */ ?>

<form class="row g-3" method="post"
      action="./index.php?seccion=im_movimiento&accion=lee_archivo&session_id=<?php echo $controlador->session_id; ?>&registro_id=<?php echo $controlador->registro_id; ?>"
      enctype="multipart/form-data">

    <div class="control-group col-12">
        <label class="control-label" for="archivo">Archivo Nomina</label>
        <div class="controls">
            <input type="file" id="archivo" name="archivo" multiple/>
        </div>
    </div>

    <div class="col-12 d-flex justify-content-end">
        <button type="submit" class="btn btn-primary" name="btn_action_next">Cargar</button>
    </div>
</form>

<div id="exampleModal" class="modal" tabindex="-1" role="dialog">
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
                    <div class="col-lg-6">
                        <form id="em_empleado_update" class="form-additional">
                            <?php echo $controlador->inputs->nombre; ?>
                            <?php echo $controlador->inputs->ap; ?>
                            <?php echo $controlador->inputs->am; ?>
                            <?php echo $controlador->inputs->nss; ?>
                            <?php echo $controlador->inputs->salario_diario; ?>
                            <?php echo $controlador->inputs->salario_diario_integrado; ?>
                        </form>
                    </div>
                    <div class="col-lg-6">
                        <form class="form-additional">
                            <?php echo $controlador->inputs->nombre_preview; ?>
                            <?php echo $controlador->inputs->ap_preview; ?>
                            <?php echo $controlador->inputs->am_preview; ?>
                            <?php echo $controlador->inputs->nss_preview; ?>
                            <?php echo $controlador->inputs->salario_diario_preview; ?>
                            <?php echo $controlador->inputs->salario_diario_integrado_preview; ?>
                        </form>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="submit" form="em_empleado_update" class="btn btn-info btn-guarda" name="btn_action_next"
                        value="modifica">Actualiza
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
