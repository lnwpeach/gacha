<?php
session_start();

$chance = ['UUR' => 0.1, 'UR' => 0.5, 'SSR' => 1, 'SR' => 5, 'R' => 30, 'N' => 100];
?>

<html>
<head></head>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<body>
    <h3>ทดสอบความเกลือ</h3>
    Round: <input type='number' name='round' value='1' min='1'> <button onclick='random()'>Random</button>
    <br>
    <br>
    <table border=1 style='width: 200px;'>
        <thead>
            <tr>
                <td>Item</td>
                <td>Qty</td>
            </tr>
        </thead>
        <tbody id='item_total'>
            <tr>
                <td>N</td>
                <td name='n_qty'></td>
            </tr>
            <tr>
                <td>R</td>
                <td name='r_qty'></td>
            </tr>
            <tr>
                <td>SR</td>
                <td name='sr_qty'></td>
            </tr>
            <tr>
                <td>SSR</td>
                <td name='ssr_qty'></td>
            </tr>
            <tr>
                <td>UR</td>
                <td name='ur_qty'></td>
            </tr>
            <tr>
                <td>UUR</td>
                <td name='uur_qty'></td>
            </tr>
        </tbody>
    </table>
</body>
</html>

<script>
function random() {
    var q = {};
        q['round'] = $('[name=round]').val();
    var json = JSON.stringify(q);
    var url = "api/random.php";
    $.ajax({
        data: {'json': json}, url: url, type: 'post', dataType: 'json',
        success: function(res) {
            if(res.success != 1) {
                alert(res.message);
                return false;
            }
            console.log(res)

        }
    })
}

$(document).ready(function() {
    console.log(1)
})
</script>

<?php

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

function submit() {
    global $chance, $round;

    foreach($chance as &$c) {
        $c /= 100;
    }

    $reward_log = [];
    for($i = 0; $i < $round; $i++) {
        $reward = random($chance);

        if(isset($reward_log[$reward]))
            $reward_log[$reward]++;
        else
            $reward_log[$reward] = 1;

        $_SESSION['round']++;
    }

    foreach($reward_log as $k => $v) {
        $_SESSION['reward_log'][$k] += $v;
    }

    // echo $reward.'<br>';

    echo 'Reward<br>';
    echo '<pre>';
    print_r($reward_log);
    echo '</pre>';

    echo 'Round: '.$_SESSION['round'].'<br><br>';

    echo 'Reward Log<br>';
    echo '<pre>';
    print_r($_SESSION['reward_log']);
    echo '</pre>';
    echo '<hr>';
}


?>