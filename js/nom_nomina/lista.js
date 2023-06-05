class Datatable {
    constructor(identificador, columns) {
        this.identificador = identificador;
        this.url = this.identificador.replace('#', '');
        this.url = this.url.replace('.', '');
        this.url = get_url(this.url, "data_ajax", {});
        this.columns = columns;
        this.extra_columns = [];
        this.filtro = [];
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
                        filtro: self.filtro
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

    filter_isempty() {
        return this.filtro.length === 0
    }

    filter_clear() {
        this.filtro = [];
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
        title: 'Empresa',
        data: 'org_empresa_descripcion'
    }

]


const datatable_nominas = new Datatable("#nom_nomina", columns);
datatable_nominas.add_columns(["em_empleado_nombres", "em_empleado_ap", "em_empleado_am"]);

datatable_nominas.init_datatable();


let sl_categoria = $("#com_cliente_id");

sl_categoria.change(function () {

    if (this.value !== "" && this.value != -1) {
        var radio = $('[type=radio][name="categorias"]:checked');

        datatable_nominas.add_filter({
            "key": radio.val() + ".id",
            "valor": this.value,
        });
    } else {
        datatable_nominas.filter_clear();
    }
    datatable_nominas.draw;
});

$('input[type=radio][name=categorias]').change(function () {
    var seccion = this.value;
    var accion = $(this).data("accion");
    var titulo = $(this).data("titulo");

    get_data2(seccion, accion, {}, sl_categoria);
    $('label[for=com_cliente_id]').html(titulo);

    datatable_nominas.filter_reset();
});