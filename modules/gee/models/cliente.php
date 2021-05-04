<?php namespace gee;

class cliente extends \mwwork\mwmodel {

    static $table_name = 'gee_cliente';

    //inverso do foreach
    static $has_many = array(
        
    );
    
    static $validates_presence_of = array(
    );

    static $validates_format_of = array(
    );

    public function post($ignore = array()) {
        parent::post($ignore);
    }
    
    public function has_access($server_vars = array()) {
        return true;
    }

    static function filter($requisition_vars = array(), $server_vars = array()) {
        return parent::filter($requisition_vars, $server_vars);
    }
}

?>