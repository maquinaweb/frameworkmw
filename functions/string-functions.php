<?php

/**
 * Suitable for Template class: similar to str_replace, but using string in first param
 * @see str_replace
 * @param string $str
 * @param string $search
 * @param string $replace
 * @return mixed
 */
function replace($str, $search, $replace) {
//echo $str. ' - '. $search.' - '. $replace;
//die();
    return str_replace($search, $replace, $str);
}

/**
 * Comparar dois valores e retorna <b>$true</b> se a comparação for 
 * verdadeira e <b>$false</b> se a comparação for falsa.
 *
 * @param string  $string: String inicial para comparação.
 * @param string  $compare: Valor para comparação. Caso seja um regex, verifica
 * se <b>$string</b> se encaixa no padrão.
 * @param mixed  $true: Valor retornado se comparação for verdadeira. Caso 
 * seja passado com o valor '__this', retorna <b>$string</b>.
 * @param mixed  $false: Valor retornado se comparação for falsa. Caso 
 * seja passado com o valor '__this', retorna <b>$string</b>.
 * @return mixed  Retorna o valor correspondente ao resultado da comparação.
 */
function siif($string, $compare, $true, $false) {
    $is_regex = (strlen($compare) > 2 and $compare[0] == '/' and $compare[strlen($compare) - 1] == '/');
    
    if ($is_regex) {
        $result = preg_match($compare, $string);
    }
    else {
        $result = (strval($string) == $compare);
    }
    
    if ($result) 
        return (($true == "__this") ? $string : $true);
    else
        return (($false == "__this") ? $string : $false);
}

/**
 * Verifica se o valor <b>$search</b> está contido em <b>$string</b>. Retorna 
 * <b>$true</b> contém e <b>$false</b> não contém.
 *
 * @param string  $string: String base para busca.
 * @param string  $search: Valor para busca.
 * @param mixed  $true: Valor retornado se busca for verdadeira. Caso seja passado
 * com o valor '__this', retorna <b>$string</b>.
 * @param mixed  $false: Valor retornado se busca for falsa. Caso seja passado
 * com o valor '__this', retorna <b>$string</b>.
 * @return mixed  Retorna o valor correspondente ao resultado da busca.
 */
function ciif($string, $search, $true, $false) {
    if (strpos($string, $search) !== false)
        return (($true == "__this") ? $string : $true);
    else
        return (($false == "__this") ? $string : $false);
}

/**
 * Verifica se o valor <b>$string</b> é vazio. Retorna <b>$true</b> se vazio e
 * <b>$false</b> se não vazio.
 *
 * @param string  $string: String base para comparação.
 * @param mixed  $true: Valor retornado se vazio. Caso seja passado com o valor
 * '__this', retorna <b>$string</b>.
 * @param mixed  $false: Valor retornado não vazio. Caso seja passado com o
 * valor '__this', retorna <b>$string</b>.
 * @return mixed  Retorna o valor correspondente ao resultado da comparação.
 */
function eiif($string, $true, $false) {
    if (empty($string))
        return (($true == "__this") ? $string : $true);
    else
        return (($false == "__this") ? $string : $false);
}

/**
 * Sanitiza url para eliminar caracteres inválidos.
 *
 * @param string  $url: Url para ser sanitizado.
 * @return string  Retorna o URL sanitizado.
 */
function sanitize_url($url)
{
    // Swap out Non "Letters" with a -
    $url = preg_replace('/[^\\pL\d]+/u', '-', $url); 

    // Trim out extra -'s
    $url = trim($url, '-');

    // Convert letters that we have left to the closest ASCII representation
    $url = iconv('utf-8', 'us-ascii//TRANSLIT', $url);

    // Make text lowercase
    $url = strtolower($url);

    // Strip out anything we haven't been able to convert
    $url = preg_replace('/[^-\w]+/', '', $url);

    return $url;
}
