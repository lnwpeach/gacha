<?php
    include('db.php');
    
    $code = isset($data['code']) ? $data['code'] : '';

    $sth = $pdo->prepare("SELECT * from code where code = :code");
    $sth->execute([':code' => $code]);
    $rs = $sth->fetch(PDO::FETCH_ASSOC);

    if($rs === false) {
        $answer['message'] = 'Invalid Code';
        exit(json_encode($answer));
    } else if($rs['status'] == 1) {
        $answer['message'] = 'This Code has been used';
        exit(json_encode($answer));
    } else if($rs['expire_dt'] < date('Y-m-d H:i:s')) {
        $answer['message'] = 'This Code has expired';
        exit(json_encode($answer));
    }

    $pdo->query("UPDATE code set status = 1 where id = {$rs['id']}");

    $answer['value'] = $rs['value'];
    $answer['success'] = 1;
    $answer['message'] = 'Success';
    exit(json_encode($answer));
?>