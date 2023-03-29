let url = getAbsolutePath();

let session_id = getParameterByName('session_id');

let btn_editar_empleado = $("#editar_empleado");
let sl_em_empleado = $("#em_empleado_id");
let sl_im_tipo_movimiento = $('#im_tipo_movimiento_id');
let txt_salario_diario = $('#salario_diario');
let txt_factor_integracion = $('#factor_integracion');
let txt_salario_diario_integrado = $('#salario_diario_integrado');
let txt_fecha_inicio_rel_laboral = $('#fecha');

sl_im_tipo_movimiento.change(function (event) {
    let tipo_mov = $("#im_tipo_movimiento_id option:selected").text();

    if (tipo_mov === 'BAJA' || tipo_mov === 'REINGRESO') {
        txt_salario_diario.prop('disabled', true);
        txt_salario_diario_integrado.prop('disabled', true);
    }
    else{
        txt_salario_diario.prop('disabled', false);
        txt_salario_diario_integrado.prop('disabled', false);
    }
});

sl_em_empleado.change(function () {
    let selected = $(this).find('option:selected').val();

    btn_editar_empleado.prop("disabled", true);

    if (selected !== ""){
        btn_editar_empleado.removeAttr('disabled');

        let url = get_url("em_empleado", "get_empleado", {em_empleado_id: selected});

        get_data(url, function (data) {

            if (data.n_registros <= 0) {
                $('#nss').val("");
                $('#nombre').val("");
                $('#ap').val("");
                $('#am').val("");

                alert(`El empleado con ID: ${sselected} no existe`);
            } else {
                let datos = data.registros[0];

                $('#nss').val(datos.em_empleado_nss);
                $('#nombre').val(datos.em_empleado_nombre);
                $('#ap').val(datos.em_empleado_ap);
                $('#am').val(datos.em_empleado_am);
            }
        });
    }

});

txt_salario_diario.change(function (){
    let salario_diario = $(this).val();
    let factor = txt_factor_integracion.val();

    let res = salario_diario * factor;
    txt_salario_diario_integrado.val(res.toFixed(2));
});

$('#em_empleado_update').submit(function (e) {
    e.preventDefault();

    let nss = $('#nss').val();
    let nombre = $('#nombre').val();
    let ap = $('#ap').val();
    let am = $('#am').val();

    if (nss === '' || nombre === '' || ap === '') {
        alert(`Datos incompletos`);
        return false;
    }

    let selected = sl_em_empleado.find('option:selected').val();

    let url = get_url("em_empleado", "modifica_bd", {registro_id: selected});

    if (selected !== "") {
        $.ajax({
            url: url,
            type: 'POST',
            async: true,
            data: $('#em_empleado_update').serialize(),
            success: function (response) {

                alert(response.mensaje);
            },
            error: function (error) {
                console.log(error)
            }
        });


    }

})

let getData = async (url, acciones) => {
    fetch(url)
        .then(response => response.json())
        .then(data => acciones(data))
        .catch(err => {
            alert(err.message);
            console.error("ERROR: ", err.message)
        });
}

