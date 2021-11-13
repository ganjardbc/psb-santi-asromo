<?php
use \Interop\Container\ContainerInterface;
class ConfigController {


   	protected $db;
   	protected $container;

   	public function __construct(ContainerInterface $container) {
       	$this->db = $container->db;
       	$this->container = $container;
   	}

   	
   	public function config($request, $response, $args){
   		try {
               $sth = $this->db->prepare("select * from psb_config");
               $sth->execute();
               $data = $sth->fetchAll();
               return $response->withJson($data);
   		} catch (Exception $e) {
   			return $response->write($e)->withStatus(400);
   		}
   	}

   	public function update($request, $response, $args){
   		try {
   			$input = $request->getParsedBody();
   			$sth = $this->db->prepare("update psb_config set 
   				biaya_pendaftaran =:biaya_pendaftaran,
   				status_gelombang =:status_gelombang,
   				max_bangku =:max_bangku 
   				where id_config =:id_config");
               $sth->bindParam("biaya_pendaftaran", $input['biaya_pendaftaran']);
               $sth->bindParam("status_gelombang", $input['status_gelombang']);
               $sth->bindParam("max_bangku", $input['max_bangku']);
               $sth->bindParam("id_config", $input['id_config']);
               $sth->execute();
               return $response->withJson(array("status" => "success"));
   		} catch (Exception $e) {
   			return $response->write($e)->withStatus(400);
   		}
   	}
}