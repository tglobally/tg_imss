<?php /** @var \tglobally\tg_imss\controllers\controlador_im_movimiento $controlador */ ?>

<?php (new \tglobally\template_tg\template())->sidebar($controlador); ?>

<div class="col-md-9 formulario">
    <div class="col-lg-12">

        <h3 class="text-center titulo-form">Hola, <?php echo $controlador->datos_session_usuario['adm_usuario_user']; ?> </h3>

        <div class="  form-main" id="form">
            <form method="post" action="<?php echo $controlador->link_alta_bd; ?>" class="form-additional">
                <?php echo $controlador->inputs->em_empleado_id; ?>
                <div class="control-group col-sm-2">
                    <button class="btn btn-info btn-guarda" disabled style="width: 100%;" value="modifica">Editar</button>
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
                <div class="buttons col-md-12">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-info btn-guarda col-md-12 " name="btn_action_next" value="modifica">Guarda</button>
                    </div>
                    <div class="col-md-6 ">
                        <a href="<?php echo $controlador->link_lista; ?>"  class="btn btn-info btn-guarda col-md-12 ">Regresar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
