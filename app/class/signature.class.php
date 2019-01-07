<?php
class Signature extends Database{

    private $expire = 60; // 20 Min.

    public function generateSignature($form,$secretKey){
        // clear expire signature.
        $this->clearSign();

        // lowercase everything
        $dataString = strtolower(hash('sha512',uniqid(mt_rand(1,mt_getrandmax()),true)));

        // generate signature using the SHA256 hashing algorithm
        $sign = hash_hmac('sha256',$dataString.$form,$secretKey);

        // Save key into Database
        parent::query('INSERT INTO signature(sign,expire,create_time,form) VALUE(:sign,:expire,:create_time,:form)');
        parent::bind(':sign'            ,$sign);
        parent::bind(':expire'          ,time() + $this->expire);
        parent::bind(':create_time'     ,date('Y-m-d H:i:s'));
        parent::bind(':form'            ,$form);
        parent::execute();

        return $sign;
    }

    public function verifySign($sign){

        if(empty($sign)) return false;

        $old_sign = $this->get($sign);

        if($sign == $old_sign){
            // $this->removeSign($sign); // REMOVE THIS SIGN
            return true;
        }else{
            return false;
        }
    }

    public function create($sign_key,$type){
        parent::query('INSERT INTO signature(sign_key,expire,type) VALUE(:sign_key,:expire,:type)');
        parent::bind(':sign_key',       $sign_key);
        parent::bind(':expire',         time()+(60*20));
        parent::bind(':type',           $type);
        parent::execute();
        return parent::lastInsertId();
    }

    public function get($sign){
        parent::query('SELECT sign FROM signature WHERE (sign = :sign AND expire > :now)');
        parent::bind(':sign'    ,$sign);
        parent::bind(':now'     ,time());
        parent::execute();
        $data = parent::single();

        if(!empty($data['sign']) && isset($data['sign'])){
            parent::query('UPDATE signature SET active_time = :active_time WHERE sign = :sign');
            parent::bind(':sign'            ,$data['sign']);
            parent::bind(':active_time'     ,date('Y-m-d H:i:s'));
            parent::execute();
        }

        return $data['sign'];
    }

    private function removeSign($sign){
        parent::query('DELETE FROM signature WHERE sign = :sign');
        parent::bind(':sign',$sign);
        parent::execute();
    }

    private function clearSign(){
        parent::query('DELETE FROM signature WHERE (expire < :now)');
        parent::bind(':now',time());
        parent::execute();
    }
}
?>