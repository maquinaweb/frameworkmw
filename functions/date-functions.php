<?php

/**
 * Formata data com base no formato fornecido
 *
 * @param string $date: Data a ser formatada
 * @param string $formato: Formato a ser usado para formatar a data
 */
function format($date, $format = 'd/m/Y') {
    if($date == null)
        return '';
    else {
        $date = str_replace('/', '-', $date);
        $date = strtotime($date);
        return date($format, $date);
    }
}

