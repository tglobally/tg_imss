<?php /** @var \tglobally\tg_imss\controllers\controlador_em_anticipo $controlador */ ?>

<form class="row g-3" method="post" action="./index.php?seccion=em_anticipo&accion=lee_archivo&session_id=<?php echo $controlador->session_id; ?>&registro_id=<?php echo $controlador->registro_id; ?>">
    <div class="control-group col-sm-12">
        <label class="control-label" for="archivo">Archivo Anticipos</label>
        <div class="controls">
            <input type="file" id="archivo" name="archivo" multiple />
        </div>
    </div>
</form>

