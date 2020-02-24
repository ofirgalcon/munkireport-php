<?php

use CFPropertyList\CFPropertyList;

class Geekbench_model extends \Model
{
    public function __construct($serial = '')
    {
        parent::__construct('id', 'geekbench'); // Primary key, tablename
        $this->rs['id'] = 0;
        $this->rs['serial_number'] = $serial;
        $this->rs['score'] = null;
        $this->rs['multiscore'] = null;
        $this->rs['model_name'] = null;
        $this->rs['description'] = null;
        $this->rs['samples'] = null;
        $this->rs['cuda_score'] = null;
        $this->rs['cuda_samples'] = null;
        $this->rs['opencl_score'] = null;
        $this->rs['opencl_samples'] = null;
        $this->rs['gpu_name'] = null;
        $this->rs['last_cache_pull'] = null;
        $this->rs['mac_benchmarks'] = null;
        $this->rs['cuda_benchmarks'] = null;
        $this->rs['opencl_benchmarks'] = null;

        if ($serial) {
            $this->retrieve_record($serial);
        }

        $this->serial_number = $serial;
    }

    /**
     * Process data sent by postflight
     *
     * @param string data
     * 
     **/
    public function process($data = '')
    {

        // Get machine machine_desc and CPU from machine table
        $machine = new Machine_model($this->serial_number);        
        $machine_desc = str_replace(array("Server"),array(""), $machine->rs["machine_desc"]);
        $machine_cpu = $machine->rs["cpu"];

        // Check if machine is a virutal machine
        if (strpos($machine_desc, 'virtual machine') !== false || strpos($machine->rs["machine_model"], 'VMware') !== false){
            print_r("Geekbench module does not support virtual machines, exiting");
            exit(0);
        }

        // Check if we have cached Geekbench JSONs
        $queryobj = new Geekbench_model();
        $sql = "SELECT last_cache_pull FROM `geekbench` WHERE serial_number = 'JSON_CACHE_DATA'";
        $cached_data = $queryobj->query($sql);

        // Get the current time
        $current_time = time();

        // Check if we have a result or a day has passed
        if($cached_data == null || ($current_time > ($cached_data[0]->last_cache_pull + 86400))){

            // Get JSONs from Geekbench API
            ini_set("allow_url_fopen", 1);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL, 'https://browser.geekbench.com/mac-benchmarks.json');
            $mac_result = curl_exec($ch);
            curl_setopt($ch, CURLOPT_URL, 'https://browser.geekbench.com/cuda-benchmarks.json');
            $cuda_result = curl_exec($ch);
            curl_setopt($ch, CURLOPT_URL, 'https://browser.geekbench.com/opencl-benchmarks.json');
            $opencl_result = curl_exec($ch);

            // Check if we got results
            if (strpos($mac_result, '"devices": [') === false || strpos($cuda_result, '"devices": [') === false || strpos($opencl_result, '"devices": [') === false){
                print_r("Unable to fetch new JSONs from Geekbench API!!");
            } else {                                
                // Delete old cached data
                $sql = "DELETE FROM `geekbench` WHERE serial_number = 'JSON_CACHE_DATA';";
                $queryobj->exec($sql);

                // Insert new cached data
                $sql = "INSERT INTO `geekbench` (serial_number,description,last_cache_pull,mac_benchmarks,cuda_benchmarks,opencl_benchmarks) 
                        VALUES ('JSON_CACHE_DATA','Do not delete this row','".$current_time."','".$mac_result."','".$cuda_result."','".$opencl_result."')";
                $queryobj->exec($sql);
            }
        }

        //
        //
        // Start of the processing of the data
        //
        //

        // Get the cached JSONs from the database
        $sql = "SELECT mac_benchmarks, cuda_benchmarks, opencl_benchmarks FROM `geekbench` WHERE serial_number = 'JSON_CACHE_DATA'";
        $cached_jsons = $queryobj->query($sql);

        // Decode JSON
        $benchmarks = json_decode($cached_jsons[0]->mac_benchmarks);
        $gpu_cuda_benchmarks = json_decode($cached_jsons[0]->cuda_benchmarks);
        $gpu_opencl_benchmarks = json_decode($cached_jsons[0]->opencl_benchmarks);

        // Prepare machine CPU type string for matching
        $machine_cpu = preg_replace("/[^A-Za-z0-9]/", '', explode("@", str_replace(array('(R)','CPU ','(TM)2','(TM)','Core2'), array('','',' 2','','Core 2'), $machine_cpu))[0]);
        
        // Fix older Macbooks
        if(strpos($machine_desc, 'MacBook (13-inch, ') !== false){
            $machine_desc = str_replace(array('13-inch, '), array(''), $machine_desc);
        }

        // Prepare machine description for matching
        $desc_array = explode("(", $machine_desc);
        if ( count($desc_array) > 1){
            // Extract model, inch, and year
            $machine_name = preg_replace("/[^A-Za-z]/", '', str_replace(array('Server'), array(''), $desc_array[0]));
            // Check if machine name contains inch
            if (strpos($machine_desc, '-inch') !== false) {
                $machine_inch = preg_replace("/[^0-9]/", '', explode("-inch", str_replace(array('5K'), array(''), $desc_array[1]))[0]);
            } else {
                $machine_inch = "";
            }
            // Check if machine name contains year
            if (strpos($machine_desc, ' 20') !== false) {
                $machine_year = "20".preg_replace("/[^0-9]/", '', explode(", ", explode(" 20", str_replace(array('5K'), array(''), $desc_array[1]))[1])[0]);
            } else {
                $machine_year = "";
            }
        } else {
            // Fix 2006 Mac Pro or other machines without a year in their name
            $machine_name = preg_replace("/[^A-Za-z]/", '', str_replace(array('Server'), array(''), $desc_array[0]));
            $machine_inch = "";
            $machine_year = "";
        }
         
        $machine_match = ($machine_name.$machine_inch.$machine_year);
        
        $did_match = false;

        // Loop through all benchmarks until match is found
        foreach($benchmarks->devices as $benchmark){

            // Prepare benchmark name for matching
            $name_array = explode("(", $benchmark->name);
            if ( count($name_array) > 1){
            // Extract model, inch, and year
                $benchmark_desc = preg_replace("/[^A-Za-z]/", '', str_replace(array('Server'), array(''), $name_array[0]));
                // Check if benchmark name contains inch
                if (strpos($benchmark->name, '-inch') !== false) {
                    $benchmark_inch = preg_replace("/[^0-9]/", '', explode("-inch", $name_array[1])[0]);
                    // Fix for 27" 5K 2014 iMac, 2013 Macbook Air, 2012 iMac
                    // top is geekbench, bottom is MR format
                    if ($benchmark->name == 'iMac (27-inch Retina)'){
                        $benchmark->name = 'iMac (Retina 27-inch Late 2014)';
                        $name_array = explode("(", $benchmark->name);
                    } else if($benchmark->name == 'MacBook Pro (15-inch Mid 2019)'){
                        $benchmark->name = 'MacBook Pro (15-inch 2019)';
                        $name_array = explode("(", $benchmark->name);
                    } else if($benchmark->name == 'MacBook (Mid 2017)'){
                        $benchmark->name = 'MacBook (Retina 12-inch 2017)';
                        $name_array = explode("(", $benchmark->name);
                    } else if($benchmark->name == 'MacBook Air (Late 2018)'){
                        $benchmark->name = 'MacBook Air (Retina 13-inch 2018)';
                        $name_array = explode("(", $benchmark->name);
                    } else if($benchmark->name == 'iMac Pro (Late 2017)'){
                        $benchmark->name = 'iMac Pro (2017)';
                        $name_array = explode("(", $benchmark->name);
                    } else if($benchmark->name == 'MacBook Pro (15-inch Mid 2012)'){
                        $benchmark->name = 'MacBook Pro (Retina Mid 2012)';
                        $name_array = explode("(", $benchmark->name);
                    } else if($benchmark->name == 'MacBook Air (Late 2018)'){
                        $benchmark->name = 'MacBook Air (Retina 13-inch 2018)';
                        $name_array = explode("(", $benchmark->name);
                    } else if($benchmark->name == "MacBook Air (11-inch Mid 2013)" && strpos($benchmark->description, '4650U') !== false){
                        $benchmark->name = "MacBook Air (11-inch Early 2014)";
                        $name_array = explode("(", $benchmark->name);
                    } else if ($benchmark->name == "iMac (21.5-inch Late 2012)" && strpos($benchmark->description, '3335S') !== false){
                        $benchmark->description = str_replace(array('3335S'), array('3330S'), $benchmark->description);
                    }
                } else {
                    $benchmark_inch = "";
                }

                // Check if benchmark name contains year
                if (strpos($benchmark->name, ' 20') !== false) {
                    $benchmark_year = "20".preg_replace("/[^0-9]/", '', explode(", ", explode(" 20", $name_array[1])[1])[0]);
                } else {
                    $benchmark_year = "";
                }
            } else {
                // Fix 2006 Mac Pro or other machines without a year in their name
                $benchmark_desc = preg_replace("/[^A-Za-z]/", '', str_replace(array('Server'), array(''), $name_array[0]));
                $benchmark_inch = "";
                $benchmark_year = "";
            }

            $benchmark_match = ($benchmark_desc.$benchmark_inch.$benchmark_year);

            // Process benchmark CPU for matching
            $benchmark_cpu = preg_replace("/[^A-Za-z0-9]/", '', explode("@", $benchmark->description)[0]);

            // Check through for a matching machine description and CPU
            if ( $benchmark_cpu == $machine_cpu){
                
                // Fill in data from matching entry
                $this->score = $benchmark->score;
                $this->multiscore = $benchmark->multicore_score;
                $this->model_name = $benchmark->name;
                $this->description = $benchmark->description;
                $this->samples = $benchmark->samples;
                
                $did_match = true;
                
                // Exit loop because we found a match
                break;
            }
        }

        // Insert last ran timestamp, may be overwritten by $data
        $this->last_cache_pull = time();

        $gpu_model = "";

        // Fill in GPU information
        // If we don't have data, use existing GPU model
        if ($data == "" && !is_null($this->rs["gpu_name"])){
            $gpu_model = $this->rs["gpu_name"];
        } else if ($data == "" && is_null($this->rs["gpu_name"])){
            // Try to get GPU model from gpu module
            $gpu = new Gpu_model($this->serial_number);        
            $gpu_model = $gpu->rs["model"];
        }

        // If we have data or gpu_model is already set
        if ($data !== "" || $gpu_model !== ""){
            // If we have data, use that
            if($data != ""){
                // Process incoming geekbench.plist
                $parser = new CFPropertyList();
                $parser->parse($data);
                $plist = $parser->toArray();

                // Prepare GPU model
                $gpu_model = $plist["model"];

                // Insert last ran timestamp
                $this->last_cache_pull = $plist["last_run"];
            }

            // Clean GPU model
            $this->gpu_name = str_replace(array('NVIDIA ','Intel ','HD Graphics 3000'), array('','','HD Graphics'), $gpu_model);

            // Loop through all GPU CUDA benchmarks until match is found
            foreach($gpu_cuda_benchmarks->devices as $gpu_cuda_benchmark){

                // Check through for a matching GPU
                if ($gpu_cuda_benchmark->name == $this->gpu_name){

                    // Fill in data from matching entry
                    $this->cuda_samples = $gpu_cuda_benchmark->samples;
                    $this->cuda_score = $gpu_cuda_benchmark->score;

                    // Exit loop because we found a match
                    break;
                }
            }

            // Loop through all GPU OpenCL benchmarks until match is found
            foreach($gpu_opencl_benchmarks->devices as $gpu_opencl_benchmark){
                
                // Prepare gpu model for matching
                $gpu_opencl_benchmark_prepared = str_replace(array('NVIDIA ','(R)','(TM)','Intel '), array('','','',''), $gpu_opencl_benchmark->name);

                // Check through for a matching GPU
                if ($gpu_opencl_benchmark_prepared == $this->gpu_name){
                    // Fill in data from matching entry
                    $this->opencl_samples = $gpu_opencl_benchmark->samples;
                    $this->opencl_score = $gpu_opencl_benchmark->score;

                    // Exit loop because we found a match
                    break;
                }
            }
        }
        
        // Save the data if matched
        if($did_match){
            $this->save();
        }
    }
}

