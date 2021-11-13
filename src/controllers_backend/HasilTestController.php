<?php
use \Interop\Container\ContainerInterface;
class HasilTestController {

   	protected $db;

   	public function __construct(ContainerInterface $container) {
       	$this->db = $container->db;
   	}

   	public function hasil_id($request, $response, $args){
		try {
			$sql = "select f.* from psb_file_hasil_seleksi f where f.id_session = (select id_session from psb_config limit 1)";
			$sth = $this->db->prepare($sql);
		    $sth->execute();
		    $data = $sth->fetchAll();
		    return $response->withJson($data);
		} catch (Exception $e) {
			echo $e;
		}
	}

	public function save($request, $response, $args){
		try {
			$input = $request->getParsedBody();
			$sql = "select 
				c.id_session
			 from psb_config c";
            $sth = $this->db->prepare($sql);
            $sth->execute();
            $config = $sth->fetchObject();
            $id_session = $config->id_session;
			$sql = "insert into psb_file_hasil_seleksi (id_session,judul,url_file,created_at) values ('$id_session',:judul,:url_file,now())";
			$sth = $this->db->prepare($sql);
			$sth->bindParam("judul", $input['judul']);
			$sth->bindParam("url_file", $input['url_file']);
		    $sth->execute();
		    return $response->withJson(array("status" => "success"));
		} catch (Exception $e) {
			echo $e;
		}
	}

	public function upload($request, $response, $args){
		try {
            $input = $request->getParsedBody();
            $target_dir = "files/";
		    $ext = strtolower(substr(strrchr(basename($_FILES["file"]["name"]), '.'), 1));
		    $target_file = $target_dir.$_POST['name'].'.'.$ext;
		    $filename = $_POST['name'].'.'.$ext;
		    move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
		    $file_data = array(
		        "file" => $filename
		    );
            return $response->withJson($file_data);
        } catch (Exception $e) {
            echo $e;
        }
	}

	public function update($request, $response, $args){
		try {
			$input = $request->getParsedBody();
			$sql = '';
			if ($input['status_upload'] === 'upload') {
				$url_file = $input['url_file'];
				$sql = "update psb_file_hasil_seleksi set judul=:judul,
			 url_file='$url_file' where id_file_hasil_seleksi=:id_file_hasil_seleksi";
			}else{
				$sql = "update psb_file_hasil_seleksi set judul=:judul where id_file_hasil_seleksi=:id_file_hasil_seleksi";
			}
			
			$sth = $this->db->prepare($sql);
			$sth->bindParam("id_file_hasil_seleksi", $input['id_file_hasil_seleksi']);
			$sth->bindParam("judul", $input['judul']);
		    $sth->execute();
		    return $response->withJson(array("status" => "success"));
		} catch (Exception $e) {
			echo $e;
		}
	}

	public function delete($request, $response, $args){
		try {
			$input = $request->getParsedBody();
			$sql = "delete from psb_file_hasil_seleksi where id_file_hasil_seleksi=:id_file_hasil_seleksi";
			$sth = $this->db->prepare($sql);
			$sth->bindParam("id_file_hasil_seleksi", $input['id_file_hasil_seleksi']);
		    $sth->execute();
		    return $response->withJson(array("status" => "success"));
		} catch (Exception $e) {
			echo $e;
		}
	}
}