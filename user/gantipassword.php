<?php

    require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
    
	$data = file_get_contents("php://input");
	$json = json_decode($data, true);
    $password_lama=$conn-> real_escape_string($json['password_lama']);
    $password_baru=password_hash($conn-> real_escape_string($json['password_baru']), PASSWORD_DEFAULT);
    $push=$conn-> real_escape_string($json['push']);
    
    $response = (object)array();
    
    $query = mysqli_query($conn, "SELECT * FROM users WHERE push='$push'");
    $row = mysqli_fetch_array($query);
    
    if ((empty($password_lama)) || (empty($password_baru))) {
        
        $response->success = 0;
        $response->message = "Form isi harus lengkap!";
        die(json_encode($response));
        
    }
    
    if($row['push']==$push && $push!=''){
	
    	$query = mysqli_query($conn, "SELECT * FROM users WHERE push='$push'");
    	$row = mysqli_fetch_array($query);
    	
        if (password_verify($password_lama, $row['password'])) {
            
            $query = mysqli_query($conn, "UPDATE users SET password='$password_baru',updated_at='$ts' WHERE push='$push'");
    	 	$response->success = 1;
    	 	$response->message = "Password berhasil diganti.";
    	 	die(json_encode($response));
    	 	
        }else {
            
            $response->success = 0;
    	 	$response->message = "Password lama yang anda masukan salah.";
    	 	die(json_encode($response));
    	 	
        }
    
    }else{
        
        $response->success = 0;
        $response->message = "API ini telah dilindungi. :)";
        die(json_encode($response));
        
    }
    
	mysqli_close($conn);

?>