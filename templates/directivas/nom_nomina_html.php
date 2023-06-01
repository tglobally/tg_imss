<?php

namespace html;

use gamboamartin\errores\errores;
use gamboamartin\im_registro_patronal\models\im_clase_riesgo;
use PDO;
use stdClass;
use tglobally\tg_imss\controllers\controlador_nom_nomina;

class nom_nomina_html extends base_nominas
{
    private function asigna_inputs(controlador_nom_nomina $controler, stdClass $inputs): array|stdClass
    {
        $controler->inputs = new stdClass();
        $controler->inputs->select = new stdClass();
        $controler->inputs->select->tg_alianza_id = $inputs->selects->tg_alianza_id;

        return $controler->inputs;
    }

    public function genera_inputs_alta(controlador_nom_nomina $controler, PDO $link,
                                       stdClass               $params = new stdClass()): array|stdClass
    {
        $inputs = $this->init_alta_html(link: $link, params: $params);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar inputs', data: $inputs);

        }

        $inputs_asignados = $this->asigna_inputs(controler: $controler, inputs: $inputs);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al asignar inputs', data: $inputs_asignados);
        }

        return $inputs_asignados;
    }

    private function init_alta_html(PDO $link, stdClass $params = new stdClass()): array|stdClass
    {
        $selects = $this->selects(link: $link, params: $params);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar selects', data: $selects);
        }

        $alta_inputs = new stdClass();
        $alta_inputs->selects = $selects;

        return $alta_inputs;
    }

    private function selects(PDO $link, stdClass $params = new stdClass()): array|stdClass
    {
        $selects = new stdClass();

        $cols_im_org_puesto_id = $params->tg_alianza->cols ?? 12;

        $select = $this->select_tg_alianza(
            cols: $cols_im_org_puesto_id, con_registros: true, id_selected: -1, link: $link, disabled: false);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        $selects->tg_alianza_id = $select;

        return $selects;
    }

    public function select_tg_alianza(int  $cols, bool $con_registros, int|null $id_selected,
                                      PDO  $link, bool $disabled = false, string $label = "Alianza:",
                                      bool $required = false): array|string
    {
        if (is_null($id_selected)) {
            $id_selected = -1;
        }

        $modelo = new im_clase_riesgo($link);

        $select = $this->select_catalogo(cols: $cols, con_registros: $con_registros, id_selected: $id_selected,
            modelo: $modelo, disabled: $disabled, label: $label, required: $required);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }


}
