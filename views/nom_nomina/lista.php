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
                <div class="card-title fs-6 fw-semibold">Seleccione una opción de busqueda:</div>
                <div>
                    <div class="card-subtitle text-disabled">Seleccione una categoría</div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="categorias" id="radio-alianza"
                               value="alianza" checked>
                        <label class="form-check-label" for="radio-alianza">Alianza</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="categorias" id="radio-cliente"
                               value="cliente">
                        <label class="form-check-label" for="radio-cliente">Cliente</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="categorias" id="radio-empresa"
                               value="empresa">
                        <label class="form-check-label" for="radio-empresa">Empresa</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="categorias" id="radio-remunerado"
                               value="remunerado">
                        <label class="form-check-label" for="radio-remunerado">Remunerado</label>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">

                    </div>
                    <div class="col-sm-6">
                        <div class="card-subtitle text-disabled">Reportes Ordinarios</div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>
<div class="table-responsive">
    <table class="table mb-0 table-striped table-sm datatable"></table>
</div>
