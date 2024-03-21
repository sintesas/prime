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
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use DOMPDFModule\View\Model\PdfModel;
use Application\Modelo\Entity\Grupoinvestigacion;
use Application\Modelo\Entity\Lineas;

class PdfController extends AbstractActionController
{
	private $auth;
	public $dbAdapter;
  
	public function __construct() {
     //Cargamos el servicio de autenticacien el constructor
     $this->auth = new AuthenticationService();
	}

     public function indexAction() {
			//Creacion adaptador de conexion, objeto de datos, auditoria
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$not = new Grupoinvestigacion($this->dbAdapter);
			$linea = new Lineas($this->dbAdapter);

         // Instantiate new PDF Model
         $pdf = new PdfModel();
          
         // set filename
         $pdf->setOption('filename', 'hello.pdf');
          
         // Defaults to "8x11"
         $pdf->setOption('paperSize', 'a4');
          
         // paper orientation
         $pdf->setOption('paperOrientation', 'portrait');
          
         $pdf->setVariables(array(
             'var1' => 'Liverpool FC',
             'var2' => 'Atletico Madrid',
             'var3' => 'Borussia Dortmund',
	     'linea' => $linea->getLineast(),
         ));
          
         return $pdf;
	}

}