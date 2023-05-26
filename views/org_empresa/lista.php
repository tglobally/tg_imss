<?php /** @var \tglobally\tg_imss\controllers\controlador_org_empresa  $controlador */ ?>

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
                            <div class="card-title fs-2 fw-semibold">Listado de Empresas</div>
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

