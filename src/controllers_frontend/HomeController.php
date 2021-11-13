<?php
use \Interop\Container\ContainerInterface;
class HomeController {

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
            
			// return $this->view->render($response, "/home.twig", array(
		    // 	"title" => "Pendaftaran Santri Asromo Online",
		    // 	"active" => "home"
		    // ));
			return $this->view->render($response, "/home.php", array(
		    	"title" => "Pendaftaran Santri Asromo Online",
		    	"active" => "home"
		    ));
		} catch (Exception $e) {
			echo $e;
		}
	}

	
}