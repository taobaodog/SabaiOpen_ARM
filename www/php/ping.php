<?php 
// Sabai Technology - Apache v2 licence
// Copyright 2016 Sabai Technology
   
$pingAddress=$_REQUEST['pingAddress'];
$pingCount=$_REQUEST['pingCount'];
$pingSize=$_REQUEST['pingSize'];

// Check pingCount since we're going to put it into the command line
    
exec("ping $pingAddress -c $pingCount -s $pingSize ", $output, $result);

$datalines = preg_filter("/^([0-9]*).*: seq=([0-9]*) ttl=([0-9]*) time=([0-9]*.[0-9]* ms)/","$1,$2,$3,$4", $output);

$dataResult = array();

foreach($datalines as &$dl){
  $dl = explode(",", $dl);
  $dataResult[] = array(
    'bytes' => $dl[0],
    'count' => $dl[1],
    'ttl' => $dl[2],
    'time' => $dl[3]
  );
}

$info = array_shift(preg_filter("/^([0-9]+) packets transmitted, ([0-9]+) packets received, ([0-9]+)% packet loss$/","$1,$2,$3",$output));

$statistics = array_shift(preg_filter("/^round-trip min\/avg\/max = ([.0-9]+)\/([.0-9]+)\/(.*)$/","$1,$2,$3",$output));

$out = array(
  'pingResults' => $dataResult,
  'pingInfo' => $info,
  'pingStatistics' => $statistics
);

echo json_encode($out,JSON_PRETTY_PRINT);

?>  
