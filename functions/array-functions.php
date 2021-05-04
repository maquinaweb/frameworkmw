<?php

/**
 * Verifica chaves de arrays
 *
 * Verifica se a chave existe no array e se ela tem algum valor.
 * Obs.: Essa função está no escopo global, pois, vamos precisar muito da mesma.
 *
 * @param array  $array O array
 * @param string $key   A chave do array
 * @return string|null  O valor da chave do array ou nulo
 */
function chk_array($array, $key) {
    //return $array[$key] ?? null;
// Verifica se a chave existe no array
    if (isset($array[$key]) && (!empty($array[$key]) || $array[$key]===0 || $array[$key]==='0')) {
// Retorna o valor da chave
        return $array[$key];
    }

// Retorna nulo por padrão
    return null;
}

/**
 * Criar uma lista em HTML com os conteúdos de um array
 *
 * @param array  $array: Array para conversão
 * @return string  String HTML com uma lista representando o array
 */
function array_to_html_list($array) {
    $html = '';
    foreach ($array as $key => $value) {
        $key = htmlentities($key);
        if (is_array($value)) {
            $html .= "<li><b>$key:</b><ul>";
            $html .= array_to_html_list($value);
            $html .= "</ul></li>";
        }
        else if(is_scalar($value)){
            $value = htmlentities($value);
            $html .= "<li><b>$key:</b> $value </li>";
        }
    }
    return $html;
}

/**
 * Remove várias variáveis de um array
 *
 * @param array $array: Array para se remover variáveis
 * $param mixed $unset: Chaves que devem ser retiradas de um array.
 */
function unset_many($array, $unset) {
    
    if (!is_array($unset)) {
        $unset = explode(',', $unset);
    }
    
    foreach ($unset as $unset_key) {
        if (isset($array[$unset_key])) {
            unset($array[$unset_key]);
        }
    }
    
    return $array;
    
}

/**
 * Semelhante a função "explode" mas com parametros invertidos
 *
 * @param string $string: String para dividir
 * @param string $delimiter: Chave delimitadora
 */
function array_explode($string, $delimiter) {
    
    return explode($delimiter, $array);
    
}

/**
 * Semelhante a função "implode" mas com parametros invertidos
 *
 * @param array $array: Array para juntar
 * @param string $delimiter: Chave delimitadora
 */
function array_implode($array, $delimiter) {
    
    return implode($delimiter, $array);
    
}

/**
 * Procura por um valor do array usando uma função definida pelo usuário
 * Retorna a chave correspondente se encontrado ou <b>FALSE</b>
 *
 * @param array $array: Array para pesquisa
 * @param function $callback: Função de pesquisa
 */
function array_find($array, $callback) {
    
    foreach ($array as $key => $value) {
        if($callback($value)) {
            return $key;
        }
    }
    
    return FALSE;
}

