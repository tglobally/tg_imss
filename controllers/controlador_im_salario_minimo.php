<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace tglobally\tg_imss\controllers;
use gamboamartin\errores\errores;
use PDO;
use stdClass;
use tglobally\template_tg\html;
use tglobally\template_tg\menu_lateral;


class controlador_im_salario_minimo extends \gamboamartin\im_registro_patronal\controllers\controlador_im_salario_minimo {


    public function __construct(PDO $link, stdClass $paths_conf = new stdClass()){

        $html_base = new html();
        parent::__construct( link: $link, html: $html_base);
        $this->titulo_lista = 'Salario Mínimo';
        $this->seccion_titulo = "Salario Mínimo";
        $this->titulo_accion = "Listado de Salarios Mínimos";

        $acciones = $this->define_acciones_menu(acciones: array("alta" => $this->link_alta));
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al integrar acciones para el menu', data: $acciones);
            print_r($error);
            die('Error');
        }

        $keys_row_lista = $this->keys_rows_lista();
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al generar keys de lista',data:  $keys_row_lista);
            print_r($error);
            exit;
        }

        $this->keys_row_lista = $keys_row_lista;
        $this->total_items_sections = 1;

        $this->actions_number['lista']['item'] = 1;
        $this->actions_number['lista']['etiqueta'] = 'Salario minimo';

        $this->actions_number['alta']['item'] = 1;
        $this->actions_number['alta']['etiqueta'] = 'Salario minimo';


        $this->number_active = 1;

        if(isset($this->actions_number[$this->accion])){
            $this->number_active = $this->actions_number[$this->accion]['item'];
        }

        $menu_lateral = (new menu_lateral())->number_head(number_active: $this->number_active);
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al integrar include', data: $menu_lateral);
            print_r($error);
            exit;
        }
        $this->menu_lateral = $menu_lateral;
    }

    public function alta(bool $header, bool $ws = false): array|string
    {
        $r_alta = parent::alta($header, $ws);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar template',data:  $r_alta, header: $header,ws:$ws);
        }

        $this->titulo_accion = "Alta Salario Mínimo";

        return $r_alta;

    }

    public function modifica(bool $header, bool $ws = false): array|stdClass
    {

        $r_modifica = parent::modifica($header, $ws);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar template',data:  $r_modifica, header: $header,ws:$ws);
        }

        $this->titulo_accion = "Modifica Salario Mínimo";

        return $r_modifica;
    }

    private function keys_rows_lista(): array
    {

        $keys_row_lista = array();

        $keys = array('im_salario_minimo_id','im_salario_minimo_codigo','im_salario_minimo_codigo_bis',
            'im_salario_minimo_descripcion','im_salario_minimo_descripcion_select');

        foreach ($keys as $campo){
            $keys_row_lista = $this->key_row_lista_init(campo: $campo, keys_row_lista: $keys_row_lista);
            if(errores::$error){
                return $this->errores->error(mensaje: 'Error al inicializar key',data: $keys_row_lista);
            }
        }

        return $keys_row_lista;
    }

    private function key_row_lista_init(string $campo, array $keys_row_lista): array
    {
        $data = new stdClass();
        $data->campo = $campo;

        $campo = str_replace(array('im_salario_minimo', '_'), array('', ' '), $campo);
        $campo = ucfirst(strtolower($campo));

        $data->name_lista = $campo;
        $keys_row_lista[]= $data;
        return $keys_row_lista;
    }


}
