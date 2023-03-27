class Excel {
    constructor(contenido) {
        this.contenido = contenido;
    }

    header(){
        return this.contenido[0];
    }

    header_table(){
        const headers = this.contenido[0];
        const salida = [];

        headers.forEach(function (currentValue, index, array) {
            salida.push({ title: currentValue });
        });

        return salida;
    }

    rows(){
        return new Coleccion(this.contenido.slice(1, this.contenido.length));
    }
}

class Coleccion {
    constructor(rows) {
        this.rows = rows;
    }
}

var excel_input = document.getElementById("archivo");

excel_input.addEventListener('change', async function () {
    const contenido = await readXlsxFile(excel_input.files[0]);
    const excel = new Excel(contenido);

    if(!$('#datos-movimiento').length){
        $(".formulario").append("<table id='datos-movimiento' class='table table-striped datatable dataTable no-footer dtr-inline' style='width: 100% !important; ' >");
    }

    let table;

    if ( ! $.fn.DataTable.isDataTable( '#datos-movimiento' ) ) {
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
        $( "#datos-movimiento tbody tr" ).removeClass( "selected" )
        $(this).toggleClass('selected');
        $('#exampleModal').modal({ show:true });

    });

})



