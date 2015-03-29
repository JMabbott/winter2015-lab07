<?php

class Order extends CI_Model 
{
    protected $xml = null;
    var $customer;
    var $type;
    var $instructions = "";
    var $burgers = array();
    var $total = 0.00;
	
    public function __construct($filename = null) 
	{
        parent::__construct();
        
		if ($filename == null)
        {
            return;
        }
        
        $this->load->model('menu');
        
        $this->xml = simplexml_load_file(DATAPATH . $filename);
		
        // Assign the customer name
        $this->customer = (string) $this->xml->customer;
        $this->type = (string) $this->xml['type'];
        
        if (isset($this->xml->special))
        {
            $this->instructions = (string) $this->xml->special;
        }
        
        $burgNum = 0;
        
        foreach ($this->xml->burger as $burg)
        {
            $burgNum++;
            $cheeses = "";
            $toppings = "";
            $sauces = "";
            
            $newBurg = array(
                'patty' => $burg->patty['type']
            );
            
            $newBurg['num'] = $burgNum;
            
            if (isset($burger->cheeses['top']))
            {
                $cheeses .= $burg->cheeses['top'] . "(top), ";
            }
            
            if (isset($burg->cheeses['bottom']))
            {
                $cheeses .= $burg->cheeses['bottom'] . "(bottom)";
            }
            
            $newBurg['cheese'] = $cheeses;
            
			if (!isset($burg->topping))
            {
                $toppings .= "none";    
            }
            
            foreach($burg->topping as $topping)
            {
                $toppings .= $topping['type'] . ", ";
            }
            
            $newBurg['toppings'] = $toppings;
            if (!isset($burg->sauce))
            {
                $sauces .= "none";    
            }
            
            foreach($burg->sauce as $sauce)
            {
                $sauces .= $sauce['type'] . ", ";
            }
            
            $newBurg['sauces'] = $sauces;
            
            if (isset($burg->instructions))
            {
                $newBurg['instructions'] = (string) $burg->instructions;
            }
            else
            {
                $newBurg['instructions'] = "";
            }
            
            $cost = $this->getCost($burg);
            
            $newBurg['cost'] = $cost;
            $this->total += $cost;
                        
            $this->burgers[] = $newBurg;
        }
    }
    
    private function getCost($burg)
    {
        $burgTotal = 0.00;
        
        $burgTotal += $this->menu->getPatty((string) $burg->patty['type'])->price;
        
        if (isset($burg->cheeses['top']))
        {
            $burgTotal += $this->menu->getCheese((string) $burg->cheeses['top'])->price; 
        }
        
        if (isset($burg->cheeses['bottom']))
        {
            $burgTotal += $this->menu->getCheese((string) $burg->cheeses['bottom'])->price; 
        }
        
        foreach ($burg->topping as $topping)
        {
            $burgTotal += $this->menu->getTopping((string) $topping['type'])->price; 
        }
        
        return $burgTotal;
    }
            
}