<?php

use CFPropertyList\CFPropertyList;

class Audio_model extends \Model {

	function __construct($serial='')
	{
		parent::__construct('id', 'audio'); // Primary key, tablename
		$this->rs['id'] = '';
		$this->rs['serial_number'] = $serial;
		$this->rs['name'] = '';
		$this->rs['default_audio_output'] = '';
		$this->rs['default_audio_input'] = '';
		$this->rs['device_input'] = ''; // True/False
		$this->rs['device_output'] = '';
		$this->rs['device_manufacturer'] = '';
		$this->rs['device_srate'] = ''; // True/False
		$this->rs['device_transport'] = '';
		$this->rs['input_source'] = '';
		$this->rs['output_source'] = '';

        if ($serial) {
            $this->retrieve_record($serial);
        }

		$this->serial_number = $serial;
	}
	
	// ------------------------------------------------------------------------
   
     /**
     * Get Audio device names for widget
     *
     **/
     public function get_audio_devices()
     {
        $out = array();
        $sql = "SELECT COUNT(CASE WHEN name <> '' AND name IS NOT NULL THEN 1 END) AS count, name 
                FROM audio
                LEFT JOIN reportdata USING (serial_number)
                ".get_machine_group_filter()."
                GROUP BY name
                ORDER BY count DESC";
        
        foreach ($this->query($sql) as $obj) {
            if ("$obj->count" !== "0") {
                $obj->name = $obj->name ? $obj->name : 'Unknown';
                $out[] = $obj;
            }
        }
        return $out;
     }
    
	/**
	 * Process data sent by postflight
	 *
	 * @param string data
	 * @author tuxudo
	 **/
	function process($plist)
	{
		// Check if we have data
		if ( ! $plist){
			throw new Exception("Error Processing Request: No property list found", 1);
		}
		
		// Delete previous set        
		$this->deleteWhere('serial_number=?', $this->serial_number);

		$parser = new CFPropertyList();
		$parser->parse($plist, CFPropertyList::FORMAT_XML);
		$myList = $parser->toArray();
        		
		$typeList = array(
			'name' => '',
			'default_audio_output' => '',
			'default_audio_input' => '',
			'device_input' => '',
			'device_output' => '',
			'device_manufacturer' => '',
			'device_srate' => '',
			'device_transport' => '',
			'input_source' => '',
			'output_source' => ''
		);

		foreach ($myList as $device) {
			// Check if we have a name
			if( ! array_key_exists("name", $device)){
				continue;
			}

			foreach ($typeList as $key => $value) {
				$this->rs[$key] = $value;
				if(array_key_exists($key, $device))
				{
					$this->rs[$key] = $device[$key];
				} else {
					$this->rs[$key] = null;
				}
			}

			// Save the device, save the game
			$this->id = '';
			$this->save();
		}
	}
}
