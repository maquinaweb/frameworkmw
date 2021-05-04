<?php

//Caso não seja debug, ativa as funções de tratamento de erros e exceções
if (!DEBUG) {
    set_exception_handler('exception_handler');
    set_error_handler('error_handler');
    register_shutdown_function("fatal_handler");
}

/**
 * Função chamada automaticamente após uma exceção lançada e não capturada
 * 
 * A função faz um log da exceção ocorrida, informando dados da exceção e do 
 * contexto em que ela ocorreu
 *
 * @param Exception $exception: Exceção lançada
 */
function exception_handler($exception) {
    $array = array(
        'Code' => $exception->getCode(),
        'Message' => $exception->getMessage(),
        'File' => $exception->getFile(),
        'Line' => $exception->getLine(),
        'StackTrace' => $exception->getTraceAsString(),
        'GET' => $_GET,
        'POST' => $_POST,
        'SESSION' => $_SESSION,
        'SERVER' => array(
            'REQUEST_SCHEME' => $_SERVER['REQUEST_SCHEME'],
            'SERVER_NAME' => $_SERVER['SERVER_NAME'],
            'REQUEST_URI' => $_SERVER['REQUEST_URI']
        )
    );

    if (EMAIL_LOG) {
        $message = '<h3> Exception Handler </h3>';
        $message .= '<ul>';
        $message .= array_to_html_list($array);
        $message .= '</ul>';

        $email = new emailhelper('sistema', true, true);

        $email->addAddress(EMAIL_LOG);
        $email->setSubject('Exception Handler - ' . date('d/m/Y H:i:s'));
        $email->setFromName('Log - Easypublish');
        $email->setMessage($message);

        $email->send();
    } else {
        $dir = ABSPATH . '/log/' . date('Ymd');
        if (!file_exists($dir)) {
            mkdir($dir);
        }
        error_log(print_r($array, true) . PHP_EOL, 3, "$dir/exception.log");
    }
}

/**
 * Função chamada automaticamente após um erro no script
 * 
 * A função faz um log do erro ocorrido, informando dados do erro e do contexto
 * em que ele ocorreu
 *
 * @param int  $errno: Código do erro
 * @param string  $errstr: Mensagem do erro
 * @param string  $errfile: Arquivo em que o erro ocorreu
 * @param int  $errline: Número da linha em que o erro ocorreu
 * @param  array $errcontext: Array com o contexto do erro
 */
function error_handler($errno, $errstr, $errfile, $errline, $errcontext) {

    $array = array(
        'Code' => $errno,
        'Message' => $errstr,
        'File' => $errfile,
        'Line' => $errline,
        'GET' => $_GET,
        'POST' => $_POST,
        'SESSION' => $_SESSION,
        'SERVER' => array(
            'REQUEST_SCHEME' => $_SERVER['REQUEST_SCHEME'],
            'SERVER_NAME' => $_SERVER['SERVER_NAME'],
            'REQUEST_URI' => $_SERVER['REQUEST_URI']
        )
    );

    if (EMAIL_LOG) {
        $message = '<h3> Error Handler </h3>';
        $message .= '<ul>';
        $message .= array_to_html_list($array);
        $message .= '</ul>';

        $email = new emailhelper('sistema', true, true);

        $email->addAddress(EMAIL_LOG);
        $email->setSubject('Error Handler - ' . date('d/m/Y H:i:s'));
        $email->setFromName('Log - Easypublish');
        $email->setMessage($message);

        $email->send();
    } else {
        $dir = ABSPATH . '/log/' . date('Ymd');
        if (!file_exists($dir)) {
            mkdir($dir);
        }
        error_log(print_r($array, true) . PHP_EOL, 3, "$dir/error.log");
    }
}

/**
 * Função chamada automaticamente após a execução do script
 * 
 * Detecta se algum erro não recuperável occorreu. Se sim, log os dados do erro
 * e o contexto em que ocorreu
 *
 */
function fatal_handler() {
    $error = error_get_last();
    if ($error) {
        $array = array(
            'Code' => $error['type'],
            'Message' => $error['message'],
            'File' => $error['file'],
            'Line' => $error['line'],
            'GET' => $_GET,
            'POST' => $_POST,
            'SESSION' => $_SESSION,
            'SERVER' => array(
                'REQUEST_SCHEME' => $_SERVER['REQUEST_SCHEME'],
                'SERVER_NAME' => $_SERVER['SERVER_NAME'],
                'REQUEST_URI' => $_SERVER['REQUEST_URI']
            )
        );

        if (EMAIL_LOG) {
            $message = '<h3> Fatal Handler </h3>';
            $message .= '<ul>';
            $message .= array_to_html_list($array);
            $message .= '</ul>';

            $email = new emailhelper('sistema', true, true);

            $email->addAddress(EMAIL_LOG);
            $email->setSubject('Fatal Handler - ' . date('d/m/Y H:i:s'));
            $email->setFromName('Log - Easypublish');
            $email->setMessage($message);

            $email->send();
        } else {
            $dir = ABSPATH . '/log/' . date('Ymd');
            if (!file_exists($dir)) {
                mkdir($dir);
            }
            error_log(print_r($array, true) . PHP_EOL, 3, "$dir/fatal.log");
        }
    }
}

/**
 * Função para carregar automaticamente todas as classes padrão
 * Ver: http://php.net/manual/pt_BR/function.autoload.php.
 */
function load_models($class_name) {

    $class = explode('\\', $class_name);

    if (count($class) != 2) {
        return false;
    } else {
        $name = $class[1];
        $module = $class[0];

        return mwwork\mwload::load_class($name, $module, 'model');
    }
}

/**
 * Função auxiliar de template. Define, a partir da query string atual, se há
 * algum atributo para se ordenar e, caso haja, se ele está no momento em modo
 * ascendente ou descendente
 *
 * @param mixed  $value: Variável usada para compatibilidade com classe template.
 * É ignorado.
 * @param string  $field: Campo procurado.
 * @param mixed  $default: Valor padrão caso campo não seja encontrado.
 * @param bool  $nodirection: Informa de deve ser retornada a direção junto ao
 * valor ('asc' ou 'desc').
 * @return mixed Retorna o campo ou o valor informado em <b>$default</b>.
 */
function fsort($value = null, $field = null, $default = '', $nodirection = 0) {
    $order = explode('-', chk_array($_GET, 'order'));
    if (count($order) != 2 or $order[0] != $field) {
        return $default;
    } else if ($order[1] == 'asc') {
        if ($nodirection)
            return '-desc';
        else
            return $field . '-desc';
    }
    else {
        if ($nodirection)
            return '-asc';
        else
            return $field . '-asc';
    }
}

/**
 * Mostra uma página de erro personalizada
 *
 * @param int $error_code: Código do erro
 */
function show_error_page($error_code = '404') {

    //Verifica a existência do arquivo relacionado ao código informado
    //Se não existir, usa 404 como padrão
    $error_file = ABSPATH . '/error_pages/';
    $error_code = file_exists("$error_file/$error_code.html") ? $error_code : "404";

    $error_file = "$error_file/$error_code.html";

    //Envia o código de erro pelo header da resposta
    http_response_code($error_code);

    $page = new \Template($error_file);

    if ($page->exists('plugins')) {
        $page->plugins = HOME_URI . '/var/plugins/';
    }

    $page->show();
    die;
}

/**
 * Verifica a resposta do captcha
 *
 * @param int $error_code: Código do erro
 */
function captcha_response($chave) {
    require_once(ABSPATH . '/externo/recaptcha/src/ReCaptcha/ReCaptcha.php');
    require_once(ABSPATH . '/externo/recaptcha/src/ReCaptcha/RequestMethod.php');
    require_once(ABSPATH . '/externo/recaptcha/src/ReCaptcha/RequestParameters.php');
    require_once(ABSPATH . '/externo/recaptcha/src/ReCaptcha/Response.php');
    require_once(ABSPATH . '/externo/recaptcha/src/ReCaptcha/RequestMethod/Post.php');
    require_once(ABSPATH . '/externo/recaptcha/src/ReCaptcha/RequestMethod/Socket.php');
    require_once(ABSPATH . '/externo/recaptcha/src/ReCaptcha/RequestMethod/SocketPost.php');
    $recaptcha = new \ReCaptcha\ReCaptcha($chave);

    $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
    return $resp->isSuccess();
}

/**
 * Passa os dados vindos de POST do usuário para os devidos atributos do model;
 *
 * @param object $model: Model em que os atributos devem ser inseridos
 * @param array $ignore: Array de atributos para serem ignorados e não modificados
 */
function postmodel($model, $ignore = array()) {
    $columns = $model::table()->columns;
    foreach ($columns as $column) {
        if (isset($_POST[$column->name]) and $column->name != "id" and $column->name != "data_exclusao" and
                $column->name != "data_criacao" and $column->name != "data_alteracao") {
            if (!in_array($column->name, $ignore)) {
                if (strstr((string) $column->raw_type, 'date')) {
                    $data = implode("-", array_reverse(explode("/", filter_input(INPUT_POST, $column->name))));
                    if ($model->{$column->name} != $data)
                        $model->{$column->name} = $data;
                }
                else {
                    if ($model->{$column->name} != filter_input(INPUT_POST, $column->name))
                        $model->{$column->name} = filter_input(INPUT_POST, $column->name);
                }
            }
        }
        if ($column->name == 'data_criacao' and $model->is_new_record())
            $model->data_criacao = date('Y-m-d H:i:s');
        if ($column->name == 'data_alteracao')
            $model->data_alteracao = date('Y-m-d H:i:s');
    }
}

/**
 * Mapeia os atributos do model em variáveis do template
 *
 * @param object $model: Model com os atributos para serem mapeados
 * @param object $template: Template para receber os atributos
 * @param string $prefix: Prefixo usado para procurar as variáveis
 */
function modeltemplate($model, $template, $prefix) {
    foreach ($model->attributes() as $name => $value) {
        if ($template->exists($prefix . '_' . $name))
            $template->{$prefix . '_' . $name} = $model->$name;
    }
}

/**
 * Mapeia atributos de um objeto qualquer ao template
 *
 * @param object $object: Objeto com os atributos para serem mapeados
 * @param object $template: Template para receber os atributos
 * @param string $prefix: Prefixo usado para procurar as variáveis
 */
function objecttemplate($object, $template, $prefix, $recursive = false, $loop = 0, $limit = 5) {
    foreach ($object as $name => $value) {
        if (is_scalar($value)) {
            if ($template->exists($prefix . '_' . $name)) {
                $template->{$prefix . '_' . $name} = $object->$name;
            }
        }
        if ($recursive and is_object($value) and $loop < $limit) {
            objecttemplate($object->$name, $template, "$prefix" . "_$name", $recursive, $loop + 1, $limit);
        }
    }
}

/**
 * Acessa superglobal <b>$_GET</b>
 *
 * @param string $key: Chave a se procurar na superglobal <b>$_GET</b>. Caso 
 * não seja informado, retorna um array com todas os valores.
 */
function get($key = null) {
    if (empty($key)) {
        $get = $_GET;
        unset($get['path']);
        unset($get['mensagem']);
        unset($get['erro']);
        unset($get['dir']);
        unset($get['ordem']);
        return $get;
    } else {
        return filter_input(INPUT_GET, $key);
    }
}

/**
 * Insere um script de logo junto a requisição do cliente
 *
 * @param mixed $data: Dado a ser logado pelo navegador
 */
function console_log($data) {
    echo '<script>';
    echo 'console.log(' . json_encode($data) . ')';
    echo '</script>';
}

/**
 * Função que simula um array global e persistente entre usuários
 *
 * @param string  $key: Chave do array.
 * É ignorado.
 * @param mixes  $value: Valor da chave em questão
 * @param string  $set: Modo de uso
 */
function globals($key = null, $value = null, $mode = 'set') {
    if (($mode == 'set' or $mode == 'unset') and $key === null) {
        $mode = 'get';
    }

    if (!file_exists(ABSPATH . "/var/globals.data")) {
        file_put_contents(ABSPATH . "/var/globals.data", "");
    }

    if (filesize(ABSPATH . "/var/globals.data") == 0) {
        $_file = array();
    } else {
        $_file = file_get_contents(ABSPATH . "/var/globals.data");
        $_file = unserialize($_file);
    }

    switch ($mode) {
        case 'set':
            $_file[$key] = $value;
            file_put_contents(ABSPATH . "/var/globals.data", serialize($_file));
            break;

        case 'get':
            if ($key === null) {
                return $_file;
            } else if (isset($_file[$key])) {
                return $_file[$key];
            } else {
                return null;
            }
            break;

        case 'unset':
            if (isset($_file[$key])) {
                unset($_file[$key]);
            }
            file_put_contents(ABSPATH . "/var/globals.data", serialize($_file));
            break;
    }
}

/**
 * Função que abre uma url e trás uma resposta
 *
 * @param string  $method: string 'post' ou 'get'.
 * É ignorado.
 * @param string  $furl: Url onde sera enviado a requisição
 * @param string  $data: dados a serem enviados no post
 * @param array $header: cabeçalho a ser enviado na requisição
 */
function openurl($method = 'post', $furl = null, $data = null, $header = array()) {
    //criando o recurso cURL 
    $cr = curl_init();
    //definindo a url de busca 
    curl_setopt($cr, CURLOPT_URL, $furl);
    //definindo a url de busca 
    curl_setopt($cr, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($cr, CURLOPT_HTTPHEADER, $header);
    if ($method == 'post') {
        //definino que o método de envio, será POST 
        curl_setopt($cr, CURLOPT_POST, TRUE);
        //definindo os dados que serão enviados 
        curl_setopt($cr, CURLOPT_POSTFIELDS, $data);
    }

    //definindo uma variável para receber o conteúdo da página... 
    $retorno = curl_exec($cr);

    if ($retorno === false) {
        throw new Exception(curl_error($cr), curl_errno($cr));
    }
    //fechando-o para liberação do sistema. 
    curl_close($cr);
    return $retorno;
}

function mask($val, $mask) {
    $maskared = '';
    $k = 0;
    for ($i = 0; $i <= strlen($mask) - 1; $i++) {
        if ($mask[$i] == '#') {
            if (isset($val[$k]))
                $maskared .= $val[$k++];
        }
        else {
            if (isset($mask[$i]))
                $maskared .= $mask[$i];
        }
    }
    return $maskared;
}

function checarcaptcha($chave) {
    if (isset($_POST['g-recaptcha-response'])) {
        $captcha_key_site = '6Lf-id4ZAAAAAOdSX-L2UImxWMHMUtRezmt8UozF';
        
        $result = my_file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$captcha_key_site."&response=".$_POST['g-recaptcha-response']."&remoteip=".$_SERVER['REMOTE_ADDR']);
        $resposta = json_decode($result, true);
        if ($resposta['success'] == true) {
            return true;
        } else {
            //echo "https://www.google.com/recaptcha/api/siteverify?secret=".$chave."&response=".$_POST['g-recaptcha-response']."&remoteip=".$_SERVER['REMOTE_ADDR'];
            //echo "<br>";
            //echo $result;
            //exit;
            return false;
        }
        
    }
    else {
        return false;
    }
}

function my_file_get_contents( $site_url ){
    $ch = curl_init();
    //$timeout = 5; // set to zero for no timeout
    curl_setopt ($ch, CURLOPT_URL, $site_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $response = curl_exec($ch);
    curl_close($ch);
    //$file_contents = json_decode($response, true);
    return $response;
}

require_once ABSPATH . '/functions/array-functions.php';
require_once ABSPATH . '/functions/string-functions.php';
require_once ABSPATH . '/functions/date-functions.php';
require_once ABSPATH . '/functions/other-functions.php';
