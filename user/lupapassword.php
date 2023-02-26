<?php

    require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
    require_once($_SERVER['DOCUMENT_ROOT']."/user/PHPMailer/src/PHPMailer.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/user/PHPMailer/src/SMTP.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/user/PHPMailer/src/Exception.php");

    $data = file_get_contents("php://input");
    $json = json_decode($data, true);
    $email= strtolower($conn-> real_escape_string($json['email']));
    
    $response = (object)array();

    if ((empty($email))) {
        
        $response->success = 0;
        $response->message = "Form email tidak boleh kosong.";
        die(json_encode($response));
        
    }
    
    $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    $row = mysqli_fetch_array($query);
    
    $temp=rand(100000,999999);
    $password=password_hash($temp, PASSWORD_DEFAULT);
    
    if($row['email']==$email) {
        
        $query = mysqli_query($conn, "UPDATE users SET password='$password',updated_at='$ts' WHERE email='$email'");
    	
    	$mail = new PHPMailer\PHPMailer\PHPMailer();     
        $mail->isSMTP(true);                                   
        $mail->Host = ""; 
        $mail->SMTPAuth = true;                             
        $mail->Username = "";                 
        $mail->Password = "";                           
        $mail->SMTPSecure = "ssl";                      
        $mail->Port = 465;                                
        $mail->From ="";
        $mail->FromName = "";
        $mail->addAddress($email,$row['name']);
        $mail->isHTML(true);
        $bodyContent = '<h1>Pembaruan Password Berhasil.</h1>';
        $bodyContent .= "<p>Silakan login menggunakan password ini $temp</p>";
        $mail->Subject = 'Lupa Password Pasar Cikalong';
        $mail->Body    = $bodyContent;
        
        if(!$mail->send()) {
            
            $response->success = 0;
            $response->message = "Email yang anda masukan tidak kenal.";
            die(json_encode($response));
            
        } else {
            
            $response->success = 1;
            $response->message = "Lupa password berhasil cek email untuk password baru";
            die(json_encode($response));
            
        }
    }else {
        
        $response->success = 2;
        $response->message = "Email yang anda masukan tidak kenal";
        die(json_encode($response));
        
    }
    
    mysqli_close($conn);
    
?>