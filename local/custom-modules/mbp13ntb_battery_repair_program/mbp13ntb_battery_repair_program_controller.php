<?php

/**
 * mbp13ntb_battery_repair_program_controller class
 *
 * @package mbp13ntb_battery_repair_program
 * @author Eric Holtam
 **/
class mbp13ntb_battery_repair_program_controller extends Module_controller
{
    public function __construct()
    {
        $this->module_path = dirname(__FILE__);
    }

    /**
     * Default method
     *
     * @author AvB
     **/
    public function index()
    {
        echo "You've loaded the mbp13ntb_battery_repair_program module!";
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

        $wifi = new mbp13ntb_battery_repair_program_model($serial_number);
        $obj->view('json', array('msg' => $mbp13ntb_battery_repair_program->rs));
    }

    /**
     * Get Eligibility information for widget
     *
     * @return void
     * @author Eric Holtam (@eholtam)
     **/
    public function get_mbp13ntb_battery_repair_program_state()
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => array('error' => 'Not authenticated')));
            return;
        }

        $mbp13ntb_battery_repair_program = new mbp13ntb_battery_repair_program_model;
        $obj->view('json', array('msg' =>$mbp13ntb_battery_repair_program->get_mbp13ntb_battery_repair_program_state()));
    }

    public function get_mbp13ntb_battery_repair_program_data($serial_number = '')
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
        }

        $mbp13ntb_battery_repair_program = new mbp13ntb_battery_repair_program_model($serial_number);
        $obj->view('json', array('msg' => $mbp13ntb_battery_repair_program->rs));
    }
} // END class mbp13ntb_battery_repair_program_controller
