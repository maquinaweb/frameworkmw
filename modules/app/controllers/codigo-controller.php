<?php

namespace app;

class codigocontroller extends \mwwork\mwcontroller {

    /**
     * Construtor da classe
     *
     * Configura as propriedades e métodos da classe.
     *
     * @access public
     */
    public function __construct($parameters, $module, $name, $action) {
        parent::__construct($parameters, $module, $name, $action);
    }
    
    /**
     * Função que lista as tabelas do banco de dados para manutenção e criação
     * de código.
     * 
     * Pode ser filtrada por módulos.
     */
    public function create() {
        $this->get_permission('app/codigo', 'read');

        //Cria view
        $vw = new \mwwork\view("app/painelCMS/content");
        $vw->addFile('content', 'app/codigo/create');
        
        $vw->breadcrumbs = $this->parse_breadcrumbs($this->action);

        //Pega os modulos do sistema
        $modules = \mwwork\mwload::get_modules();
        
        //Procura no banco tabelas
        $tables = menu::connection()->tables();

        $vw->url = $this->url;
        
        //Itera sobre tabelas para montar a view
        $contatabela = 0;
        
        $first_iteration = true;
        
        foreach ($tables as $key => $value) {
            
            //Separa o nome do módulo (prefixo) do restante da tabela
            $value = explode('_', $value);
            if (count($value) > 1) {
                $module_name = $value[0];
                $table = $value[1];
            } else {
                $module_name = "";
                $table = $value[0];
            }
            
            //Modulo não cadastrado, pula
            if (!in_array($module_name, $modules)) {
                continue;
            }
            
            //Primeira iteração, seta parent module
            if ($first_iteration) {
                $parent_module = $module_name;
                $first_iteration = false;
            }
            
            //Modulo anterior diferente dest
            if ($module_name != $parent_module) {
                $parent_key = array_search($parent_module, $modules);
                $vw->module = $parent_module;
                $vw->module_key = $parent_key;
                
                $vw->block('block_module');
                
                $parent_module = $module_name;
                unset($modules[$parent_key]);
            }

            $vw->table = $table;
            $vw->table_module = $module_name;
            $vw->model = (file_exists(ABSPATH . "/modules/$module_name/models/$table.php")) ? 1 : 0;
            $vw->view = (file_exists(ABSPATH . "/modules/$module_name/views/$table/")) ? 1 : 0;
            $vw->controller = (file_exists(ABSPATH . "/modules/$module_name/controllers/$table-controller.php")) ? 1 : 0;
            $vw->block('block_row');
        }
        if (!$first_iteration) {
            $parent_key = array_search($parent_module, $modules);
            $vw->module = $parent_module;
            $vw->module_key = $parent_key;

            $vw->block('block_module');
        }
        
        $this->response($vw);
    }

    public function generate() {
        $this->get_permission('app/codigo', 'read');
        $vw = new \mwwork\view('app/codigo/generate');
        
        $vw = new \mwwork\view("app/painelCMS/content");
        $vw->addFile('content', 'app/codigo/generate');
        
        $vw->breadcrumbs = $this->parse_breadcrumbs($this->action);
        
        //Se houver script, carrega
        if (\mwwork\view::view_exists("app/codigo/generate/script.js")) {
            $vw_scripts = new \mwwork\view("app/codigo/generate/script.js");
            $vw->scripts = $vw_scripts->parse();
        }
        
        //Módulo
        $module = chk_array($this->parameters, 0);
        
        //Tabela
        $table = chk_array($this->parameters, 1);
        
        //Classe do model
        $model = "\\$module\\$table";
        
        //Flag de erro
        $invalid_requisition = false;
        
        //Verifica se módulo existe
        if (!in_array($module, \mwwork\mwload::get_modules())) {
            $invalid_requisition = true;
            $error_message = "O módulo requisitado não está cadastrado no sistema.";
        }
        
        //Verifica se tabela existe
        if (!$invalid_requisition and !  in_array("$module" . "_$table", menu::connection()->tables())) {
            $invalid_requisition = true;
            $error_message = "Tabela inexistente";
        }
        
        //Manda resposta de erro 
        if ($invalid_requisition) {
            $response = array(
                'status'    => '1',                 // 0 para erro, 1 para sucesso
                'message'   => $error_message,      // mensagem sobre o resultado   // contém os erros caso haja algum
                'redirect'  => HOME_URI . "/app/codigo/create?error=$error_message"                 // link de redirecionamento caso haja algum
            );
             
            $this->response($response);
        }
        
        $vw->url = HOME_URI;
        $vw->module = "app";
        $vw->controller = "codigo";
        $vw->save_module = $module;
        $vw->save_table = $table;
        
        //Verifica que arquivo do model existe. Se não existe, cria um básico para
        //ter acesso as informações da tabela
        if (!file_exists(ABSPATH . "/modules/$module/models/$table.php")) {
            $vw_model = new \mwwork\view("app/codigo/templates/basic_model.txt", true);
            $vw_model->module = $module;
            $vw_model->table = $table;
            
            file_put_contents(ABSPATH . "/modules/$module/models/$table.php",
                    $vw_model->parse());
        }
        
        //Instência do model criado
        $object = new $model;
        
        //Campos da tabela
        $columns = $model::table()->columns;
        $column_counter = 1;
        
        //Itera pelos campos para montar o passo montar a tabela de criação de 
        //corpo de grid
        foreach ($columns as $column) {
            $vw->column = $column->name;
            $vw->column_counter = $column_counter;
            $vw->column_preset = implode(" ", array_map('ucfirst', explode("_", $column->name)));
            $vw->block('block_grid_body_row');
            $column_counter++;       
        }
        
        $column_counter = 1;
        
        //Itera pelos campos para montar o passo montar a tabela de criação de 
        //corpo do form
        foreach ($columns as $column) {
            $vw->column = $column->name;
            $vw->column_counter = $column_counter;
            $vw->column_preset = implode(" ", array_map('ucfirst', explode("_", $column->name)));
            $vw->block('block_form_body_row');
            $column_counter++;       
        }
        
        $column_counter = 1;
        
        //Itera pelos campos para montar o passo de montar validações do model
        foreach ($columns as $column) {
            $vw->column = $column->name;
            $vw->column_counter = $column_counter;
            $vw->block('block_model_validation_row'); 
            $column_counter++;    
        }
        
        $vw->descritive_name = ucfirst($table);
        $vw->descritive_name_plural =  ucfirst($table) . "s";
        
        $vw->menu = new \app\menu();
        $vw->modulo = new \app\modulo();
        
        $this->response($vw);
    }
    
    public function save() {
        
        //Módulo
        $module = chk_array($this->parameters, 0);
        
        //Tabela
        $table = chk_array($this->parameters, 1);
        
        //Classe do model
        $model = "\\$module\\$table";
        
        $object = new $model;
        
        //Atributos da tabela
        $attributes = $object->attributes();
        
        //Flag de erro
        $invalid_requisition = false;
        
        //Verifica se módulo existe
        if (!in_array($module, \mwwork\mwload::get_modules())) {
            $invalid_requisition = true;
            $error_message = "O módulo requisitado não está cadastrado no sistema.";
        }
        
        //Verifica se tabela existe
        if (!$invalid_requisition and !  in_array("$module" . "_$table", menu::connection()->tables())) {
            $invalid_requisition = true;
            $error_message = "Tabela inexistente";
        }
        
        //Manda resposta de erro 
        if ($invalid_requisition) {
            $response = array(
                'status'    => '0',                 // 0 para erro, 1 para sucesso
                'message'   => $error_message,      // mensagem sobre o resultado   // contém os erros caso haja algum
                'redirect'  => HOME_URI . "/app/codigo/create?error=$error_message"                 // link de redirecionamento caso haja algum
            );
             
            $this->response($response);
        }
        
        //Foi marcado que grid deve ser cirado, logo, cria grid
        if (chk_array($_POST, 'has_grid') and isset($_POST['grid_body'])) {
            
            //Colunas selecionadas
            $columns = chk_array($_POST['grid_body'], 'columns');
            
            $columns = array_filter($columns, function($val) use($attributes) {
                return array_key_exists($val, $attributes);
            });
            
            //Labels da grid
            $labels = (isset($_POST['grid_body']['label'])) ? $_POST['grid_body']['label'] : array();
            
            //Define prioridades da grid
            $priorities = (isset($_POST['grid_body']['priorities'])) ? $_POST['grid_body']['priorities'] : array();
            
            //Define ordem da grid
            $orders = (isset($_POST['grid_body']['order'])) ? $_POST['grid_body']['order'] : array();
            
            //Ordena colunas
            usort($columns, function($val_a, $val_b) use($orders) {
                $order_a = (isset($orders[$val_a])) ? $orders[$val_a] : 10;
                $order_b = (isset($orders[$val_b])) ? $orders[$val_b] : 10;
                return ($order_a > $order_b);
            });
            
            //View base para a grid
            $vw_grid = new \mwwork\view("app/codigo/templates/grid.txt", true);
            
            //Primeira iteração
            $first_iteration = true;
            
            //Itera sobre colunas pra montar grid
            foreach ($columns as $column) {
                $vw_grid->column = $column;
                $vw_grid->label = (isset($labels[$column])) ? $labels[$column] : $column;
                $vw_grid->grid_priority = (isset($priorities[$column])) ? $priorities[$column] : 10;
                
                $vw_grid->block('block_grid_header');
                
                //Primeira iteração destaca a linha, devido a isso, o procedimento
                //difere um pouco
                if ($first_iteration) {
                    $vw_grid->block('block_grid_row_primary');
                    $first_iteration = false;
                }
                else {
                    $vw_grid->block('block_grid_row');
                }
            }
            
            //Salva o arquivo
            if (!file_exists(ABSPATH . "/modules/$module/views/$table/")) {
                mkdir(ABSPATH . "/modules/$module/views/$table/", 0755, true); 
            }
            file_put_contents(ABSPATH . "/modules/$module/views/$table/grid.html",
                    $vw_grid->parse());
        }
        
        //Foi marcado que form deve ser criado, logo, cria o form
        if (chk_array($_POST, 'has_form') and isset($_POST['form_body'])) {
            
            //Colunas selecionadas
            $columns = chk_array($_POST['form_body'], 'columns');
            
            $columns = array_filter($columns, function($val) use($attributes) {
                return array_key_exists($val, $attributes);
            });
            
            //Labels do form
            $labels = (isset($_POST['form_body']['label'])) ? $_POST['form_body']['label'] : array();
            
            //Define ordem do form
            $orders = (isset($_POST['form_body']['order'])) ? $_POST['form_body']['order'] : array();
            
            //Campos requeridos
            $required = (isset($_POST['form_body']['required'])) ? $_POST['form_body']['required'] : array();
            
            //Campos com mascara
            $masks = (isset($_POST['form_body']['mask'])) ? $_POST['form_body']['mask'] : array();
            
            //Tipos dos campos
            $types = (isset($_POST['form_body']['type'])) ? $_POST['form_body']['type'] : array();
            
            //Ordena colunas
            usort($columns, function($val_a, $val_b) use($orders) {
                $order_a = (isset($orders[$val_a])) ? $orders[$val_a] : 10;
                $order_b = (isset($orders[$val_b])) ? $orders[$val_b] : 10;
                return ($order_a > $order_b);
            });
            
            //View base para o form
            $vw_form = new \mwwork\view("app/codigo/templates/form.txt", true);
            
            //Por padrão, form não tem input de arquivo
            $vw_form->has_file = 0;
            
            //Itera sobre colunas pra montar form
            foreach ($columns as $column) {
                $type = (isset($types[$column])) ? $types[$column] : 'input_text';
                
                //Verifica se o tipo informado está presente no modelo
                if ($vw_form->blockexists("block_form_field_$type")) {
                    $vw_form->column = $column;
                    $vw_form->label = (isset($labels[$column])) ? $labels[$column] : $column;
                    $vw_form->required = (isset($required[$column])) ? $required[$column] : 0;
                    $vw_form->mask = (isset($masks[$column])) ? $masks[$column] : "";
                    $vw_form->block("block_form_field_$type");
                    $vw_form->block("block_form_row");
                    
                    if ($type == 'input_file')
                        $vw_form->has_file = 1;
                }
            }
            
            //Salva o arquivo
            if (!file_exists(ABSPATH . "/modules/$module/views/$table/")) {
                mkdir(ABSPATH . "/modules/$module/views/$table/", 0755, true); 
            }
            file_put_contents(ABSPATH . "/modules/$module/views/$table/form.html",
                $vw_form->parse());
        }
        
        //Verifica se foi enviado dados sobre o model para criar
        if (chk_array($_POST, 'model_validation')) {
            
            //Campos requeridos
            $requireds = (isset($_POST['model_validation']['required'])) ? $_POST['model_validation']['required'] : array();
                    
            //Campos fixos do model
            $fixeds = (isset($_POST['model_validation']['fixed'])) ? $_POST['model_validation']['fixed'] : array();
            
            //Campos com validação regex
            $regex = (isset($_POST['model_validation']['regex'])) ? $_POST['model_validation']['regex'] : array();
            
            //Id de configuração geral
            $identifier = (isset($_POST['controller_variable'])) ? chk_array($_POST['controller_variable'], 'identifier') : "";
            
            //View base para o model
            $vw_model = new \mwwork\view("app/codigo/templates/model.txt", true);
            
            $vw_model->module = $module;
            $vw_model->table = $table;
            
            //Monta bloco de campos requeridos
            for($counter = 0; $counter < count($requireds); $counter++) {
                $vw_model->column = $requireds[$counter];
                $vw_model->is_last_required_column = !(($counter + 1) < count($requireds));
                $vw_model->block('block_required_validation');
            }
            
            //Filtra para array conter apenas regex nã vazios
            $regex =  array_filter($regex, function($val){
                return !empty($val);
            });
            
            //Como regez é um array associativo, separa as chaves em outra variavel
            $regex_keys = array_keys($regex);
            
            //Monta bloco de campos com regex
            for($counter = 0; $counter < count($regex_keys); $counter++) {
                $vw_model->column = $regex_keys[$counter];
                $vw_model->column_regex = $regex[$regex_keys[$counter]];
                $vw_model->is_last_regex_column = !(($counter + 1) < count($regex_keys));
                $vw_model->block('block_regex_validation');
            }
            
            //Não há valores fixos e não há identificador, então usa bloco para 
            //função de POST padrão. Caso contrário, monta a função avançada
            if (empty($fixeds) and empty($identifier)) {
                $vw_model->block('block_default_post');
            }
            else {
                $vw_model->identifier = $identifier;
                
                if (!empty($identifier))
                    $vw_model->block('block_identifier_post');
                
                //Coloca os elemntos do array entre '' para o php reconhecer
                $fixeds = array_map(function($val) {
                    return "'$val'";
                }, $fixeds);
                
                $vw_model->fixed_values = implode(',', $fixeds);
                
                $vw_model->block('block_advanced_post');
            }
            
            //Não  há identificador, então usa bloco de acesso padrão. Caso 
            //contrário, monta a função avançada
            if(empty($identifier)) {
                $vw_model->block('block_default_access');
            }
            else {
                $vw_model->identifier = $identifier;
                $vw_model->block('block_identifier_access');
            }
            
            //Salva o arquivo
            if (!file_exists(ABSPATH . "/modules/$module/models/")) {
                mkdir(ABSPATH . "/modules/$module/models/", 0755, true); 
            }
            file_put_contents(ABSPATH . "/modules/$module/models/$table.php",
                $vw_model->parse());
            
        }
       
        //Criação do controller
        
        //View base para o controller
        $vw_controller = new \mwwork\view("app/codigo/templates/controller.txt", true);
        
        $controller_variables = (isset($_POST['controller_variable'])) ? $_POST['controller_variable'] : array();
        
        $vw_controller->descritive_name = chk_array($controller_variables, 'descritive_name');
        $vw_controller->descritive_name_plural = chk_array($controller_variables, 'descritive_name_plural');
        $vw_controller->module = $module;
        $vw_controller->table = $table;
        
        //Salva o arquivo
        if (!file_exists(ABSPATH . "/modules/$module/controllers/")) {
            mkdir(ABSPATH . "/modules/$module/controllers/", 0755, true); 
        }
        file_put_contents(ABSPATH . "/modules/$module/controllers/$table-controller.php",
            $vw_controller->parse());
        
        //Inicializa a variável de menu
        $menu = null;
        //Foi marcado que o modulo deve ser criado, logo, cria o modulo
        if (chk_array($_POST, 'has_modulo')) {
            
            //Array com dados do modulo
            $modulo_data = isset($_POST['modulo']) ? $_POST['modulo'] : array();
            
            $modulo = new \app\modulo();
            $modulo->grupomodulo_id = $modulo_data['grupomodulo_id'];
            $modulo->nome = $modulo_data['nome'];
            $modulo->descricao = $modulo_data['descricao'];
            $modulo->save();
        }
        //Foi marcado que o menu deve ser criado, logo, cria o menu
        if (chk_array($_POST, 'has_menu')) {
            
            $last_menu_order = 1;
            
            $last_menu = \app\menu::first(array(
                'limit' => '1',
                'order' => 'ordem desc'
            ));
            
            if (is_object($last_menu)) {
                $last_menu_order = (int) $last_menu->ordem + 1;
            }
            
            //Array com dados do menu
            $menu_data = isset($_POST['menu']) ? $_POST['menu'] : array();
            
            $menu = new \app\menu();
            $menu->grupomenu_id = $menu_data['grupomenu_id'];
            $menu->menu_id = $menu_data['menu_id'];
            //$menu->modulo_id = $modulo->id;
            $menu->nome = $menu_data['nome'];
            $menu->url = $menu_data['url'];
            $menu->icone = $menu_data['icone'];
            $menu->ordem = $last_menu_order;
            if(!empty($menu_data['modulo_id'])){
                if($menu_data['modulo_id']>0)
                    $menu->modulo_id = $menu_data['modulo_id'] ;
                else{
                    if(is_object($modulo)){
                         $menu->modulo_id = $modulo->id ;
                    }else{
                        $menu->modulo_id = null;
                    }
                }
            }else{
                $menu->modulo_id = null;
            }
            $menu->save();
        }
        
        
        $response = array(
            'status'    => '1',                 // 0 para erro, 1 para sucesso
            'message'   => "Código para $module/$table criado com sucesso!",      // mensagem sobre o resultado   // contém os erros caso haja algum
            'redirect'  => HOME_URI . "/app/codigo/create?success=Código para $module/$table criado com sucesso!"                 // link de redirecionamento caso haja algum
        );
        
        $this->response($response);
    }
}
