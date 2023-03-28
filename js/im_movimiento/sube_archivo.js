class Excel {
    constructor(contenido) {
        this.contenido = contenido;
    }

    header() {
        return this.contenido[0];
    }

    header_table() {
        const headers = this.contenido[0];
        const salida = [];

        headers.forEach(function (currentValue, index, array) {
            salida.push({title: currentValue});
        });

        return salida;
    }

    rows() {
        return new Coleccion(this.contenido.slice(1, this.contenido.length));
    }
}

class Coleccion {
    constructor(rows) {
        this.rows = rows;
    }
}

var excel_input = document.getElementById("archivo");

let em_empleado_id = "";

excel_input.addEventListener('change', async function () {
    const contenido = await readXlsxFile(excel_input.files[0]);

    const excel = new Excel(contenido);

    if (!$('#datos-movimiento').length) {
        $(".formulario").append("<table id='datos-movimiento' class='table table-striped datatable dataTable no-footer dtr-inline' style='width: 100% !important; ' >");
    }

    let table;

    if (!$.fn.DataTable.isDataTable('#datos-movimiento')) {
        table = $('#datos-movimiento').DataTable({
            searching: false,
            paging: false,
            ordering: false,
            info: false,
            columns: excel.header_table(),
        });
    } else {
        table = $('#datos-movimiento').DataTable();
    }

    table.clear();

    table.rows.add(excel.rows().rows).draw();
    table.columns.adjust().draw();

    $('#datos-movimiento').on('click', 'tbody tr', function (event) {
        $("#datos-movimiento tbody tr").removeClass("selected")
        $(this).toggleClass('selected');
        $('#exampleModal').modal({show: true});

        let selectedData = table.row(this).data();

        $('#nss').val(selectedData[3]);
        $('#nombre').val(selectedData[4]);
        $('#ap').val(selectedData[5]);
        $('#am').val(selectedData[6]);
        $('#salario_diario').val(selectedData[7]);
        $('#salario_diario_integrado').val(selectedData[9]);

        let url = get_url("em_empleado", "get_empleado", {em_empleado_nss: selectedData[3]});

        get_data(url, function (data) {

            if (data.n_registros <= 0) {
                $('#nss_preview').val("");
                $('#nombre_preview').val("");
                $('#ap_preview').val("");
                $('#am_preview').val("");
                $('#salario_diario_preview').val("");
                $('#salario_diario_integrado_preview').val("");

                em_empleado_id = "";

                alert(`El NSS ${selectedData[3]} no existe`);
            } else {
                let datos = data.registros[0];

                em_empleado_id = datos.em_empleado_id;

                $('#nss_preview').val(datos.em_empleado_nss);
                $('#nombre_preview').val(datos.em_empleado_nombre);
                $('#ap_preview').val(datos.em_empleado_ap);
                $('#am_preview').val(datos.em_empleado_am);
                $('#salario_diario_preview').val(datos.em_empleado_salario_diario);
                $('#salario_diario_integrado_preview').val(datos.em_empleado_salario_diario_integrado);
            }
        });

    });

})

$('#em_empleado_update').submit(function (e) {
    e.preventDefault();

    let nss = $('#nss').val();
    let nombre = $('#nombre').val();
    let ap = $('#ap').val();
    let am = $('#am').val();
    let salario_diario = $('#salario_diario').val();
    let salario_diario_integrado = $('#salario_diario_integrado').val();

    if (nss === '' || nombre === '' || ap === '' || salario_diario === '' || salario_diario_integrado === '') {
        alert(`Datos incompletos`);
        return false;
    }

    let url = get_url("em_empleado", "modifica_bd", {registro_id: em_empleado_id});

    if (em_empleado_id !== '') {
        $.ajax({
            url: url,
            type: 'POST',
            async: true,
            data: $('#em_empleado_update').serialize(),
            success: function (response) {

                let url_get_empleado = get_url("em_empleado", "get_empleado", {em_empleado_id: em_empleado_id});

                get_data(url_get_empleado, function (data) {

                    if (data.n_registros <= 0) {
                        $('#nss_preview').val("");
                        $('#nombre_preview').val("");
                        $('#ap_preview').val("");
                        $('#am_preview').val("");
                        $('#salario_diario_preview').val("");
                        $('#salario_diario_integrado_preview').val("");

                        em_empleado_id = "";
                    } else {
                        let datos = data.registros[0];

                        em_empleado_id = datos.em_empleado_id;

                        $('#nss_preview').val(datos.em_empleado_nss);
                        $('#nombre_preview').val(datos.em_empleado_nombre);
                        $('#ap_preview').val(datos.em_empleado_ap);
                        $('#am_preview').val(datos.em_empleado_am);
                        $('#salario_diario_preview').val(datos.em_empleado_salario_diario);
                        $('#salario_diario_integrado_preview').val(datos.em_empleado_salario_diario_integrado);
                    }

                });
                alert(response.mensaje);
            },
            error: function (error) {
                console.log(error)
            }
        });


    }


})



