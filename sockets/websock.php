<?php
/* WEBsock class by DX */

class websock
{
  /*????????private*/
  var $sock;
  var $connection=0;
  var $keepalive=300;
  var $request='';
  var $browser='Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1) Gecko/20061010 Firefox/2.0';
  var $proto='1.0';
  var $addr='';
  var $success=0;
  var $proxy='';
  var $pport=0;
  var $port=0;
  var $s_addr='';
  var $s_port=0;
  var $use_lowlevel_socks=true;
  var $fsock_err='';
  var $auto_unzip=true;
  var $unzipped=false;
  var $proxy_add_str='';
  var $is_socks5=false;
  var $s_user='';
  var $s_pass='';


  /*** ?????? $addr - ?????IP ??? $port - ??. ***/

  function websock($addr,$port=80,$use_lowlevel_socks=true)
  {
    if(!function_exists('socket_create') && $use_lowlevel_socks)
      return;

    $this->addr=$addr;
    $this->s_addr=$addr;
    $this->s_port=$port;
    $this->use_lowlevel_socks=$use_lowlevel_socks;


    if($this->use_lowlevel_socks)
    {
      if(!preg_match("/^(\d{1,3}\.){3}\d{1,3}$/",$addr))
        $addr=gethostbyname($addr);

      if(!$addr) return;

      $this->sock=socket_create(AF_INET,SOCK_STREAM,SOL_TCP);

      if(!$this->sock)
        return;
    }

    $this->success=1;
  }

  function set_autounzip($au=true)
  {
    $this->auto_unzip=$au ? true : false;
    return true;
  }

  function get_unzipped()
  {
    return $this->unzipped;
  }


  function get($service_uri='/',$params='',$cookie='',$ref='')
  {
    $this->sconnect();
    $this->get_header($service_uri,'',$params,'GET',$cookie,$ref);
    $this->swrite();
    $ret=$this->http11read();
    $this->sclose();
    return $ret;
  }

  function post($service_uri='/',$params='',$cookie='',$ref='')
  {
    $this->sconnect();
    $this->get_header($service_uri,'',$params,'POST',$cookie,$ref);
    $this->swrite();
    $ret=$this->http11read();
    $this->sclose();
    return $ret;
  }


  /*** ?????????(??? ??? ???????). ***/

  function sconnect()
  {
    if($this->use_lowlevel_socks)
    {
      if($this->proxy)
      {
        if(!socket_connect($this->sock,$this->proxy,$this->pport)) return false;

        if($this->is_socks5) return $this->authorize();
      }
      else
      {
        if(!socket_connect($this->sock,$this->s_addr,$this->s_port)) return false;
      }

      if(!socket_set_nonblock($this->sock)) return false;
    }
    else
    {
      if($this->proxy)
      {
        $this->sock=fsockopen("tcp://".$this->proxy,$this->pport,$errno,$errstr,30);
        $this->fsock_err=$errno.': '.$errstr;
        if(!$this->sock) return false;

        if($this->is_socks5) return $this->authorize();
      }
      else
      {
        $this->sock=fsockopen("tcp://".$this->s_addr,$this->s_port,$errno,$errstr,30);
        $this->fsock_err=$errno.': '.$errstr;
        if(!$this->sock) return false;
      }
    }

    return true;
  }




  /*** ??????? ????? ???, ?? ??????? socks5. ***/

  function authorize()
  {
    $h=pack("H*",'05020002');
    $this->swrite($h);
    $result=bin2hex($this->sread(4));

    if($result=='0502')
    {
      $len_login=chr(strlen($this->s_user));
      $len_pass=chr(strlen($this->s_pass));
  
      $h=pack("H*","01").$len_login.$this->s_user.$len_pass.$this->s_pass;
      $this->swrite($h);
      
      $result=bin2hex($this->sread(4));

      if($result{3}!=0)
        return false;
    }
    else if($result!='0500')
    {
      return false;
    }

    $len_h=chr(strlen($this->s_addr));
    $h=pack("H*","05010003").$len_h.$this->s_addr.pack("n",$this->s_port);
    $this->swrite($h);
      
    $result=bin2hex($this->sread(100));

    if($result{3}==0)
      return true;
  }


  /*** ?????????? ?ip $addr ???? $port ????? ?? ??????. ***/

  function set_proxy($addr,$port,$user='',$pass='')
  {
    $this->proxy=$addr;
    $this->pport=$port;
    $this->is_socks5=false;

    if(strlen($user)>0 || strlen($pass)>0)
      $this->proxy_add_str='Proxy-Authorization: Basic '.base64_encode($user.':'.$pass)."\r\n";
    else
      $this->proxy_add_str='';


    return true;
  }


  /*** ???????socks5-??? ?ip $addr ???? $port ????? ?? ??????. ***/

  function set_socks5($addr,$port,$user='',$pass='')
  {
    $this->proxy=$addr;
    $this->pport=$port;
    $this->is_socks5=true;
    $this->s_user=$user;
    $this->s_pass=$pass;

    return true;
  }


  /*** ???? ?????????? ??? ??????? ???. ***/

  function check_success()
  {
    return $this->success;
  }


  /*** ??? ???? $data - ?????? ??? ???? ????????? ??, ?????? ???? ?? $data ? ???? ? ??????? ????? ?????????? get_header() (?. ??). ***/

  function swrite($data='')
  {
    if(!$data) $data=$this->request;

    if($this->use_lowlevel_socks)
    {
      if(socket_select($r=NULL,$w=array($this->sock),$f=NULL,5)!=1)
        return false;

      return socket_write($this->sock,$data);
    }
    else
    {
      return fwrite($this->sock,$data);
    }
  }


  /*** ??? $bytes ?? ? ???. ??????????????? ***/

  function sread($bytes)
  {
    if($this->use_lowlevel_socks)
    {
      if(socket_select($r=array($this->sock),$w=NULL,$f=NULL,5)!=1)
        return false;

      return socket_read($this->sock,$bytes);
    }
    else
    {
      return fread($this->sock,$bytes);
    }
  }


  /*** ??? ? ??? ???????? ?????? ?????? ? ???? ?HTTP/1.1. $stepbytes - ???? ??? ????? ? ?? ??????????????? ***/

  function sreadfull($stepbytes=128)
  {
    $reading='';

    while(($ret=$this->sread($stepbytes))!='')
    {
      $reading.=$ret;
    }

    $reading=$this->get_content_headers($reading);

    if($this->auto_unzip)
    {
      if(strpos($reading[0],'Content-Encoding: gzip')!==false)
      {
        $this->unzipped=true;
        $reading[1]=$this->gzBody($reading[1]);
      }
      else
      {
        $this->unzipped=false;
      }
    }

    return $reading;
  }





  /*** ??????? ?????. $conn==0 - close, $conn==1 - keep-alive; $keepalive - ??? ??????????. ***/

  function set_connection($conn=0,$keepalive=300)
  {
    $this->connection=$conn==0 ? 0 : 1;
    $this->keepalive=$keepalive;
    return true;
  }

  function set_proto($proto='1.0') //set 1.0 or 1.1
  {
    $this->proto= ($proto=='1.0' || $proto=='1.1') ? $proto : '1.0';
    return true;
  }


  /*** ??????? ???? ???? ? ???, ????????? ????? ? ?? ?????. ?????????: $c[0] - ????? $c[1] - ????? ***/

  function http11read($stepbytes=128)
  {
    $c=$this->sreadfull($stepbytes);

    if(strpos($c[0],'Transfer-Encoding: chunked')!==false)
      return array($c[0],$this->remove_lengths($c[1]));
    else
      return $c;
  }


  /*** ????? cookies ? ???????? ????????????? ???, ?????? ??????????Cookie. ***/
  /*** $header - ???????? ???? $ret==0 - ???????????? ?? ?????Cookie, $ret==1 - ???????????? cookies. ***/

  function get_cookie($header,$ret=0)
  {
    preg_match_all("/Set-Cookie: (.+)(;|\r)/iUs",$header,$cook);
    if(!isset($cook[1])) return $ret==0 ? '' : array();

    $carr=array_unique($cook[1]);

    $cookies=implode('; ',$carr);
    return $ret==0 ? $cookies : $carr;
  }


  /*** ?????? ??????Transfer-encoding: chunked. ????????????????? ***/

  function remove_lengths($res)
  {
    $len=1;
    $nlen=0;
    $curlen=0;
    $ret='';

    $tmp=explode("\r\n",$res);

    foreach($tmp as $line)
    {
      if($len==1)
      {
        if($line=="\r\n")
        {
          $ret.=$line."\r\n";
          continue;
        }

        $nlen=base_convert($line,16,10);
        if($nlen==0) continue;
        $len=0;
        $curlen=0;
        continue;
      }

      $curlen+=strlen($line."\r\n");

      $ret.=$line."\r\n";

      if($curlen>=$nlen)
      {
        $len=1;
        continue;
      }
    }

    return $ret;
  }


  /*** ???????????????? ???????????. $res - ?? ???? ????????: $ret[0] - ????? $ret[1] - ?????. ***/

  function get_content_headers($res)
  {
    $ret=explode("\r\n\r\n",$res,2);
    return $ret;
  }


  /*** ????????????????? ***/

  function set_browser($browser='Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1) Gecko/20061010 Firefox/2.0')
  {
    $this->browser=$browser;
    return true;
  }


  /*** ???????????? ??? ???? ***/
  /*** $service_uri - SERVICE_URI. ????, ?? http://site.com/aaa/bbb.php ?? /aaa/bbb.php ***/
  /*** $page - ????? ?? ????????? site.com. ?? ???????? ??new websock('site.com'), ? ????????? ?????. ?? ? ??new websock('1.2.3.4'), ? ???????????. ***/
  /*** $data - ???????????????(??? ?? POST) ***/
  /*** $method - GET/POST ??? ***/
  /*** $cookie - cookies ??? ??? ***/
  /*** $ref - Referer ***/
  /*** $addheaders - ????????????? ????? ??? ????? ***/
  /***  ????????? ??????????????? ? ?????. ***/

  function get_header($service_uri,$page='',$data='',$method='GET',$cookie='',$ref='',$addheaders='')
  {
    if(!$page) $page=$this->addr;

    if($method=='GET' && strlen($data)>0)
    {
      $service_uri.='?'.$data;
      $data='';
    }

    if($this->proxy && !$this->is_socks5)
      $request="$method http://{$this->s_addr}:{$this->s_port}$service_uri HTTP/{$this->proto}\r\n";
    else
      $request="$method $service_uri HTTP/{$this->proto}\r\n";

    $request.=$this->proxy_add_str;

    $request.="Host: $page\r\n";
    if($this->browser) $request.="User-Agent: {$this->browser}\r\n";
    if($ref) $request.="Referer: $ref\r\n";

    if($method=='POST')
    {
      $request.="Content-Type: application/x-www-form-urlencoded\r\n";
      $request.="Content-Length: ".strlen($data)."\r\n";
    }

    if($this->connection==0)
    { 
      $request.="Connection: close\r\n";
    }
    else
    {
      $request.="Keep-alive: {$this->keepalive}\r\n";
      $request.="Connection: keep-alive\r\n";
    }

    if($addheaders)
      $request.=$addheaders;


    if($cookie)
      $request.="Cookie: $cookie\r\n";

    $request.="\r\n";

    $request.=$data;

    $this->request=$request;

    return $request;
  }


  /*** ????? ????? ***/

  function sclose()
  {
    return $this->use_lowlevel_socks ? socket_close($this->sock) : fclose($this->sock);
  }


  /*** ??????????? ??? ??? ?????. ***/

  function serr()
  {
    return $this->use_lowlevel_socks ? socket_last_error($this->sock) : $this->fsock_err;
  }


  function gzBody($gzData)
  {
    if(substr($gzData,0,3)=="\x1f\x8b\x08")
    {
      $i=10;
      $flg=ord(substr($gzData,3,1));
      if($flg>0)
      {
        if($flg&4)
        {
          list($xlen)=unpack('v',substr($gzData,$i,2));
          $i=$i+2+$xlen;
        }

        if($flg&8) $i=strpos($gzData,"\0",$i)+1;
        if($flg&16) $i=strpos($gzData,"\0",$i)+1;
        if($flg&2) $i=$i+2;
      }

      return gzinflate(substr($gzData,$i,-8));
    }
    else
      return false;
  }
}

?>