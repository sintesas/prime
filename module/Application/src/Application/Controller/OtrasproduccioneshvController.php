<?php
namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Editarusuario\OtrasproduccioneshvForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Otrasproduccioneshv;
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

class OtrasproduccioneshvController extends AbstractActionController
{

    private $auth;

    public $dbAdapter;

    public function __construct()
    {
        // Cargamos el servicio de autenticacien el constructor
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
        
        if ($this->getRequest()->isPost()) {
            // Creacion adaptador de conexion, objeto de datos, auditoria
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $not = new Otrasproduccioneshv($this->dbAdapter);
            $auth = $this->auth;
            
            // verifica si esta conectado
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
            
            $rol = new Roles($this->dbAdapter);
            $rolusuario = new Rolesusuario($this->dbAdapter);
            $rolusuario->verificarRolusuario($identi->id_usuario);
            foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
                $dat["id_rol"];
            }
            
            // obtiene la informacion de las pantallas
            $data = $this->request->getPost();
            $OtrasproduccioneshvForm = new OtrasproduccioneshvForm();
            // adiciona la noticia
            $id = (int) $this->params()->fromRoute('id', 0);
            $id2 = (int) $this->params()->fromRoute('id2', 0);
            $upload = new \Zend\File\Transfer\Adapter\Http();

            if($id2!=0){
                $resultado=$not->updateOtrasproduccioneshv($id2, $data);
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
                        $resultado = $not->getOtrasproduccioneshvById($id2);
                        if($resultado[0]["archivo"] != ""){
                            unlink("public/images/uploads/producciones/us_pro__".$id."_".$resultado[0]["archivo"]);
                        }
                        $upload->setDestination('./public/images/uploads/producciones/');
                        $upload->addFilter('File\Rename', array('target' => $upload->getDestination().DIRECTORY_SEPARATOR . "us_pro__".$id."_".$archi,'overwrite' => true));
                        $rtn = array('success' => null);
                        $rtn['success'] = $upload->receive();
                        $resultado = $not->updatearchivoOtrasproduccioneshv($id2,$archi);

                        $resul = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
                        $evento = 'Edición de otras producciones de investigación : ' . $resultado . ' (aps_hv_otra_prod)';
                        $ad->addAuditoriadet($evento, $resul);

                        $this->flashMessenger()->addMessage("Artículo actualizado con éxito.");
                    }else{
                        $this->flashMessenger()->addMessage("Artículo actualizado sin archivo. Recuerde que el tamaño máximo del archivo es de 25MB.");
                    }
                }else{
                    $this->flashMessenger()->addMessage("Artículo actualizado con éxito.");
                }
            }else{
                //Subir el nuevo archivo
                /* */
                    $upload->setDestination('./public/images/uploads/producciones/');
                    $files = $upload->getFileInfo();
                    foreach($files as $f){
                        $archi=$f["name"];
                    }

                    $upload->addFilter('File\Rename', array('target' => $upload->getDestination().DIRECTORY_SEPARATOR . "us_pro__".$id."_".$archi,'overwrite' => true));
                    $upload->setValidators(array(
                        'Size'  => array('min' => 1, 'max' => 50000000),
                    ));
                    
                    if($upload->isValid()){
                        $rtn = array('success' => null);
                        $rtn['success'] = $upload->receive();
                        $resultado = $not->addOtrasproduccioneshv($data, $id, $archi);

                        $resul = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
                        $evento = 'Creación de otras producciones de investigación : ' . $resultado . ' (aps_hv_otra_prod)';
                        $ad->addAuditoriadet($evento, $resul);

                        $this->flashMessenger()->addMessage("Producción agregado con éxito.");
                    }else{
                        $resultado = $not->addOtrasproduccioneshv($data, $id, "");
                        $this->flashMessenger()->addMessage("Producción creado sin archivo. Recuerde que el tamaño máximo del archivo es de 25MB.");
                    }
                /* */
            }
            return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() . '/application/editarusuario/index/' . $id);
        } else {
            $OtrasproduccioneshvForm = new OtrasproduccioneshvForm();
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $u = new Otrasproduccioneshv($this->dbAdapter);
            $pt = new Agregarvalflex($this->dbAdapter);
            $auth = $this->auth;
            $id = (int) $this->params()->fromRoute('id', 0);
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
            
            /*
             * foreach ($u->getOtrasproduccioneshv($id) as $dat) {
             * echo $dat["id_grupo_inv"];
             * }
             */
            
            ?>
<script>
if (document.cookie.indexOf="pais_o" >= 0) {
  // They've been here before.
  alert("hello again");
}
else {
  // set a new cookie
document.cookie="pais_o=1";
}
</script>
<?php
            
            $filter = new StringTrim();
            $id2 = (int) $this->params()->fromRoute('id2', 1);
            
            $id3 = (int) $this->params()->fromRoute('id3', 1);
            
        
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
            
            $OtrasproduccioneshvForm->add(array(
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
            
            // verificar roles
            $per = array(
                'id_rol' => ''
            );
            $dat = array(
                'id_rol' => ''
            );
            $rolusuario = new Rolesusuario($this->dbAdapter);
            $permiso = new Permisos($this->dbAdapter);
            
            // me verifica el tipo de rol asignado al usuario
            $rolusuario->verificarRolusuario($identi->id_usuario);
            foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
                $dat["id_rol"];
            }
            
            if ($dat["id_rol"] != '') {
                // me verifica el permiso sobre la pantalla
                $pantalla = "editargrupoinv";
                $panta = 0;
                $pt = new Agregarvalflex($this->dbAdapter);
                
                foreach ($pt->getValoresflexdesc($pantalla) as $panta) {
                    $panta["id_valor_flexible"];
                }
                
                $permiso->verificarPermiso($dat["id_rol"], $panta["id_valor_flexible"]);
                foreach ($permiso->verificarPermiso($dat["id_rol"], $panta["id_valor_flexible"]) as $per) {
                    $per["id_rol"];
                }
            }
            $id = (int) $this->params()->fromRoute('id', 0);
            $id2 = (int) $this->params()->fromRoute('id2', 0);
            if($id2!=0){
                $linea = new Otrasproduccioneshv($this->dbAdapter);
                $datBase = $linea->getOtrasproduccioneshvById($id2);
                if($datBase!=null){
                    $OtrasproduccioneshvForm->get('nombre_producto')->setValue($datBase[0]["nombre_producto"]);
                    $OtrasproduccioneshvForm->get('descripcion_producto')->setValue($datBase[0]["descripcion_producto"]);
                    $OtrasproduccioneshvForm->get('tipo_producto')->setValue($datBase[0]["tipo_producto"]);
                    $OtrasproduccioneshvForm->get('id_pais')->setValue($datBase[0]["id_pais"]);
                    $OtrasproduccioneshvForm->get('id_ciudad')->setValue($datBase[0]["id_ciudad"]);
                    $OtrasproduccioneshvForm->get('instituciones')->setValue($datBase[0]["instituciones"]);
                    $OtrasproduccioneshvForm->get('registro')->setValue($datBase[0]["registro"]);
                    $OtrasproduccioneshvForm->get('autores')->setValue($datBase[0]["autores"]);
                    $OtrasproduccioneshvForm->get('otra_info')->setValue($datBase[0]["otra_info"]);
                    $OtrasproduccioneshvForm->get('mes')->setValue($datBase[0]["mes"]);
                    $OtrasproduccioneshvForm->get('ano')->setValue($datBase[0]["ano"]);
                    $OtrasproduccioneshvForm->get('id_autor')->setValue($datBase[0]["id_autor"]);
                    $OtrasproduccioneshvForm->get('submit')->setValue("Actualizar");
                }
            }

            $view = new ViewModel(array(
                'form' => $OtrasproduccioneshvForm,
                'titulo' => "Otras Producciones de Investigación Hv",
                'url' => $this->getRequest()->getBaseUrl(),
                'datos' => $u->getOtrasproduccioneshv($id),
                'id' => $id,
                'id2' => $id2,
                'id3' => $id3,
                'menu' => $dat["id_rol"]
            ));
            return $view;
        }
    }

    public function eliminarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Otrasproduccioneshv($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        $u->eliminarOtrasproduccioneshv($id);

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
        $evento = 'Eliminación de otras producciones de investigación : ' . $id . ' (aps_hv_otra_prod)';
        $ad->addAuditoriadet($evento, $resul);

        $this->flashMessenger()->addMessage("Produccion eliminada con exito");
        return $this->redirect()->toUrl($this->getRequest()
            ->getBaseUrl() . '/application/editarusuario/index/' . $id2);
    }

    public function delAction()
    {
        $OtrasproduccioneshvForm = new OtrasproduccioneshvForm();
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Otrasproduccioneshv($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        
        $view = new ViewModel(array(
            'form' => $OtrasproduccioneshvForm,
            'titulo' => "Eliminar registro",
            'url' => $this->getRequest()->getBaseUrl(),
            'datos' => $id,
            'id2' => $id2
        ));
        return $view;
    }

    public function bajarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Otrasproduccioneshv($this->dbAdapter);
        $filter = new StringTrim();
        $id = (int) $this->params()->fromRoute('id');
        $id2 = (int) $this->params()->fromRoute('id2');        
        $data = $u->getOtrasproduccioneshvById($id);        
        foreach ($data as $d) {
            $fileName = $d["archivo"];
        }
        $response = new \Zend\Http\Response\Stream();   
        $response->setStream(fopen("public/images/uploads/producciones/us_pro__".$id2."_".$filter->filter($fileName), 'r'));
        $response->setStatusCode(200);
        $headers = new \Zend\Http\Headers();
        $headers->addHeaderLine('Content-Type', 'whatever your content type is')
                ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $filter->filter($fileName) . '"')
                ->addHeaderLine('Content-Length', filesize("public/images/uploads/producciones/us_pro__".$id2."_".$filter->filter($fileName)));
        $response->setHeaders($headers);
        return $response;
    }
}