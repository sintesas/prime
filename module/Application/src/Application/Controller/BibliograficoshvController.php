<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Editarusuario\BibliograficoshvForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Bibliograficoshv;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

class BibliograficoshvController extends AbstractActionController
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

		function get_real_ip()
		{
			if (isset($_SERVER["HTTP_CLIENT_IP"])) {
				return $_SERVER["HTTP_CLIENT_IP"];
			} elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
				return $_SERVER["HTTP_X_FORWARDED_FOR"];
			} elseif (isset($_SERVER["HTTP_X_FORWARDED"])) {
				return $_SERVER["HTTP_X_FORWARDED"];
			} elseif (isset($_SERVER["HTTP_FORWARDED_FOR"])) {
				return $_SERVER["HTTP_FORWARDED_FOR"];
			} elseif (isset($_SERVER["HTTP_FORWARDED"])) {
				return $_SERVER["HTTP_FORWARDED"];
			} else {
				return $_SERVER["REMOTE_ADDR"];
			}
		}

		$au = new Auditoria($this->dbAdapter);
		$ad = new Auditoriadet($this->dbAdapter);
        
		if($this->getRequest()->isPost()){
			//Creacion adaptador de conexion, objeto de datos, auditoria
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$not = new Bibliograficoshv($this->dbAdapter);
			$auth = $this->auth;

			//verifica si esta conectado
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


			//obtiene la informacion de las pantallas
			$data = $this->request->getPost();
			$BibliograficoshvForm = new BibliograficoshvForm();
			//adiciona la noticia
			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2', 0);
			$upload = new \Zend\File\Transfer\Adapter\Http();

			
	                
			if($id2!=0){
				$resultado=$not->updateBibliograficoshv($id2, $data);
				if($upload->isUploaded()){
                    //Archivo cargado
                    $upload->setValidators(array(
                        'Size'  => array('min' => 1, 'max' => 50000000),
                    ));
                    if($upload->isValid()){
                        $files = $upload->getFileInfo();
                        foreach($files as $f){
                            $archi=$f["name"];
                        }
                        $resultado = $not->getBibliograficoshvById($id2);
                        if($resultado[0]["archivo"] != ""){
                            unlink("public/images/uploads/documentos/us_doc__".$id."_".$resultado[0]["archivo"]);
                        }
                        $upload->setDestination('./public/images/uploads/documentos/');
                        $upload->addFilter('File\Rename', array('target' => $upload->getDestination().DIRECTORY_SEPARATOR . "us_doc__".$id."_".$archi,'overwrite' => true));
                        $rtn = array('success' => null);
                        $rtn['success'] = $upload->receive();
                        $resultado = $not->updatearchivoBibliograficoshv($id2,$archi);

						$resul = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
						$evento = 'Edición de documentos bibliográficos : ' . $resultado . ' (aps_hv_bibliograficos)';
						$ad->addAuditoriadet($evento, $resul);

                        $this->flashMessenger()->addMessage("Documento actualizado con éxito.");
                    }else{
                        $this->flashMessenger()->addMessage("Documento actualizado sin archivo. Recuerde que el tamaño máximo del archivo es de 25MB.");
                    }
                }else{
                    $this->flashMessenger()->addMessage("Documento actualizado con éxito.");
                }
			}else{
				
				//Subir el nuevo archivo
	            /* */
	                $upload->setDestination('./public/images/uploads/documentos/');
	                $files = $upload->getFileInfo();
	                foreach($files as $f){
	                    $archi=$f["name"];
	                }

	                $upload->addFilter('File\Rename', array('target' => $upload->getDestination().DIRECTORY_SEPARATOR . "us_doc__".$id."_".$archi,'overwrite' => true));
	                $upload->setValidators(array(
	                    'Size'  => array('min' => 1, 'max' => 50000000),
	                ));
	                
	                if($upload->isValid()){
	                    $rtn = array('success' => null);
	                    $rtn['success'] = $upload->receive();
	                    $resultado=$not->addBibliograficos($data, $id, $archi);

						$resul = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
						$evento = 'Creación de documentos bibliográficos : ' . $resultado . ' (aps_hv_bibliograficos)';
						$ad->addAuditoriadet($evento, $resul);

	                    $this->flashMessenger()->addMessage("Documento agregado con éxito.");
	                }else{
	                	$resultado=$not->addBibliograficos($data, $id, "");
	                    $this->flashMessenger()->addMessage("Documento creado sin archivo. Recuerde que el tamaño máximo del archivo es de 25MB.");
	                }
	            /* */
			}
			
			//redirige a la pantalla de inicio del controlador
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editarusuario/index/'.$id);
		}
		else
		{
			$BibliograficoshvForm = new BibliograficoshvForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Bibliograficoshv($this->dbAdapter);
			$pt = new Agregarvalflex($this->dbAdapter);
			$auth = $this->auth;
	
			$identi=$auth->getStorage()->read();
			if($identi==false && $identi==null){
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
			}

			$filter = new StringTrim();
	// define el campo autor
            $usuario = new Usuarios($this->dbAdapter);
            $opciones = array();
            $a = '';
            
            foreach ($usuario->getUsuarios($a) as $pa) {
                $nombre_completo = $pa["primer_nombre"] . ' ' . $pa["segundo_nombre"] . ' ' . $pa["primer_apellido"] . ' ' . $pa["segundo_apellido"];
                $op = array(
                    $pa["id_usuario"] => $nombre_completo
                );
                $opciones = $opciones + $op;
            }
            
            $BibliograficoshvForm->add(array(
                'name' => 'id_autor',
                'type' => 'Zend\Form\Element\Select',
                'attributes' => array(
                    'id' => 'id_autor'
                ),
                'options' => array(
                    
                    'label' => 'Autor : ',
                    'value_options' => $opciones
                )
            ));

			//define el campo pais
			$vf = new Agregarvalflex($this->dbAdapter);
			$opciones=array();
			foreach ($vf->getArrayvalores(20) as $pa) {
			$op=array($pa["id_valor_flexible"]=>$pa["descripcion_valor"]);
			$opciones=$opciones+$op;
			}
			$BibliograficoshvForm->add(array(
			'name' => 'id_pais',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
            'label' => 'Pais : ',
            'value_options' => 
				$opciones
            ,
			),
			)); 


			//define el campo ciudad
			$vf = new Agregarvalflex($this->dbAdapter);
			$opciones=array();
			foreach ($vf->getArrayvalores(1) as $dept) {
			$op=array($dept["id_valor_flexible"]=>$dept["descripcion_valor"]);
			$opciones=$opciones+$op;
			}
			$BibliograficoshvForm->add(array(
			'name' => 'id_ciudad',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
            'label' => 'Ciudad : ',
            'value_options' => 
				$opciones
            ,
			),
			)); 
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
			$pantalla="editarusuario";
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
		
			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2', 0);
			if($id2!=0){
				$linea = new Bibliograficoshv($this->dbAdapter);
				$datBase = $linea->getBibliograficoshvById($id2);
				if($datBase!=null){
					$BibliograficoshvForm->get('nombre_documento')->setValue($datBase[0]["nombre_documento"]);
					$BibliograficoshvForm->get('numero_paginas')->setValue($datBase[0]["numero_paginas"]);
					$BibliograficoshvForm->get('instituciones')->setValue($datBase[0]["instituciones"]);
					$BibliograficoshvForm->get('ano')->setValue($datBase[0]["ano"]);
					$BibliograficoshvForm->get('mes')->setValue($datBase[0]["mes"]);
					$BibliograficoshvForm->get('num_indexacion')->setValue($datBase[0]["num_indexacion"]);
					$BibliograficoshvForm->get('url')->setValue($datBase[0]["url"]);
					$BibliograficoshvForm->get('medio_divulgacion')->setValue($datBase[0]["medio_divulgacion"]);
					$BibliograficoshvForm->get('descripcion')->setValue($datBase[0]["descripcion"]);
					$BibliograficoshvForm->get('autores')->setValue($datBase[0]["autores"]);
					$BibliograficoshvForm->get('id_autor')->setValue($datBase[0]["id_autor"]);
					$BibliograficoshvForm->get('pais')->setValue($datBase[0]["pais"]);
					$BibliograficoshvForm->get('ciudad')->setValue($datBase[0]["ciudad"]);
					$BibliograficoshvForm->get('submit')->setValue("Actualizar");
				}
			}

			$view = new ViewModel(array('form'=>$BibliograficoshvForm,
										'titulo'=>"Otros Documentos Bibliográficos Hv",
										'url'=>$this->getRequest()->getBaseUrl(),
										'datos'=>$u->getBibliograficos($id),
										'id'=>$id,
										'id2'=>$id2,
										'menu'=>$dat["id_rol"]));
			return $view;
		}
    }

	public function eliminarAction(){
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$u = new Bibliograficoshv($this->dbAdapter);
		//print_r($data);
		$id = (int) $this->params()->fromRoute('id',0);
		$id2 = (int) $this->params()->fromRoute('id2',0);
		$u->eliminarBibliograficos($id);

		function get_real_ip()
		{
			if (isset($_SERVER["HTTP_CLIENT_IP"])) {
				return $_SERVER["HTTP_CLIENT_IP"];
			} elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
				return $_SERVER["HTTP_X_FORWARDED_FOR"];
			} elseif (isset($_SERVER["HTTP_X_FORWARDED"])) {
				return $_SERVER["HTTP_X_FORWARDED"];
			} elseif (isset($_SERVER["HTTP_FORWARDED_FOR"])) {
				return $_SERVER["HTTP_FORWARDED_FOR"];
			} elseif (isset($_SERVER["HTTP_FORWARDED"])) {
				return $_SERVER["HTTP_FORWARDED"];
			} else {
				return $_SERVER["REMOTE_ADDR"];
			}
		}

		$auth = $this->auth;
		$identi=$auth->getStorage()->read();

		$au = new Auditoria($this->dbAdapter);
		$ad = new Auditoriadet($this->dbAdapter);

		$resul = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
		$evento = 'Eliminación de documentos bibliográficos : ' . $id . ' (aps_hv_bibliograficos)';
		$ad->addAuditoriadet($evento, $resul);

		$this->flashMessenger()->addMessage("Documento eliminado con exito");
		return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editarusuario/index/'.$id2);
	}

	public function delAction(){
$BibliograficoshvForm = new BibliograficoshvForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Bibliograficoshv($this->dbAdapter);
			//print_r($data);
			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2',0);


			$view = new ViewModel(array('form'=>$BibliograficoshvForm,
										'titulo'=>"Eliminar registro",
										'url'=>$this->getRequest()->getBaseUrl(),
										'datos'=>$id,'id2'=>$id2));
			return $view;
	}

	public function bajarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Bibliograficoshv($this->dbAdapter);
        $filter = new StringTrim();
        $id = (int) $this->params()->fromRoute('id');
        $id2 = (int) $this->params()->fromRoute('id2');        
        $data = $u->getBibliograficoshvById($id);        
        foreach ($data as $d) {
            $fileName = $d["archivo"];
        }
        $response = new \Zend\Http\Response\Stream();   
        $response->setStream(fopen("public/images/uploads/documentos/us_doc__".$id2."_".$filter->filter($fileName), 'r'));
        $response->setStatusCode(200);
        $headers = new \Zend\Http\Headers();
        $headers->addHeaderLine('Content-Type', 'whatever your content type is')
                ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $filter->filter($fileName) . '"')
                ->addHeaderLine('Content-Length', filesize("public/images/uploads/documentos/us_doc__".$id2."_".$filter->filter($fileName)));
    	$response->setHeaders($headers);
        return $response;
    }
	

}