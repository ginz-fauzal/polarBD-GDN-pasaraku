<?php

	require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
	
	$data = file_get_contents("php://input");
	$json = json_decode($data, true);
	
    $nama=$conn-> real_escape_string($json['nama']);
    $telepon=$conn-> real_escape_string($json['telepon']);
    $tanggal_lahir=$conn-> real_escape_string($json['tanggal_lahir']);
    $jenis_kelamin=$conn-> real_escape_string($json['jenis_kelamin']);
    $filePath=$_SERVER['DOCUMENT_ROOT'].'/uploads/'.$conn-> real_escape_string($json["image"]);
    $push=$conn-> real_escape_string($json['push']);
    $tanggal_lahir = substr($tanggal_lahir, 0, 10);
    
	$response = (object)array();
	
	if ((empty($telepon)) || (empty($tanggal_lahir)) || (empty($nama))) { 
	    
	 	$response->success = 0;
	 	$response->message = "Kolom tidak boleh kosong";
	 	die(json_encode($response));
	 	
	 }else{
	    
	    $query = mysqli_query($conn, "SELECT * FROM users WHERE push='$push'");
        $row = mysqli_fetch_array($query);
    
        if($row['push']==$push && $push!=''){
            
            if((empty($json["image"]))){
                
                $query2 = mysqli_query($conn, "UPDATE users SET jenis_kelamin='$jenis_kelamin',name='$nama',telepon='$telepon',tanggal_lahir='$tanggal_lahir',updated_at='$ts' WHERE push='$push'");
                
            }else{
                
                if (file_exists($filePath)){
                    
                    $temp = explode(".", $filePath);
                    $newfilename = round(microtime(true)) . '.' . end($temp);
                    rename( $filePath,$_SERVER['DOCUMENT_ROOT'].'/uploads/'.$newfilename);
                    
                }
                
                $query2 = mysqli_query($conn, "UPDATE users SET jenis_kelamin='$jenis_kelamin',gambar='$newfilename',name='$nama',telepon='$telepon',tanggal_lahir='$tanggal_lahir',updated_at='$ts' WHERE push='$push'");
        	 	
            }
            
            if($query){
                
	 	        $response->success = 1;
                $response->message = "Edit profile berhasil";
    	       	die(json_encode($response));
    	       	
            }else{
                
	 	        $response->success = 0;
                $response->message = "Server sedang gangguan";
    	 	    die(json_encode($response));
    	 	    
            }
    	 	
    
        }else{
        	$response->success = 0;
            $response->message = "API ini telah dilindungi. :)";
            die(json_encode($response));
        }
	}
	
    
    
    
	mysqli_close($conn);

?>