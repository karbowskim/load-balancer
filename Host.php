<?php

class Host 
{    
     /**
     * Host number
     */
    private $number;
    
    /**
     * Host load
     */
    private $load;
    
    /**
     * 
     * @param int $number
     * @param float $load
     */
    public function __construct($number, $load) 
    {
        $this->load = $load;
        $this->number = $number;
    }
    
    /**
     * 
     * @param int $load
     */
    public function setLoad($load)
    {
        $this->load = $load;
    }
    
    /**
     * Returns host's current load
     * 
     * @return float
     */
    public function getLoad()
    {
        return $this->load;
    }
    
    /**
     * Returns host number
     *
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }
    
    /**
     * Handle request
     */
    public function handleRequest()
    {
        echo 'Request handled by host ' . $this->number . PHP_EOL;
        $this->load += 0.05;
    }
    
}
