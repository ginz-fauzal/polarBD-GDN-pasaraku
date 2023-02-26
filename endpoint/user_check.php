<?php

    require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');

	$data = file_get_contents("php://input");
	$json = json_decode($data, true);
    $push=$conn-> real_escape_string($json['push']);
    $query = mysqli_query($conn, "SELECT * FROM users WHERE push='$push'");
    $row = mysqli_fetch_array($query);
    
    if($row['push']==$push && $push!=''){
        $id=$row['id'];
        $query2 = mysqli_query($conn, "SELECT sum(sdata_listkeranjang.jumlah) as jumlah FROM sdata_listkeranjang INNER JOIN sdata_keranjang on sdata_keranjang.id_keranjang=sdata_listkeranjang.id_keranjang WHERE sdata_keranjang.id='$id'");
        $row2 = mysqli_fetch_array($query2);
        
        $query3 = mysqli_query($conn, "SELECT count(id_notifikasi) as jumlah FROM notifikasi WHERE status_baca='0' AND id='$id'");
        $row3 = mysqli_fetch_array($query3);
        
        $query4 = mysqli_query($conn, "SELECT count(id_checkout) as jumlah FROM sdata_checkout WHERE id='$id' AND status='Menunggu'");
        $row4 = mysqli_fetch_array($query4);
       
        $response->notif = $row3['jumlah'] == null ? 0:(int)$row3['jumlah'];
        $response->keranjang = $row2['jumlah']== null ? 0:(int)$row2['jumlah'];
        $response->transaksi = $row4['jumlah'] == null ? 0:(int)$row4['jumlah'];
        $response->success = 1;
 	    die(json_encode($response));
    }else{
        $response->success = 0;
        $response->message = "Anda telah login di device berbeda.";
        die(json_encode($response));
    }
    
    
    
	mysqli_close($conn);

?>