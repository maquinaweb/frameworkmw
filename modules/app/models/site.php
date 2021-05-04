<?php namespace app;
class site extends \mwwork\mwmodel
{
 
    static $table_name = 'app_site';
   
    public function post($ignore = array()) {
        parent::post($ignore);
    }

    public static function filter($requisition_vars = array(), $server_vars = array()){
        if(isset($_GET['filter'])){
            $filtro = "id like '%".addslashes($_GET['filter'])."%'";
            foreach ($this->attributes() as $name=>$value) {
                if($name!='id')
                $filtro .= " or $name like '%".addslashes($_GET['filter'])."%'";
            }
            return array('conditions' => array($filtro));
        }
        return array();
    }

    ////Validações gerais. Recebe o parametro tipo
    //Ações: Valida se o usuario atual pode fazer alguma ação com
    //   o objeto. Se for de uma empresa diferente, por exemplo,
    //   o acesso será negado
    //Salvar: Valida os dados para o salvamento. Verifica a integridade
    //   dos dados.
    //Deletar: Valida os dados para ver se é possível deletar.
    public function valida($tipo = "Ações"){
        if ($tipo == "Ações") {
            return true;
        }
        else if ($tipo == "Salvar") {
            
//            if($this->campocontido->chave != $_SESSION['chave'])
//                return array('erro' => 'Erro ao salvar: O campocontido não pertence a chave.');
            return true;
        }
        else if ($tipo == "Deletar") {
            return true;
        }
        else {
            return true;
        }
    }

//  
//   DESCOMENTAR FUNÇÕES PARA USO
//
//   //Sobreescreve função padrão de save do model
//   public function save($validate = false, $ignorar = false) {
//        //Identifica se é novo
//        $novo = $this->is_new_record();
//
//        //Uso o método padrão de salvar
//        parent::save($validate);
//
//        //Se não ignorar, executa a função pos_save dizendo se era novo ou não
//        if (!$ignorar) {
//            $this->_pos_save($novo);
//        }
//   }
//
//    //Função que executa passos necessários após salvar
//    //Se necessário salvar novamente aqui, passar status ignorar como true
//    //Para não entrar em loop
//    private function _pos_save($novo = false) {
//        //Apenas para registros recém inseridos
//        if ($novo) {
//            
//        }
//
//        //Para dados que já existiam
//        else {
//           
//        }
//    }
//
//    public function redirect() {
//
//    }
}
?>