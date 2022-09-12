<?php use config\views; ?>

<div class="col-md-3 secciones">
    <div class="col-md-12 int_secciones ">
        <div class="col-md-4 seccion">
            <img src="<?php echo (new views())->url_assets.'img/stepper/1.svg'?>" class="img-seccion">
        </div>
        <div class="col-md-8">
            <h3>Alta Clase Riesgo</h3>
            <?php include "templates/im_clase_riesgo/_base/buttons/1.azul.alta.php"; ?>
        </div>
    </div>
</div>