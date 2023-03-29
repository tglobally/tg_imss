<?php
/** @var string $url_template */
use config\views;

$ruta_template_base = (new views())->ruta_template_base;
include $ruta_template_base.'assets/css/_base_css.php';

?>

<style>

    .modal-header {
        display: flex !important;
        justify-content: space-around !important;
    }

    .modal-title {
        width: 95%;
        font-weight: bold;
        font-size: 15px;
    }

    .close {
        width: 5%;
    }


    .color-secondary{
        background-color: white !important;
    }


</style>








