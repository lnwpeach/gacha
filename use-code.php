<?php
session_start();
?>

<html>
<head></head>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<body>
    <h3>Use Code</h3>
    Code: <input name="code" type="text">
    <button onclick='use()' style='margin-left:20px;'>Use</button>
    <br><br>

    <span name='result'></span>
</body>
</html>

<script>
function use() {
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

            $('[name=result]').html(`Success add value ${value}<br>Amount: ${amount}`);
            $('[name=code]').val('');
        }
    })
}

$(document).ready(function() {
    
})
</script>