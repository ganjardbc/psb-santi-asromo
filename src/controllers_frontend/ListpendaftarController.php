<?php
use \Interop\Container\ContainerInterface;
class ListpendaftarController {

   	protected $db;
   	protected $view;

   	public function __construct(ContainerInterface $container) {
       	$this->db = $container->db;
       	$this->view = $container->view;
   	}

   	public function index($request, $response, $args){
		try {
			$common  = new common();
           /* if ($common->date_diff()) {
            	$data_common = $common->countdown($this->db);
            	if ($data_common['diff_date_from'] > 1) {
	            	return $this->view->render($response, "/comingsoon.twig", array(
				    	"title" => "Pendaftaran Santri Asromo Online",
				    	"tanggal_pembukaan" => $data_common['date_from']
				    ));
	            }
	            if ($data_common['diff_date_to'] <= 1) {
	            	return $this->view->render($response, "/penutupan.twig", array(
				    	"title" => "Pendaftaran Santri Asromo Online",
				    	"tanggal_penutupan" => $data_common['date_to']
				    ));
	            }
            }*/
            
			$sql = "select id_session from psb_config";
            $sth = $this->db->prepare($sql);
            $sth->execute();
            $config = $sth->fetchObject();
            $id_session = $config->id_session;

            $page = ($request->getParam('page', 0) > 0) ? $request->getParam('page') : 1;
		    $limit = 50;
		    $skip = ($page - 1) * $limit;
		    $sql = "select count(id) as cnt from psb_pendaftaran where id_session='$id_session'";
			$sth = $this->db->prepare($sql);
		    $sth->execute();
		    $count_data = $sth->fetchObject();
		    $count = intval($count_data->cnt);

		    $sql = "select id,nama,jenis_kelamin,jenjang,nik,status from psb_pendaftaran where id_session='$id_session' limit $limit offset $skip";
            $sth = $this->db->prepare($sql);
            $sth->execute();
            $datas = $sth->fetchAll();
			return $this->view->render($response, "/list-pendaftar.twig", array(
		    	"title" => "List Pendaftar Santri Asromo Online",
		    	"active" => "list-pendaftar",
		    	"datas" => $datas,
		    	"value" => "",
		    	"needed" => $count > $limit,
		    	"page" => $page,
		    	"skip" => $skip,
		    	"lastpage" => (ceil($count / $limit) == 0 ? 1 : ceil($count / $limit)),
		    	"limit" => $limit
		    ));
		} catch (Exception $e) {
			echo $e;
		}
	}

	public function detail($request, $response, $args){
		try {
			$sql = "select id_session from psb_config";
            $sth = $this->db->prepare($sql);
            $sth->execute();
            $config = $sth->fetchObject();
            $id_session = $config->id_session;

            $id = $args['id'];
		    $sql = "select * from psb_pendaftaran where id_session='$id_session' and id='$id'";
            $sth = $this->db->prepare($sql);
            $sth->execute();
            $data = $sth->fetchObject();
			return $this->view->render($response, "/list-pendaftar-detail.twig", array(
		    	"title" => $data->nama." - pendaftaran Santri Asromo Online",
		    	"active" => "list-pendaftar-detail",
		    	"data" => $data,
		    	"value" => "",
		    	
		    ));
		} catch (Exception $e) {
			echo $e;
		}
	}

	public function index_search($request, $response, $args){
		try {
			$sql = "select id_session from psb_config";
            $sth = $this->db->prepare($sql);
            $sth->execute();
            $config = $sth->fetchObject();
            $id_session = $config->id_session;

            $limit     = 50;
		    $q = $request->getParam('q');
			if ($q=='') {
				return $response->withRedirect($request->getUri()->getBasePath().'/list-pendaftar');
			}

		    $sql = "select id,nama,jenis_kelamin,jenjang,nik,status from psb_pendaftaran where 
		    	(nama like '%$q%' or nik like '%$q%' or nisn like '%$q%') and id_session='$id_session' limit ".$limit;
            $sth = $this->db->prepare($sql);
            $sth->execute();
            $datas = $sth->fetchAll();
			return $this->view->render($response, "/list-pendaftar.twig", array(
		    	"title" => $q." Pendaftar Santri Asromo Online",
		    	"active" => "list-pendaftar",
		    	"value" => $q,
		    	"datas" => $datas,
		    	"skip" => 0,
		    	"needed" => false,
		    	"page" => 1,
		    	"lastpage" => 1,
		    	"limit" => $limit
		    ));
		} catch (Exception $e) {
			echo $e;
		}
	}
}