<?php /** @var \tglobally\tg_imss\controllers\controlador_im_rcv $controlador */ ?>
<?php (new \tglobally\template_tg\template())->sidebar($controlador); ?>
<div class="col-md-9 formulario">
    <div class="col-lg-12">

        <h3 class="text-center titulo-form">Hola, <?php echo $controlador->datos_session_usuario['adm_usuario_user']; ?> </h3>


        <div class="  form-main" id="form">
            <form method="post" action="<?php echo $controlador->link_alta_bd; ?>" class="form-additional">

                <?php echo $controlador->inputs->codigo; ?>
                <?php echo $controlador->inputs->codigo_bis; ?>
                <?php echo $controlador->inputs->descripcion; ?>
                <?php echo $controlador->inputs->descripcion_select; ?>
                <?php echo $controlador->inputs->alias; ?>
                <?php echo $controlador->inputs->factor; ?>
                <?php echo $controlador->inputs->monto_inicial; ?>
                <?php echo $controlador->inputs->monto_final; ?>

                <div class="buttons col-md-12">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-info btn-guarda col-md-12 " >Guarda</button>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-info btn-guarda col-md-12 " >Siguiente</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
