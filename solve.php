<?php
$inputData = file_get_contents("cities.txt", "r");
$dataList = preg_split("/\r\n|\n|\r/", $inputData);

static $allPaths = [];
$cities = [];
$longitudes = [];
$latitudes = [];
$shortestDistance = 0;
$path = [];

foreach ($dataList as $data) {
    $cityInfo = explode(' ', $data);

    $cities[] = $cityInfo[0];
    $latitudes[$cityInfo[0]] = $cityInfo[1];
    $longitudes[$cityInfo[0]] = $cityInfo[2];
}

$allPaths = makeArrayPermutations($cities);

foreach ($allPaths as $key => $possiblePath) {
    $i = 0;
    $total = 0;
    foreach ($possiblePath as $city) {
        $total += calculateDistance($latitudes[$perms[$i]], $longitudes[$perms[$i]], $latitudes[$perms[$i + 1]], $longitudes[$perms[$i + 1]]);
    }
    $allPaths[$key]['distance'] = $total;

    if ($total < $shortestDistance || $shortestDistance == 0) {
        $shortestDistance = $total;
        $shortestPath = $possiblePath;
    }
}

printOutput(shortestPath);

function calculateDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo)
{
    // asumption
    $earthRadius = 1000;
    // convert from degrees to radians
    $latFrom = deg2rad($latitudeFrom);
    $lonFrom = deg2rad($longitudeFrom);
    $latTo = deg2rad($latitudeTo);
    $lonTo = deg2rad($longitudeTo);

    $latDelta = $latTo - $latFrom;
    $lonDelta = $lonTo - $lonFrom;

    $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
        cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
    return $angle * $earthRadius;
}

function makeArrayPermutations($items, $perms = array())
{
    static $allPermutations;
    if (empty($items)) { 
        $allPermutations[] = $perms;
    } else {
        for ($i = count($items) - 1; $i >= 0; --$i) {
             $newitems = $items;
             $newperms = $perms;
             list($foo) = array_splice($newitems, $i, 1);
             array_unshift($newperms, $foo);
             makeArrayPermutations($newitems, $newperms);
         }
    }
    return $allPermutations;
}

function printOutput($cities){
    foreach($cities as $city){
        echo $city . '/n';
    }
}
