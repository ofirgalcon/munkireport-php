<?php 

/**
 * Audio module class
 *
 * @package munkireport
 * @author tuxudo
 **/
class Audio_controller extends Module_controller
{
	
	/*** Protect methods with auth! ****/
	function __construct()
	{
		// Store module path
		$this->module_path = dirname(__FILE__);
	}

	/**
	 * Default method
	 * @author avb
	 *
	 **/
	function index()
	{
		echo "You've loaded the audio module!";
	}

   /**
     * Get Audio device names for widget
     *
     * @return void
     * @author tuxudo
     **/
     public function get_audio_devices()
     {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => array('error' => 'Not authenticated')));
            return;
        }
        
        $usb = new Audio_model;
        $obj->view('json', array('msg' => $usb->get_audio_devices()));
     }
    
   /**
     * Retrieve data in json format
     *
     **/
    public function get_data($serial_number = '')
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }
        
        $queryobj = new Audio_model();
        
        $sql = "SELECT name, default_audio_output, default_audio_input, device_input, link_width, device_output, device_manufacturer, 
                        device_srate, device_transport, input_source, output_source
                        FROM audio 
                        WHERE serial_number = '$serial_number'";
        
        $audio_tab = $queryobj->query($sql);

        $obj->view('json', array('msg' => current(array('msg' => $audio_tab)))); 
    }
		
} // End class Audio_controller
