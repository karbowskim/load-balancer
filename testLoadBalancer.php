<?php
include_once 'LoadBalancer.php';
include_once 'Host.php';
include_once 'Request.php';

CONST HOSTS_NUMBER = 5;
CONST INITIAL_LOAD = 0.6;
CONST SEQENTIAL_REQUESTS_AMOUNT = 11;
CONST OPTIMIZED_REQUESTS_AMOUNT = 11;

for($i=1; $i<=HOSTS_NUMBER; $i++){
    $hostInstances[$i] = new Host($i, INITIAL_LOAD);
}

$loadBalancer =  new LoadBalancer($hostInstances, 1);
$request =  new Request();

echo PHP_EOL;
echo "Testing sequential request handling" . PHP_EOL;
echo '-----------------------------------' . PHP_EOL;;

for($i=0; $i<SEQENTIAL_REQUESTS_AMOUNT ; $i++){
    $loadBalancer->handleRequest($request);
}

// Clearing hostInstances array for optimized balancing test
$hostInstances = array();
for($i=1; $i<=HOSTS_NUMBER; $i++){
    $hostInstances[$i] = new Host($i, INITIAL_LOAD);
}

$loadBalancer->setBalancingVariant(2);

echo PHP_EOL;
echo 'Testing optimized request handling' . PHP_EOL;
echo '----------------------------------' . PHP_EOL;
echo PHP_EOL;
echo 'Initial load:' . PHP_EOL;

printHostsLoad($loadBalancer);

for($i=0; $i<OPTIMIZED_REQUESTS_AMOUNT; $i++){
    $loadBalancer->handleRequest($request);
    echo 'New load:' . PHP_EOL;
    printHostsLoad($loadBalancer);
}

function printHostsLoad($loadBalancer)
{
    foreach($loadBalancer->getHostInstances() as $host){
    echo 'Host\'s ' . $host->getNumber() . ' load: ' . $host->getLoad() . PHP_EOL;
    }
    echo PHP_EOL;
}
