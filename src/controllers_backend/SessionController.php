<?php
use \Interop\Container\ContainerInterface;
class SessionController {

   	protected $db;
   	protected $container;

   	public function __construct(ContainerInterface $container) {
       	$this->db = $container->db;
       	$this->container = $container;
   	}

   	public function session($request, $response, $args){
		try {
			$limit = 50;
			$page = ($args['page'] - 1) * $limit;
            $sth = $this->db->prepare("select s.id_session,s.session_name,IFNULL(c.id_session,'0') as activate from psb_session s left join psb_config c on s.id_session = c.id_session order by s.session_name desc limit $limit offset $page");
            $sth->execute();
            $data = $sth->fetchAll();
            return $response->withJson($data);
		} catch (Exception $e) {
			return $response->write($e)->withStatus(400);
		}
	}

	public function search($request, $response, $args){
		try {
			$limit = 50;
			$q = $request->getParam('q');
            $sth = $this->db->prepare("select s.id_session,s.session_name,IFNULL(c.id_session,'0') as activate from psb_session s left join psb_config c on s.id_session = c.id_session where s.session_name like '%$q%' limit $limit");
            $sth->execute();
            $data = $sth->fetchAll();
            return $response->withJson($data);
		} catch (Exception $e) {
			return $response->write($e)->withStatus(400);
		}
	}

	public function save($request, $response, $args){
		try {
			$input = $request->getParsedBody();
			$sth = $this->db->prepare("select count(id_session) as cnt from psb_session where session_name = :session_name");
            $sth->bindParam("session_name", $input['session_name']);
            $sth->execute();
            $check_name = $sth->fetchObject();
            if ($check_name->cnt === '0') {
            	$sth = $this->db->prepare("insert into psb_session 
	            (
	            	session_name,
	            	created_at
	            ) 
	            values(
	            	:session_name,
	            	now()
	            )");
	            $sth->bindParam("session_name", $input['session_name']);
	            $sth->execute();
	            return $response->withJson(array("status" => "success"));
            }else{
            	return $response->withJson(array("status" => "error"));
            }
            
		} catch (Exception $e) {
			return $response->write($e)->withStatus(400);
		}
	}

	public function update($request, $response, $args){
		try {
			$input = $request->getParsedBody();
			$sth = $this->db->prepare("select count(id_session) as cnt from psb_session where session_name = :session_name");
            $sth->bindParam("session_name", $input['session_name']);
            $sth->execute();
            $check_name = $sth->fetchObject();
            if ($check_name->cnt === '0') {
            	$sth = $this->db->prepare("update psb_session set session_name =:session_name where id_session = :id_session");
	            $sth->bindParam("session_name", $input['session_name']);
	            $sth->bindParam("id_session", $input['id_session']);
	            $sth->execute();
	            return $response->withJson(array("status" => "success"));
            }else{
            	return $response->withJson(array("status" => "error"));
            }
            
		} catch (Exception $e) {
			return $response->write($e)->withStatus(400);
		}
	}

	public function delete($request, $response, $args){
		try {
			$input = $request->getParsedBody();
			$sth = $this->db->prepare("select count(id_session) as cnt from psb_config where id_session = :id_session");
            $sth->bindParam("id_session", $input['id_session']);
            $sth->execute();
            $check_session = $sth->fetchObject();
            if ($check_session->cnt === '0') {
            	$sth = $this->db->prepare("delete from psb_session where id_session=:id_session");
	            $sth->bindParam("id_session", $input['id_session']);
	            $sth->execute();
	            return $response->withJson(array("status" => "success"));
            }else{
            	return $response->withJson(array("status" => "error"));
            }
            
		} catch (Exception $e) {
			return $response->write($e)->withStatus(400);
		}
	}

	public function activate($request, $response, $args){
		try {
			$input = $request->getParsedBody();
            $sth = $this->db->prepare("update psb_config set id_session =:id_session where id_config = 1");
            $sth->bindParam("id_session", $input['id_session']);
            $sth->execute();
            return $response->withJson(array("status" => "success"));
		} catch (Exception $e) {
			return $response->write($e)->withStatus(400);
		}
	}

}