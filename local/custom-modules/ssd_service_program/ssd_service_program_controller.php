<?php
/**
 * Security module class
 *
 * @package munkireport
 * @author eholtam
 **/
class Ssd_service_program_controller extends Module_controller
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
     **/
    public function index()
    {
        echo "You've loaded the ssd_service_program module!";
    }
    
    /**
     * Get security for serial_number
     *
     * @param string $serial serial number
     **/
    public function get_data($serial = '')
    {
        $out = array();
        if (! $this->authorized()) {
            $out['error'] = 'Not authorized';
        } else {
            $prm = new ssd_service_program_model;
            foreach ($prm->retrieve_records($serial) as $ssd_service_program) {
                $out[] = $ssd_service_program->rs;
            }
        }
        
        $obj = new View();
        $obj->view('json', array('msg' => $out));
    }

    public function get_ssd_service_program_stats()
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }

        $needs_service = new Ssd_service_program_model;
        $obj->view('json', array('msg' => $needs_service->get_ssd_service_program_stats()));
    }

} // END class default_module
