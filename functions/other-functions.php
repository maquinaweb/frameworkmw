<?php

/**
 * Returna informação de localização baseados no IP fornecido
 *
 * @param string $ip: IP relacionado a localização procurada
 * @param string $format: Formato em que as informações devem retornar
 */
function get_location_by_ip($ip, $format) {
    
    //Url para pegar localização por ip
    $url = "freegeoip.net";
    
    //Cria uma chamada para a url
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, "$url/$format/$ip");
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    //Resposta
    $response = curl_exec($curl);
    
    curl_close($curl);
    
    if(!$response) {
        return false;
    }

    return $response;
}

function upload($file, $caminho, $extensoes) {
    if (isset($file)) {
// Recuperando informações do arquivo
        $nome = $file['name'];
        $temp = $file['tmp_name'];
// Verifica se a extensão é permitida
        if (!in_array(strtolower(strrchr($nome, ".")), $extensoes)) {
            $erro = 'Extensão inválida';
        }
// Se não houver erro
        if (!isset($erro)) {
            if (!file_exists(ABSPATH . $caminho))
                mkdir(ABSPATH . $caminho, 0777, true);
// Gerando um nome aleatório para o arquivo
            $nomeAleatorio = md5(uniqid(time())) . strrchr($nome, ".");
// Movendo arquivo para servidor
            if (!move_uploaded_file($temp, ABSPATH . $caminho . $nomeAleatorio)) {
                $erro = 'Não foi possível anexar o arquivo';
            }
        }
        if (isset($erro)) {
            $retorno['erro'] = $erro;
        } else {
            $retorno['arquivo'] = $nomeAleatorio;
        }

        return $retorno;
    }
}

function request($string = '', $tipo = 'get', $var = '', $funcao = null) {
    if ($tipo == 'get') {
        $dado = filter_input(INPUT_GET, $var);
    }
    if ($tipo == 'post') {
        $dado = filter_input(INPUT_POST, $var);
    }
    if ($funcao == 'date') {
        return implode("-", array_reverse(explode("/", $dado)));
    } 
    elseif($funcao=='url'){
        return $string.'?'.$var.'='.filter_input(INPUT_GET, $var);
    }
    else {
        return $dado;
    }
}

function fcheckbox($array = null, $key = null) {
    if (isset($array[$key]) && !empty($array[$key])) {
// Retorna o valor da chave
        return $array[$key];
    }

// Retorna nulo por padrão
    return 0;
}

function checatamanhopost() {
    if (isset($_SERVER['REQUEST_METHOD']) and ($_SERVER['REQUEST_METHOD'] === 'POST') and
            isset($_SERVER['CONTENT_LENGTH']) and (empty($_POST))) {
        
        $max_post_size = ini_get('post_max_size');
        $content_length = $_SERVER['CONTENT_LENGTH'] / 1024 / 1024;
        if ($content_length > $max_post_size ) {
            return array(
                'maximo' => $max_post_size
            );
        }
    }
    
    return false;
}