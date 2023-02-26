<?php

	require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
    
	$data = file_get_contents("php://input");
	$json = json_decode($data, true);
    $nama = $conn-> real_escape_string($json['nama']);
    $alamat = $conn-> real_escape_string($json['alamat']);
    $telepon = $conn-> real_escape_string($json['telepon']);
    $desa = $conn-> real_escape_string($json['desa']);
    $kecamatan = $conn-> real_escape_string($json['kecamatan']);
    $kota = $conn-> real_escape_string($json['kota']);
    $provinsi = $conn-> real_escape_string($json['provinsi']);
    $latitude = $conn-> real_escape_string($json['latitude']);
    $longitude = $conn-> real_escape_string($json['longitude']);
    $push=$conn-> real_escape_string($json['push']);
    
    $response = (object)array();
    
    $query = mysqli_query($conn, "SELECT * FROM users WHERE push='$push'");
    $row = mysqli_fetch_array($query);
    $id=$row['id'];
	
	if($row['push']==$push && $push!=''){
	
    	if((empty($nama)) || (empty($alamat)) || (empty($desa)) || (empty($kecamatan)) || (empty($kota)) || (empty($provinsi))   || (empty($telepon)) || (empty($latitude)) || (empty($longitude))){ 
    	    
         	$response->success = 0;
         	$response->message = "Kolom tidak boleh kosong.";
         	die(json_encode($response));
         	
        }else{
           
            $query1 = mysqli_query($conn, "insert into alamat values(null,'$id','$nama','$alamat','$latitude','$longitude','$provinsi','$kota','$kecamatan','$desa','$telepon','0','$ts',null)");
            
            if($query1){
                
                $response->success = 1;
                $response->message = "Data Berhasil ditambahkan.";
             	die(json_encode($response));
            	 	
            }else{
                
                $response->success = 0;
                $response->message = "Silakan periksa kembali input anda.";
             	die(json_encode($response));
            
            }   
        }
	
	}else{
	    
        $response->success = 0;
        $response->message = "API ini telah dilindungi. :)";
        die(json_encode($response));
        
    }
    
	mysqli_close($conn);

?>