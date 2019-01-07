<?php
class Desktop_Detect{
	public $os;
	public $browser;

	public function __construct(){
		$this->getDesktopOS($_SERVER['HTTP_USER_AGENT']);
		$this->getDesktopBrowser($_SERVER['HTTP_USER_AGENT']);
    }

	private function getDesktopOS($user_agent){
		$os_platform    =   "Unknown OS Platform";
		$os_array       =   array(
			'/windows nt 6.3/i'     =>  'Windows 8.1',
            '/windows nt 6.2/i'     =>  'Windows 8',
            '/windows nt 6.1/i'     =>  'Windows 7',
            '/windows nt 6.0/i'     =>  'Windows Vista',
            '/windows nt 5.1/i'     =>  'Windows XP',
            '/windows xp/i'         =>  'Windows XP',
            '/windows nt 5.0/i'     =>  'Windows 2000',
            '/windows me/i'         =>  'Windows ME',
            '/win98/i'              =>  'Windows 98',
            '/win95/i'              =>  'Windows 95',
            '/win16/i'              =>  'Windows 3.11',
            '/macintosh|mac os x/i' =>  'Mac OS X',
            '/mac_powerpc/i'        =>  'Mac OS 9',
            '/linux/i'              =>  'Linux',
            '/ubuntu/i'             =>  'Ubuntu',
            '/iphone/i'             =>  'iPhone',
            '/ipod/i'               =>  'iPod',
            '/ipad/i'               =>  'iPad',
            '/android/i'            =>  'Android',
            '/blackberry/i'         =>  'BlackBerry',
            '/webos/i'              =>  'Mobile'
        );

	    foreach($os_array as $regex => $value){
	    	if(preg_match($regex, $user_agent)){
	    		$os_platform = $value;
	    	}
	    }
		
		$this->os = $os_platform;
	}
	
	private function getDesktopBrowser($user_agent){
		$browser        =   "Unknown Browser";
		$browser_array  =   array(
			'/msie/i'       =>  'Internet Explorer',
			'/firefox/i'    =>  'Firefox',
			'/safari/i'     =>  'Safari',
			'/chrome/i'     =>  'Chrome',
			'/opera/i'      =>  'Opera',
			'/netscape/i'   =>  'Netscape',
			'/maxthon/i'    =>  'Maxthon',
			'/konqueror/i'  =>  'Konqueror',
			'/mobile/i'     =>  'Handheld Browser'
		);

	    foreach($browser_array as $regex => $value){
	    	if(preg_match($regex, $user_agent)){
	    		$browser    =   $value;
	    	}
	    }

		$this->browser = $browser;
	}
}
?>