<?php
use \Interop\Container\ContainerInterface;
class HasilseleksiController {

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
            }
          */
			     $sql = "select id_session from psb_config";
          $sth = $this->db->prepare($sql);
          $sth->execute();
          $config = $sth->fetchObject();
          $id_session = $config->id_session;

          $sql = "select * from psb_file_hasil_seleksi where id_session = $id_session";
          $sth = $this->db->prepare($sql);
          $sth->execute();
          $datas = $sth->fetchAll();
			     return $this->view->render($response, "/hasil-seleksi.twig", array(
  		    	"title" => "Hasil seleksi ujian - Santri Asromo Online",
  		    	"active" => "pendaftaran",
  		    	"datas" => $datas
  		    ));
		} catch (Exception $e) {
			echo $e;
		}
	}
}