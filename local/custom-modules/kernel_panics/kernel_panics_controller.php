<?php
/**
 * kernel_panics module class
 *
 * @package munkireport
 * @author tuxudo
 **/
class Kernel_panics_controller extends Module_controller
{

    /*** Protect methods with auth! ****/
    public function __construct()
    {
        // Store module path
        $this->module_path = dirname(__FILE__);
    }

    /**
    * Default method
    *
    * @author AvB
    **/
    public function index()
    {
        echo "You've loaded the kernel_panics module!";
    }

    /**
    * Retrieve panic log
    *
    * @return void
    * @author tuxudo
    **/
    public function get_panic_log($anonymous_uuid = '')
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }

        $sql = "SELECT `full_text`
                        FROM kernel_panics
                        WHERE anonymous_uuid = '$anonymous_uuid'";

        $queryobj = new Kernel_panics_model();
        $kernel_panics_tab = $queryobj->query($sql);
        print_r(preg_replace('~[\r\n]~', '<br>', $kernel_panics_tab[0]->full_text));
    }
    
    /**
    * Retrieve data in json format
    *
    * @return void
    * @author tuxudo
    **/
    public function get_tab_data($serial_number = '')
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }

        $sql = "SELECT `anonymous_uuid`, `type`, `crash_file`, `process_name`, `date`, `caller`, `macos_version`, `kernel_version`, `model_id`, `extensions_backtrace`, `non_apple_loaded_kexts`, `full_text`
                        FROM kernel_panics
                        WHERE serial_number = '$serial_number'
                        ORDER BY `date` DESC";

        $queryobj = new Kernel_panics_model();
        $kernel_panics_tab = $queryobj->query($sql);
        $obj->view('json', array('msg' => current(array('msg' => $kernel_panics_tab)))); 
    }
} // End class Kernel_panics_controller