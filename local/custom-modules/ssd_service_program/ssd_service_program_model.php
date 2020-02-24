<?php

use CFPropertyList\CFPropertyList;

class Ssd_service_program_model extends \Model
{
    public function __construct($serial = '')
    {
        parent::__construct('id', 'ssd_service_program'); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = $serial; //$this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
        $this->rs['needs_service'] = null;
        $this->rs['ssd_model'] = null;
        $this->rs['ssd_revision'] = null;
        
        
        // Add indexes
        $this->idx[] = array('serial_number');
        $this->idx[] = array('needs_service');
        $this->idx[] = array('ssd_model');
        $this->idx[] = array('ssd_revision');

        // Schema version, increment when creating a db migration
        $this->schema_version = 1;
                
        if ($serial) {
            $this->retrieve_record($serial);
        }
        
        $this->serial = $serial;
    }
    
    
    // ------------------------------------------------------------------------

    /**
     * Process data sent by postflight
     *
     * @param string data
     *
     **/
    public function process($data)
    {
        $parser = new CFPropertyList();
        $parser->parse($data);

        $plist = $parser->toArray();

        foreach (array('needs_service', 'ssd_model', 'ssd_revision') as $item) {
            if (isset($plist[$item])) {
                $this->$item = $plist[$item];
            } else {
                $this->$item = null;
            }
        }
        $this->save();
    }

    public function get_ssd_service_program_stats()
    {
        $sql = "SELECT COUNT(CASE WHEN needs_service = 'True' THEN 1 END) AS needs_service FROM ssd_service_program
			LEFT JOIN reportdata USING (serial_number)
			".get_machine_group_filter();
        return current($this->query($sql));
    }


}
