<?php /** @var \tglobally\tg_imss\controllers\controlador_im_registro_patronal $controlador */ ?>

<div class="container-lg">

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="./index.php?seccion=adm_session&accion=inicio&session_id=<?php echo $controlador->session_id ?>">Inicio</a></li>
            <li class="breadcrumb-item"><a href="<?php echo $controlador->link_lista?>">Lista</a></li>
            <li class="breadcrumb-item active" aria-current="page">Modifica</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-xl-12">
            <div class="card mb-4">
                <div class="card-body p-4">

                    <div class="row">
                        <div class="col">
                            <div class="card-title fs-2 fw-semibold">Modifica Registro Patronal</div>
                        </div>
                        <div class="col-auto ms-auto">
                            <button class="btn btn-secondary btn-sm" type="button" data-coreui-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-list-task icon me-2"></i>
                                Acciones
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?php echo $controlador->link_alta?>">Alta</a></li>
                            </ul>
                        </div>
                    </div>

                    <form class="row g-3" method="post" action="<?php echo $controlador->link_modifica_bd; ?>">

                        <?php echo $controlador->inputs->fc_csd_id; ?>
                        <?php echo $controlador->inputs->im_clase_riesgo_id; ?>
                        <?php echo $controlador->inputs->descripcion; ?>

                        <div class="col-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary" name="btn_action_next">Actualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

