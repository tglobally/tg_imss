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
                    d.filtros = {
                        filtro: self.filtro,
                        filtro_especial: self.filtro_especial,
                        extra_join: [
                            {
                                "entidad": "adm_usuario",
                                "key": "id",
                                "enlace": "em_anticipo",
                                "key_enlace": "usuario_alta_id",
                                "renombre": "adm_usuario"
                            },
                            {
                                "entidad": "org_empresa",
                                "key": "id",
                                "enlace": "org_sucursal",
                                "key_enlace": "org_empresa_id",
                                "renombre": "org_empresa"
                            }
                        ]

                    }
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
        data: 'em_empleado_nombre_completo'
    },
    {
        title: 'Fecha Prestación',
        data: 'em_anticipo_fecha_prestacion'
    },
    {
        title: 'Amortización',
        data: 'em_anticipo_monto'
    }
]

const datatable_anticipos = new Datatable("#em_anticipo", columns);

datatable_anticipos.init_datatable();

let sl_categoria = $("#org_empresa_id");
let txt_fecha_inicio = $("#fecha_inicio");
let txt_fecha_final = $("#fecha_final");

$('input[type=radio][name=categorias]').change(function () {
    var seccion = this.value;
    var accion = $(this).data("accion");
    var titulo = $(this).data("titulo");

    get_data2(seccion, accion, {}, sl_categoria);

    $('label[for=org_empresa_id]').html(titulo);

    datatable_anticipos.filter_reset();
});

sl_categoria.change(function () {
    var seccion = $('[type=radio][name="categorias"]:checked').val();

    if (this.value !== "" && this.value != -1) {
        datatable_anticipos.add_filter({
            "key": seccion + ".id",
            "valor": this.value,
        });

    } else {
        datatable_anticipos.filter_clear();
    }

    datatable_anticipos.draw;
});

txt_fecha_inicio.change(function () {

    datatable_anticipos.filtro_especial_clear();

    var fecha_final = txt_fecha_final.val();

    if (fecha_final == ""){
        const date = new Date();

        let year= date.getFullYear();
        let month= String(date.getMonth()+1).padStart(2,"0");
        let day= String(date.getDate()).padStart(2, '0');

        fecha_final = `${year}-${month}-${day}`;
    }

    if (this.value !== "") {

        datatable_anticipos.add_filtro_especial({
            "key": "em_anticipo.fecha_prestacion",
            "valor": this.value,
            "operador": "<=",
            "comparacion": "AND"
        });

        datatable_anticipos.add_filtro_especial({
            "key": "em_anticipo.fecha_prestacion",
            "valor": fecha_final,
            "operador": ">=",
            "comparacion": "AND"
        });
    }

    datatable_anticipos.draw;
});

txt_fecha_final.change(function () {

    datatable_anticipos.filtro_especial_clear();

    var fecha_inicio = txt_fecha_inicio.val();

    if (fecha_inicio == ""){
        fecha_inicio = "2000-01-01";
    }

    if (this.value !== "") {

        datatable_anticipos.add_filtro_especial({
            "key": "em_anticipo.fecha_prestacion",
            "valor": fecha_inicio,
            "operador": "<=",
            "comparacion": "AND"
        });

        datatable_anticipos.add_filtro_especial({
            "key": "em_anticipo.fecha_prestacion",
            "valor": this.value,
            "operador": ">=",
            "comparacion": "AND"
        });
    }

    datatable_anticipos.draw;
});
