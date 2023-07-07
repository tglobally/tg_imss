class Datatable {
    constructor(identificador, columns) {
        this.identificador = identificador;
        this.url = this.identificador.replace('#', '');
        this.url = this.url.replace('.', '');
        this.url = get_url(this.url, "data_ajax", {});
        this.columns = columns;
        this.extra_columns = [];
        this.filtro = [];
        this.filtro_especial = [];
    }

    init_datatable() {
        const self = this;

        this.datatable = $(this.identificador).DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            "deferRender": true,
            ajax: {
                url: this.url,
                data: function (d) {
                    d.columns = self.columns.map(column => column.data).concat(self.extra_columns);
                }
            },

            columns: self.columns,
        });
    }

    add_columns(extra_columns) {
        this.extra_columns = extra_columns;
    }

    add_filter(filter) {

        const existe = this.filtro.findIndex(elemento => elemento.key === filter.key);

        if (existe !== -1) {
            this.filtro[existe].valor = filter.valor;
        } else {
            this.filtro.push(filter);
        }
    }

    add_filtro_especial(filter) {

        this.filtro_especial.push(filter);
    }

    filter_isempty() {
        return this.filtro.length === 0
    }

    filter_clear() {
        this.filtro = [];
    }

    filtro_especial_clear() {
        this.filtro_especial = [];
    }

    filter_reset() {
        if (!this.filter_isempty()) {
            this.filtro = [];
            this.draw;
        }
    }

    get draw() {
        return this.datatable.draw();
    }
}

const columns = [
    {
        title: 'Id',
        data: 'em_anticipo_id'
    },
    {
        title: 'Empresa',
        data: 'org_sucursal_descripcion'
    },
    {
        title: 'Registro Patronal',
        data: 'em_registro_patronal_descripcion'
    },
    {
        title: 'Remunerado',
        data: 'em_empleado_nombre'
    },
    {
        title: 'Amortización',
        data: 'em_anticipo_monto'
    }
]

const datatable_nominas = new Datatable("#em_anticipo", columns);

datatable_nominas.init_datatable();

let sl_categoria = $("#org_empresa_id");

$('input[type=radio][name=categorias]').change(function () {
    var seccion = this.value;
    var accion = $(this).data("accion");
    var titulo = $(this).data("titulo");

    get_data2(seccion, accion, {}, sl_categoria);

    $('label[for=org_empresa_id]').html(titulo);

    datatable_nominas.filter_reset();
});
