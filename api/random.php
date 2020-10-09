<?php
session_start();

$answer = ['success' => 0, 'message' => ''];
if(!isset($_POST['json'])) {
    $answer['message'] = "Error: no data";
    exit(json_encode($answer));
}

$data = json_decode($_POST['json'], true);

// ---------------------------------------------------

$chance = ['UUR' => 0.1, 'UR' => 0.5, 'SSR' => 1, 'SR' => 5, 'R' => 30, 'N' => 100];

foreach($chance as &$c) {
    $c /= 100;
}

function random($chance) {
    $rand = ((float)mt_rand()/(float)getrandmax());
    $reward = 'Out';
    foreach($chance as $k => $c) {
        if($rand < $c) {
            $reward = $k;
            break;
        }
        $rand -= $c;
    }
    return $reward;
}

$round = (int)$data['round'];

$reward = [];
foreach($chance as $k => $v) {
    $reward[$k] = 0;
}

for($i = 0; $i < $round; $i++) {
    $item = random($chance);

    if(isset($reward[$item]))
        $reward[$item]++;
    else
        $reward[$item] = 1;
}

$answer['reward'] = $reward;
$answer['success'] = 1;
$answer['message'] = 'Success';
exit(json_encode($answer));
?>