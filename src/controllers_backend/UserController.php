<?php
use \Interop\Container\ContainerInterface;
use \Firebase\JWT\JWT;
use \Tuupola\Base62;
class UserController {

   	protected $db;
   	protected $container;

   	public function __construct(ContainerInterface $container) {
       	$this->db = $container->db;
       	$this->container = $container;
   	}

   	public function user_login($request, $response, $args){
		try {
			$input = $request->getParsedBody();
			$password = md5($input['password']);
			$sql = "select username from psb_admin where username = :username and password = :password";
			$sth = $this->db->prepare($sql);
			$sth->bindParam("username", $input['username']);
	        $sth->bindParam("password", $password);
		    $sth->execute();
		    $user = $sth->fetchObject();
		    if (!$user) {
		    	return $response->withJson(array("token" => "error"));
		    }
		    $now = new DateTime();
    		$future = new DateTime("+1 days");
    		$server = $request->getServerParams();
    		// $jti = (new Base62)->encode(mcrypt_create_iv(16));
    		$payload = [
		        "iat" => $now->getTimeStamp(),
		        "exp" => $future->getTimeStamp(),
		        // "jti" => $jti,
		        "sub" => $server["SERVER_NAME"],
		        "data" => $user
		    ];
		    $token = JWT::encode($payload, $this->container['settings']['jwt']['secret'], "HS256");
		    $data["token"] = $token;
    		$data["expires"] = $future->getTimeStamp();
		    return $response->withJson($data);
		} catch (Exception $e) {
			return $response->write($e)->withStatus(400);
		}
	}

	public function auth($request, $response, $args){
		return $response->withJson(array(
            "status" => "ok"
        ));
	}

}