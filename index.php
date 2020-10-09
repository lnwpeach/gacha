<?php
session_start();

$chance = ['UUR' => 0.1, 'UR' => 0.5, 'SSR' => 1, 'SR' => 5, 'R' => 30, 'N' => 100];
?>

<html>
<head></head>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<body>
    <h3>ทดสอบความเกลือ</h3>
    Code: <input name="code" type="text">
    <button onclick='use_code()' style='margin-left:20px;'>Use</button> <span name='result' style='margin-left:10px;'></span>
    <br><br>
    Amount: <span name='amount'></span>
    <br><br>
    Qty: <input type='number' name='round' value='1' min='1' oninput='cal_price()'> <button onclick='random()'>Random</button>
    <br><br>
    Price: <span name='price'>50</span>
    <br><br>

    Reward: <span name='reward'></span>

    <br><br>
    <table border=1 style='width: 200px;'>
        <thead>
            <tr>
                <td>Item</td>
                <td>Rate</td>
                <td>Qty</td>
            </tr>
        </thead>
        <tbody id='item_total'>
            <tr>
                <td>N</td>
                <td>66.4</td>
                <td name='n_qty'></td>
            </tr>
            <tr>
                <td>R</td>
                <td>30</td>
                <td name='r_qty'></td>
            </tr>
            <tr>
                <td>SR</td>
                <td>5</td>
                <td name='sr_qty'></td>
            </tr>
            <tr>
                <td>SSR</td>
                <td>1</td>
                <td name='ssr_qty'></td>
            </tr>
            <tr>
                <td>UR</td>
                <td>0.5</td>
                <td name='ur_qty'></td>
            </tr>
            <tr>
                <td>UUR</td>
                <td>0.1</td>
                <td name='uur_qty'></td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan=2>Sum Qty</td>
                <td name='sum_qty'></td>
            </tr>
        </tfoot>
    </table>

    <br>
    <button onclick='clear_reward()'>Clear</button>
</body>
</html>

<script>
function random() {
    var amount = $('[name=amount]').text()*1;
    var price = $('[name=price]').text()*1;
    
    if(price > amount) {
        alert("No money no life");
        return false;
    }

    var q = {};
        q['round'] = $('[name=round]').val();
    var json = JSON.stringify(q);
    var url = "api/random.php";
    $.ajax({
        data: {'json': json}, url: url, type: 'post', dataType: 'json',
        success: function(res) {
            if(res.success != 1) {
                alert(res.message);
            }
            
            var reward = res.reward;

            if(localStorage.amount) {
                amount = localStorage.amount*1 - price;
            } else {
                amount = 0 - price;
            }
            localStorage.setItem('amount', amount);

            var txt = "";
            $.each(reward, function(k, v) {
                if(v)
                    txt += `${k}: ${v} | `;
            });
            txt = txt.slice(0, -3);
            $('[name=reward]').text(txt);

            if(localStorage.reward_total && localStorage.reward_total != '{}') {
                reward_total = JSON.parse(localStorage.reward_total);
            } else {
                reward_total = {};
            }

            $.each(reward, function(k, v) {
                if(reward_total[k]) {
                    reward_total[k] += v;
                } else {
                    reward_total[k] = v;
                }
            });

            reward_total = JSON.stringify(reward_total);
            localStorage.setItem('reward_total', reward_total);
            
            retrieve();
        }
    })
}

function retrieve() {
    if(localStorage.amount) {
        amount = localStorage.amount*1;
    } else {
        amount = 0;
    }

    if(localStorage.reward_total && localStorage.reward_total != '{}') {
        reward_total = JSON.parse(localStorage.reward_total);
    } else {
        reward_total = '';
    }

    $('[name=amount]').text(amount);

    if(reward_total) {
        var sum_qty = 0;
        $.each(reward_total, function(k, v) {
            sum_qty += v*1;
        });

        $("[name=n_qty]").text(reward_total.N);
        $("[name=r_qty]").text(reward_total.R);
        $("[name=sr_qty]").text(reward_total.SR);
        $("[name=ssr_qty]").text(reward_total.SSR);
        $("[name=ur_qty]").text(reward_total.UR);
        $("[name=uur_qty]").text(reward_total.UUR);
        $("[name=sum_qty]").text(sum_qty);
    } else {
        $("[name=n_qty]").text(0);
        $("[name=r_qty]").text(0);
        $("[name=sr_qty]").text(0);
        $("[name=ssr_qty]").text(0);
        $("[name=ur_qty]").text(0);
        $("[name=uur_qty]").text(0);
        $("[name=sum_qty]").text(0);
    }
}

function clear_reward() {
    if( !confirm('Are you sure?') ) return false;

    $('[name=reward]').text('');
    localStorage.setItem('reward_total', '{}');
    retrieve();
}

function cal_price() {
    var round = $('[name=round]').val();
    var price = round * 50;
    $('[name=price]').text(price);
}

function use_code() {
    $('[name=result]').html('');

    var q = {};
        q['code'] = $('[name=code]').val().replace(/[^0-9a-zA-Z]/g, '').toUpperCase();
    var json = JSON.stringify(q);
    var url = "api/use-code.php";
    $.ajax({
        data: {'json': json}, url: url, type: 'post', dataType: 'json',
        success: function(res) {
            if(res.success != 1) {
                alert(res.message);
                return false;
            }

            var value = res.value*1;

            if(localStorage.amount) {
                amount = localStorage.amount*1 + value;
            } else {
                amount = value;
            }
            localStorage.setItem('amount', amount);

            var span =`<span>Success add value ${value}</span>`;
            var temp = $(span).appendTo('[name=result]');
            $('[name=code]').val('');
            retrieve();

            setTimeout(() => {
                temp.remove();
            }, 3000);
        }
    })
}

$("[name=round]").on('keydown', function(e) {
    if(e.keyCode == 13) random();
})

$(document).ready(function() {
    retrieve();
})
</script>