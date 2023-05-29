<?php /** @var \tglobally\tg_imss\controllers\controlador_im_uma $controlador */ ?>

<div class="container-lg">

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item">
                <a href="./index.php?seccion=adm_session&accion=inicio&session_id=<?php echo $controlador->session_id ?>">Inicio</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Lista</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-xl-12">
            <div class="card mb-4">
                <div class="card-body p-4">

                    <div class="row">
                        <div class="col">
                            <div class="card-title fs-2 fw-semibold">Listado UMA</div>
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

                    <div class="table-responsive">
                        <table class="table mb-0 table-striped table-sm datatable"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
