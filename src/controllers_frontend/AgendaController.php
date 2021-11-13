<?php
use \Interop\Container\ContainerInterface;
class AgendaController {

   	protected $db;
   	protected $view;

   	public function __construct(ContainerInterface $container) {
       	$this->db = $container->db;
       	$this->view = $container->view;
   	}

   	public function index($request, $response, $args){
		try {
			$common  = new common();
            /*if ($common->date_diff()) {
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
            
            $sql = "select 
				c.id_session,
				c.biaya_pendaftaran,
				c.ibtida,
				c.pekan_taaruf
			 from psb_config c";
            $sth = $this->db->prepare($sql);
            $sth->execute();
            $config = $sth->fetchObject();
            $sql = "select * from psb_agenda";
            $sth = $this->db->prepare($sql);
            $sth->execute();
            $datas = $sth->fetchAll();
			return $this->view->render($response, "/agenda.twig", array(
		    	"title" => "Agenda Pendaftaran Santri Asromo Online",
		    	"active" => "agenda",
		    	"datas" => $datas,
		    	"pekan_taaruf" => $config->pekan_taaruf,
		    	"ibtida" => $config->ibtida,
		    	"biaya_pendaftaran" => $config->biaya_pendaftaran
		    ));

		} catch (Exception $e) {
			echo $e;
		}
	}

	
}