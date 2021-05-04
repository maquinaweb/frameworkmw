<?php

namespace mwwork;

class mwwork {

    private $rota;
    private $rotas;
    private $module;
    private $controller;
    private $namecontroller;
    private $action;
    private $parameters;

    public function __construct($rotas) {


        // Obtém os valores do controller, ação e parâmetros da URL.
        // E configura as propriedades da classe.
        // Verifica se o parâmetro path foi enviado
        if (isset($_GET['path'])) {

            // Captura o valor de $_GET['path']
            $path = $_GET['path'];
        } else {
            $path = '/';
        }
        $this->rota = $path;
        $this->rotas = $rotas ;
        $this->conect();
        $this->rota($path);


        return;
    }

// __construct

    /**
     * Obtém parâmetros de $_GET['path']
     *
     * Obtém os parâmetros de $_GET['path'] e configura as propriedades 
     * $this->controller, $this->action e $this->parameters
     *
     * A URL deverá ter o seguinte formato:
     * http://www.example.com/controller/action/parametro1/parametro2/etc...
     */
    public function get_url_data($path) {

        // Limpa os dados
        $path = rtrim($path, '/');
        $path = filter_var($path, FILTER_SANITIZE_URL);
        // Cria um array de parâmetros
        $path = explode('/', $path);

        // Configura as propriedades
        $this->module = chk_array($path, 0);
        $this->controller = chk_array($path, 1);
        $this->namecontroller = $this->controller;
        $this->controller .= '-controller';
        $this->action = chk_array($path, 2);
        // Configura os parâmetros
        if (chk_array($path, 3)) {
            unset($path[0]);
            unset($path[1]);
            unset($path[2]);

            // Os parâmetros sempre virão após a ação
            $this->parameters = array_values($path);
        }
    }

    public function conect() {
        \ActiveRecord\Config::initialize(function($cfg) {
            //Exemplo
            //$cfg->set_model_directory(array(__DIR__ . '/../models1', __DIR__ . '/../models2'));
            //$cfg->set_model_directory(ABSPATH . '/models');
            $cfg->set_connections(array('development' => 'mysql://' . DB_USER . ':' . DB_PASSWORD . '@' . DB_HOST . '/' . DB_NAME . '?charset=' . DB_CHARSET));
            \ActiveRecord\DateTime::$DEFAULT_FORMAT = 'd/m/Y H:i:s';
            date_default_timezone_set('America/Sao_Paulo');

            // you can change the default connection with the below
            //$cfg->set_default_connection('production');
        });
    }

    public function rota($arota) {
        /**
         * Verifica se o controller existe. Caso contrário, adiciona o
         * controller padrão (controllers/home-controller.php) e chama o método index().
         */
        $x= true ;
        while ($x==true){
            $url = explode('/', $arota, 2);
            if(!isset($url[0]) or empty($url[0]))
                $url[0]='/';
            if(isset($this->rotas[$url[0]])){
                $busca = $this->rotas[$url[0]] ;
                if(!is_array($busca)){
                    $this->get_url_data($busca.'/' .chk_array($url, 1));
                    $x = false ;
                }else{
                    $arota = $url[1];
                    $this->rotas = $busca ;
                }
            }else{
                $this->get_url_data($this->rota);
                $x = false ;
            }
            
        }
        
//        $rota = \app\rotas::search($this->rota);
//        if (is_object($rota)) {
//            $this->get_url_data($rota->rota);
//        } else {
//            $this->get_url_data($this->rota);
//        }
        $this->go();
        
    }
    
    private function go(){
        if (!$this->controller) {

            // Adiciona o controller padrão
            $this->module = default_module;
            $this->namecontroller = default_controller;
            $this->controller = default_controller . '-controller';

            $this->action = default_action;
        }

        // Se o arquivo do controller não existir, não faremos nada
        if (!file_exists(ABSPATH . '/modules/' . $this->module . '/controllers/' . $this->controller . '.php')) {
            // Página não encontrada
            $this->page_not_found();

            // FIM :)
            return;
        }

        // Inclui o arquivo do controller
        require_once ABSPATH . '/modules/' . $this->module . '/controllers/' . $this->controller . '.php';

        // Remove caracteres inválidos do nome do controller para gerar o nome
        // da classe. Se o arquivo chamar "news-controller.php", a classe deverá
        // se chamar NewsController.
        $this->controller = preg_replace('/[^a-zA-Z]/i', '', $this->controller);

        $this->controller = '\\' . $this->module . '\\' . $this->controller;

        // Se a classe do controller indicado não existir, não faremos nada
        if (!class_exists($this->controller)) {
            // Página não encontrada
            $this->page_not_found();

            // FIM :)
            return;
        } // class_exists
        // Cria o objeto da classe do controller e envia os parâmentros
        $this->controller = new $this->controller($this->parameters, $this->module, $this->namecontroller, $this->action);
        $this->controller->url = HOME_URI;
        $this->controller->path = ABSPATH;

        // Remove caracteres inválidos do nome da ação (método)
        $this->action = preg_replace('/[^a-zA-Z]/i', '', $this->action);

        // Se o método indicado existir, executa o método e envia os parâmetros
        if (method_exists($this->controller, $this->action)) {
            $this->controller->{$this->action}($this->parameters);

            // FIM :)
            return;
        } // method_exists
        // Sem ação, chamamos o método index
        if (!$this->action && method_exists($this->controller, 'index')) {
            $this->controller->index($this->parameters);


            // FIM :)
            return;
        } // ! $this->action 
        // Página não encontrada
        $this->page_not_found();

        // FIM :)
    }

    private function page_not_found() {
        // verifica se existe uma pagina criada para a url proposta
        show_error_page('404');
    }

// get_url_data
}
