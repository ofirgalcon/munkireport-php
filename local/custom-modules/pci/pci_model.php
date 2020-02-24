<?php

use CFPropertyList\CFPropertyList;

class Pci_model extends \Model {

	function __construct($serial='')
	{
		parent::__construct('id', 'pci'); // Primary key, tablename
		$this->rs['id'] = '';
		$this->rs['serial_number'] = $serial;
		$this->rs['name'] = '';
		$this->rs['device_id'] = '';
		$this->rs['device_type'] = '';
		$this->rs['driver_installed'] = ''; // True/False
		$this->rs['link_speed'] = '';
		$this->rs['link_width'] = '';
		$this->rs['msi'] = ''; // True/False
		$this->rs['device_name'] = '';
		$this->rs['revision_id'] = '';
		$this->rs['slot_name'] = '';
		$this->rs['subsystem_id'] = '';
		$this->rs['subsystem_vendor_id'] = '';
		$this->rs['vendor_id'] = '';

        if ($serial) {
            $this->retrieve_record($serial);
        }

		$this->serial_number = $serial;
	}
	
	// ------------------------------------------------------------------------
   
     /**
     * Get PCI device names for widget
     *
     **/
     public function get_pci_devices()
     {
        $out = array();
        $sql = "SELECT COUNT(CASE WHEN name <> '' AND name IS NOT NULL THEN 1 END) AS count, name 
                FROM pci
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
			'device_id' => '',
			'device_type' => '',
			'driver_installed' => '',
			'link_speed' => '',
			'link_width' => '',
			'msi' => '',
			'device_name' => '',
			'revision_id' => '',
			'slot_name' => '',
			'subsystem_id' => '',
			'subsystem_vendor_id' => '',
			'vendor_id' => ''
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
