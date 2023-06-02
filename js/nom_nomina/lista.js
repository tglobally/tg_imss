let sl_categoria = $("#com_cliente_id");

$('input[type=radio][name=categorias]').change(function() {
    var seccion = this.value;
    var accion = $(this).data("accion");
    var titulo = $(this).data("titulo");

    get_data2(seccion,accion,{}, sl_categoria);
    $('label[for=com_cliente_id]').html(titulo);

});