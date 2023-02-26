<?php

    require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
    
	$data = file_get_contents("php://input");
	$json = json_decode($data, true);
    $jumlah=(int)$conn-> real_escape_string($json['jumlah']);
    $id_barang=(int)$conn-> real_escape_string($json['id_barang']);
    $keterangan=$conn-> real_escape_string($json['keterangan']);
    $push=$conn-> real_escape_string($json['push']);
    
    $response = (object)array();
    
    $query = mysqli_query($conn, "SELECT * FROM users WHERE push='$push'");
    $row = mysqli_fetch_array($query);
    
    if($row['push']==$push && $push!=''){
        
        $id=$row['id'];
        $query1 = mysqli_query($conn, "SELECT * FROM sdata_keranjang WHERE id='$id'");
        $row1 = mysqli_fetch_array($query1);
        $id_keranjang=$row1['id_keranjang'];
        $query2 = mysqli_query($conn, "SELECT * FROM sdata_listkeranjang WHERE id_barang='$id_barang' AND id_keranjang='$id_keranjang'");
        $row2 = mysqli_fetch_array($query2);
        $id_listkeranjang= isset($row2['id_listkeranjang']) ?  $row2['id_listkeranjang']:null;
        
        if($id_listkeranjang==null){
            
            $query3 = mysqli_query($conn, "insert into sdata_listkeranjang values(null,'$id_keranjang','$id_barang','$jumlah','$keterangan','0','$ts',null)");
            
        }else{
            
            $jumlah=$jumlah+(int)$row2['jumlah'];
            $query3 = mysqli_query($conn, "UPDATE sdata_listkeranjang SET jumlah='$jumlah',keterangan='$keterangan',updated_at='$ts' WHERE id_listkeranjang='$id_listkeranjang'");
            
        }
        
        if($query3){
            $response->success = 1;
            $response->message = "Barang ditambahkan ke keranjang.";
    	 	die(json_encode($response));
        }else{
            
            $response->success = 0;
            $response->message = "Maaf server sedang bermasalah.";
            die(json_encode($response));
            
        }
        

    }else{
        
        $response->success = 0;
        $response->message = "API ini telah dilindungi. :)";
        die(json_encode($response));
        
    }
    
    
	mysqli_close($conn);

?>