<?php
namespace mwwork ;
class mwmodel extends \ActiveRecord\Model {

    public $json_variables = array();
    
    public function __clone()
    {
        $this->id = null;
        return parent::__clone();
    }
    
    public function &__get($name) {
        //Verifica se é uma variavel JSON já inicializada
        if (isset($this->json_variables[$name])) {
            return $this->json_variables[$name];
        }
        //Verififica se é uma possível variável json
        else if (property_exists($this, 'is_json') and isset($this::$is_json[$name])) {
            
            //Verifica se é vazio mas contém uma classe para inicilização
            if (empty(parent::__get($name)) and isset($this::$is_json[$name]['class_name'])) {
                $class = $this::$is_json[$name]['class_name'];
                $this->json_variables[$name] = new $class;
            }
            //Não é vazio ou não contém classe padrão, faz decode
            else {
                $this->json_variables[$name] = json_decode(parent::__get($name));
            }
            return $this->json_variables[$name];
        }
        //Não é, chama __get do pai
        return parent::__get($name);
    }
    
    public function __set($name, $value) {
        //Verifica se é uma variavel JSON já inicializada
        if (isset($this->json_variables[$name])) {
            return $this->json_variables[$name];
        }
        //Verififica se é uma possível variável json
        else if (property_exists($this, 'is_json') and isset($this::$is_json[$name])) {
            return $this->json_variables[$name] = $value;
        }
        return parent::__set($name, $value);
    }
    
    protected function post($ignore = array()) {
        $columns = $this::table()->columns;
        
        foreach ($columns as $column) {
            if ($column->name != "id" and $column->name != "data_exclusao" and 
                    $column->name != "data_criacao" and $column->name != "data_alteracao") {
                if (!in_array($column->name ,$ignore)) {
                    if (strstr((string) $column->raw_type, 'date')) {
                        $data = implode("-", array_reverse(explode("/", filter_input(INPUT_POST, $column->name))));
                        if ($this->{$column->name} != $data)
                            $this->{$column->name} = $data;
                    }
                    else {
                        if ($this->{$column->name} != filter_input(INPUT_POST, $column->name))
                            $this->{$column->name} = filter_input(INPUT_POST, $column->name);
                    }
                }
            }
            if ($column->name == 'data_criacao' and $this->is_new_record())
                $this->data_criacao = date('Y-m-d H:i:s');
            if ($column->name == 'data_alteracao') 
                $this->data_alteracao = date('Y-m-d H:i:s');
        }
        
        $this->encode_json();
    }
    
    public function encode_json() {
        foreach ($this->json_variables as $name => $value) {
            $new_json_value = json_encode($value);
            
            //Substitui apenas se diferente
            if ($new_json_value != parent::__get($name))
                parent::__set($name, json_encode($value));
        }
    }
    
        
    public function save($validate = false) {
        $this->encode_json();
        parent::save($validate);
    }
    
    /**
     * Diz se o registo podem ser deletado ou se há chaves ligadas
     *
     * @access public
     */
    public function is_deletable() {
        
        //Separa o nome do modulo e da tabela em duas variáveis
        //list($module, $name) = explode('_', $this::$table_name);
        
        //Verifica se há algum registro do tipo has_one ligado
        if (property_exists($this, 'has_one')) {
            foreach ($this::$has_one as $constraint) {
                if (is_object($this->{$constraint[0]})) {
                    return array(
                        'error' => 1,
                        'table' => $constraint[0]
                    );
                }
            }
        }
        
        //Verifica se há algum registro do tipo has_many ligado
        if (property_exists($this, 'has_many')) {
            foreach ($this::$has_many as $constraint) {
                if (count($this->{$constraint[0]}) > 0) {
                    return array(
                        'error' => 1,
                        'table' => $constraint[0]
                    );
                }
            }
        }
        
        return array('error' => 0);
    }
    
    /**
     * Monta uma condição para filtragem avançada
     *
     * @access protected
     * 
     * @param array $requisition_vars: Variáveis do cliente
     * @param array $server_vars: Variáveis do servidor
     */
    protected static function get_advanced_filter_conditions($requisition_vars = array(), $server_vars = array()) {
        //Pega a classe que fez a requisição
        $model = '\\' . get_called_class();
        
        //Guarda em uma variável o nome da classe
        $name = explode('_', $model::$table_name);
        $name = array_pop($name);
        
        $filter = "";
        $parameters = array();
        $object = new $model;

        //Procura por filtros do tipo equals
        if(isset($requisition_vars['equals'])) {
            
             //Iteração para cada coluna
                foreach ($object::table()->columns as $column) {
                    $value = trim(chk_array($requisition_vars['equals'], $column->name));
                    if ($value !== '') {
                        if (!empty($filter)) {
                            $filter .= ' and ';
                        }
                        $filter .= "$name.$column->name = ?";
                        $parameters[] = $value;
                    }
                }
        }
        
        //Procura por filtros do tipo like
        if(isset($requisition_vars['contains'])) {
            
             //Iteração para cada coluna
                foreach ($object::table()->columns as $column) {
                    $value = trim(chk_array($requisition_vars['contains'], $column->name));
                    if ($value !== '') {
                        if (!empty($filter)) {
                            $filter .= ' and ';
                        }
                        $filter .= "$name.$column->name like '%" . addslashes($value) . "%'";
                    }
                }
        }
        
        //Procura por filtros do tipo higher
        if(isset($requisition_vars['higher'])) {
            
             //Iteração para cada coluna
                foreach ($object::table()->columns as $column) {
                    $value = trim(chk_array($requisition_vars['higher'], $column->name));
                    if ($value !== '') {
                        if (!empty($filter)) {
                            $filter .= ' and ';
                        }
                        $filter .= "$name.$column->name > ?";
                        $parameters[] = $value;
                    }
                }
        }
        
        //Procura por filtros do tipo lower
        if(isset($requisition_vars['lower'])) {
            
             //Iteração para cada coluna
                foreach ($object::table()->columns as $column) {
                    $value = trim(chk_array($requisition_vars['lower'], $column->name));
                    if ($value !== '') {
                        if (!empty($filter)) {
                            $filter .= ' and ';
                        }
                        $filter .= "$name.$column->name < ?";
                        $parameters[] = $value;
                    }
                }
        }
        
        return array('filter' => $filter, 'parameters' => $parameters);
    }
    
    /**
     * Monta uma condição para filtragem padrão
     *
     * @access protected
     * 
     * @param array $requisition_vars: Variáveis do cliente
     * @param array $server_vars: Variáveis do servidor
     */
    protected static function get_default_filter_conditions($requisition_vars = array(), $server_vars = array()) {
        //Pega a classe que fez a requisição
        $model = '\\' . get_called_class();
        
        //Guarda em uma variável o nome da classe
        $name = explode('_', $model::$table_name);
        $name = array_pop($name);

        $filter = '';
        
        //Verifica se há requisição de filtragem
        if (isset($requisition_vars['filter'])) {
            
            //Monta o filtro
            $filter .= "$name.id like '%" . addslashes($requisition_vars['filter']) . "%'";
            foreach ($model::table()->columns as $column => $value) {
                if ($column != 'id')
                    $filter .= " or $name.$column like '%" . addslashes($requisition_vars['filter']) . "%'";
            }
        }
        
        return array('filter' => $filter, 'parameters' => array());
    }
    
    protected static function filter($requisition_vars = array(), $server_vars = array()) {
        //Pega a classe que fez a requisição
        $model = '\\' . get_called_class();
        
        //Guarda em uma variável o nome da classe
        $name = explode('_', $model::$table_name);
        $name = array_pop($name);
        
        //Recupera o filtro padrão
        $default_filter_conditions = $model::get_default_filter_conditions($requisition_vars, $server_vars);
        
        //Recupera o filtro avançado
        $advanced_filter_conditions = $model::get_advanced_filter_conditions($requisition_vars, $server_vars);
        
        $filter = "($name.data_exclusao is null)";
        $parameters = array();
        
        //Junta os filtros
        if (!empty($default_filter_conditions['filter'])) {
            if(!empty($filter))
                $filter .= " and ";
            
            $filter .= "(" . $default_filter_conditions['filter'] . ")";
            $parameters = $default_filter_conditions['parameters'];
        }
        if (!empty($advanced_filter_conditions['filter'])) {
            if(!empty($filter))
                $filter .= " and ";
            
            $filter .= $advanced_filter_conditions['filter'];
            $parameters = array_merge($parameters, $advanced_filter_conditions['parameters']);
        }
        
        //Insere as condições de filter como primeiro elemento de parameters
        array_unshift($parameters, $filter);
        
        $return = array(
            'from' => $model::$table_name . " as $name",
            'select' => "$name.*",
            'conditions' => $parameters
        );
       
        return $return;
    }

}
