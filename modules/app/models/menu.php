<?php namespace app;

class menu extends \mwwork\mwmodel {

    static $table_name = 'app_menu';
    
    static $belongs_to = array(
        array(
            'pai', 
            'class_name' => 'menu', 
            'foreign_key' => 'menu_id'
        ),
        array(
            'grupomenu', 
            'class_name' => 'grupomenu', 
            'foreign_key' => 'grupomenu_id'
        ),
        array(
            'modulo', 
            'class_name' => 'modulo', 
            'foreign_key' => 'modulo_id'
        )
    );
    
  
    static $validates_presence_of = array(
        array('nome', 'message' => 'Nome é um campo obrigatório!')
    );

    static $validates_format_of = array(
    );

    public function post($ignore = array()) {
        parent::post(array_merge($ignore, array('ordem')));
        
        //Se novo, define ordem
        if ($this->is_new_record()) {
            $last_menu_order = 1;
            
            $last_menu = \app\menu::first(array(
                'limit' => '1',
                'order' => 'ordem desc'
            ));
            
            if (is_object($last_menu)) {
                $last_menu_order = (int) $last_menu->ordem + 1;
            }
            
            $this->ordem = $last_menu_order;
        }
        
        $this->modulo_id = !empty($_POST['modulo_id']) ? $_POST['modulo_id']: null;
    }
    
    public function has_access($server_vars = array()) {
        return true;
    }

    static function filter($requisition_vars = array(), $server_vars = array()) {
        return parent::filter($requisition_vars, $server_vars);
    }
    
    public function getcompleteurl() {
        if (substr($this->url, 0, 4) != 'http')
            return HOME_URI . $this->url;
        else
            return $this->url;
    }

    public function getselectgrupomenu() {
        $vw = new \mwwork\view('app/painelCMS/select');
        $grupos = \app\grupomenu::all(array(
            'conditions' => array('data_exclusao is null')
        ));
        foreach ($grupos as $grupo) {
            $vw->value = $grupo->id;
            $vw->text = $grupo->nome;
            if ($this->grupomenu_id == $grupo->id)
                $vw->selected = 'selected="selected"';
            else
                $vw->clear('selected');
            $vw->block('linha');
        }
        return $vw->parse();
    }
    
    public function getselectmodulo() {
        $vw = new \mwwork\view('app/painelCMS/select');
        $grupos = \app\modulo::all(array(
            'conditions' => array('data_exclusao is null')
        ));
        foreach ($grupos as $grupo) {
            $vw->value = $grupo->id;
            $vw->text = $grupo->nome;
            if ($this->modulo_id == $grupo->id)
                $vw->selected = 'selected="selected"';
            else
                $vw->clear('selected');
            $vw->block('linha');
        }
        return $vw->parse();
    }
    
    public function getselectmenuid() {
        $vw = new \mwwork\view('app/painelCMS/select');
        $menus = menu::all(array('conditions' => array('menu_id=0 and data_exclusao is null')));
        $vw->value = '0';
        $vw->text = 'Menu Pai';
        if ($this->menu_id == 0)
            $vw->selected = 'selected="selected"';
        else
            $vw->clear('selected');
        $vw->block('linha');
        foreach ($menus as $menu) {
            $vw->value = $menu->id;
            $vw->text = $menu->nome;
            if ($this->menu_id == $menu->id)
                $vw->selected = 'selected="selected"';
            else
                $vw->clear('selected');
            $vw->block('linha');
        }
        return $vw->parse();
    }
    
    public function getpainome() {
       if (is_object($this->pai))
           return $this->pai->nome;
       else
           return '';
    }
}

?>