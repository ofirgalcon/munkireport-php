<?php

use CFPropertyList\CFPropertyList;

class Kernel_panics_model extends \Model
{
    public function __construct($serial = '')
    {
        parent::__construct('id', 'kernel_panics'); // Primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = $serial;
        $this->rs['anonymous_uuid'] = '';
        $this->rs['type'] = '';
        $this->rs['full_text'] = '';
        $this->rs['crash_file'] = '';
        $this->rs['date'] = 0;
        $this->rs['caller'] = '';
        $this->rs['process_name'] = '';
        $this->rs['macos_version'] = '';
        $this->rs['kernel_version'] = '';
        $this->rs['model_id'] = '';
        $this->rs['extensions_backtrace'] = '';
        $this->rs['non_apple_loaded_kexts'] = '';
        
		$this->serial_number = $serial;
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
        // If data is empty, echo out error
        if (! $data) {
            echo ("Error Processing kernel_panics module: No data found");
        } else { 
            
            // Delete previous entries
            $this->deleteWhere('serial_number=?', $this->serial_number);

            // Process incoming kernel_panics.plist
            $parser = new CFPropertyList();
            $parser->parse($data, CFPropertyList::FORMAT_XML);
            $plist = $parser->toArray();

            foreach ($plist as $panic_file) {
                foreach (array('anonymous_uuid', 'type', 'full_text', 'crash_file', 'date', 'caller', 'process_name', 'macos_version', 'kernel_version', 'model_id', 'extensions_backtrace', 'non_apple_loaded_kexts') as $item) {
                    // If key does not exist in $panic_file, null it
                    if ( ! array_key_exists($item, $panic_file) || $panic_file[$item] == '') {
                        $this->$item = null;
                    // Set the db fields to be the same as those in the panic
                    } else {
                        $this->$item = $panic_file[$item];
                    }
                }
                
                // Save the data, save the colonel!!
                $this->id = '';
                $this->save(); 
            }
        }
    }
}