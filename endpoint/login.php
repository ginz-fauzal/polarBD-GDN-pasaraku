<?php

	require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');

	$data = file_get_contents("php://input");
	$json = json_decode($data, true);
    $email=strtolower($conn-> real_escape_string($json['email']));
    $password=$conn-> real_escape_string($json['password']);
	$push=$conn-> real_escape_string($json['push']);
	
	$response = (object)array();
	
	if ((empty($email)) || (empty($password))) { 
	    
	 	$response->success = 0;
	 	$response->message = "Kolom tidak boleh kosong";
	 	die(json_encode($response));
	 	
	}
	 
	$query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
	$row = mysqli_fetch_array($query);

	if (password_verify($password, $row['password'])) {
	    
        if($row['token']==1){
            
    	 	$response->success = 0;
            $response->push=null;
    	 	$response->message = "Akun belum terverifikasi silakan cek email anda.";
    	 	die(json_encode($response));
    	 	
        }else{
            
            $query2 = mysqli_query($conn, "update users set push='',updated_at='$ts' where push='$push'");
            $query2 = mysqli_query($conn, "update users set push='$push',updated_at='$ts' where email='$email'");
    	 	$response->success = 1;
            $response->push=$push;
    	 	$response->message = "Selamat datang ".$row['name'];
    	 	die(json_encode($response));
    	 	
        }
        
    }else {
        
        $response->success = 0;
        $response->push=null;
	 	$response->message = "Email atau password salah.";
	 	die(json_encode($response));
	 	
    }
	
	
	mysqli_close($conn);

?>