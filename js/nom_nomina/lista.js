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
                                "entidad": "tg_empleado_sucursal",
                                "key": "em_empleado_id",
                                "enlace": "em_empleado",
                                "key_enlace": "id",
                                "renombre": "tg_empleado_sucursal"
                            },
                            {
                                "entidad": "com_sucursal",
                                "key": "id",
                                "enlace": "tg_empleado_sucursal",
                                "key_enlace": "com_sucursal_id",
                                "renombre": "com_sucursal"
                            },
                            {
                                "entidad": "nom_clasificacion_nomina",
                                "key": "nom_nomina_id",
                                "enlace": "nom_nomina",
                                "key_enlace": "id",
                                "renombre": "nom_clasificacion_nomina"
                            },
                            {
                                "entidad": "com_cliente",
                                "key": "id",
                                "enlace": "com_sucursal",
                                "key_enlace": "com_cliente_id",
                                "renombre": "com_cliente"
                            },
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
        data: 'nom_nomina_id'
    },
    {
        title: 'RFC',
        data: 'em_empleado_rfc'
    },
    {
        title: 'Empleado',
        data: 'em_empleado_nombre_completo'
    },
    {
        title: '# Días Pagados',
        data: 'nom_nomina_num_dias_pagados'
    },
    {
        title: 'Fecha Pago',
        data: 'nom_nomina_fecha_pago'
    },
    {
        title: 'Cliente',
        data: 'com_cliente_razon_social'
    },
    {
        title: 'Empresa',
        data: 'org_empresa_descripcion'
    }

]


const datatable_nominas = new Datatable("#nom_nomina", columns);
datatable_nominas.add_columns(["em_empleado_nombre", "em_empleado_ap", "em_empleado_am", "em_registro_patronal_id",
    "nom_clasificacion_nomina_descripcion"]);

datatable_nominas.init_datatable();


let sl_categoria = $("#categoria_id");
let sl_registro_patronal = $("#em_registro_patronal_id");
let txt_fecha_inicio = $("#fecha_inicio");
let txt_fecha_final = $("#fecha_final");

$('input[type=radio][name=categorias]').change(function () {
    var seccion = this.value;
    var accion = $(this).data("accion");
    var titulo = $(this).data("titulo");
    var extra_params = [];

    if (seccion === "em_empleado") {
        extra_params = ["em_registro_patronal_id"];
    }

    get_data2(seccion, accion, {}, sl_categoria, extra_params);

    $('label[for=com_cliente_id]').html(titulo);

    datatable_nominas.filter_reset();
});
sl_categoria.change(function () {
    var seccion = $('[type=radio][name="categorias"]:checked').val();

    if (this.value !== "" && this.value != -1) {
        datatable_nominas.add_filter({
            "key": seccion + ".id",
            "valor": this.value,
        });

    } else {
        datatable_nominas.filter_clear();
    }

    datatable_nominas.draw;
});

sl_registro_patronal.change(function () {

    if (this.value !== "" && this.value != -1) {
        datatable_nominas.add_filter({
            "key": "em_registro_patronal.id",
            "valor": this.value,
        });

    } else {
        datatable_nominas.filter_clear();
    }

    datatable_nominas.draw;
});

txt_fecha_final.change(function () {

    var fecha_inicio = txt_fecha_inicio.val();

    if (fecha_inicio == ""){
        fecha_inicio = "2000-01-01";
    }

    if (this.value !== "") {
        datatable_nominas.add_filtro_especial({
            "key": "nom_nomina.fecha_pago",
            "valor": fecha_inicio,
            "operador": "<=",
            "comparacion": "AND"
        });

        datatable_nominas.add_filtro_especial({
            "key": "nom_nomina.fecha_pago",
            "valor": this.value,
            "operador": ">=",
            "comparacion": "AND"
        });

    } else {
        datatable_nominas.filtro_especial_clear();
    }

    datatable_nominas.draw;
});



/*
$('a:contains("Exportar Nominas")').click(function(e) {
    e.preventDefault();

    var seccion = $('[type=radio][name="categorias"]:checked').val();
    var categoria = sl_categoria.val();
    var registro_patronal = sl_registro_patronal.val();

    var data =  {"seccion": seccion, "categoria": categoria, "registro_patronal": registro_patronal};
    var link = $(this).attr('href');

    $.ajax({
        url: link,
        type: 'POST',
        data: data,
        success: function(response) {
            console.log(response);
        },
        error: function(xhr, status, error) {
            console.error('Error en la solicitud:', status, error);
        }
    });
});
*/



