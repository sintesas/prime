<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Editarsemilleroinv\GrupossemilleroForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Grupossemillero;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Grupoinvestigacion;


class GrupossemilleroController extends AbstractActionController
{
	private $auth;
	public $dbAdapter;
  
	public function __construct() {
     //Cargamos el servicio de autenticacien el constructor
     $this->auth = new AuthenticationService();
	}
	
    public function indexAction()
    {
    	$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$auth = $this->auth;

		$permisos = new Permisos($this->dbAdapter);
    	$identi = print_r($auth->getStorage()->read()->id_usuario,true);
		$resultadoPermiso = $permisos->getValidarPermisoPantalla($identi,get_class());
    	if($resultadoPermiso==0){
    		$this->flashMessenger()->addMessage("Su rol no tiene permiso para ver el formulario. Si desea puede solicitarlo al administrador.");
    		return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/mensajeadministrador/index');
    	}

		$grupos = array();
		$GrupossemilleroForm = new GrupossemilleroForm();
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$permiso = new Permisos($this->dbAdapter);

		$id = (int) $this->params()->fromRoute('id',0);
		$id2 = (int) $this->params()->fromRoute('id2',0);
    	$id3 = (int) $this->params()->fromRoute('id3',0);
		$u = new Grupossemillero($this->dbAdapter);

		$identi=$auth->getStorage()->read();
		if($identi==false && $identi==null){
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
		}

		$rol = new Roles($this->dbAdapter);
		$rolusuario = new Rolesusuario($this->dbAdapter);
		$rolusuario->verificarRolusuario($identi->id_usuario);
		foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
			$dat["id_rol"];
		}

		if ($dat["id_rol"]!= ''){
			//me verifica el permiso sobre la pantalla
			$pantalla="editarredinv";
			$panta=0;
			$pt = new Agregarvalflex($this->dbAdapter);
			foreach ($pt->getValoresflexdesc($pantalla) as $panta) {
				$panta["id_valor_flexible"];
			}

			$permiso->verificarPermiso($dat["id_rol"],$panta["id_valor_flexible"]);
			foreach ($permiso->verificarPermiso($dat["id_rol"],$panta["id_valor_flexible"]) as $per) {
				$per["id_rol"];
			}
		}

		if($id3 != 0){
			if($this->getRequest()->isPost()){
				$upload = new \Zend\File\Transfer\Adapter\Http();
                $file_name = "";
                $error_file = false;
                if($upload->isUploaded()){
                    //Archivo cargado
                    $upload->setValidators(array(
                        'Size'  => array('min' => 1, 'max' => 50000000),
                    ));
                    if($upload->isValid()){
                        $files = $upload->getFileInfo();
                        foreach($files as $f){
                            $file_name=$f["name"];
                        }
                        if($id2!=0){
                            $registro = $u->getGruposById($id2);
                            if($registro != null){
                                unlink("public/images/uploads/si/gruposr/si_gr__".$id."_".$registro[0]["archivo"]);
                            }
                        }
                        $upload->setDestination('./public/images/uploads/si/gruposr/');
                        $upload->addFilter('File\Rename', array('target' => $upload->getDestination().DIRECTORY_SEPARATOR . "si_gr__".$id."_".$file_name,'overwrite' => true));
                        $rtn = array('success' => null);
                        $rtn['success'] = $upload->receive();
                        $resul=$u->updateArchivo($id2, $file_name);
                    }else{
                        $error_file = true;
                    }
                    if($error_file){
						$this->flashMessenger()->addMessage("Recuerde que el tamaño máximo del archivo es de 25MB.");
                    }else{
	                    $this->flashMessenger()->addMessage("Archivo cargado exitosamente");
                    }
	                return $this->redirect()->toUrl($this->getRequest()
	                        ->getBaseUrl() . '/application/editarsemilleroinv/index/' . $id);
               	}
			}else{
				$view = new ViewModel(
					array(
						'form'=>$GrupossemilleroForm,
						'titulo'=>"Grupos de Investigación - Cargar archivo",
						'url'=>$this->getRequest()->getBaseUrl(),
						'id'=>$id,
						'id2'=>$id2,
						'id3'=>$id3,
						'menu'=>$dat["id_rol"]
					)
				);	
			}
			return $view;
		}else{
			if($this->getRequest()->isPost()){
				$data = $this->request->getPost();
				$id = (int) $this->params()->fromRoute('id',0);
				$grupoinvestigacion = new Grupoinvestigacion($this->dbAdapter);
				$grupos = $grupoinvestigacion->filtroRedes($data);
			}
			$pt = new Agregarvalflex($this->dbAdapter);
			$auth = $this->auth;

			$identi=$auth->getStorage()->read();
			if($identi==false && $identi==null){
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
			}

			$filter = new StringTrim();
				
				// define el campo autor
	        $usuario = new Usuarios($this->dbAdapter);
	        $opciones = array(""=>"");
	        $a = '';
	        
	        foreach ($usuario->getUsuarios($a) as $pa) {
	            $nombre_completo = $pa["primer_nombre"] . ' ' . $pa["segundo_nombre"] . ' ' . $pa["primer_apellido"] . ' ' . $pa["segundo_apellido"];
	            $op = array(
	                $pa["id_usuario"] => $nombre_completo
	            );
	            $opciones = $opciones + $op;
	        }
	        
	        $GrupossemilleroForm->add(array(
	            'name' => 'id_autor',
	            'type' => 'Zend\Form\Element\Select',
	            'attributes' => array(
	                'id' => 'id_autor'
	            ),
	            'options' => array(
	            	'label' => ' ',
	                'value_options' => $opciones,
	            )
	        ));

			$vf = new Agregarvalflex($this->dbAdapter);
			$opciones=array();
			foreach ($vf->getArrayvalores(23) as $uni) {
				$op=array(''=>'');
				$op=$op+array($uni["id_valor_flexible"]=>$uni["descripcion_valor"]);
				$opciones=$opciones+$op;
			}
			$GrupossemilleroForm->add(array(
				'name' => 'unidad_academica',
				'type' => 'Zend\Form\Element\Select',
				'options' => array(
		            'label' => 'Filtro unidad académica:',
		            'value_options' => $opciones,
				),
			));

			//define el campo ciudad
			$vf = new Agregarvalflex($this->dbAdapter);
			$opciones=array();
			foreach ($vf->getArrayvalores(33) as $dept) {
				$op=array(''=>'');
				$op=$op+array($dept["id_valor_flexible"]=>$dept["descripcion_valor"]);
				$opciones=$opciones+$op;
			}
			$GrupossemilleroForm->add(array(
				'name' => 'dependencia_academica',
				'type' => 'Zend\Form\Element\Select',
				'options' => array(
		            'label' => 'Filtro dependencia académica:',
		            'value_options' => $opciones,
				),
			)); 

			//define el campo ciudad
			$vf = new Agregarvalflex($this->dbAdapter);
			$opciones=array();
			foreach ($vf->getArrayvalores(34) as $dept) {
				$op=array(''=>'');
				$op=$op+array($dept["id_valor_flexible"]=>$dept["descripcion_valor"]);
				$opciones=$opciones+$op;
			}
			$GrupossemilleroForm->add(array(
				'name' => 'programa_academico',
				'type' => 'Zend\Form\Element\Select',
				'options' => array(
		            'label' => 'Filtro programa académico:',
		            'value_options' => $opciones,
				),
			)); 
	        
	        //Verifica permisos sobre la pantalla
			//verificar roles
			$per=array('id_rol'=>'');
			$dat=array('id_rol'=>'');
			$rolusuario = new Rolesusuario($this->dbAdapter);
			$permiso = new Permisos($this->dbAdapter);
		
			//me verifica el tipo de rol asignado al usuario
			$rolusuario->verificarRolusuario($identi->id_usuario);
			foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
				$dat["id_rol"];
			}
		
			if ($dat["id_rol"]!= ''){
			//me verifica el permiso sobre la pantalla
			$pantalla="editarsemilleroinv";
			$panta=0;
			$pt = new Agregarvalflex($this->dbAdapter);
			foreach ($pt->getValoresflexdesc($pantalla) as $panta) {
				$panta["id_valor_flexible"];
			}

			$permiso->verificarPermiso($dat["id_rol"],$panta["id_valor_flexible"]);
			foreach ($permiso->verificarPermiso($dat["id_rol"],$panta["id_valor_flexible"]) as $per) {
				$per["id_rol"];
			}
			}
			//termina de verifcar los permisos
			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2',0);
			$view = new ViewModel(
				array(
					'form'=>$GrupossemilleroForm,
					'titulo'=>"Agregar Grupos de investigación",
					'url'=>$this->getRequest()->getBaseUrl(),
					'id'=>$id,
					'id2'=>$id2,
					'menu'=>$dat["id_rol"],
					'grupos' => $grupos
				)
			);
			return $view;
		}				
    }

    public function bajarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Grupossemillero($this->dbAdapter);
        $filter = new StringTrim();
        $id = (int) $this->params()->fromRoute('id');
        $id2 = (int) $this->params()->fromRoute('id2');        
        $data = $u->getGruposById($id2);        
        foreach ($data as $d) {
            $fileName = $d["archivo"];
        }
        $response = new \Zend\Http\Response\Stream();   
        $response->setStream(fopen("public/images/uploads/si/gruposr/si_gr__".$id."_".$filter->filter($fileName), 'r'));
        $response->setStatusCode(200);
        $headers = new \Zend\Http\Headers();
        $headers->addHeaderLine('Content-Type', 'whatever your content type is')
                ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $filter->filter($fileName) . '"')
                ->addHeaderLine('Content-Length', filesize("public/images/uploads/si/gruposr/si_gr__".$id."_".$filter->filter($fileName)));
    	$response->setHeaders($headers);
        return $response;
    }

	public function eliminarAction(){
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$u = new Grupossemillero($this->dbAdapter);
		$id = (int) $this->params()->fromRoute('id',0);
		$id2 = (int) $this->params()->fromRoute('id2',0);
		$u->eliminarGrupossemillero($id);
		$this->flashMessenger()->addMessage("Producción de investigación eliminada con éxito.");
		return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editarsemilleroinv/index/'.$id2);	
	}

	public function delAction(){
		$GrupossemilleroForm = new GrupossemilleroForm();
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$u = new Grupossemillero($this->dbAdapter);
		$id = (int) $this->params()->fromRoute('id',0);
		$id2 = (int) $this->params()->fromRoute('id2',0);
		$view = new ViewModel(
			array(
				'form'=>$GrupossemilleroForm,
				'titulo'=>"Eliminar Producción de investigación",
				'url'=>$this->getRequest()->getBaseUrl(),
				'id'=>$id,
				'id2'=>$id2
			)
		);
		return $view;
	}

	public function addAction(){
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$u = new Grupossemillero($this->dbAdapter);
		$id = (int) $this->params()->fromRoute('id',0);
		$id2 = (int) $this->params()->fromRoute('id2',0);
		$id3 = (int) $this->params()->fromRoute('id3',0);
		if($id3==0){
			$u->addGruposemillero($id,$id2);
		}else{
			$u->updateGrupossemillero($id3,$id, "");
		}
		
		$this->flashMessenger()->addMessage("Grupo de investigación agregado con éxito.");
		return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editarsemilleroinv/index/'.$id2);	
	}
	
	

}