class Datatable {
    constructor(identificador, columns) {
        this.identificador = identificador;
        this.url = this.identificador.replace('#', '');
        this.url = this.url.replace('.', '');
        this.url = get_url(this.url, "data_ajax", {});
        this.columns = columns;
    }

    init_datatable() {
        this.datatable = $(this.identificador).DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: this.url,
            columns: this.columns,
        });
    }

    get instance() {
        return this.datatable;
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
datatable_nominas.init_datatable();


let sl_categoria = $("#com_cliente_id");

$('input[type=radio][name=categorias]').change(function () {
    var seccion = this.value;
    var accion = $(this).data("accion");
    var titulo = $(this).data("titulo");

    get_data2(seccion, accion, {}, sl_categoria);
    $('label[for=com_cliente_id]').html(titulo);

});