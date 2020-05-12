<?php

     read_pwd_file();
      

     function read_pwd_file()                                                                        
     {                                                                                               
         $handler = fopen("hashkiller.txt","r") or die("CANT LOCATE FILE");                          
         if($handler)                                                                                
         {                                                                                           
             while(($line = fgets($handler)) !== false)                                              
             {                                                                                       
                 $line = preg_replace('/\s+/','',$line);                                             
                 $hash = md5($line);                                                                 
                 checkData($line,$hash);                                                              
             }                                                                                       
         }                                                                                           
     }
    function checkData($line,$hash)
    {
        $host ="localhost"; $uname = "root"; $pwd = "pirate0013"; $dbname = "Brute";
        $conn = mysqli_connect($host,$uname,$pwd,$dbname);
        $query = "SELECT MD5Hash FROM passwords WHERE MD5Hash = ?";
        $stmt = mysqli_stmt_init($conn);
        $stmt->prepare($query);
        $stmt->bind_param("s", $hash);
        $stmt->execute();
        $exists = $stmt->num_rows();
        if($exists < 1)
        {
            sendData($conn,$line,$hash);
        }
    }

    function sendData($conn,$line,$hash)
    {
        $query = "INSERT INTO passwords(Password,MD5Hash) VALUES (?,?)";
        $stmt = mysqli_stmt_init($conn);
        $stmt->prepare($query);
        $stmt->bind_param("ss",$password, $hash);
        $stmt->execute();
        echo "[".$password."][".$hash."] \r\n";
    }
    
?>