<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace tglobally\tg_imss\controllers;

use config\generales;
use gamboamartin\errores\errores;
use JsonException;
use models\adm_accion;
use models\adm_usuario;
use PDO;
use stdClass;

class controlador_adm_session extends \gamboamartin\controllers\controlador_adm_session {
    public bool $existe_msj = false;
    public string $include_menu = '';
    public string $mensaje_html = '';

    public string $link_lista_im_tipo_salario_minimo = '';
    public string $link_lista_im_salario_minimo = '';
    public string $link_alta_im_registro_patronal = '';
    public string $link_lista_im_registro_patronal = '';
    public string $link_alta_im_clase_riesgo = '';
    public string $link_lista_im_clase_riesgo = '';
    public string $link_lista_im_movimiento = '';
    public string $link_lista_im_tipo_movimiento = '';
    public string $link_lista_im_codigo_clase = '';
    public string $link_lista_im_conf_pres_empresa = '';
    public string $link_lista_im_uma = '';
    public string $link_lista_im_rcv = '';
    public string $link_lista_org_empresa = '';
    public string $link_lista_com_cliente = '';
    public string $link_lista_em_empleado = '';


    public function __construct(PDO $link, stdClass $paths_conf = new stdClass())
    {
        parent::__construct($link, $paths_conf);

        $this->titulo_pagina = "Inicio Sesion";
    }

    /**
     * Funcion de controlador donde se ejecutaran siempre que haya un acceso denegado
     * @param bool $header Si header es true cualquier error se mostrara en el html y cortara la ejecucion del sistema
     *              En false retornara un array si hay error y un string con formato html
     * @param bool $ws Si ws es true retornara el resultado en formato de json
     * @return array vacio siempre
     */
    public function denegado(bool $header, bool $ws = false): array
    {

        return array();

    }

    /**
     * Funcion de controlador donde se ejecutaran los elementos necesarios para poder mostrar el inicio en
     *      session/inicio
     *
     * @param bool $aplica_template Si aplica template buscara el header de la base
     *              No recomendado para vistas ajustadas como esta
     * @param bool $header Si header es true cualquier error se mostrara en el html y cortara la ejecucion del sistema
     *              En false retornara un array si hay error y un string con formato html
     * @param bool $ws Si ws es true retornara el resultado en formato de json
     * @return string|array string = html array = error
     * @throws JsonException si hay error en forma ws
     */
    public function inicio(bool $aplica_template = false, bool $header = true, bool $ws = false): string|array
    {

        $template =  parent::inicio($aplica_template, false); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->retorno_error(mensaje:  'Error al generar template',data: $template, header: $header, ws: $ws);
        }

        $hd = "index.php?seccion=im_tipo_salario_minimo&accion=lista&session_id=$this->session_id";
        $this->link_lista_im_tipo_salario_minimo= $hd;

        $hd = "index.php?seccion=im_salario_minimo&accion=lista&session_id=$this->session_id";
        $this->link_lista_im_salario_minimo= $hd;

        $hd = "index.php?seccion=im_registro_patronal&accion=alta&session_id=$this->session_id";
        $this->link_alta_im_registro_patronal= $hd;

        $hd = "index.php?seccion=im_registro_patronal&accion=lista&session_id=$this->session_id";
        $this->link_lista_im_registro_patronal = $hd;

        $hd = "index.php?seccion=im_clase_riesgo&accion=alta&session_id=$this->session_id";
        $this->link_alta_im_clase_riesgo= $hd;

        $hd = "index.php?seccion=im_clase_riesgo&accion=lista&session_id=$this->session_id";
        $this->link_lista_im_clase_riesgo = $hd;

        $hd = "index.php?seccion=im_movimiento&accion=lista&session_id=$this->session_id";
        $this->link_lista_im_movimiento = $hd;
        
        $hd = "index.php?seccion=im_tipo_movimiento&accion=lista&session_id=$this->session_id";
        $this->link_lista_im_tipo_movimiento = $hd;

        $hd = "index.php?seccion=im_codigo_clase&accion=lista&session_id=$this->session_id";
        $this->link_lista_im_codigo_clase = $hd;

        $hd = "index.php?seccion=im_conf_pres_empresa&accion=lista&session_id=$this->session_id";
        $this->link_lista_im_conf_pres_empresa = $hd;

        $hd = "index.php?seccion=im_uma&accion=lista&session_id=$this->session_id";
        $this->link_lista_im_uma = $hd;

        $hd = "index.php?seccion=im_rcv&accion=lista&session_id=$this->session_id";
        $this->link_lista_im_rcv = $hd;

        $hd = "index.php?seccion=org_empresa&accion=lista&session_id=$this->session_id";
        $this->link_lista_org_empresa = $hd;

        $hd = "index.php?seccion=com_cliente&accion=lista&session_id=$this->session_id";
        $this->link_lista_com_cliente = $hd;

        $hd = "index.php?seccion=em_empleado&accion=lista&session_id=$this->session_id";
        $this->link_lista_em_empleado = $hd;

        $this->include_menu = (new generales())->path_base;
        $this->include_menu .= 'templates/inicio.php';

        return $template;
    }

    /**
     * Funcion de controlador donde se ejecutaran los elementos necesarios para la asignacion de datos de logueo
     * @param bool $header Si header es true cualquier error se mostrara en el html y cortara la ejecucion del sistema
     *              En false retornara un array si hay error y un string con formato html
     * @param bool $ws Si ws es true retornara el resultado en formato de json
     * @param string $accion_header
     * @param string $seccion_header
     * @return array string = html array = error
     *
     */
    public function loguea(bool $header, bool $ws = false, string $accion_header = 'login', string $seccion_header = 'session'): array
    {
        $loguea = parent::loguea(header: true,accion_header:  $accion_header,
            seccion_header:  $seccion_header); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->retorno_error(mensaje:  'Error al loguear',data: $loguea, header: $header, ws: $ws);
        }
        return $loguea;
    }


    /**
     * Funcion de controlador donde se ejecutaran los elementos de session/login
     *
     * @param bool $header Si header es true cualquier error se mostrara en el html y cortara la ejecucion del sistema
     *              En false retornara un array si hay error y un string con formato html
     * @param bool $ws Si ws es true retornara el resultado en formato de json
     * @return string|array string = html array = error
     */
    public function login(bool $header = true, bool $ws = false): stdClass|array
    {
        $login = parent::login($header, $ws); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->retorno_error(mensaje:  'Error al generar template',data: $login, header: $header, ws: $ws);
        }

        $this->mensaje_html = '';
        if(isset($_GET['mensaje']) && $_GET['mensaje'] !==''){
            $mensaje = trim($_GET['mensaje']);
            if($mensaje !== ''){
                $this->mensaje_html = $mensaje;
                $this->existe_msj = true;
            }
        }

        $this->include_menu .= 'templates/login.php';

        return $login;

    }


}
