<?php

namespace mwwork;

/**
 * mwcontroller - Todos os controladores deverão estender essa classe
 */
class mwcontroller {
    
    /**
     * Nome do módulo solicitado
     *
     * @access public
     */
    public $module;

    /**
     * Nome do controlador solicitado
     *
     * @access public
     */
    public $name;

    /**
     * Nome da ação solicitada
     *
     * @access public
     */
    public $action;

    /**
     * Url do domínio
     *
     * @access public
     */
    public $url;

    /**
     * Nome da ação solicitada
     *
     * @access public
     */
    public $path;

    /**
     * Paramêtros passados na criação do controlador
     *
     * @access public
     */
    public $parameters = array();

    /**
     * Nome da tabela 
     *
     * @access public
     */
    protected $table_name = '';
    
    /**
     * Classe da tabela 
     *
     * @access private
     */
    protected $table_class = '';
    
    /**
     * Indica tipo de resposta requisitado
     *
     * @access private
     */
    protected $response_type = '';
    
    /**
     * Colunas que não são exibidas na exportação
     * 
     * @access protected
     */
    protected static $excluded_export_columns = array(
        'id', 'data_criacao', 'data_exclusao'
    );
    
    /**
     * Formatos aceitos para exportação
     * 
     * @access protected
     */
    protected static $export_formats = array(
        'print' => 'Imprimir',
        'xls' => 'Exportar para Excel'
    );
    
    /**
     * Define os limites possíveis para serem usados em condições para a montagem
     * de um grid.
     * 
     * @access protected
     */
    protected static $grid_limits = array(
        '10', '50', '100'
    );
    
    /**
     * Define o número máximo de paginação para esquerda e para a direita.
     * 
     * @access protected
     */
    protected static $max_pages = '3';

    /**
     * Construtor da classe
     *
     * Configura as propriedades e métodos da classe.
     *
     * @access public
     */
    public function __construct($parameters = array(), $module, $name, $action) {
        
        //Charset padrão
        ini_set('default_charset', 'UTF-8');
        
        // Parâmetros
        $this->parameters = $parameters;
        $this->module = $module;
        $this->name = $name;
        $this->action = $action;
        
        //Verifica se está sendo requisitado algum tipo de resposta específico
        if (isset($_POST['response']) and is_string($_POST['response']))
            $this->response_type = $_POST['response'];
        else
            $this->response_type = 'html';
        
        //Modifica o header de acordo com a requisição
        switch ($this->response_type) {
            case 'json':
                header('Content-Type: application/json; charset=utf-8'); 
                break;
            case 'partial_html':
                header('Content-Type: text/plain; charset=utf-8'); 
                break;
            //Tipo html é tratado como padrão
            case 'html':
            default:
                header('Content-Type: text/html; charset=utf-8'); 
                break;
        }
    }
    
    /**
     * Envia a resposta da requisiçao
     *
     * @access protected
     */
    protected function response($response, $data = array(), $response_type = null) {
        
        //Força um tipo de resposta de necessário
        if ($response_type != null) {
            $this->response_type = $response_type;
        }
        
        //Verifica o tipo de resposta e envia ao cliente
        if ($this->response_type == 'json') {
            if (is_array($response)) {
                header('Content-type: text/json');
                echo json_encode($response);
            }
        }
        else if ($this->response_type == 'partial_html') {
            if (is_object($response) and get_class($response) == 'mwwork\\view') {
                $response->show(true);
            }
        }
        else { // "if ($this->response_type == 'html')"
            if (is_object($response) and get_class($response) == 'mwwork\\view') {
                $response->masterpage('painelCMS');
                $response->show(true);
            } else if (is_array($response)) {
                if (isset($response['redirect'])) {
                    $this->redirect($response['redirect']);
                }
            }
        }
        die;
    }
    
    /**
     * Salva um arquivo
     *
     * @access protected
     */
    protected function download_content($content, $name, $format) {
        
        $filename = "$name.$format";
        
        //Verifica o tipo de resposta e envia ao cliente
        if ($format == 'xls') {
            // Configurações header para forçar o download
            header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
            header ("Cache-Control: no-cache, must-revalidate");
            header ("Pragma: no-cache");
            header ("Content-type: application/x-msexcel; charset=utf-8");
            header ("Content-Disposition: attachment; filename=\"{$filename}\"" );
            header ("Content-Description: PHP Generated Data" );
            
            echo pack("CCC",0xef,0xbb,0xbf);
            echo $content;
        }
        die;
    }

    /**
     * Função para buscar permissão de acesso
     *
     * Contém a lógica de busca as permissões para acesso aos métodos do sistema
     *
     * @access private
     */
    protected function get_permission($module, $type, $redirect = true) {
        $extension = "";
        if (!CHECK_PERMISSION) {
            $p = new \app\permissao();
            $p->read = true;
            $p->create = true;
            $p->update = true;
            $p->delete = true;
            $p->print = true;
            return $p;
        }
        
        //Não há sessão
        if(!isset($_SESSION) or !isset($_SESSION['app'])) {
            if ($redirect) {
                goto redirect;
            }
            else {
                return false;
            }
        }
        
        //Verifica se usuário está ativo
        if ($_SESSION['app']['usuario_alteracao'] < \app\usuario::find_by_id($_SESSION['app']['usuario_id'])->data_alteracao->format('Y-m-d H:i:s')) {
            session_destroy();
            
            if ($redirect) {
                $extension = "&error=Sessão expirada";
                goto redirect;
            }
            else {
                return false;
            }
        }
        
        //Caso esteja em modo DEBUG, mostra onde o acesso foi negado na url
        if (DEBUG) {
            $extension = "&modulo=" . substr($module, 9) . "/";
            if (!is_array($type))
                $extension .= $type[0];
            else {
                foreach ($type as $t) {
                    $extension .= $t[0];
                }
            }
        }
        if (!isset($_SESSION['app']['usuario_id']) and ! isset($_SESSION['app']['grupo_id'])) {
            if ($redirect)
                goto redirect;
            else
                return false;
        }

        //Tratamento para o grupo especial, permissão por usuário
        if ($_SESSION['app']['grupo'] == 'Especial') {
            //Verifica se é uma permissão única ou várias
            if (!is_array($type))
                $permissao = \app\permissao::first(array('joins' => array('modulo'), 'conditions' => array("app_modulo.nome='$module' and app_permissao.usuario_id=" . $_SESSION['app']['usuario_id'] . " and app_permissao." . $type . "=1")));
            else {
                $types = "";
                foreach ($type as $t) {
                    if ($types != "")
                        $types .= " and ";
                    $types .= "app_permissao." . addslashes($t) . "=1";
                }
                $permissao = \app\permissao::first(array('joins' => array('modulo'), 'conditions' => array("app_modulo.nome='$module' and app_permissao.usuario_id=" . $_SESSION['app']['usuario_id'] . " and " . $types)));
            }
        }
        //Tratamento para demais grupos, permissão por grupo
        else {
            if (!is_array($type))
                $permissao = \app\permissao::first(array('joins' => array('modulo'), 'conditions' => array("app_modulo.nome='$module' and app_permissao.grupousuario_id=" . $_SESSION['app']['grupo_id'] . " and app_permissao." . $type . "=1")));
            else {
                $types = "";
                foreach ($type as $t) {
                    if ($types != "")
                        $types .= " and ";
                    $types .= "app_permissao." . addslashes($t) . "=1";
                }
                $permissao = \app\permissao::first(array('joins' => array('modulo'), 'conditions' => array("app_modulo.nome='$module' and app_permissao.grupousuario_id=" . $_SESSION['app']['grupo_id'] . " and " . $types)));
            }
        }
        //Permissão encontrada, retorna verdadeiro
        if (is_object($permissao)) {
            return $permissao;
        }
        //Permissão não encontrada, retorna falso ou redireciona, dependendo do 
        //paramêtro redirect
        else {

            if ($redirect)
                goto redirect;
            else
                return false;
        }

        redirect: header("Location: " . HOME_URI . '/app/usuario/login?direciona=' . $_SERVER['REQUEST_URI'] . $extension);
        die;
        //redirect: echo permissao::table()->last_sql . '<br>' . $type;
        //die;
    }
    
    /**
     * Função para identificar tabela do controlador
     *
     * @access private
     */
    protected function set_table() {
        
        if (class_exists("\\$this->module\\$this->name")) {
            $this->table_name = "$this->module" . "_$this->name";
            $this->table_class = "\\$this->module\\$this->name";
            return true;
        }
        else {
            return false;
        }
        //$this->permissao();
    }
    
    /**
     * Monta condições de pesquisa do grid
     *
     * @access protected
     */
    protected function get_grid_conditions() {
        //Identifica a classe que está sen chamada
        $called_class = '\\' . get_class($this);
        
        //Armazena o model que deve ser utilizado
        $model = $this->table_class;
        
        $limits = self::$grid_limits;
        
        //Caso exista uma grida especifica para a classe, usa ela
        if (property_exists($called_class, 'grid_limits')) {
            $limits = $called_class::$grid_limits;
        }

        //Recebe o filtro padrão da tabela
        $conditions = $model::filter(get(), $_SESSION);
        
        //Modifica partes do filtro de acordo com a requisição
        if (!isset($conditions['limit'])) {
            if (isset($_GET['limit']) and in_array($_GET['limit'], $limits)){
                $conditions['limit'] = $_GET['limit'];
            }
            else {
                $conditions['limit'] = $limits[0];
            }
        }
        
        if (!isset($conditions['offset'])) {
            if (isset($_GET['pag']))
                $conditions['offset'] = (abs(intval($_GET['pag']) - 1) * $conditions['limit']);
            else
                $conditions['offset'] = 0;
        }
        
        if (!isset($conditions['order'])) {
            if (isset($_GET['order'])) {
                $order_array = explode('-',$_GET['order']);
                if (count($order_array) == 1) {
                    $order_array[1] = 'asc';
                } 
                
                if (count($order_array) == 2) {
                    if (isset($model::table()->columns[$order_array[0]])) {
                        $conditions['order'] = "$this->name." . $order_array[0];
                        $conditions['order'] .= ($order_array[1] == 'desc') ? ' desc' : ' asc';
                    }
                }
            }
            
            //Verifica se, mesmo com o processamento acima, não foi colocado
            //a condição de order
            if (!isset($conditions['order'])) {
                if (property_exists($called_class, 'default_order_column')) {
                    $conditions['order'] = $called_class::$default_order_column;
                }
                else {
                    $conditions['order'] = "$this->name.id asc";
                }
            }
        }
        
        return $conditions;
        
    }
    
    /**
     * Aplica blocos de permissão, de acordo com as permissões concedidas
     *
     * @access protected
     */
    protected function parse_permission($vw, $permissao) {
        
        //blocos de permissão
        if ($permissao->create) {
            $count = 1;
            while ($vw->blockexists("block_permission_create_$count")) {
                $vw->block("block_permission_create_$count");
                $count++;
            }
        }
        if ($permissao->update) {
            $count = 1;
            while ($vw->blockexists("block_permission_update_$count")) {
                $vw->block("block_permission_update_$count");
                $count++;
            }
        }
        if ($permissao->delete) {
            $count = 1;
            while ($vw->blockexists("block_permission_delete_$count")) {
                $vw->block("block_permission_delete_$count");
                $count++;
            }
        }
        if ($permissao->print) {
            $count = 1;
            while ($vw->blockexists("block_permission_print_$count")) {
                $vw->block("block_permission_print_$count");
                $count++;
            }
        }

        return $vw;
        
    }
    
    /**
     * Setá variaveis padrão
     *
     * @access protected
     */
    protected function set_default_vars($vw, $unset_query = array()) {
        if ($vw->exists('url'))
            $vw->url = HOME_URI;
        
        if ($vw->exists('module'))
            $vw->module = $this->module;
        
        if ($vw->exists('controller'))
            $vw->controller = $this->name;
        
        if ($vw->exists('action'))
            $vw->action = $this->action;

        if ($vw->exists('query') or $vw->exists('query_array')) {
            $get = get();
            foreach ($unset_query as $value) {
                if (isset($get[$value]))
                    unset($get[$value]);
            }
            if($vw->exists('query'))
                $vw->query = http_build_query($get);
            if($vw->exists('query_json'))
                $vw->query_json = json_encode($get);
        }
        
        return $vw;
    }
    
    /**
     * Monta breadcrumbs da página
     *
     * @access protected
     */
    protected function parse_breadcrumbs($type, $object = null) {
        //View de breadcrumbs
        if (\mwwork\view::view_exists("$this->module/$this->name/breadcrumbs")) {
            $vw = new \mwwork\view("$this->module/$this->name/breadcrumbs");
        }
        else {
            $vw = new \mwwork\view("app/painelCMS/breadcrumbs");
        }
        
        //Identifica a classe que está sendo chamada
        $called_class = '\\' . get_class($this);
        
        //Coloca os componente de um breadcrumbs em array
        $breadcrumbs = array();
        $breadcrumbs['Home'] = array(
            'url' => HOME_URI . '/painel'
        );
        
        //Nome descritivo da classe
        $descritive_name = ucfirst($this->name);
        if (property_exists($called_class, 'descritive_name')) {
            $descritive_name = $called_class::$descritive_name;
        }
        
        //Nome descritivo da classe plural
        $descritive_name_plural = $descritive_name . "s";
        if (property_exists($called_class, 'descritive_name_plural')) {
            $descritive_name_plural = $called_class::$descritive_name_plural;
        }
        
        //Tenta encontrar menu relacionado
        $menu = \app\menu::first(array(
            'conditions' => array("url like '%$this->name/$this->action%'")
        )); 
        
        //Se ação for "grid" ou "form" e menu não tiver sido encontrado anteriormente,
        //tenta uma segunda pesquisa
        if (!is_object($menu) and ($this->action == 'grid' or $this->action == 'form')) {
            $action = ($this->action == 'form') ? 'grid' : 'form';
            
            $menu = \app\menu::first(array(
                'conditions' => array("url like '%$this->name/$action%'")
            )); 
            
        }
        
        //Menu não encontraddo, monta breadcrumbs padrão
        if (!is_object($menu)) {
             $breadcrumbs[$descritive_name_plural] = array(
                'active' => 1
            );
        }
        //Breadcrumbs baseado no menu
        else {
            $limit = 3;
            $is_active = 1;
            
            //Array auxiliar do breadcrumbs
            $_breadcrumbs = array();
            
            while (is_object($menu) and $limit > 0) {
                $limit = 3;
                
                $breadcrumb = array();
                $breadcrumb['name'] = $menu->nome;
                if ($is_active) {
                    $breadcrumb['active'] = 1;
                }
                if (!$is_active and $menu->url != "#" and !empty($menu->url)) {
                    $breadcrumb['url'] = $menu->url;
                }
                
                $_breadcrumbs[] = $breadcrumb;
                
                $menu = $menu->pai;
                
                $limit--;
                $is_active = 0;
            }
            
            //Itera sobre array auxiliar para inserir os breadcrumbs
            for ($i = count($_breadcrumbs); $i > 0; $i--) {
                $breadcrumb = $_breadcrumbs[$i - 1];
                $name = $breadcrumb['name'];
                unset($breadcrumb['name']);
                $breadcrumbs[$name] = $breadcrumb;
            }
        }
        
        $vw->title_breadcrumb = $descritive_name_plural;
        
        foreach ($breadcrumbs as $name => $breadcrumb) {
            if (!is_array($breadcrumb)) {
                $breadcrumb = array();
            }
            
            $vw->is_active = (isset($breadcrumb['active'])) ? $breadcrumb['active'] : false;
            $vw->name = $name;
            if (isset($breadcrumb['url'])) {
                $vw->url = $breadcrumb['url'];
                $vw->block('block_breadcrumb_has_href');
            }
            else {
                $vw->block('block_breadcrumb_no_href');
            }
            $vw->block('block_breadcrumb');
        }
        
        $vw = $this->set_default_vars($vw, array('error', 'success', 'ref'));
        
        return $vw->parse();
        
    }
    
    /**
     * Monta a view de actions para a grid
     *
     * @access protected
     */
    protected function parse_grid_actions($permissao = null) {
        //Identifica a classe que está sen chamada
        $called_class = '\\' . get_class($this);
        
        if (\mwwork\view::view_exists("$this->module/$this->name/grid/actions")) {
            $vw = new \mwwork\view("$this->module/$this->name/grid/actions");
        }
        else {
            $vw = new \mwwork\view("app/painelCMS/default/grid/actions");
        }
        
        //Monta select de limits para mostrar
        if ($vw->blockexists('block_select_limits')) {
            $limits = self::$grid_limits;
        
            //Caso exista uma lista especifica para a classe, usa ela
            if (property_exists($called_class, 'grid_limits')) {
                $limits = $called_class::$grid_limits;
            }
            
            foreach ($limits as $limit) {
                $vw->limit_value = $limit;
                if ($limit == chk_array($_GET, 'limit'))
                    $vw->is_selected = 1;
                else
                    $vw->clear('is_selected');
                
                $vw->block('block_select_limits');
            }
            
        }
        
        //Verifica se existe busca avançada e insere o form caso sim
        if($vw->blockexists('block_advanced_search')) {
            if (\mwwork\view::view_exists("$this->module/$this->name/grid/advancedsearch"))
                $vw->block('block_advanced_search');
        }
        
        //Caso já haja um filtro digitado, preenche no form
        if ($vw->exists('filter') and isset($_GET['filter'])) {
            $vw->filter = $_GET['filter'];
        }
        
        $vw = $this->set_default_vars($vw, array('error', 'success', 'ref'));
        
        if (is_object($permissao)) {
            $vw = $this->parse_permission($vw, $permissao);
        }
        return $vw->parse();
        
    }
    
    /**
     * Monta a view de busca avançada para a grid
     *
     * @access protected
     */
    protected function parse_grid_advancedsearch($permissao = null) {
        if (\mwwork\view::view_exists("$this->module/$this->name/grid/advancedsearch")) {
            $vw = new \mwwork\view("$this->module/$this->name/grid/advancedsearch");
        }
        else {
            return "";
        }
        
        //Guarda os tipos de filtro avançados que não requerem nenhum 
        //processamento extra
        $filter_types = array(
            'equals',
            'contains',
            'higher',
            'lower'
        );
        
        $model = $this->table_class;
        
        $object = new $model;
        
        //Passa por todos tipos de filtro
        foreach ($filter_types as $filter_type) {
            
            //Verifica se há dados passados no tipo de filtro atual
            if (isset($_GET[$filter_type])) {

                //Iteração para cada coluna
                foreach ($object::table()->columns as $column) {
                    if (isset($_GET[$filter_type][$column->name]) and $vw->exists("$filter_type" . "_$column->name")) {
                        $vw->{"$filter_type" . "_$column->name"} = trim($_GET[$filter_type][$column->name]);
                    }
                }
            }
        }
        
        $vw = $this->set_default_vars($vw, array(
            'error', 'success', 'ref', 'equals', 'like', 'higher', 'lower'
        ));
        
        if (is_object($permissao)) {
            $vw = $this->parse_permission($vw, $permissao);
        }
        return $vw->parse();
        
    }
    
    /**
     * Monta a view de opções de exportação
     *
     * @access protected
     */
    protected function parse_grid_exportoptions($permissao = null) {
        if (\mwwork\view::view_exists("$this->module/$this->name/grid/exportoptions")) {
            $vw = new \mwwork\view("$this->module/$this->name/grid/exportoptions");
        }
        else {
            $vw = new \mwwork\view("app/painelCMS/default/grid/exportoptions");
        }
        
        $model = $this->table_class;
        
        $object = new $model;
        
        //Caso exista bloco de colunas, itera sobre ele
        if ($vw->blockexists('block_export_rows')) {
            foreach ($object::table()->columns as $column) {
                
                //Se for uma coluna excluida, ignora
                if (in_array($column->name, $this::$excluded_export_columns)) {
                    continue;
                }
                //Chave estrangeira, ignora
                else if (substr($column->name, -2) == 'id') {
                    continue;
                }
                
                $vw->column = $column->name;
                $vw->block('block_export_rows');
            }
        }
        
        //Caso exista bloco de formatos, itera sobre ele
        if ($vw->blockexists('block_export_formats')) {
            foreach ($this::$export_formats as $format => $description) {
                $vw->format = $format;
                $vw->format_description = $description;
                $vw->block('block_export_formats');
            }
        }
      
        $vw = $this->set_default_vars($vw, array(
            'error', 'success', 'ref', 'equals', 'like', 'higher', 'lower'
        ));
        
        if (is_object($permissao)) {
            $vw = $this->parse_permission($vw, $permissao);
        }
        return $vw->parse();
        
    }
    
    /**
     * Monta e insere scrips da grid
     *
     * @access protected
     */
    protected function parse_grid_scripts($permissao = null) {
        if (\mwwork\view::view_exists("$this->module/$this->name/grid/script.js")) {
            $vw = new \mwwork\view("$this->module/$this->name/grid/script.js");

            $vw = $this->set_default_vars($vw, array('error', 'success', 'ref'));

            if (is_object($permissao)) {
                $vw = $this->parse_permission($vw, $permissao);
            }
            return $vw->parse();
            
        }
        else {
            return "";
        }
        
    }
    
    /**
     * Monta e insere scrips do form
     *
     * @access protected
     */
    protected function parse_form_scripts($permissao = null) {
        if (\mwwork\view::view_exists("$this->module/$this->name/form/script.js")) {
            $vw = new \mwwork\view("$this->module/$this->name/form/script.js");

            $vw = $this->set_default_vars($vw, array('error', 'success', 'ref'));

            if (is_object($permissao)) {
                $vw = $this->parse_permission($vw, $permissao);
            }
            return $vw->parse();
            
        }
        else {
            return "";
        }
        
    }
    
    /**
     * Monta a view de paginação para a grid
     *
     * @access protected
     */
    protected function parse_grid_pagination($conditions, $permissao = null) {   
        //Identifica a classe que está sen chamada
        $called_class = '\\' . get_class($this);
        
        //Armazena o model que deve ser utilizado
        $model = $this->table_class;
        
        //Páginações a esquerda e a direita
        $max_pages = self::$max_pages;
        
        //Caso exista uma lista especifica para a classe, usa ela
        if (property_exists($called_class, 'max_pages')) {
            $max_pages = $called_class::$max_pages;
        }
        
        //View de paginação
        if (\mwwork\view::view_exists("$this->module/$this->name/grid/pagination")) {
            $vw = new \mwwork\view("$this->module/$this->name/grid/pagination");
        }
        else {
            $vw = new \mwwork\view("app/painelCMS/default/grid/pagination");
        }
        
        $vw = $this->set_default_vars($vw, array('error', 'success', 'pag', 'ref'));
        
        //Condições sem limite ou offset
        $conditions_all = $conditions;
        
        unset($conditions_all['offset']);
        unset($conditions_all['limit']);
        
        //Inicializa variaveis relacionadas a paginação
        $rows = $model::count($conditions_all);
        
        $vw->total_rows = $rows;
        
        $first_row = abs($conditions['offset'] / $conditions['limit'] * $conditions['limit']) + 1;
        $vw->first_row = $first_row;
        
        $last_row = ($first_row + $conditions['limit']);
        $vw->last_row = ($last_row > $rows ) ? $rows : $last_row - 1;
        
        $page = ($conditions['offset'] / $conditions['limit'] + 1);
        $pages = ceil($rows / $conditions['limit']);
        $vw->query_first_page = '?pag=1';
        $vw->query_last_page = '?pag=' . $pages;

        $page_start = $page - $max_pages;
        if ($page_start < 1)
            $page_start = 1;
        $page_end = $page + $max_pages;
        if ($page_end > $pages)
            $page_end = $pages;
        
        //Informa se é primeira página
        $vw->is_first_page = ($page == 1)? 1 : 0;
        
        //Informa se é última página
        $vw->is_last_page = ($page == $pages)? 1 : 0;
        
        //Loop de paginação
        for ($p = $page_start; $p <= $page_end; $p++) {
            $vw->query_page = '?pag=' . $p;
            $vw->number_page = $p;
            $vw->clear('active');
            if ($page == $p)
                $vw->active = '1';
            $vw->block('block_pagination');
        }

        if (is_object($permissao)) {
            $vw = $this->parse_permission($vw, $permissao);
        }

        return $vw->parse();
        
    }
    
    /**
     * Monta o corpo (linhas) da grid
     *
     * @access protected
     */
    protected function get_grid_body($vw, $conditions, $permissao = null) {
        $vw = $this->set_default_vars($vw, array('error', 'success', 'ref'));
        
        $model = $this->table_class;
        $rows = $model::all($conditions);
        
        foreach ($rows as $row) {
            $vw->grid = $row;

            $vw = $this->parse_permission($vw, $permissao);

            $vw->block('block_row');
        }
        
        return $vw;
    }
    
    /**
     * Monta o corpo (linhas) da grid de exportacao
     *
     * @access protected
     */
    protected function get_exportgrid_body($vw, $conditions, $columns, $format = 'print') {
        $vw = $this->set_default_vars($vw, array('error', 'success', 'ref'));
        
        $model = $this->table_class;
        $rows = $model::all($conditions);
        
        //Itera sobre registros para montar header
        if ($vw->blockexists('block_grid_header')) {
            foreach ($columns as $column) {
                $vw->header = $column;
                $vw->block('block_grid_header');
            }
        }
        
        //Itera sobre registros para montar corpo
        if ($vw->blockexists('block_grid_row')) {
            foreach ($rows as $row) {
                
                if ($vw->exists('row')) {
                    $vw->row = $row;
                }
                
                if ($vw->blockexists('block_grid_column')) {
                    foreach ($columns as $column) {
                        if ($vw->exists('header'))
                            $vw->header = $column;
                        if ($vw->exists('column'))
                            $vw->column = $row->{$column};
                            
                        $vw->block('block_grid_column');
                    }
                }
                $vw->block('block_grid_row');
            }
        }
        
        return $vw;
    }
    
    /**
     * Monta o corpo (linhas) do form
     *
     * @access protected
     */
    protected function get_form_body($vw, $object, $permissao = null) {
        $vw = $this->set_default_vars($vw, array('error', 'success'));
        
        $vw->form = $object;
        if ($vw->exists('url_cancel')) {
            if (isset($_GET['ref'])) {
                $vw->url_cancel = $_GET['ref'];
            }
            else {
                $vw->url_cancel = "/$this->module/$this->name/grid";
            }
        }
        
        $vw = $this->parse_permission($vw, $permissao);
        
        
        return $vw;
    }

    /**
     * Reidireciona para o URL informado
     *
     * @access public
     */
    public function redirect($url = null) {
        header("Location: " . $url);
        die;
    }

    /**
     * Função padrão de montagem de grids
     *
     * @access protected
     */
    protected function grid() {

        //Verifica se há conexão entro o controlador e uma tabela
        if (!$this->set_table()) {
            show_error_page('404');
        }
        
        //Verifica se há permissões para o acesso
        $permissao = $this->get_permission("$this->module/$this->name", 'read');
        if (!is_object($permissao)) {
            show_error_page('403');
        }
        
        $conditions = $this->get_grid_conditions();
        
        //View da grid
        //$vw = new view("$this->module/$this->name/grid");
        
        $vw = new view("app/painelCMS/content");
        $vw->addFile('content', "$this->module/$this->name/grid");
        
        $vw->breadcrumbs = $this->parse_breadcrumbs($this->action);
        $vw->advancedsearch = $this->parse_grid_advancedsearch($permissao);
        $vw->exportoptions = $this->parse_grid_exportoptions($permissao);
        $vw->actions = $this->parse_grid_actions($permissao);
        $vw->pagination = $this->parse_grid_pagination($conditions, $permissao);
        $vw->scripts = $this->parse_grid_scripts($permissao);
        
        $vw = $this->get_grid_body($vw, $conditions, $permissao);
        
        $this->response($vw);
        
    }

    /**
     * Função padrão de montagem de forms
     *
     * @access protected
     */
    protected function form() {
        //Verifica se há conexão entro o controlador e uma tabela
        if (!$this->set_table()) {
            show_error_page('404');
        }
        
        //Verifica se há permissões para o acesso
        $permissao = $this->get_permission("$this->module/$this->name", 'read');
        if (!is_object($permissao)) {
            show_error_page('403');
        }
        
        //Identifica o model
        $model = $this->table_class;
        
        //Novo registro
        if (empty(chk_array($this->parameters, 0)) or chk_array($this->parameters, 0) == 'new') {
            $object = new $model();
        }
        //Registro existentes
        else {
            //Pega objeto no banco
            $object = $model::first(array(
                'conditions' => array('id = ? and data_exclusao is null', intval(chk_array($this->parameters, 0)))
            ));

            //Veririca se objeto existe
            if (!is_object($object)) {
                show_error_page('404');
            }

            //Verifica se há acesso ao objeto, necessário por ser multi-site
            if (!$object->has_access($_SESSION)) {
                show_error_page('403');
            }
        }
        
        //View do form
        $vw = new view("app/painelCMS/content");
        
        if(DEBUG and isset($_GET['example']))
            $vw->addFile('content', "app/painelCMS/example/form");
        else
            $vw->addFile('content', "$this->module/$this->name/form");
        
        //Insere Breacrumbs do form
        if ($object->is_new_record())
            $vw->breadcrumbs = $this->parse_breadcrumbs("create$this->action");
        else
            $vw->breadcrumbs = $this->parse_breadcrumbs("update$this->action", $object);
        
        $vw->scripts = $this->parse_form_scripts($permissao);
        
        $vw = $this->get_form_body($vw, $object, $permissao);
        
        $this->response($vw);
    }
    
    /**
     * Função para salvar dados da tabela no banco
     *
     * @access protected
     */
    protected function save() {
        //Inicializa variável de resposta
        $response = array(
            'status'    => '0',     // 0 para erro, 1 para sucesso
            'message'   => null,    // mensagem sobre o resultado
            'errors'    => null,    // contém os erros caso haja algum
            'redirect'  => null     // link de redirecionamento caso haja algum
        );
        
        //Verifica se há conexão entre o controlador e uma tabela
        if (!$this->set_table()) {
            show_error_page('404');
        }
        
        $is_new_object = true;
        
        //Identifica o model
        $model = $this->table_class;
        
        //Novo registro
        if (empty(chk_array($this->parameters, 0)) or chk_array($this->parameters, 0) == 'new') {
            $object = new $model();
        }
        //Registro existentes
        else {
            //Pega objeto no banco
            $object = $model::first(array(
                'conditions' => array('id = ? and data_exclusao is null', intval(chk_array($this->parameters, 0)))
            ));

            //Veririca se objeto existe
            if (!is_object($object)) {
                show_error_page('404');
            }

            //Verifica se há acesso ao objeto, necessário por ser multi-site
            if (!$object->has_access($_SESSION)) {
                show_error_page('404');
            }
            
            $is_new_object = false;
        }
        
        //Verifica se há permissões para o acesso
        $permissao = $this->get_permission("$this->module/$this->name", 
                ($is_new_object ? 'create' : 'update'));
        if (!is_object($permissao)) {
            show_error_page('403');
        }
        
        //$checagem = checatamanhopost();
        /*if (!$checagem) {
            die;
        }*/
        
        //Post não pode estar vazio
        if (!empty($_POST)) {
            
            $object->post();
            if ($object->is_invalid()) {
                $extension = "error=";

                $raw_errors = $object->errors->get_raw_errors();

                foreach ( $raw_errors as $attribute => $errors) {
                    $extension .= "$attribute contém os seguintes erros:" . implode('/', $errors) . "; ";
                }

                $response['errors'] = $errors;
                $response['message'] = "Erro ao salvar! $extension";

            } 
            else {
                if (LOG_ACTIONS and !$is_new_object) {
                    \app\log::create(array(
                        'usuario_id' => (DEBUG) ? 0 : $_SESSION['app']['usuario_id'],
                        'tipo_usuario' => 'app_usuario',
                        'data' => new \DateTime(),
                        'conteudo_id' => $object->id,
                        'conteudo' => $this->table_name,
                        'acao' => 'save/update',
                        'extra' => json_encode($object->dirty_attributes())
                    ));
                }
                $object->save();
                if (LOG_ACTIONS and $is_new_object) {
                    \app\log::create(array(
                        'usuario_id' => (DEBUG) ? 0 : $_SESSION['app']['usuario_id'],
                        'tipo_usuario' => 'app_usuario',
                        'data' => new \DateTime(),
                        'conteudo_id' => $object->id,
                        'conteudo' => $this->table_name,
                        'acao' => 'save/insert',
                        'extra' => $object->to_json()
                    ));
                }
                $extension = 'success=Dados Cadastrados com Sucesso';

                $response['status'] = '1';
                $response['message'] = "Salvo com sucesso.";
            }
        }
        else {
            show_error_page('404');
        }
        
        $redirect = '';
        if (method_exists($object, 'redirect'))
            $redirect = $object->redirect();
        if (empty($redirect))
            $response['redirect'] = ($this->url . "/$this->module/$this->name/grid" . '?' . $extension . ((!empty(get())) ? '&' . http_build_query(get()) : ''));
        else
            $response['redirect'] = ($this->url . $redirect . ((!empty(get())) ? '?' . http_build_query(get()) : ''));
        
        
        $this->response($response);
        
    }
    
    /**
     * Função para mudar um atributo apenas da tabela
     *
     * @access protected
     */
    protected function saveattribute() {
        
        //Inicializa variável de resposta
        $response = array(
            'status'    => '0',     // 0 para erro, 1 para sucesso
            'message'   => null,    // mensagem sobre o resultado
            'errors'    => null,    // contém os erros caso haja algum
            'redirect'  => null     // link de redirecionamento caso haja algum
        );
        
        //Verifica se há conexão entre o controlador e uma tabela
        if (!$this->set_table()) {
            show_error_page('404');
        }
        
        //Identifica o model
        $model = $this->table_class;
        
        //Registro não informado
        if (empty(chk_array($this->parameters, 0))) {
            show_error_page('404');
        }
        
        //Pega objeto no banco
        $object = $model::first(array(
            'conditions' => array('id = ? and data_exclusao is null', intval(chk_array($this->parameters, 0)))
        ));

        //Veririca se objeto existe
        if (!is_object($object)) {
            show_error_page('404');
        }
        
        //Verifica se há acesso ao objeto, necessário por ser multi-site
        if (!$object->has_access($_SESSION)) {
            show_error_page('404');
        }
        
        //Verifica se há permissões para o acesso
        $permissao = $this->get_permission("$this->module/$this->name", "update");
        if (!is_object($permissao)) {
            show_error_page('403');
        }
        
        //Recebe o atributo a se modificar e o valor
        $attribute = chk_array($this->parameters, 1);
        $value = chk_array($this->parameters, 2);
        
        //Checar se atributo é modificavel
        if (!property_exists($model, 'save_attribute_can_update')) {
           show_error_page('404');
        }
        
        if(!in_array($attribute, $model::$save_attribute_can_update)) {
            show_error_page('404');
        }
        
        //Conexão com o banco de dados. Para fazer transação
        $connection = $model::connection();
        $connection->transaction();
        
        try {
            $object->{$attribute} = $value;
            
            //Verifica se é válido
            if ($object->is_invalid()) {
                $extension = "error=";

                $raw_errors = $object->errors->get_raw_errors();

                foreach ( $raw_errors as $attribute => $errors) {
                    $extension .= "$attribute contém os seguintes erros:" . implode('/', $errors) . "; ";
                }

                $response['errors'] = $errors;
                $response['message'] = "Erro ao salvar! $extension";

            } 
            else {
                $object->save();
                if (LOG_ACTIONS) {
                    \app\log::create(array(
                        'usuario_id' => (DEBUG) ? 0 : $_SESSION['app']['usuario_id'],
                        'tipo_usuario' => 'app_usuario',
                        'data' => new \DateTime(),
                        'conteudo_id' => $object->id,
                        'conteudo' => $this->table_name,
                        'acao' => 'saveattribute',
                        'extra' => json_encode($object->dirty_attributes())
                    ));
                }

                $connection->commit();

                $extension = 'success=Dados Cadastrados com Sucesso';

                $response['status'] = '1';
                $response['message'] = "Salvo com sucesso.";
            }
        }
        catch (Exception $ex) {
            $connection->rollback();
            
            throw $ex;
        }
        
        $redirect = '';
        if (method_exists($object, 'redirect'))
            $redirect = $object->redirect();
        if (empty($redirect))
            $response['redirect'] = ($this->url . "/$this->module/$this->name/grid" . '?' . $extension . ((!empty(get())) ? '&' . http_build_query(get()) : ''));
        else
            $response['redirect'] = ($this->url . $redirect . ((!empty(get())) ? '?' . http_build_query(get()) : ''));
        
        
        $this->response($response);
        
    }
    
    /**
     * Função para excluir um registro na tabela do banco
     *
     * @access protected
     */
    protected function delete() {
        //Inicializa variável de resposta
        $response = array(
            'status'    => '0',     // 0 para erro, 1 para sucesso
            'message'   => null,    // mensagem sobre o resultado
            'errors'    => null,    // contém os erros caso haja algum
            'redirect'  => null     // link de redirecionamento caso haja algum
        );
        
        //Verifica se há conexão entre o controlador e uma tabela
        if (!$this->set_table()) {
            show_error_page('404');
        }
        
        //Identifica o model
        $model = $this->table_class;
        
        //Pega objeto no banco
        $object = $model::first(array(
            'conditions' => array('id = ? and data_exclusao is null', intval(chk_array($this->parameters, 0)))
        ));

        //Veririca se objeto existe
        if (!is_object($object)) {
            show_error_page('404');
        }
        
        //Verifica se há permissões para o acesso
        $permissao = $this->get_permission("$this->module/$this->name", 'delete');
        if (!is_object($permissao)) {
            show_error_page('403');
        }

        //Verifica se há acesso ao objeto, necessário por ser multi-site
        if (!$object->has_access($_SESSION)) {
            show_error_page('403');
        }

        //Verifica se é deletável.
        $deletable = $object->is_deletable();
        
        //Se for deletável, "deleta"
        if($deletable['error'] == 0) {
            $object->data_exclusao = new \DateTime();
            $object->save();

            if (LOG_ACTIONS) {
                \app\log::create(array(
                    'usuario_id' => (DEBUG) ? 0 : $_SESSION['app']['usuario_id'],
                    'tipo_usuario' => 'app_usuario',
                    'data' => new \DateTime(),
                    'conteudo_id' => $object->id,
                    'conteudo' => $this->table_name,
                    'acao' => 'delete'
                ));
            }

            $extension = "success=Deletado com sucesso.";

            $response['status'] = '1';
            $response['message'] = "Deletado com sucesso.";
        }
        else {
            $extension = "error=Há um ou mais registros do tipo \\'" . $deletable['table'] 
                    . "\\' ligados ao registro atual.";

            $response['status'] = '0';
            $response['message'] = "Há um ou mais registros do tipo '" . $deletable['table'] 
                    . "' ligados ao registro atual.";
        }
        
        $redirect = '';

        if (method_exists($object, 'redirect'))
            $redirect = $object->redirect();
        
        if (empty($redirect))
            $response['redirect'] = ($this->url . "/$this->module/$this->name/grid/" . '?' . $extension . ((!empty(get())) ? '&' . http_build_query(get()) : ''));
        else
            $response['redirect'] = ($this->url . $redirect . ((!empty(get())) ? '?' . http_build_query(get()) : ''));
        
        $this->response($response);
    }
    
    /**
     * Função para excluir um ou mais registros na tabela do banco
     *
     * @access protected
     */
    protected function deletemultiple() {
        //Inicializa variável de resposta
        $response = array(
            'status'    => '0',     // 0 para erro, 1 para sucesso
            'message'   => null,    // mensagem sobre o resultado
            'errors'    => null,    // contém os erros caso haja algum
            'redirect'  => null     // link de redirecionamento caso haja algum
        );
        
        //Verifica se há conexão entre o controlador e uma tabela
        if (!$this->set_table()) {
            show_error_page('404');
        }
        
        //Verifica se há permissões para o acesso
        $permissao = $this->get_permission("$this->module/$this->name", 'delete');
        if (!is_object($permissao)) {
            show_error_page('403');
        }
        
        //Verifica se foi recebido ids para ação
        if (!is_array(chk_array($_POST, 'ids'))) {
            die;
        }
        
        //Sanitiza ids
        $ids = array_map('intval', $_POST['ids']);
        
        //Identifica o model
        $model = $this->table_class;
        
        $rows = array();
        
        //Itera sobre ids selecionados e procura no banco
        foreach ($ids as $id) {
            
            if (!isset($rows[$id])) {
                $object = $model::first(array(
                    'select' => '*',
                    'conditions' => array('id = ? and data_exclusao is null', $id)
                ));
                
                if (is_object($object)) {
                    $rows[$id] = $object;
                }
                else {
                    $response['message'] = "Linha não encontrada[$id]";
                    $this->response($response);
                    die;
                }
                
                //Verifica se há acesso ao objeto, necessário por ser multi-site
                if (!$object->has_access($_SESSION)) {
                    $response['message'] = "Acesso negado no registro[$id]";
                    $this->response($response);
                    die;
                }
                
                //Verifica se não há registros ligados
                $deletable =  $object->is_deletable();
                if($deletable['error'] == 1) {
                    $extension = "error=Há um ou mais registros do tipo \\'" . $deletable['table'] 
                    . "\\' ligados ao registro $object->id.";
        
                    $redirect = '';
                    
                    $response['message'] = "Há um ou mais registros do tipo '" . $deletable['table'] 
                    . "' ligados ao registro $object->id";
                    
                    if (method_exists($object, 'redirect'))
                        $redirect = $object->redirect();

                    if (empty($redirect))
                        $response['redirect'] = ($this->url . "/$this->module/$this->name/grid/" . '?' . $extension . ((!empty(get())) ? '&' . http_build_query(get()) : ''));
                    else
                        $response['redirect'] = ($this->url . $redirect . ((!empty(get())) ? '?' . http_build_query(get()) : ''));
                    
                    $this->response($response);
                }
            }
        }
        
        //Conta os registros deletados
        $count = 0;
        
        //Conexão com o banco de dados. Para fazer transação
        $connection = $model::connection();
        $connection->transaction();
        
        try {
            //Número aleatório para marcar ações na mesma transação
            $transaction_number = uniqid();
            $transaction_data = json_encode(array('transaction' => $transaction_number));
            
            //Itera objetos para deleção
            foreach ($rows as $object) {
                //Exclusão lógica
                //$object->delete();
                $object->data_exclusao = new \DateTime();
                $object->save();
                
                if (LOG_ACTIONS) {
                    \app\log::create(array(
                        'usuario_id' => (DEBUG) ? 0 : $_SESSION['app']['usuario_id'],
                        'tipo_usuario' => 'app_usuario',
                        'data' => new \DateTime(),
                        'conteudo_id' => $object->id,
                        'conteudo' => $this->table_name,
                        'acao' => 'deletemultiple',
                        'extra' => $transaction_data
                    ));
                }
                
                $count++;
            }
            $connection->commit();
        } catch (Exception $ex) {
            $connection->rollback();
            
            //Loga que a transação falhou
            if (LOG_ACTIONS) {
                $transaction_data = json_encode(array(
                    'transaction' => $transaction_number,
                    'success' => 0,
                    'message' => $ex->getMessage()
                ));
                \app\log::create(array(
                    'usuario_id' => (DEBUG) ? 0 : $_SESSION['app']['usuario_id'],
                    'tipo_usuario' => 'app_usuario',
                    'data' => new \DateTime(),
                    'conteudo_id' => null,
                    'conteudo' => $this->table_name,
                    'acao' => 'deletemultiple',
                    'extra' => $transaction_data
                ));
            }
            
            throw $ex;
        }
        
        //Loga que ocorreu sucesso
        if (LOG_ACTIONS) {
            $transaction_data = json_encode(array(
                'transaction' => $transaction_number,
                'success' => 1
            ));
            \app\log::create(array(
                'usuario_id' => (DEBUG) ? 0 : $_SESSION['app']['usuario_id'],
                'tipo_usuario' => 'app_usuario',
                'data' => new \DateTime(),
                'conteudo_id' => null,
                'conteudo' => $this->table_name,
                'acao' => 'deletemultiple',
                'extra' => $transaction_data
            ));
        }
        
        $extension = "success=$count registros deletados com sucesso.";
        
        $response['status'] = '1';
        $response['message'] = "$count registros deletados com sucesso.";
        
        $redirect = '';
        
        if (method_exists($object, 'redirect'))
            $redirect = $object->redirect();
        
        if (empty($redirect))
            $response['redirect'] = ($this->url . "/$this->module/$this->name/grid/" . '?' . $extension . ((!empty(get())) ? '&' . http_build_query(get()) : ''));
        else
            $response['redirect'] = ($this->url . $redirect . ((!empty(get())) ? '?' . http_build_query(get()) : ''));
        
        $this->response($response);
    }
    
    /**
     * Função padrão de exportação de dados
     *
     * @access protected
     */
    protected function export() {
        
        //Verifica se há conexão entro o controlador e uma tabela
        if (!$this->set_table()) {
            show_error_page('404');
        }
        
        //Verifica se há permissões para o acesso
        $permissao = $this->get_permission("$this->module/$this->name", 'print');
        if (!is_object($permissao)) {
            show_error_page('403');
        }
        
        //Verifica se formato solicitado existe.
        if (!isset($this::$export_formats[chk_array($_POST, 'format')])) {
            show_error_page('404');
        }
        
        //Verifica se existe colunas na requisição
        if (empty(chk_array($_POST, 'columns'))) {
            show_error_page('404');
        }
        
        //Formato requisitado
        $format = $_POST['format'];
        
        //Colunas para exportar
        $columns = $_POST['columns'];
        
        $excluded = $this::$excluded_export_columns;
        $columns = array_filter($columns, function ($column) use($excluded) {
            //Se for uma coluna excluida, filtra
            if (in_array($column, $excluded)) {
                return false;
            }
            //Chave estrangeira, filtra
            else if (substr($column, -2) == 'id') {
                return false;
            }
            return true;
        });
        
        //Verifica se existe colunas após filtragem
        if (empty($columns)) {
            show_error_page('404');
        }

        //Pega as condições ja pesquisadas
        $conditions = $this->get_grid_conditions();
        
        //Remove limit se houver
        if(isset($conditions['limit'])) {
            unset($conditions['limit']);
        }
        
        //remove offset se houver
        if(isset($conditions['offset'])) {
            unset($conditions['offset']);
        }
        
        //View de exportação do formato 
        if (\mwwork\view::view_exists("$this->module/$this->name/grid/export/$format.txt")) {
            $vw = new \mwwork\view("$this->module/$this->name/grid/export/$format.txt");
        }
        else {
            $vw = new \mwwork\view("app/painelCMS/default/grid/export/$format.txt");
        }
        
        $vw = $this->get_exportgrid_body($vw, $conditions, $columns, $format);
        
        if ($format == 'print') {
            $this->response($vw, array(), 'partial_html');
        }
        else {
            $this->download_content($vw->parse(), "$this->module-$this->name-" . date('YmdHis'), $format);
        }
    }
    
    /**
     * Retorna os módulos do sistema
     *
     * @access public
     */
    public function modules() {
        
        //Verifica se há permissões para o acesso
        $permissao = $this->get_permission("cms/conteudo", 'read');
        if (!is_object($permissao)) {
            show_error_page('403');
        }
        
        $this->response(\mwwork\mwload::get_modules());
    }
    
    /**
     * Retorna as tabelas do módulo requisitado
     *
     * @access public
     */
    public function tables() {
        
        //Verifica se há permissões para o acesso
        $permissao = $this->get_permission("cms/conteudo", 'read');
        if (!is_object($permissao)) {
            show_error_page('403');
        }
        
        //Módulo requisitado
        $requisition_module = chk_array($this->parameters, 0);
        
        //Módulo inválido, mostra erro
        if (!in_array($requisition_module, \mwwork\mwload::get_modules())) {
            show_error_page('404');
        }
        
        $tables = \app\log::connection()->tables();
        $module_tables = array_values(
            array_filter($tables, function($table) use($requisition_module) {
                return(substr($table, 0, strlen($requisition_module) + 1) == "$requisition_module" . "_");
            })
        );
        
        $this->response($module_tables);
    }
    
    /**
     * Retorna os attributos da tabela
     *
     * @access public
     */
    public function attributes() {
        
        //Verifica se há conexão entre o controlador e uma tabela
        if (!$this->set_table()) {
            show_error_page('404');
        }
        
        //Verifica se há permissões para o acesso
        $permissao = $this->get_permission("cms/conteudo", 'read');
        if (!is_object($permissao)) {
            show_error_page('403');
        }
        
        //Identifica o model
        $model = $this->table_class;
        
        //Colunas pegas de forma bruta
        $columns = $model::table()->columns;
        
        //Array que guardará atributos formatados
        $attributes = array();
        
        //Itera sobre as colunas
        foreach ($columns as $column) {
            if ($column->name != 'data_criacao' and $column->name != 'data_exclusao') {
                $attributes[] = $column->name;
            }
        }
        
        $this->response($attributes);
    }
    
    /**
     * Informa os relacionamentos do atributo informado
     *
     * @access public
     */
    public function relationships() {
        
        //Verifica se há conexão entre o controlador e uma tabela
        if (!$this->set_table()) {
            show_error_page('404');
        }
        
        //Verifica se há permissões para o acesso
        $permissao = $this->get_permission("cms/conteudo", 'read');
        if (!is_object($permissao)) {
            show_error_page('403');
        }
        
        //Identifica o model
        $model = $this->table_class;
        
        //Colunas pegas de forma bruta
        $columns = $model::table()->columns;
        
        //Módulo requisitado
        $requisition_relationship = chk_array($this->parameters, 0);
        
        //Coluna não existe, retorna 404
        if (!isset($columns[$requisition_relationship])) {
            show_error_page('404');
        }
        
        //Array que guarda os relacionamentos da coluna requisitada
        $relationships = array();
        
        //Verifica se a tabela está ligada a outra pelo atributo
        if(property_exists($model, 'belongs_to')) {
            foreach ($model::$belongs_to as $relationship) {
                if ($relationship['foreign_key'] == $requisition_relationship) {
                    $relationship_class = $relationship['class_name'];
                    $relationship_class = explode("\\", $relationship_class);
                    
                    if (count($relationship_class) == 1) {
                        $relationships[] = array(
                            'module' => $this->module,
                            'table' => $relationship_class[0]
                        );
                    } else {
                        $relationships[] = array(
                            'module' => $relationship_class[1],
                            'table' => $relationship_class[2]
                        );
                    }
                }
            }
        }

        $this->response($relationships);
    }
}
// class MainController