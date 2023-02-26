<?

	require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
    require_once($_SERVER['DOCUMENT_ROOT']."/user/PHPMailer/src/PHPMailer.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/user/PHPMailer/src/SMTP.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/user/PHPMailer/src/Exception.php");
    
	$data = file_get_contents("php://input");
	$json = json_decode($data, true);
    $nama = $conn-> real_escape_string($json['nama']);
    $telepon = $conn-> real_escape_string($json['telepon']);
    $email = strtolower($conn-> real_escape_string($json['email']));
    $passwords = $conn-> real_escape_string($json['password']);
    $password=password_hash($passwords, PASSWORD_DEFAULT);
    
    $response = (object)array();
	
	if((empty($nama)) || (empty($telepon)) || (empty($email)) || (empty($passwords))){ 
     	$response->success = 0;
     	$response->message = "Kolom tidak boleh kosong";
     	die(json_encode($response));
    }
	 
	 if(filter_var($email, FILTER_VALIDATE_EMAIL)){
	     
    	$query2 = mysqli_query($conn, "insert into users values(null,'$nama','$email',null,null,'$telepon','$password','167210996X.png','1','','$ts',null)");
    	
    	if($query2){
    	    
    	    $mail = new PHPMailer\PHPMailer\PHPMailer();           
            $mail->isSMTP();                                   
            $mail->Host = ""; 
            $mail->SMTPAuth = true;                             
            $mail->Username = "";                 
            $mail->Password = "";                           
            $mail->SMTPSecure = "ssl";                      
            $mail->Port = 465;                                
            $mail->From ="";
            $mail->FromName = "";
            $mail->addAddress($email,$nama);
            $mail->isHTML(true);
            $mail->Subject = "Verifikasi email Pasar Cikalong";
            $mail->Body = "
            <h1>Registrasi Berhasil.</h1>
            <p>Silakan klik button ini untuk melanjutkan =>
                <a href=?email=$email>
                    <button>
                        Klik ME
                    </button>
                </a>
            </p>";
            
            if(!$mail->send()){
                
                $query3 = mysqli_query($conn, "delete from users where email='$email'");	
                $response->success = 0;
        	 	$response->message = "Email verifikasi tidak terkirim silakan registrasi kembali";
        	 	die(json_encode($response));
        	 	
            }else{
                
                $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
                $row = mysqli_fetch_array($query);
                $id=$row['id'];
                $query5 = mysqli_query($conn, "insert into sdata_keranjang values(null,'$id','$ts',null)");
                $response->success = 1;
                $response->message = "Registrasi berhasil, Silakan cek email untuk verifikasi.";
        	 	die(json_encode($response));
        	 	
            }
    	    
        }else{
            
            $response->success = 0;
            $response->message = "Email sudah digunakan";
    	 	die(json_encode($response));
    	 	
        }   
        
     }else{
         
        $response->success = 0;
	 	$response->message = "Masukan Email dengan benar";
	 	die(json_encode($response)); 
	 	
     }
	 
	mysqli_close($conn);

?>