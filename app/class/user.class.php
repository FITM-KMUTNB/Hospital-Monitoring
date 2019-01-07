<?php
class User extends Database{
	public $id;
	public $email;
	public $fname;
	public $lname;
	public $ip;
	public $type;
	public $status;
	public $register_time;
	public $visit_time;

    private $key = 'bRuD5WYw5wd0rdHR9yLlM6wt2vteuiniQBqE70nAuhU=';

	private $password;
	private $salt;

    public function getUser($user_id){
    	parent::query('SELECT id,email,fname,lname,password,salt,type,status,ip,register_time,visit_time FROM user WHERE id = :user_id');
		parent::bind(':user_id',$user_id);
		parent::execute();
		$dataset = parent::single();

		$this->id             = $dataset['id'];
		$this->email          = $dataset['email'];
		$this->fname          = $dataset['fname'];
		$this->lname          = $dataset['lname'];
		$this->password       = $dataset['password'];
		$this->salt           = $dataset['salt'];
		$this->ip             = $dataset['ip'];
		$this->type           = $dataset['type'];
		$this->status         = $dataset['status'];
		$this->register_time  = $dataset['register_time'];
		$this->visit_time     = $dataset['visit_time'];
    }

    public function sec_session_start() {
        $session_name   = 'sec_session_id';   // Set a custom session name
        $secure         = false;
        // session.cookie_secure specifies whether cookies should only be sent over secure connections. (https)

        // This stops JavaScript being able to access the session id.
        $httponly = true;

        // Forces sessions to only use cookies.
        // if(ini_set('session.use_only_cookies', 1) === FALSE) {
        //     header("Location: ../error.php?err=Could_not_initiate_a_safe_session");
        //     exit();
        // }

        // Gets current cookies params.
        $cookieParams = session_get_cookie_params();
        session_set_cookie_params(600,$cookieParams["path"],$cookieParams["domain"],$secure,$httponly);
        // session_set_cookie_params('600'); // 10 minutes.

        // Sets the session name to the one set above.
        session_name($session_name);
        session_start();             // Start the PHP session
        // session_regenerate_id(true); // regenerated the session, delete the old one.
    }

    public function loginChecking(){
        // READ COOKIES
        if(!empty($_COOKIE['user_id']) && empty($_SESSION['user_id']))
        	$_SESSION['user_id'] = $_COOKIE['user_id'];
        if(!empty($_COOKIE['login_string']) && empty($_SESSION['login_string']))
        	$_SESSION['login_string'] = $_COOKIE['login_string'];

        // Check if all session variables are set
        if(isset($_SESSION['user_id'],$_SESSION['login_string'])){

            $user_id        = $_SESSION['user_id'];
            $login_string   = $_SESSION['login_string'];

            // Get the user-agent string of the user.
            $user_browser   = $_SERVER['HTTP_USER_AGENT'];

            $this->getUser($this->Decrypt($user_id));

            if(!empty($this->id)){
                $login_check = hash('sha512',$this->password.$user_browser);

                if($login_check == $login_string){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function login($email,$password){
        $email          = filter_var(strip_tags(trim($email)),FILTER_SANITIZE_EMAIL);
        $password       = trim($password);
        $cookie_time    = time() + 3600 * 24 * 12; // Cookie Time (1 year)

        // GET USER DATA BY EMAIL
        parent::query('SELECT id,password,salt FROM user WHERE email = :email');
		parent::bind(':email',$email);
		parent::execute();
		$user_data = parent::single();

		if($this->checkBrute($user_data['id'])){
			if((hash('sha512',$password.$user_data['salt']) == $user_data['password'])){
				// PASSWORD IS CORRECT!
				$user_browser = $_SERVER['HTTP_USER_AGENT'];

				// XSS protection as we might print this value
				$user_id = preg_replace("/[^0-9]+/",'',$user_data['id']);
				// Encrypt UserID before send to cookie.
				$user_id = $this->Encrypt($user_id);

				// SET SESSION AND COOKIE
				$_SESSION['user_id'] = $user_id;
				setcookie('user_id',$user_id,$cookie_time);
				$_SESSION['login_string'] = hash('sha512',$user_data['password'].$user_browser);
				setcookie('login_string',hash('sha512',$user_data['password'].$user_browser),$cookie_time);

				// Save log to attempt : [successful]
				// parent::recordAttempt($user_data['id'],'successful');

				return 1; // LOGIN SUCCESS
			}else{
				// Save log to attempt : [fail]
				if(!empty($user_data['id'])){
					$this->recordAttempt($user_data['id']); // Login failure!
				}

				return 0; // LOGIN FAIL!
			}
		}else{
			return -1; // ACCOUNT LOCKED!
		}
        // Note: crypt — One-way string hashing (http://php.net/manual/en/function.crypt.php)
    }

    private function checkBrute($user_id){
        // First step clear attempt log.
        // parent::clearAttempt();
        // return (parent::countAttempt($user_id) >= 5 ? true : false);

        return true;
    }

    public function register($email,$fullname,$password){

        $email      = filter_var(strip_tags(trim($email)),FILTER_SANITIZE_EMAIL);
        // Random password if password is empty value
        $password   = (empty($password)?hash('sha512',uniqid(mt_rand(1,mt_getrandmax()),true)):$password);
        $salt       = hash('sha512',uniqid(mt_rand(1,mt_getrandmax()),true));
        // Create salted password
        $password   = hash('sha512',$password.$salt);

        $name = explode(' ',strip_tags(trim($fullname)));
        $fname = trim($name[0]);
        $lname = trim($name[1]);

        if($this->userAlready($email)){
        	parent::query('INSERT INTO user(email,fname,lname,password,salt,type,ip,register_time,visit_time) VALUE(:email,:fname,:lname,:password,:salt,:type,:ip,:register_time,:visit_time)');
			parent::bind(':email' 		,$email);
			parent::bind(':fname' 		,$fname);
			parent::bind(':lname' 		,$lname);
			parent::bind(':password' 	,$password);
			parent::bind(':salt' 		,$salt);
            parent::bind(':type'        ,1); // 1 = Normal
			parent::bind(':ip' 			,parent::GetIpAddress());
			parent::bind(':register_time' ,date('Y-m-d H:i:s'));
			parent::bind(':visit_time' 	,date('Y-m-d H:i:s'));
			parent::execute();

			$user_id = parent::lastInsertId();

        }else{
        	return 0;
        }

        return $user_id;
    }

    private function userAlready($email){
		parent::query('SELECT id FROM user WHERE email = :email');
		parent::bind(':email',$email);
		parent::execute();
		$dataset = parent::single();
		
		if(empty($dataset['id'])) return true;
		else return false;
	}

	private function Encrypt($data){
        $key = $this->key;
        $password = $this->cookie_salt;
        $encryption_key = base64_decode($key.$password);
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($data, 'aes-256-cbc', $encryption_key, 0, $iv);
        return base64_encode($encrypted . '::' . $iv);
    }
    private function Decrypt($data){
        $key = $this->key;
        $password = $this->cookie_salt;
        $encryption_key = base64_decode($key.$password);
        list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
        return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv);
    }


    // LOGIN ATTEMPTS
	private function recordAttempt($user_id){
		parent::query('INSERT INTO login_attempts(user_id,time,ip) VALUE(:user_id,:time,:ip)');
		parent::bind(':user_id' ,$user_id);
		parent::bind(':time' 	,time());
		parent::bind(':ip' 		,parent::GetIpAddress());

		parent::execute();
		return parent::lastInsertId();
	}

	private function clearAttempt(){
		parent::query('DELETE FROM login_attempts WHERE time < :limittime');
		parent::bind(':limittime', time() - 60);
		parent::execute();
	}

    public function findUserWithEmail($email){
        parent::query('SELECT id FROM user WHERE email = :email');
        parent::bind(':email',$email);
        parent::execute();
        $dataset = parent::single();
        return $dataset['id'];
    }
}
?>
