<?php /** @var \tglobally\tg_imss\controllers\controlador_em_anticipo $controlador  controlador en ejecucion */ ?>

<div class="accordion accordion-flush" id="accordionFlushExample">
    <div class="accordion-item">
        <h2 class="accordion-header" id="flush-headingOne">
            <button class="accordion-button collapsed" type="button" data-coreui-toggle="collapse"
                    data-coreui-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                Filtros avanzados
            </button>
        </h2>
        <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne"
             data-coreui-parent="#accordionFlushExample">
            <div class="accordion-body">

                <form action="<?php echo $controlador->link_nom_nomina_exportar_nominas; ?>" method="post" class="form-additional" id="form_export">
                    <div class="card-title fs-6 fw-semibold">Seleccione una opción de busqueda:</div>
                    <div>
                        <div class="card-subtitle text-disabled">Seleccione una categoría</div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="categorias" id="radio-empresa"
                                   value="org_empresa" checked data-accion="get_empresa" data-titulo="Empresa:">
                            <label class="form-check-label" for="radio-empresa">Empresa</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="categorias" id="radio-remunerado"
                                   value="em_empleado" data-accion="get_empleado" data-titulo="Remunerado:">
                            <label class="form-check-label" for="radio-remunerado">Remunerado</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="categorias" id="radio-usuario"
                                   value="adm_usuario" data-accion="get_usuario" data-titulo="Ejecutivos:">
                            <label class="form-check-label" for="radio-remunerado">Ejecutivo</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <?php echo $controlador->inputs->org_empresa_id; ?>
                            <div class="col-sm-12">
                                <?php echo $controlador->inputs->fecha_inicio; ?>
                                <?php echo $controlador->inputs->fecha_final; ?>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary export" name="btn_action_next"
                            style="border-radius: 5px" value="exportar" form="form_export">
                        Exportar
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>
<div class="table-responsive">
    <table id="em_anticipo" class="table mb-0 table-striped table-sm datatables"></table>
</div>