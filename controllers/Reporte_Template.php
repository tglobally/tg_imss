<?php

namespace tglobally\tg_imss\controllers;

use PhpOffice\PhpSpreadsheet\Style\Fill;

class Reporte_Template
{
    const REPORTE_GENERAL = [
        "A:M" => [
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center',
            ]
        ],
        "C" => [
            'alignment' => [
                'horizontal' => 'left',
                'vertical' => 'center',
            ]
        ],
        "F:I" => [
            'numberFormat' => [
                'formatCode' => "$#,##0.00;-$#,##0.00",
            ],
        ],
        "A1:A3" => [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
            ],
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('rgb' => '0070C0')
            ),
            'alignment' => [
                'horizontal' => 'right',
                'vertical' => 'center',
            ]
        ],
        "B1:B3" => [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '000000'],
                'size' => 11,
            ],
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center',
            ]
        ],
        "E1:E3" => [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '000000'],
                'size' => 11,
            ],
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center',
            ]
        ],


        "A4:M4" => [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
            ],
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('rgb' => '0070C0')
            ),
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center',
            ]
        ],

    ];

}