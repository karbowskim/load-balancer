<?php

/**
 * It is assumed that the balancing variant is expressed as an integer as
 * it's safer to pass int IDs than descriptive but misspelling prone strings.
 * 
 * The two constants were introduced to make the code easier to read - the reader
 * doesn't have to check what $this->loadBalancingVariant == 1 means in practice.
 * 
 * It is also assumed that hosts list must be sent as an array.
 * 
 * To make the code cleaner and more self-explanatory, additional functions were introduced.
 * They were not in the task description but were also not forbidden. :)
 * 
 * In case of optimized balancing, if two hosts have the same minimal load, 
 * it is assumed that it doesn't matter which of them will be chosen.
 * 
 * @author Marcin Karbowski <marcin.a.karbowski@gmail.com>
 */
class LoadBalancer 
{
    /**
     * Balancing variants constants - used for better code transparency
     * Sequential - passing requests sequentially to hosts on the list
     * Optimized - requests are passed to the first hosts with load < 0.75
     * If none of the hosts has load < 0.75, the one with the lowest load is chosen.
     */
   CONST SEQUENTIAL = 1;
   CONST OPTIMIZED = 2;
   
   /**
     * Host instances list
     *
     * @var array $hostInstances
     */
    private $hostInstances;

    /**
     * Balancing variant chosen
     *
     * @var int $loadBalancingVariant
     */
    private $loadBalancingVariant;

    /**
     * @param array $hostInstances
     * @param int $loadBalancingVariant
     */
    public function __construct(array $hostInstances, $loadBalancingVariant)
    {
        $this->hostInstances = $hostInstances;
        $this->loadBalancingVariant = $loadBalancingVariant;
    }

    /**
     * Handle the request according to the chosen balancing variant
     * 
     * @param Request $request
     * @throws Exception
     */
    public function handleRequest(Request $request = null)
    {
        if ( ! $this->parametersAreValid()){
            echo 'Incorrect balancing parameters. Terminating.' . PHP_EOL;
            return;
        }
        $host = ($this->loadBalancingVariant == self::SEQUENTIAL) ?
                $this->findNextHost() :
                $this->findOptimalHost();
        
        $host->handleRequest($request);
    }
    
    /**
     * Check if balancing parameters are correct
     * 
     * @return bool
     * @throws Exception
     */
    private function parametersAreValid()
    {
        if(empty($this->hostInstances)){
            echo 'Provided hosts list is empty!' . PHP_EOL;
            return false;
        }
        if(! is_int($this->loadBalancingVariant) ||
           $this->loadBalancingVariant != self::SEQUENTIAL && 
           $this->loadBalancingVariant != self::OPTIMIZED){
                echo 'Incorrect balancing variant!' . PHP_EOL;
                return false;
           }
        return true;   
    }

    /**
     * Returns first host in the array and puts him on host array end
     */
    private function findNextHost()
    {
        $currentHost = array_shift($this->hostInstances);
        array_push($this->hostInstances, $currentHost);
        return $currentHost;
    }
    
    /**
     * Finds host with load < 0.75 or, if not found, a host with the lowest load
     * 
     * @return int
     */
    private function findOptimalHost()
    {
        foreach((array)$this->hostInstances as $key => $instance){
            if($instance->getLoad() < 0.75){
                return $instance;
            }
        }
        $loadsArray = array();
        foreach((array)$this->hostInstances as $key => $instance){
            $loadsArray[$key] = $instance->getLoad();
        }
        $leastLoadedHostId = array_search(min($loadsArray), $loadsArray);
        return $this->hostInstances[$leastLoadedHostId];
    }
    
    /**
     * Set new balancing variant
     * 
     * @param int $balancingVariant
     */
    public function setBalancingVariant($balancingVariant)
    {
        $this->loadBalancingVariant = $balancingVariant;
    }
    
        public function getBalancingVariant()
    {
        return $this->loadBalancingVariant;
    }
}
