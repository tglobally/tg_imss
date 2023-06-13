<?php

namespace html;

use gamboamartin\comercial\models\com_cliente;
use gamboamartin\empleado\models\em_registro_patronal;
use gamboamartin\errores\errores;
use PDO;
use stdClass;
use tglobally\tg_imss\controllers\controlador_nom_nomina;

class nom_nomina_html extends base_nominas
{
    private function asigna_inputs(controlador_nom_nomina $controler, stdClass $inputs): array|stdClass
    {
        $controler->inputs = new stdClass();
        $controler->inputs->select = new stdClass();
        $controler->inputs->text = new stdClass();
        $controler->inputs->select->filtro_categoria = $inputs->selects->tg_alianza_id;
        $controler->inputs->select->em_registro_patronal_id = $inputs->selects->em_registro_patronal_id;
        $controler->inputs->text->fecha_inicio = $inputs->text->fecha_inicio;
        $controler->inputs->text->fecha_final = $inputs->text->fecha_final;
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

        $text = $this->text(row_upd: new stdClass());
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar selects', data: $selects);
        }

        $alta_inputs = new stdClass();
        $alta_inputs->selects = $selects;
        $alta_inputs->text = $text;

        return $alta_inputs;
    }

    private function selects(PDO $link, stdClass $params = new stdClass()): array|stdClass
    {
        $selects = new stdClass();

        $select = $this->select_tg_alianza(
            cols: 12, con_registros: true, id_selected: -1, link: $link, disabled: false);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        $selects->tg_alianza_id = $select;

        $select = $this->select_em_registro_patronal(
            cols: 12, con_registros: true, id_selected: -1, link: $link, disabled: false);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        $selects->em_registro_patronal_id = $select;

        return $selects;
    }

    public function select_tg_alianza(int  $cols, bool $con_registros, int|null $id_selected,
                                      PDO  $link, bool $disabled = false, string $label = "Cliente:",
                                      bool $required = false): array|string
    {
        if (is_null($id_selected)) {
            $id_selected = -1;
        }

        $modelo = new com_cliente($link);

        $select = $this->select_catalogo(cols: $cols, con_registros: $con_registros, id_selected: $id_selected,
            modelo: $modelo, disabled: $disabled, label: $label, name: "categoria_id", required: $required);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }

    public function select_em_registro_patronal(int  $cols, bool $con_registros, int|null $id_selected,
                                      PDO  $link, bool $disabled = false, string $label = "Registro Patronal:",
                                      bool $required = false): array|string
    {
        if (is_null($id_selected)) {
            $id_selected = -1;
        }

        $modelo = new em_registro_patronal($link);

        $select = $this->select_catalogo(cols: $cols, con_registros: $con_registros, id_selected: $id_selected,
            modelo: $modelo, disabled: $disabled, label: $label, required: $required);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }

    private function text(stdClass $row_upd):
    array|stdClass
    {
        $texts = new stdClass();

        $fecha_inicio = $this->input_fecha_inicio(cols: 12, row_upd: $row_upd, value_vacio: false);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar input', data: $fecha_inicio);
        }
        $texts->fecha_inicio = $fecha_inicio;

        $fecha_final = $this->input_fecha_final(cols: 12, row_upd: $row_upd, value_vacio: false);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar input', data: $fecha_final);
        }
        $texts->fecha_final = $fecha_final;

        return $texts;
    }

    public function input_fecha_inicio(int $cols, stdClass $row_upd, bool $value_vacio, bool $disabled = false):
    array|string
    {
        $valida = $this->directivas->valida_cols(cols: $cols);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al validar columnas', data: $valida);
        }

        $html = $this->directivas->fecha(disabled: $disabled, name: 'fecha_inicio', place_holder: 'Fecha Inicio', required: false,
            row_upd: $row_upd, value_vacio: $value_vacio);

        $div = $this->directivas->html->div_group(cols: $cols, html: $html);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;
    }

    public function input_fecha_final(int $cols, stdClass $row_upd, bool $value_vacio, bool $disabled = false):
    array|string
    {
        $valida = $this->directivas->valida_cols(cols: $cols);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al validar columnas', data: $valida);
        }

        $html = $this->directivas->fecha(disabled: $disabled, name: 'fecha_final', place_holder: 'Fecha Final', required: false,
            row_upd: $row_upd, value_vacio: $value_vacio);

        $div = $this->directivas->html->div_group(cols: $cols, html: $html);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;
    }


}
