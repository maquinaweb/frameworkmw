<?php
namespace mwwork ;
class mwload {

    /**
     * Diretorio padrão de módulos
     *
     * @access private
     */
    private static $default_directory = ABSPATH . "/modules/";
    
    /**
     * Mapeamento de módulos
     *
     * @access private
     */
    private static $class_map = array(
        'modules' => array(
            'app' => array(
                'directory' => '/modules/app/',
                'model' => 'models/'
            ),
            'gee' => array(
                'directory' => '/modules/gee/',
                'model' => 'models/'
            )
        )
    );
    
    public function __construct() {
        
    }
    
    /**
     * Autoload para classes dos módulos do sistema
     *
     * @access public
     * 
     * @param string $class_name: Nome da classe requisitada
     * @param string $module: Nome do módulo requisitado
     * @param string $class_type: Tipo de classe requisitada
     */
    public static function load_class($class_name, $module, $class_type = null) {
        if (!isset(self::$class_map['modules'][$module]))
            return false;
        
        $module_config = self::$class_map['modules'][$module];
        
        if (isset($module_config['directory']))
            $directory = ABSPATH . $module_config['directory'];
        else
            $directory = self::$default_directory . "$module/";
        
        if ($class_type != null and isset($module_config[$class_type])) {
            $directory .= $module_config[$class_type];
        }
        
        if (file_exists("$directory$class_name.php")) {
            include_once "$directory$class_name.php";
            return true;
        }
        else {
            return false;
        } 
    }
    
    /**
     * Recupera os módulos cadastrados no sistema
     *
     * @access public
     * 
     */
    public static function get_modules() {
        $modules = array();
        
        foreach (self::$class_map['modules'] as $key => $value) {
            $modules[] = $key;
        }
        
        return $modules;
    }
    
}
