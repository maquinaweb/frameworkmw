<?php namespace cms;

class contentmanager {
    
    /**
     * Funções de mysql que podem ser usadas no lugar de um valor
     *
     * @access private
     */
    public static $valid_mysql_compare_functions = array(
        'NOW()'
    );
    
    /**
     * Funções de mysql que podem ser usadas para filtrar um campo antes da comparação
     *
     * @access private
     */
    public static $valid_mysql_filter_functions = array(
        'STR_TO_DATE' => array(
            'template' => 'STR_TO_DATE({value}, "%d/%m/%Y")',
            'description' => 'Converte uma string em uma formato de data específico'
        ),
        'YEAR' => array(
            'template' => 'YEAR({value})',
            'description' => 'Informa o ano da data específicada'
        ),
        'MONTH' => array(
            'template' => 'MONTH({value})',
            'description' => 'Informa o mês da data específicada'
        ),
        'DAY' => array(
            'template' => 'DAY({value})',
            'description' => 'Informa o dia da data específicada'
        )
        
    );
    
    /**
     * Preenche váriaveis padrão em uma view e seu contéudo, caso <b>$sitesessao</b>
     * seja informado
     * 
     * @access public
     */
    public static function process($vw, $sitesessao = null) {
        
        ////////////////////////////////////////////////////////////////////////
        //VARIAVEIS GERAIS
        ////////////////////////////////////////////////////////////////////////
        
        //URL do domínio
        if ($vw->exists('url')) {
            $vw->url = HOME_URI;
        }
        //Data de hoje
        if ($vw->exists('now')) {
            $vw->now = new \DateTime();
        }
        
        //Verifica se a sessão foi informada
        if (is_object($sitesessao)) {
            
            $vw = self::process_destaques($vw, $sitesessao);
            
            //try {
                $vw = self::process_content($vw, $sitesessao);
            //}
            //catch (\Exception $ex) {
//                print_r(\conteudo\modulo::connection()->last_query);
//                die;
            //}
            
            $vw = self::process_parameters($vw, $sitesessao);
            
            if (!empty($sitesessao->grupomenu_id)) {
                $vw = self::process_menu($vw, $sitesessao);
            }
        }
        
        return $vw;
    }
    
    /**
     * Preenche destaques da view baseado no <b>$sitesessao</b> informado
     * 
     * @access private
     */
    private static function process_destaques($vw, $sitesessao) {
        
        ////////////////////////////////////////////////////////////////////////
        //DESTAQUES
        ////////////////////////////////////////////////////////////////////////

        //Carrega objeto de destaque
        $destaques = (is_object($sitesessao->destaques)) ? $sitesessao->destaques : new \cms\destaques();

        //Itera parametros para verificar existência de destaques
        foreach ($sitesessao->parametros as $attributte => $parametro_destaque) {

            //Verifica se existe um bloco de repetição
            if($vw->blockexists("block_loop_var_$attributte")) {
                
                //Verifica se atributo existe
                if ($vw->exists("var_$attributte")) {
                    //Define a quantidade de repetições
                    $loops = 1;

                    if (property_exists($parametro_destaque, 'loops')) {
                        $loops = intval($parametro_destaque->loops);
                    }

                    if ($loops < 1) {
                        $loops = 1;
                    }

                    if ($loops > 20) {
                        $loops = 20;
                    }

                    //Itera sobre repetições do destaque
                    for ($count = 0; $count < $loops; $count++) {

                        //Verifica se o destaque desse atributo foi atribuido
                        if(property_exists($destaques->parametros, $attributte)
                                and property_exists($destaques->parametros->{$attributte}, 'entries')
                                and isset($destaques->parametros->{$attributte}->entries[$count])){
                                    
                            $vw->{"var_$attributte"} = $destaques->parametros->{$attributte}->entries[$count]->value;
                            
                            $vw->block("block_loop_var_$attributte");
                        }
                    }
                }
            }
            //Não há bloco, mas se houver a variavel, mostra o primeiro
            else {
                
                //Verifica se atributo existe
                if ($vw->exists("var_$attributte")) {

                    //Verifica se o destaque desse atributo foi atribuido
                    if(property_exists($destaques->parametros, $attributte)
                            and property_exists($destaques->parametros->{$attributte}, 'entries')
                            and isset($destaques->parametros->{$attributte}->entries[0])){
                        $vw->{"var_$attributte"} = $destaques->parametros->{$attributte}->entries[0]->value;
                    }
                    else {
                        $vw->{"var_$attributte"} = "";
                    }
                }
            }
            
        }
        
        return $vw;
    }
    
    /**
     * Preenche parametros da view baseado no <b>$sitesessao->site</b> informado
     * 
     * @access private
     */
    private static function process_parameters($vw, $sitesessao) {
        
        ////////////////////////////////////////////////////////////////////////
        //PARÂMETROS
        ////////////////////////////////////////////////////////////////////////

        //Carrega objeto de parâmetros
        $parameters_object = $sitesessao->site->parametros;

        //Verifica se grupos existe
        if (property_exists($parameters_object, 'groups')) {
            
            //Itera grupos de parâmetros
            foreach ($parameters_object->groups as $group) {
                
                //Verifica se parâmetros existem
                if (property_exists($group, 'fields')) {
                    
                    //Itera parâmetros do grupo
                    foreach ($group->fields as $parameter) {

                        //Verifica se atributo existe
                        if ($vw->exists("par_$parameter->name")) {

                            //Verifica se o valor desse atributo foi atribuido
                            if(property_exists($parameter, 'value') and $parameter->value !== null){
                                $vw->{"par_$parameter->name"} = $parameter->value;
                            }
                            else {
                                $vw->{"par_$parameter->name"} = "";
                            }
                        }
                    }
                }
            }  
        }

        return $vw;
    }
    
    /**
     * Preenche menus da view baseado no <b>$sitesessao->grupomenu</b> informado
     * 
     * @access private
     */
    private static function process_menu($vw, $sitesessao) {
        
        ////////////////////////////////////////////////////////////////////////
        //MENU
        ////////////////////////////////////////////////////////////////////////
        
        //Verifica se grupo de menu e bloco existem
        if (is_object($sitesessao->grupomenu) and $vw->blockexists('block_menu_' . $sitesessao->grupomenu->nome_sistema)) {
            
            $menus = \cms\menu::all(array(
                'conditions' => array('grupomenu_id = ? and menu_id is null and data_exclusao is null',
                    $sitesessao->grupomenu->id)
            ));
            
            //Verifica se algum menu encontra-se na condição
            if (!empty($menus)) {
                
                $vw = self::parse_menus($vw, $menus, $sitesessao->grupomenu, 0, 9);
                
            }
        }

        return $vw;
    }
    
    /**
     * Recebe um objeto no padrão "Group" e retorna a string do grupo de filtro
     * 
     * @access public
     */
    public static function parse_menus($vw, $menus, $grupomenu, $iteration = 0 , $loops = 9) {
        //Contador
        $counter = 0;
        
        //Iteração sobre menus
        foreach ($menus as $menu) {
            
            $submenus = null;
            
            //Verifica se é o menu pai ou um sub menu
            if ($iteration == 0) {
                modeltemplate($menu, $vw, 'menu');
                
                //Variáveis adicionais
                if ($vw->exists('menu_counter')) {
                    $vw->menu_counter = $counter;
                }
                
                if ($vw->exists('menu_children')) {
                    $submenus = \cms\menu::all(array(
                        'conditions' => array('menu_id = ? and data_exclusao is null',
                            $menu->id)
                    ));
                    
                    $vw->menu_children = count($submenus);
                }
            }
            else {
                modeltemplate($menu, $vw, 'menu_sub' . $iteration);
                
                //Variáveis adicionais
                if ($vw->exists('menu_sub' . $iteration . '_counter')) {
                    $vw->{'menu_sub' . $iteration . '_counter'} = $counter;
                }
                
                if ($vw->exists('menu_sub' . $iteration . '_children')) {
                    $submenus = \cms\menu::all(array(
                        'conditions' => array('menu_id = ? and data_exclusao is null',
                            $menu->id)
                    ));
                    
                    $vw->{'menu_sub' . $iteration . '_children'} = count($submenus);
                }
            }
            
            //Verifica se há bloco de submenu
            if ($iteration < $loops 
                    and $vw->blockexists('block_menu_' . $grupomenu->nome_sistema . '_sub' . ($iteration + 1))) {

                //Se não foi consultado, recebe submenus agora
                if ($submenus == null) {
                    $submenus = \cms\menu::all(array(
                        'conditions' => array('menu_id = ? and data_exclusao is null',
                            $menu->id)
                    ));
                }
                
                //Se há submenus, prossegue
                if(!empty($submenus)) {
                    $vw = self::parse_menus($vw, $submenus, $grupomenu, ($iteration + 1), $loops);
                }
            }
            
            //Verifica se é o menu pai ou um sub menu
            if ($iteration == 0) {
                $vw->block('block_menu_' . $grupomenu->nome_sistema);
            }
            else {
                $vw->block('block_menu_' . $grupomenu->nome_sistema . '_sub' . $iteration);
            }
            $counter++;
        }
       
        return $vw;
    }
    
    /**
     * Preenche conteúdos da view baseado no <b>$sitesessao</b> informado
     * 
     * @access private
     */
    private static function process_content($vw, $sitesessao) {
        
        ////////////////////////////////////////////////////////////////////////
        //CONTEÚDOS
        ////////////////////////////////////////////////////////////////////////

        //Itera sobre os contéudos do sitesessao
        foreach ($sitesessao->sitesessaoconteudo as $sitesessaoconteudo) {

            //Verifica se é um conteúdo ou genérico
            if(empty($sitesessaoconteudo->conteudo_id)) {
                //Passa para função de processamento de genérico
                $vw = self::process_content_gen($vw, $sitesessaoconteudo);
            }
            else {
                //Conteúdo propriamente dito ligado pelo sitesessaoconteudo
                $conteudo = $sitesessaoconteudo->conteudo;

                //Blocos e variáveis relacionados ao conteúdo
                $blocos = $conteudo->blocos;
                $variaveis = $conteudo->variaveis;

                //Verifica se esta corretamente ligado a um model. Se não, pula
                if (!property_exists($conteudo->parametros, 'module') 
                        or !property_exists($conteudo->parametros, 'table')) {
                    continue;
                }

                if(!class_exists("\\" . $conteudo->parametros->module . "\\" . $conteudo->parametros->table)) {
                    continue;
                }

                //Separa o nome dessa sitesessaoconteudo
                $sitesessaoconteudo_name = $sitesessaoconteudo->parametros->name;

                //Seta variáveis
                $module = $conteudo->parametros->module;
                $table = $conteudo->parametros->table;

                $model = "\\$module\\$table";

                $filter_rules = $conteudo->parametros->columns->filter;
                $order_rules = $conteudo->parametros->columns->order;

                $filters = (property_exists($sitesessaoconteudo->parametros, 'filters')) ?
                        $sitesessaoconteudo->parametros->filters : array();

                $orders = (property_exists($sitesessaoconteudo->parametros, 'orders')) ?
                        $sitesessaoconteudo->parametros->orders : array();

                $limit = (property_exists($sitesessaoconteudo->parametros, 'limit')) ?
                        $sitesessaoconteudo->parametros->limit : 10;
                
                //Verifica se há paginação (para offset)
                $page = 1;

                //Verifica se qusery string de pagina existe
                if (isset($_GET['pag_' . $sitesessaoconteudo->sitesessao_id . '_' . $sitesessaoconteudo_name])) {

                    //Seta novo valor da pagina
                    $page = abs(intval($_GET['pag_' . $sitesessaoconteudo->sitesessao_id . '_' . $sitesessaoconteudo_name]));

                    if ($page < 1) {
                        $page = 1;
                    }
                }

                //Monta a query de pesquisa
                $query = self::create_content_query($filter_rules, $filters, $order_rules, $orders, $limit, $page);

                //Procure registros
                $rows = $model::all($query);

                //Itera sobre registros para preencher view
                foreach ($rows as $row) {

                    //Preenche variáveis
                    foreach ($variaveis as $variavel) {

                        //Verifica se variável existe
                        if ($vw->exists("$variavel->variavel_sistema" . "_" . "$sitesessaoconteudo_name")) {

                            //Verifica se attributo ou método compatível existe. Se não, joga vazio
                            if (method_exists($row, "get$variavel->variavel_sistema")) {
                                $vw->{"$variavel->variavel_sistema" . "_" . "$sitesessaoconteudo_name"} = $row->{"get$variavel->variavel_sistema"}();
                            }
                            else if (isset($row->attributes()[$variavel->variavel_sistema])) {
                                $vw->{"$variavel->variavel_sistema" . "_" . "$sitesessaoconteudo_name"} = $row->{$variavel->variavel_sistema};
                            }
                            else {
                                $vw->{"$variavel->variavel_sistema" . "_" . "$sitesessaoconteudo_name"} = "";
                            }
                        }
                    }

                    //Roda blocos
                    foreach ($blocos as $bloco) {

                        //Verifica se bloco existe
                        if($vw->blockexists("$bloco->bloco_sistema" . "_" . "$sitesessaoconteudo_name")) {
                            $vw->block("$bloco->bloco_sistema" . "_" . "$sitesessaoconteudo_name");
                        }
                    }
                }
                
                //Modifica query para pegar numero total de registros
                $modified_query = $query;
                unset($modified_query['offset']);
                unset($modified_query['limit']);
                unset($modified_query['group']);
                
                $modified_query['select'] = "COUNT(DISTINCT id)";

                //Busca por sql
                $sql = $model::table()->options_to_sql($modified_query);
                $values = $sql->get_where_values();

                //Total de registros
                $total = $model::connection()->query_and_fetch_one($sql->to_s(),$values);

                //Última página
                $last_page = ceil($total/$query['limit']); 

                //Variáveis de paginação
                if($vw->exists("pagination_first_$sitesessaoconteudo_name")) {

                    if ($page == 1) {
                        $vw->{"pagination_first_$sitesessaoconteudo_name"} = "";
                    }
                    else {
                        $vw->{"pagination_first_$sitesessaoconteudo_name"} = 1;
                    }
                }

                if($vw->exists("pagination_last_$sitesessaoconteudo_name")) {

                    if ($page == $last_page) {
                        $vw->{"pagination_last_$sitesessaoconteudo_name"} = "";
                    }
                    else {
                        $vw->{"pagination_last_$sitesessaoconteudo_name"} = $last_page;
                    }
                }

                if($vw->exists("pagination_prev_$sitesessaoconteudo_name")) {
                    if ($page == 1) {
                        $vw->{"pagination_prev_$sitesessaoconteudo_name"} = "";
                    }
                    else {
                        $vw->{"pagination_prev_$sitesessaoconteudo_name"} = $page - 1;
                    }
                }

                if($vw->exists("pagination_next_$sitesessaoconteudo_name")) {
                    if ($page == $last_page) {
                        $vw->{"pagination_next_$sitesessaoconteudo_name"} = "";
                    }
                    else {
                        $vw->{"pagination_next_$sitesessaoconteudo_name"} = $page + 1;
                    }
                }

                //Verifica se bloco existe paginação
                if($vw->blockexists("pagination_" . "$sitesessaoconteudo_name")) {

                    //Verifica numero maximo de paginação lateral
                    $max_pages = 0;

                    if (property_exists($sitesessaoconteudo->parametros, 'pagination_number')) {
                        $max_pages = abs(intval($sitesessaoconteudo->parametros->pagination_number));
                    }

                    $page_start = $page - $max_pages;
                    if ($page_start < 1)
                        $page_start = 1;
                    $page_end = $page + $max_pages;
                    if ($page_end > $last_page)
                        $page_end = $last_page;

                    //Loop de paginação
                    for ($p = $page_start; $p <= $page_end; $p++) {

                        if ($vw->exists("pagination_page_$sitesessaoconteudo_name")) {
                            $vw->{"pagination_page_$sitesessaoconteudo_name"} = $p;
                        }

                        if ($vw->exists("pagination_active_$sitesessaoconteudo_name")) {
                            if ($page == $p) {
                                $vw->{"pagination_active_$sitesessaoconteudo_name"} = 1;
                            }
                            else {
                                $vw->{"pagination_active_$sitesessaoconteudo_name"} = 0;
                            }
                        }
                        $vw->block("pagination_" . "$sitesessaoconteudo_name");
                    }
                }
            }
        }
        return $vw;
    }
    
    /**
     * Preenche conteúdos genéricos da view baseado no <b>$sitesessao</b> informado
     * 
     * @access private
     */
    private static function process_content_gen($vw, $sitesessaoconteudo) {
        //Sanitiza id
        $id = intval(preg_replace('/[^\d]/', '', $sitesessaoconteudo->parametros->id_conteudo));

        $conteudo = null;
        
        //Verifica se é módulo ou filtro
        if ($sitesessaoconteudo->parametros->type_conteudo == "module") {
            $conteudo = \conteudo\modulo::first(array(
                'conditions' => array('id = ? and data_exclusao is null',
                    $id)
            ));
            $model = "\\conteudo\\moduloitens";
            $prefix = "moduloitens";
        }
        else if ($sitesessaoconteudo->parametros->type_conteudo == "filter") {
            $conteudo = \conteudo\filtro::first(array(
                'conditions' => array('id = ? and data_exclusao is null',
                    $id)
            ));
            $model = "\\conteudo\\filtroitens";
            $prefix = "filtroitens";
        }
        
        //Verifica se conteudo foi encontrado; Se não, retorna
        if($conteudo == null) {
            return $vw;
        }

        //Inicializa campos
        $fields = new \stdClass();
        
        //Verifica campos do objeto
        if (property_exists($conteudo->parametros, 'fields')) {
            $fields = $conteudo->parametros->fields;
        }

        //Separa o nome dessa sitesessaoconteudo
        $sitesessaoconteudo_name = $sitesessaoconteudo->parametros->name;

        $filters = (property_exists($sitesessaoconteudo->parametros, 'filters')) ?
                $sitesessaoconteudo->parametros->filters : array();

        $orders = (property_exists($sitesessaoconteudo->parametros, 'orders')) ?
                $sitesessaoconteudo->parametros->orders : array();

        $limit = (property_exists($sitesessaoconteudo->parametros, 'limit')) ?
                $sitesessaoconteudo->parametros->limit : 10;
        
        //Verifica se há paginação (para offset)
        $page = 1;
        
        //Verifica se qusery string de pagina existe
        if (isset($_GET['pag_' . $sitesessaoconteudo->sitesessao_id . '_' . $sitesessaoconteudo_name])) {
            
            //Seta novo valor da pagina
            $page = abs(intval($_GET['pag_' . $sitesessaoconteudo->sitesessao_id . '_' . $sitesessaoconteudo_name]));
            
            if ($page < 1) {
                $page = 1;
            }
        }

        //Monta a query de pesquisa
        $query = self::create_content_gen_query($fields, $filters, $orders, $limit, $page, $prefix, $id);

        //Procure registros
        $rows = $model::all($query);
        
        //Itera sobre registros para preencher view
        foreach ($rows as $row) {

            //Preenche variáveis (campos)
            foreach ($fields as $field) {

                //Verifica se variável existe
                if ($vw->exists("$field->name" . "_" . "$sitesessaoconteudo_name")) {

                    //Verifica se attributo existe. Se não, joga vazio
                    if (property_exists($row->parametros, 'fields')
                            and property_exists($row->parametros->fields, $field->name)
                            and property_exists($row->parametros->fields->{$field->name}, 'value')) {
                        $vw->{"$field->name" . "_" . "$sitesessaoconteudo_name"} = $row->parametros->fields->{$field->name}->value;
                    }
                    else {
                        $vw->{"$field->name" . "_" . "$sitesessaoconteudo_name"} = "";
                    }
                }
            }
            
            //Verifica se atributos naturais existem
            if ($vw->exists("id_$sitesessaoconteudo_name")) {
                $vw->{"id_$sitesessaoconteudo_name"} = $row->id;
            }
            if ($vw->exists("nome_$sitesessaoconteudo_name")) {
                $vw->{"nome_$sitesessaoconteudo_name"} = $row->nome;
            }
            
            //Verifica se bloco existe
            if($vw->blockexists("repetition_" . "$sitesessaoconteudo_name")) {
                $vw->block("repetition_" . "$sitesessaoconteudo_name");
            }
        }
        
        //Modifica query para pegar numero total de registros
        $modified_query = $query;
        unset($modified_query['offset']);
        unset($modified_query['limit']);
        unset($modified_query['group']);
        
        if ($prefix == 'moduloitens') {
            $modified_query['select'] = "COUNT(DISTINCT moduloitens.id)";
        }
        else if ($prefix == 'filtroitens') {
            $modified_query['select'] = "COUNT(DISTINCT filtroitens.id)";
        }
        
        //Busca por sql
        $sql = $model::table()->options_to_sql($modified_query);
        $values = $sql->get_where_values();
        
        //Total de registros
        $total = $model::connection()->query_and_fetch_one($sql->to_s(),$values);

        //Última página
        $last_page = ceil($total/$query['limit']); 

        //Variáveis de paginação
        if($vw->exists("pagination_first_$sitesessaoconteudo_name")) {
            
            if ($page == 1) {
                $vw->{"pagination_first_$sitesessaoconteudo_name"} = "";
            }
            else {
                $vw->{"pagination_first_$sitesessaoconteudo_name"} = 1;
            }
        }
        
        if($vw->exists("pagination_last_$sitesessaoconteudo_name")) {
            
            if ($page == $last_page) {
                $vw->{"pagination_last_$sitesessaoconteudo_name"} = "";
            }
            else {
                $vw->{"pagination_last_$sitesessaoconteudo_name"} = $last_page;
            }
        }
        
        if($vw->exists("pagination_prev_$sitesessaoconteudo_name")) {
            if ($page == 1) {
                $vw->{"pagination_prev_$sitesessaoconteudo_name"} = "";
            }
            else {
                $vw->{"pagination_prev_$sitesessaoconteudo_name"} = $page - 1;
            }
        }
        
        if($vw->exists("pagination_next_$sitesessaoconteudo_name")) {
            if ($page == $last_page) {
                $vw->{"pagination_next_$sitesessaoconteudo_name"} = "";
            }
            else {
                $vw->{"pagination_next_$sitesessaoconteudo_name"} = $page + 1;
            }
        }
        
        //Verifica se bloco existe paginação
        if($vw->blockexists("pagination_" . "$sitesessaoconteudo_name")) {
            
            //Verifica numero maximo de paginação lateral
            $max_pages = 0;
            
            if (property_exists($sitesessaoconteudo->parametros, 'pagination_number')) {
                $max_pages = abs(intval($sitesessaoconteudo->parametros->pagination_number));
            }
            
            $page_start = $page - $max_pages;
            if ($page_start < 1)
                $page_start = 1;
            $page_end = $page + $max_pages;
            if ($page_end > $last_page)
                $page_end = $last_page;

            //Loop de paginação
            for ($p = $page_start; $p <= $page_end; $p++) {
                
                if ($vw->exists("pagination_page_$sitesessaoconteudo_name")) {
                    $vw->{"pagination_page_$sitesessaoconteudo_name"} = $p;
                }
                
                if ($vw->exists("pagination_active_$sitesessaoconteudo_name")) {
                    if ($page == $p) {
                        $vw->{"pagination_active_$sitesessaoconteudo_name"} = 1;
                    }
                    else {
                        $vw->{"pagination_active_$sitesessaoconteudo_name"} = 0;
                    }
                }
                
                $vw->block("pagination_" . "$sitesessaoconteudo_name");
            }
        }
        
        return $vw;
    }
    
    /**
     * Monta a query do conteudo com os parametros fornecidos
     * 
     * @access private
     */
    private static function create_content_query($filter_rules, $filters, $order_rules, $orders, $limit, $page) {
        
        //Array de query
        $query = array();
        
        //Insere limite
        $query['limit'] = $limit;
        
        //Insere offset com base na pagina forneceida
        $query['offset'] = ($page - 1) * $limit;
        
        //Cria 'conditions'
        $conditions = "";
        
        //Parametros de conditions
        $conditions_parameters = array();
        
        //Itera sobre filters
        foreach ($filters as $filter_config) {
            
            //Verifica se é um filtro ou um grupo
            if ($filter_config->type == "Group") {
                $raw_conditions = self::parse_filter_group($filter_config, $filter_rules);
            }
            else if ($filter_config->type == "Filter") {
                $raw_conditions .= self::parse_filter($filter_config, $filter_rules);
            }
            
            //Pega o retorno e separa em string e parametros
            $conditions .= $raw_conditions[0];
            $conditions_parameters = array_merge($conditions_parameters, $raw_conditions[1]);
            
        }
        
        //Evita dados excluidos
        $conditions .= " and data_exclusao is null";
        
        //Cria array de conditions na query
        $query['conditions'] = array_merge(array($conditions), $conditions_parameters);
        
        //String de ordenação
        $order = self::parse_order($orders, $order_rules);
        
        $query['order'] = $order;
        
        return $query;
    }
    
    /**
     * Monta a query do conteudo (genérico) com os parametros fornecidos
     * 
     * @access private
     */
    private static function create_content_gen_query($fields, $filters, $orders, $limit, $page, $prefix, $id) {
        
        //Array de query
        $query = array();
        
        //Filtros para usar join
        $filters_to_join = array();
        
        //Insere limite
        $query['limit'] = $limit;
        
        //Insere offset com base na pagina forneceida
        $query['offset'] = ($page - 1) * $limit;
        
        //Insere select e form com base no prefixo
        if ($prefix == 'moduloitens') {
            $query['from'] = "conteudo_moduloitens as moduloitens";
            $query['select'] = "moduloitens.*";
            $prefixparent = 'modulo';
            
            //Inicializa joins
            $query['joins'] = "JOIN conteudo_modulo modulo ON (modulo.id = moduloitens.modulo_id and modulo.data_exclusao is null)";
        }
        else if ($prefix == 'filtroitens') {
            $query['from'] = "conteudo_filtroitens as filtroitens";
            $query['select'] = "filtroitens.*";
            $prefixparent = 'filtro';
            
            //Inicializa joins
            $query['joins'] = "JOIN conteudo_filtro filtro ON (filtro.id = filtroitens.filtro_id and filtro.data_exclusao is null)";
        }
        
        //Cria 'conditions'
        $conditions = "";
        
        //Parametros de conditions
        $conditions_parameters = array();
        
        //Itera sobre filters
        foreach ($filters as $filter_config) {
            
            //Verifica se é um filtro ou um grupo
            if ($filter_config->type == "Group") {
                $raw_conditions = self::parse_filter_group_gen($filter_config, $prefix);
            }
            else if ($filter_config->type == "Filter") {
                $raw_conditions .= self::parse_filter_gen($filter_config, $prefix);
            }
            
            //Pega o retorno e separa em string e parametros
            $conditions .= $raw_conditions[0];
            $conditions_parameters = array_merge($conditions_parameters, $raw_conditions[1]);

            //Verifica se houve join
            if (isset($raw_conditions[2])) {
                $filters_to_join = $filters_to_join + $raw_conditions[2];
            }
            
        }
        
        //Evita dados excluidos
        if (!empty($conditions)) {
            $conditions .= " and ";
        }
        $conditions .= "JSON_VALID($prefix.parametros) and $prefix.data_exclusao is null and $prefixparent.id = " . $id;
        
        //Cria array de conditions na query
        $query['conditions'] = array_merge(array($conditions), $conditions_parameters);
        
        //Itera sobre filtros para usar join
        foreach ($filters_to_join as $key => $value) {
            $query['joins'] .= "JOIN conteudo_modulofiltro modulofiltro$key ON (modulofiltro$key.$prefix" . "_id = $prefix.id) "
                            . "LEFT JOIN conteudo_filtroitens filtroitens$key ON (modulofiltro$key.filtroitens_id = filtroitens$key.id and filtroitens$key.filtro_id = $key) ";
        }
        
        //String de ordenação
        $order = self::parse_order_gen($orders, $prefix);
        
        $query['order'] = $order;
        $query['group'] = "$prefix.id";

        return $query;
    }
    
    /**
     * Recebe um objeto no padrão "Group" e retorna a string do grupo de filtro
     * 
     * @access public
     */
    public static function parse_filter_group($group, $filter_rules) {
        
        //String do grupo
        $group_string = "(";
        
        //Array de parametros
        $group_parameters = array();
        
        //Itera sobre membros do grupo
        foreach ($group->filters as $filter_config) {
            
            //Verifica se é um filtro ou um grupo
            if ($filter_config->type == "Group") {
                $response = self::parse_filter_group($filter_config, $filter_rules);
            }
            else if ($filter_config->type == "Filter") {
                $response = self::parse_filter($filter_config, $filter_rules);
            }
            
            //Pega o retorno e separa em string e parametros
            $group_string .= $response[0];
            $group_parameters = array_merge($group_parameters, $response[1]);
            
        }
        
        //Fecha o grupo
        $group_string .= ")";
        
        //Verifica a existência de um operador lógico. Se sim, inclue
        if (property_exists($group, 'logical_operator')) {
            $group_string .= " $group->logical_operator ";
        }
        
        return array($group_string, $group_parameters);
    }
    
    /**
     * Recebe um objeto no padrão "Group" e retorna a string do grupo de filtro
     * 
     * @access public
     */
    public static function parse_filter_group_gen($group, $prefix) {
        
        //String do grupo
        $group_string = "(";
        
        //Array de parametros
        $group_parameters = array();
        
        //Filtros para usar join
        $filters_to_join = array();
        
        //Itera sobre membros do grupo
        foreach ($group->filters as $filter_config) {
            
            //Verifica se é um filtro ou um grupo
            if ($filter_config->type == "Group") {
                $response = self::parse_filter_group_gen($filter_config, $prefix);
            }
            else if ($filter_config->type == "Filter") {
                $response = self::parse_filter_gen($filter_config, $prefix);
            }
            
            //Pega o retorno e separa em string e parametros
            $group_string .= $response[0];
            $group_parameters = array_merge($group_parameters, $response[1]);
            
            //Verifica se houve join
            if (isset($response[2])) {
                $filters_to_join = $filters_to_join + $response[2];
            }
        }

        //Fecha o grupo
        $group_string .= ")";
        
        //Verifica a existência de um operador lógico. Se sim, inclue
        if (property_exists($group, 'logical_operator')) {
            $group_string .= " $group->logical_operator ";
        }
        
        return array($group_string, $group_parameters, $filters_to_join);
    }
    
    /**
     * Recebe um objeto no padrão "Filter" e retorna a string do filtro
     * 
     * @access public
     */
    public static function parse_filter($filter, $filter_rules) {
        
        //Caso não siga as regras
        if(!property_exists($filter_rules, $filter->column)) {
            return array("", array());
        }
        
        //String do filtro
        $filter_string = "";
        
        //Array de parametros
        $filter_parameters = array();
        
        //Adiciona coluna
        $filter_column = $filter->column;

        //Faz tramento por funções caso seja estabelecido
        if (property_exists($filter, 'filter_functions')) {

            foreach ($filter->filter_functions as $function) {
                if(isset(self::$valid_mysql_filter_functions[$function])) {
                    $filter_column = str_replace('{value}', $filter_column, self::$valid_mysql_filter_functions[$function]['template']); 
                }
            }

            $filter_string .= $filter_column;

        }
        else {
            $filter_string .= $filter_column;
        }
        
        //Adiciona comparador
        $filter_string .= " $filter->comparison ";
        
        //Adiciona valor de comparação
        switch ($filter->comparison) {
            case '=':
            case '!=':
            case '>':
            case '<':
                
                if (in_array(strtoupper($filter->value), self::$valid_mysql_compare_functions)) {
                    $filter_string .= strtoupper($filter->value);
                }
                else {
                    $filter_string .= "?";
                    $filter_parameters[] = $filter->value;
                }
                break;
            
            case 'LIKE':
                
                $filter_string .= ("'%" . addslashes($filter->value) . "%'");
                break;
        }
        
        //Verifica a existência de um operador lógico. Se sim, inclue
        if (property_exists($filter, 'logical_operator')) {
            $filter_string .= " $filter->logical_operator ";
        }
        
        return array($filter_string, $filter_parameters);
    }
    
    /**
     * Recebe um objeto no padrão "Filter" e retorna a string do filtro
     * 
     * @access public
     */
    public static function parse_filter_gen($filter, $prefix) {

        //Verifica se a coluna é uma ligação com filtro
        if (explode('-', $filter->column)[0] == 'filter' and $prefix == 'moduloitens') {
            $filter_id = intval(explode('-', $filter->column)[1]);
            $filter_prefix = "filtroitens$filter_id";
            
            //String do filtro
            $filter_string = "";

            //Array de parametros
            $filter_parameters = array();

            //Adiciona coluna
            $filter_column = "$filter_prefix.id";
            
            //Faz tramento por funções caso seja estabelecido
            if (property_exists($filter, 'filter_functions')) {
                
                foreach ($filter->filter_functions as $function) {
                    if(isset(self::$valid_mysql_filter_functions[$function])) {
                        $filter_column = str_replace('{value}', $filter_column, self::$valid_mysql_filter_functions[$function]['template']); 
                    }
                }
                
                $filter_string .= $filter_column;
                
            }
            else {
                $filter_string .= $filter_column;
            }
            

            //Adiciona comparador
            $filter_string .= " $filter->comparison ";

            //Adiciona valor de comparação
            switch ($filter->comparison) {
                case '=':
                case '!=':
                case '>':
                case '<':

                    if (in_array(strtoupper($filter->value), self::$valid_mysql_functions)) {
                        $filter_string .= strtoupper($filter->value);
                    }
                    else {
                        $filter_string .= "?";
                        $filter_parameters[] = $filter->value;
                    }
                break;

                case 'LIKE':

                    $filter_string .= ("'%" . addslashes($filter->value) . "%'");
                    break;
            }

            //Verifica a existência de um operador lógico. Se sim, inclue
            if (property_exists($filter, 'logical_operator')) {
                $filter_string .= " $filter->logical_operator ";
            }

            return array($filter_string, $filter_parameters, array($filter_id => true));
            
        }
        else {
            //String do filtro
            $filter_string = "";

            //Array de parametros
            $filter_parameters = array();

            //Adiciona coluna
            if ($filter->column == "nome" or $filter->column == 'id') {
                $filter_column = "$prefix.$filter->column";
            }
            else {
                $filter_column = "JSON_UNQUOTE(JSON_EXTRACT($prefix.parametros, '$.fields.$filter->column.value'))";
            }
            
            //Faz tramento por funções caso seja estabelecido
            if (property_exists($filter, 'filter_functions')) {
                
                foreach ($filter->filter_functions as $function) {
                    if(isset(self::$valid_mysql_filter_functions[$function])) {
                        $filter_column = str_replace('{value}', $filter_column, self::$valid_mysql_filter_functions[$function]['template']); 
                    }
                }
                
                $filter_string .= $filter_column;
                
            }
            else {
                $filter_string .= $filter_column;
            }

            //Adiciona comparador
            $filter_string .= " $filter->comparison ";

            //Adiciona valor de comparação
            switch ($filter->comparison) {
                case '=':
                case '!=':
                case '>':
                case '<':

                    if (in_array(strtoupper($filter->value), self::$valid_mysql_compare_functions)) {
                        $filter_string .= strtoupper($filter->value);
                    }
                    else {
                        $filter_string .= "?";
                        $filter_parameters[] = $filter->value;
                    }
                    break;

                case 'LIKE':

                    $filter_string .= ("'%" . addslashes($filter->value) . "%'");
                    break;
            }

            //Verifica a existência de um operador lógico. Se sim, inclue
            if (property_exists($filter, 'logical_operator')) {
                $filter_string .= " $filter->logical_operator ";
            }

            return array($filter_string, $filter_parameters);
        }
    }
    
    /**
     * Recebe um array de objetos no padrão "Order" e retorna a string de ordenação
     * 
     * @access public
     */
    public static function parse_order($orders, $order_rules) {
        
        //String de ordenação
        $order_string = "";
        
        //Itera sobre parametros de ordenação
        foreach ($orders as $order) {
            
            //Caso não siga as regras
            if(!property_exists($order_rules, $order->column)) {
                continue;
            }
            
            //Adiciona coluna
            $order_column = $order->column;

            //Faz tramento por funções caso seja estabelecido
            if (property_exists($order, 'filter_functions')) {

                foreach ($order->filter_functions as $function) {
                    if(isset(self::$valid_mysql_filter_functions[$function])) {
                        $order_column = str_replace('{value}', $order_column, self::$valid_mysql_filter_functions[$function]['template']); 
                    }
                }

                $order_string .= ((empty($order_string)) ? "" : ", ") . "$order_column $order->direction";

            }
            else {
                $order_string .= ((empty($order_string)) ? "" : ", ") . "$order_column $order->direction";
            }
        }
        
        return $order_string;
    }
    
    /**
     * Recebe um array de objetos no padrão "Order" e retorna a string de ordenação
     * 
     * @access public
     */
    public static function parse_order_gen($orders, $prefix) {
        
        //String de ordenação
        $order_string = "";
        
        //Itera sobre parametros de ordenação
        foreach ($orders as $order) {
            
            //Adiciona coluna
            if ($order->column == 'nome' or $order->column == 'id') {
                $order_column = "$prefix.$order->column";
            }
            else {
                $order_column = "JSON_UNQUOTE(JSON_EXTRACT($prefix.parametros, '$.fields.$order->column.value'))";
            }

            //Faz tramento por funções caso seja estabelecido
            if (property_exists($order, 'filter_functions')) {

                foreach ($order->filter_functions as $function) {
                    if(isset(self::$valid_mysql_filter_functions[$function])) {
                        $order_column = str_replace('{value}', $order_column, self::$valid_mysql_filter_functions[$function]['template']); 
                    }
                }

                $order_string .= ((empty($order_string)) ? "" : ", ") . "$order_column $order->direction";

            }
            else {
                $order_string .= ((empty($order_string)) ? "" : ", ") . "$order_column $order->direction";
            }
        }
        
        return $order_string;
    }
}
