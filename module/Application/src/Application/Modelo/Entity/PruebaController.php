<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Editarusuario\PruebaForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Prueba;
use Application\Modelo\Entity\Pruebamares;
use Application\Modelo\Entity\Pruebamaresdet;
use Application\Modelo\Entity\Tablafinp;
use Application\Modelo\Entity\Talentohumano;



use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;

use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Informes;
use Application\Modelo\Entity\Requisitos;
use Application\Modelo\Entity\Aspectoeval;
use Application\Modelo\Entity\Requisitosdoc;
use Application\Modelo\Entity\Requisitosap;
use Application\Modelo\Entity\Requisitosapdoc;
use Application\Modelo\Entity\Actas;
use Application\Modelo\Entity\Rolesconv;
use Application\Modelo\Entity\Archivosconv;
use Application\Modelo\Entity\Url;

use Application\Modelo\Entity\Camposadd;
use Application\Modelo\Entity\Tablaequipop;
use Application\Modelo\Entity\Grupoinvestigacion;
use Application\Modelo\Entity\Gruposparticipantes;
use Application\Modelo\Entity\Gruposproy;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;


class PruebaController extends AbstractActionController
{
	private $auth;
	public $dbAdapter;
  
	public function __construct() {
     //Cargamos el servicio de autenticacien el constructor
     $this->auth = new AuthenticationService();
	}
	
    public function indexAction()
    {
			$PruebaForm= new PruebaForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db4');
			$pr = new Prueba($this->dbAdapter);
$this->dbAdapter1=$this->getServiceLocator()->get('db1');
$tb = new Tablafinp($this->dbAdapter1);
$id = $this->params()->fromRoute('id');
$id2 = $this->params()->fromRoute('id2');
			$Tablafinproy = new Tablafinp($this->dbAdapter1);
			$valflex = new Agregarvalflex($this->dbAdapter1);
			$tablae = new Tablaequipop($this->dbAdapter1);
$Archivosconv = new Archivosconv($this->dbAdapter1);
			$Propuesta_inv = new Actas($this->dbAdapter1);
$grupar = new Gruposproy($this->dbAdapter1);
$gruinv = new Grupoinvestigacion($this->dbAdapter1);

			$filter = new StringTrim();

$id2 = $this->params()->fromRoute('id2');

			$view = new ViewModel(array('form'=>$PruebaForm,

'Archivosconv'=>$Archivosconv->getArchivosconv($id2),
'arch'=>$Propuesta_inv->getArchivos($id2),
'grupar'=>$grupar->getGruposparticipantes($id_conv),
'Tablafinper'=>$Tablafinproy->getArrayfinanciaper($id_conv),
'tablaeper'=>$tablae->getArrayequiposper($id2),
'gruinv'=>$gruinv->getGrupoinvestigacion(),
'sumfuente'=>$Tablafinproy->sumFuente($id_conv),
'sumrubro'=>$Tablafinproy->sumRubro($id_conv),
'sumtotal'=>$Tablafinproy->sumTotal($id_conv),


										'titulo'=>"Gestión Presupuestal"
						,'p'=>$pr->getPrueba1($id),
'vig'=>$pr->getVigencias($id),
						'tb'=>$tb->getTablafinorder($id2)
));
			return $view;





	}


   public function thAction()
    {

			$PruebaForm= new PruebaForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db2');
			$pr = new Talentohumano($this->dbAdapter);

			$id = $this->params()->fromRoute('id');
			$filter = new StringTrim();


			$view = new ViewModel(array('form'=>$PruebaForm,

						'p'=>$pr->getusprueba('19097872'),
));
			return $view;


	}

    public function indexMARESAction()
    {

			$PruebaForm= new PruebaForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db3');
			$pr = new Pruebamares($this->dbAdapter);

$id = $this->params()->fromRoute('id');
			$filter = new StringTrim();


			$view = new ViewModel(array('form'=>$PruebaForm,

						'p'=>$pr->getPrueba(),
));
			return $view;


	}
}