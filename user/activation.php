<?php

    require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
    
    $email=$conn-> real_escape_string($_GET["email"]);
    
    $query = mysqli_query($conn, "select * from users where email='$email'");
    $row = mysqli_fetch_array($query);
    $emailCek=$row['email'];
    
    if($emailCek==$email){
        $query2 = mysqli_query($conn, "UPDATE users SET token=null where email='$email'");
        if($query2){
            echo "<h1>Registrasi berhasil.</h1>";   
        }else{
            echo "<h1>Registrasi gagal server sedang perbaikan. </h1>";   
        }
    }else{
        echo "<h1>Email tidak cocok dengan yang sedang didaftarkan.</h1>"; 
    }
    
	mysqli_close($conn);


?>