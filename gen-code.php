<?php
session_start();

if( !(isset($_GET['h']) && $_GET['h'] == 'abd95192953f0d55134f07404259652402b84b2f81f6bbeb17b7a9f40e27e584') ) {
    echo "Unauthorized Page";
    exit();
}
?>

<html>
<head></head>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<body>
    <h3>Gen Code</h3>
    Value: 
    <select name="value">
        <option>50</option>
        <option>100</option>
        <option>300</option>
        <option>500</option>
        <option>1000</option>
        <option>3000</option>
        <option>5000</option>
    </select>
    <button onclick='generate()' style='margin-left:20px;'>Generate</button>
    <br>
    <br>
    Code: <input name="code" class="code" type="text" readonly>
</body>
</html>

<script>
function generate() {
    var q = {};
        q['value'] = $('[name=value]').val();
    var json = JSON.stringify(q);
    var url = "api/gen-code.php";
    $.ajax({
        data: {'json': json}, url: url, type: 'post', dataType: 'json',
        success: function(res) {
            if(res.success != 1) {
                alert(res.message);
                return false;
            }

            var code = res.code;
            for(var i=0;i<code.length;i++) {
                if(i == 4 && code[i] != '-') {
                    code = code.slice(0, i)+'-'+code.slice(i);
                } else if(i == 9 && code[i] != '-') {
                    code = code.slice(0, i)+'-'+code.slice(i);
                } else if(i == 14 && code[i] != '-') {
                    code = code.slice(0, i)+'-'+code.slice(i);
                }
            }
            
            $('[name=code]').val(code);
        }
    })
}

$('.code').on('focus', function() {
    $(this).select();
})

$(document).ready(function() {
    
})
</script>