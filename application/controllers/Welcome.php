<?php

/**
 * Our homepage. Show the most recently added quote.
 * 
 * controllers/Welcome.php
 *
 * ------------------------------------------------------------------------
 */
class Welcome extends Application {

    function __construct()
    {
	parent::__construct();
    }

    //-------------------------------------------------------------
    //  Homepage: show a list of the orders on file
    //-------------------------------------------------------------

    function index()
    {
		// Build a list of orders
		$map = directory_map('./data/');
		$test = '.xml';
		$orders = array();
		$this->load->model('order');
		
		foreach($map as $str)
		{
			if(substr_compare($str, $test, strlen($str)-strlen($test),
			strlen($test)) === 0 && $str != 'menu.xml')
			{
				$order = new Order($str);
				$filename = array(
					'filename' => ucfirst(substr($str, 0, -4)),
					'customer' => $order->customer
				);
				array_push($orders, $filename);
			}
		}
		
		// Present the list to choose from
		$this->data['orders'] = $orders;
		$this->data['pagebody'] = 'homepage';
		$this->render();
    }
    
    //-------------------------------------------------------------
    //  Show the "receipt" for a specific order
    //-------------------------------------------------------------

    function order($filename = NULL)
    {
		// Build a receipt for the chosen order
		$this->load->model('order');
		$order = new Order($filename);
		$this->data['filename'] = $filename;
        $this->data['customer'] = $order->customer;
        $this->data['type'] = $order->type;
        $this->data['burgers'] = $order->burgers;
        $this->data['total'] = $order->total;
        $this->data['special'] = $order->instructions;
	
		// Present the list to choose from
		$this->data['pagebody'] = 'justone';
		$this->render();
    }
    

}
