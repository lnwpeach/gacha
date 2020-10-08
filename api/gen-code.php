<?php
    include('db.php');

    function gen_code($len) {
        $str = "";
        for($i=1;$i<=$len;$i++) {
            $rand_up = rand(65, 90);
            $rand_num = rand(48, 57);
        
            $rand_arr = [$rand_up, $rand_num];
            $rand_sel = rand(0, 1);
            $chr = chr($rand_arr[$rand_sel]);
            $str .= $chr;
        }
    
        return $str;
    }

    function gen_num($len) {
        $str = "";
        for($i=1;$i<=$len;$i++) {
            $rand_num = rand(48, 57);
        
            $chr = chr($rand_num);
            $str .= $chr;
        }
    
        return $str;
    }
    
    $value = (int)$data['value'];
    $code = gen_code(16);

    $dup = $pdo->query("SELECT count(*) from code where code = '{$code}'")->fetchColumn();
    if($dup > 0) {
        $answer['message'] = "Duplicate Code";
        exit(json_encode($answer));
    }

    $expire_dt = date('Y-m-d H:i:s', strtotime('+1 month'));
    $sth = $pdo->query("INSERT INTO code (code, value, expire_dt) values('{$code}', '{$value}', '{$expire_dt}')");
    if(!$sth) {
        $answer['message'] = $pdo->errorInfo();
        exit(json_encode($answer));
    }

    $answer['code'] = $code;
    $answer['success'] = 1;
    $answer['message'] = 'Success';
    exit(json_encode($answer));
?>