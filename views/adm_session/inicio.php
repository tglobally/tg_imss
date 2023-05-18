<?php /** @var \controllers\controlador_adm_session $controlador */

use config\views;
$url_assets = (new views())->url_assets;
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="cont_text_inicio">
                <h1 class="h-side-title page-title page-title-big text-color-primary">Hola, <?php echo $controlador->datos_session_usuario['adm_usuario_user']; ?></h1>
                <h1 class="h-side-title page-title text-color-primary">Da click en la sección que deseas utilizar</h1>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="col-sm-2">
                <a href="<?php echo $controlador->link_lista_im_tipo_salario_minimo; ?>">
                    <div class="cont_imagen_accion">
                        <img src="<?php echo $url_assets; ?>img/inicio/imagen_2.jpg">
                    </div>
                    <div class="cont_text_accion">
                        <h4 class="text_seccion">Tipo Salario Minimo</h4>
                        <h4 class="text_accion">Catalogo</h4>
                    </div>
                </a>
            </div>

            <div class="col-sm-2">
                <a href="<?php echo $controlador->link_lista_im_salario_minimo; ?>">
                    <div class="cont_imagen_accion">
                        <img src="<?php echo $url_assets; ?>img/inicio/imagen_2.jpg">
                    </div>
                    <div class="cont_text_accion">
                        <h4 class="text_seccion">Salario Minimo</h4>
                        <h4 class="text_accion">Catalogo</h4>
                    </div>
                </a>
            </div>

            <div class="col-sm-2">
                <a href="<?php echo $controlador->link_lista_im_registro_patronal; ?>">
                    <div class="cont_imagen_accion">
                        <img src="<?php echo $url_assets; ?>img/inicio/imagen_2.jpg">
                    </div>
                    <div class="cont_text_accion">
                        <h4 class="text_seccion">Registro Patronal</h4>
                        <h4 class="text_accion">Catalogo</h4>
                    </div>
                </a>
            </div>
            <div class="col-sm-2">
                <a href="<?php echo $controlador->link_lista_im_clase_riesgo; ?>">
                    <div class="cont_imagen_accion">
                        <img src="<?php echo $url_assets; ?>img/inicio/imagen_2.jpg">
                    </div>
                    <div class="cont_text_accion">
                        <h4 class="text_seccion">Clases de riesgo</h4>
                        <h4 class="text_accion">Catalogo</h4>
                    </div>
                </a>
            </div>
            <div class="col-sm-2">
                <a href="<?php echo $controlador->link_lista_im_movimiento; ?>">
                    <div class="cont_imagen_accion">
                        <img src="<?php echo $url_assets; ?>img/inicio/imagen_2.jpg">
                    </div>
                    <div class="cont_text_accion">
                        <h4 class="text_seccion">Movimientos Empleados</h4>
                        <h4 class="text_accion">Catalogo</h4>
                    </div>
                </a>
            </div>
            <div class="col-sm-2">
                <a href="<?php echo $controlador->link_lista_im_tipo_movimiento; ?>">
                    <div class="cont_imagen_accion">
                        <img src="<?php echo $url_assets; ?>img/inicio/imagen_2.jpg">
                    </div>
                    <div class="cont_text_accion">
                        <h4 class="text_seccion">Tipos de Movimiento</h4>
                        <h4 class="text_accion">Catalogo</h4>
                    </div>
                </a>
            </div>
            <div class="col-sm-2">
                <a href="<?php echo $controlador->link_lista_im_codigo_clase; ?>">
                    <div class="cont_imagen_accion">
                        <img src="<?php echo $url_assets; ?>img/inicio/imagen_2.jpg">
                    </div>
                    <div class="cont_text_accion">
                        <h4 class="text_seccion">Codigos Clase</h4>
                        <h4 class="text_accion">Catalogo</h4>
                    </div>
                </a>
            </div>
            <!--<div class="col-sm-2">
                <a href="<?php echo $controlador->link_lista_im_conf_pres_empresa; ?>">
                    <div class="cont_imagen_accion">
                        <img src="<?php echo $url_assets; ?>img/inicio/imagen_2.jpg">
                    </div>
                    <div class="cont_text_accion">
                        <h4 class="text_seccion">Configuracion Prestaciones Empresa</h4>
                        <h4 class="text_accion">Catalogo</h4>
                    </div>
                </a>
            </div>-->
            <div class="col-sm-2">
                <a href="<?php echo $controlador->link_lista_im_uma; ?>">
                    <div class="cont_imagen_accion">
                        <img src="<?php echo $url_assets; ?>img/inicio/imagen_2.jpg">
                    </div>
                    <div class="cont_text_accion">
                        <h4 class="text_seccion">Uma</h4>
                        <h4 class="text_accion">Catalogo</h4>
                    </div>
                </a>
            </div>
            <div class="col-sm-2">
                <a href="<?php echo $controlador->link_lista_im_rcv; ?>">
                    <div class="cont_imagen_accion">
                        <img src="<?php echo $url_assets; ?>img/inicio/imagen_2.jpg">
                    </div>
                    <div class="cont_text_accion">
                        <h4 class="text_seccion">RCV</h4>
                        <h4 class="text_accion">Catalogo</h4>
                    </div>
                </a>
            </div>

            <div class="col-sm-2">
                <a href="<?php echo $controlador->link_lista_org_empresa; ?>">
                    <div class="cont_imagen_accion">
                        <img src="<?php echo $url_assets; ?>img/inicio/imagen_2.jpg">
                    </div>
                    <div class="cont_text_accion">
                        <h4 class="text_seccion">Empresas</h4>
                        <h4 class="text_accion">Catalogo</h4>
                    </div>
                </a>
            </div>


        </div>
    </div>
</div>