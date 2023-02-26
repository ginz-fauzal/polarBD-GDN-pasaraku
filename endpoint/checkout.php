<?php
    
    require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
    
	$data = file_get_contents("php://input");
	$json = json_decode($data, true);
    $dataJson=$json['data'];
    $asuransi=$conn-> real_escape_string($json['asuransi']);
    $jp=$conn-> real_escape_string($json['jenis_pengiriman']);
    $total_bayar=$conn-> real_escape_string($json['total_bayar']);
    $penerima=$conn-> real_escape_string($json['penerima']);
    $ongkir=$conn-> real_escape_string($json['ongkir']);
    $push=$conn-> real_escape_string($json['push']);
    $alamat=$conn-> real_escape_string($json['alamat']);
    $telepon=$conn-> real_escape_string($json['telepon']);
    $jenis_bayar=$conn-> real_escape_string($json['jenis_bayar']);
    $latitude=$conn-> real_escape_string($json['latitude']);
    $longitude=$conn-> real_escape_string($json['longitude']);
    
    $response = (object)array();
    
    $query = mysqli_query($conn, "SELECT * FROM users WHERE push='$push'");
    $row = mysqli_fetch_array($query);
    $id=$row['id'];
        
    if($row['push']==$push && $push!=''){
        
        $query1 = mysqli_query($conn, "SELECT count(id_checkout) as hitung FROM sdata_checkout WHERE id='$id' AND status='Menunggu'");
        $row1 = mysqli_fetch_array($query1);
        $hitung=(int)$row1['hitung'];
        
        if($hitung<2){
            
            $total_barang=count($dataJson);
            $kode = 'INV-'.round(microtime(true));
            $query2 = mysqli_query($conn, "insert into sdata_checkout values(null,'$id',null,'$kode','$total_barang','$asuransi','$ongkir','$jp','$total_bayar','0','$alamat','$telepon','$latitude','$longitude','$penerima','$dates','$times','Menunggu','$jenis_bayar','',null,null,null,'$ts',null)");
            $id_checkout = mysqli_insert_id($conn);
            
            if($query2){
                
                $total_barang=0;
                $total=0;
                $total_dasar=0;
                
                foreach($dataJson as $item){
                    
                    $id_barang=$conn-> real_escape_string($item['id_barang']);
                    $jumlah=$conn-> real_escape_string($item['jumlah']);
                    $keterangan=$conn-> real_escape_string($item['keterangan']);
                    $id_listkeranjang=$conn-> real_escape_string($item['id_listkeranjang']);
                    $query4 = mysqli_query($conn, "DELETE FROM `sdata_listkeranjang` WHERE id_listkeranjang='$id_listkeranjang'");
                    $query5 = mysqli_query($conn, "SELECT * FROM sdata_listcheckout WHERE id_barang='$id_barang' AND id_checkout='$id_checkout'");
                    $row5 = mysqli_fetch_array($query5);
                    $jumlah1=isset($row5['jumlah']) ?  (int)$row5['jumlah'] : null;
                    $query6 = mysqli_query($conn, "SELECT * FROM sdata_barang WHERE id_barang='$id_barang'");
                    $row6 = mysqli_fetch_array($query6);
                    $dis=$row6['dis'];
            	    $harga=$row6['harga'];
            	    $harga_dasar=$row6['harga_dasar'];
            	    $harga_dis=$harga-$harga*$dis/100;
            	    $total_dasar=$total_dasar+($jumlah*$harga_dasar);
                    $total=$jumlah*$harga_dis;
                    $total_barang=$total_barang+$jumlah;
                    $query7 = mysqli_query($conn, "insert into sdata_listcheckout values(null,'$id_checkout','$id_barang','$jumlah','$keterangan','$total','$ts',null)");
                    
                }
                
                $query8 = mysqli_query($conn, "UPDATE sdata_checkout SET total_barang='$total_barang',total_dasar='$total_dasar' WHERE id_checkout='$id_checkout'");
                $query9=mysqli_query($conn, "SELECT push FROM admin where id_admin='1'");
                $row9 = mysqli_fetch_array($query9);
                $to=$row9['push'];
                
                $data=array(
                    'title'=>"Notifikasi",
                    'icon'=>'myicon',
                    'sound'=>'res://bell.wav',
                    'body'=>"Anda mendapatkan pesanan baru.",
                    'color' => '#00ff00',
                    'sound'=>'default',
                    'vibrate' => 10);
                sendPushNotification($to,$data);
                
                
                if($query7){
                    
                    $response->id=$id_checkout;
                    $response->success = 1;
                    $response->message = "Data berhasil ditambahkan";
            	 	die(json_encode($response));
            	 	
                }else{
                    
                    $response->id=0;
                    $response->success = 0;
                    $response->message = "Maaf server sedang bermasalah.";
            	 	die(json_encode($response));
            	 	
                }
                
            }else{
                
                $response->id=0;
                $response->success = 0;
                $response->message ="Checkout gagal";
        	 	die(json_encode($response));
        	 	
            }
            
        }else{
            
            $response->id=0;
            $response->success = 0;
            $response->message = "Maaf Max. 2 pesanan menunggu.";
    	 	die(json_encode($response));
    	 	
        }    
   
    }else{
        
        $response->id=0;
        $response->success = 0;
        $response->message = "API ini telah dilindungi. :)";
        die(json_encode($response));
        
    }
    
    
	mysqli_close($conn);

?>