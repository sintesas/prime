<?php
namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Convocatoria\EditaraplicariForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Aplicar;
use Application\Modelo\Entity\Convocatoria;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Tablafin;
use Application\Modelo\Entity\Tablafinproy;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Proyectosinv;
use Application\Modelo\Entity\Proyectos;
use Application\Modelo\Entity\Mares;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Formacionacahv;
use Application\Modelo\Entity\Idiomashv;
use Application\Modelo\Entity\Formacioncomhv;
use Application\Modelo\Entity\Experiencialabhv;
use Application\Modelo\Entity\Objetivosespecificos;
use Application\Modelo\Entity\Objetivosmetas;
use Application\Modelo\Entity\Cronogramaap;
use Application\Modelo\Entity\Gruposaplicari;
use Application\Modelo\Entity\Grupoinvestigacion;
use Application\Modelo\Entity\Tablaequipo;
use Application\Modelo\Entity\Coinvestigadores;
use Application\Modelo\Entity\Contratacionpersonal;
use Application\Modelo\Entity\Camposaddproy;
use Application\Modelo\Entity\Camposadd;
use Application\Modelo\Entity\Requisitosdoc;
use Application\Modelo\Entity\Requisitosapdoc;
use Application\Modelo\Entity\Paresevaluadores;
use Application\Modelo\Entity\Propuestainv;
use Application\Modelo\Entity\Semillero;
use Application\Modelo\Entity\Agregarresponsable;
use Application\Modelo\Entity\Sesionesperiodicasformacion;
use Application\Modelo\Entity\Sesionesperiodicasestudiantes;
use Application\Modelo\Entity\Requisitos;
use Application\Modelo\Entity\Requisitosap;
use Application\Pdf\PdfgiForm;
use DOMPDFModule\View\Model\PdfModel;
use Application\Modelo\Entity\Areashv;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

class EditaraplicariController extends AbstractActionController {
	private $auth;
	public $dbAdapter;
	public function __construct() {
		// Cargamos el servicio de autenticacien el constructor
		$this->auth = new AuthenticationService ();
	}
	public function indexAction() {
    $this->dbAdapter=$this->getServiceLocator()->get('db1');
    $auth = $this->auth;
    $permisos = new Permisos($this->dbAdapter);
    $identi = print_r($auth->getStorage()->read()->id_usuario,true);
    $resultadoPermiso = $permisos->getValidarPermisoPantalla($identi,get_class());
    if($resultadoPermiso==0){
        $this->flashMessenger()->addMessage("Su rol no tiene permiso para ver el formulario. Si desea puede solicitarlo al administrador.");
        return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/mensajeadministrador/index');
    }
    
		$auth = $this->auth;
		$EditaraplicariForm = new EditaraplicariForm ();
		$this->dbAdapter = $this->getServiceLocator()->get ('db1');
		$us = new Usuarios ( $this->dbAdapter );
		$identi = $auth->getStorage ()->read ();
		$filter = new StringTrim ();
		$id = (int) $this->params ()->fromRoute ('id', 0);
        $id2 = (int) $this->params ()->fromRoute ('id2', 0);
		$aplicar = new Aplicar ( $this->dbAdapter );
		$Objetivosespecificos = new Objetivosespecificos ( $this->dbAdapter );
		$Objetivosmetas = new Objetivosmetas ( $this->dbAdapter );
		$Cronogramaap = new Cronogramaap ( $this->dbAdapter );
		$Gruposaplicari = new Gruposaplicari ( $this->dbAdapter );
		$Grupoinvestigacion = new Grupoinvestigacion ( $this->dbAdapter );
		$tablae = new Tablaequipo($this->dbAdapter);
		$Coinvestigadores = new Coinvestigadores($this->dbAdapter);
		$Contratacionpersonal = new Contratacionpersonal($this->dbAdapter);
		$Camposaddproy = new Camposaddproy($this->dbAdapter);
		$Camposadd = new Camposadd($this->dbAdapter);
		$Requisitosdoc = new Requisitosdoc($this->dbAdapter);
        $Requisitosapdoc = new Requisitosapdoc($this->dbAdapter);
        $Paresevaluadores = new Paresevaluadores($this->dbAdapter);
        $Propuestainv = new Propuestainv($this->dbAdapter);
        $Semillero = new Semillero($this->dbAdapter);
        $convocatoria = new Convocatoria($this->dbAdapter);
        $Agregarresponsable = new Agregarresponsable($this->dbAdapter);
        $Sesionesperiodicasformacion = new Sesionesperiodicasformacion($this->dbAdapter);
        $Sesionesperiodicasestudiantes = new Sesionesperiodicasestudiantes($this->dbAdapter);

		$vf = new Agregarvalflex ( $this->dbAdapter );

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

        $identi=$auth->getStorage()->read();

        $au = new Auditoria($this->dbAdapter);
        $ad = new Auditoriadet($this->dbAdapter);

		if ($this->getRequest ()->isPost ()) {
            $data = $this->request->getPost();
            $id_modalidad = explode("-",$data->id_categoria)[0];
            $nombre_modalidad = "";
            foreach ($vf->getArrayValoresOrdenados(70) as $dat) {
                if($dat["id_valor_flexible"] == $id_modalidad){
                    $nombre_modalidad=$dat["descripcion_valor"];
                }
            }

			$id_aplicar = $aplicar->updateAplicari($data, $id, $nombre_modalidad);            

            $resul = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
		    $evento = 'Edición de aplicar a la convocatoria interna : ' . $id . ' (mgc_aplicar)';
		    $ad->addAuditoriadet($evento, $resul);

			$this->flashMessenger()->addMessage("Aplicación creada con éxito.");
               return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() . '/application/editaraplicari/index/' . $id);
		} 

        $opciones = array('' => '');
        foreach ($vf->getArrayValoresOrdenados(70) as $dat) {
            $opciones = $opciones + array(
                $dat["id_valor_flexible"] . '-' . $dat["valor_flexible_padre_id"]  => $dat["descripcion_valor"]
            );
        }
        $EditaraplicariForm->get('id_categoria')->setValueOptions($opciones);
		
		$opciones = array('' => '');
		foreach ($vf->getArrayvalores(40) as $dat) {
            $opciones = $opciones + array(
                $dat["id_valor_flexible"] . '-' . $dat["valor_flexible_padre_id"]  => $dat["descripcion_valor"]
            );
        }
        $EditaraplicariForm->get('id_linea_inv')->setValueOptions($opciones);

        $opciones = array('' => '');
		foreach ($vf->getArrayvalores(37) as $dat) {
            $opciones = $opciones + array(
                $dat["id_valor_flexible"] => $dat["descripcion_valor"]
            );
        }
        $EditaraplicariForm->get('id_campo')->setValueOptions($opciones);

        $opciones = array('' => '');
		foreach ($vf->getArrayvalores(42) as $dat) {
            $opciones = $opciones + array(
                $dat["id_valor_flexible"] => $dat["descripcion_valor"]
            );
        }
        $EditaraplicariForm->get('id_area_tematica')->setValueOptions($opciones);
		
		$opciones = array('' => '');
		foreach ($vf->getArrayvalores(23) as $dat) {
            $opciones = $opciones + array(
                $dat["id_valor_flexible"] => $dat["descripcion_valor"]
            );
        }
        $EditaraplicariForm->get('id_unidad_academica')->setValueOptions($opciones);

        $opciones = array('' => '');
		foreach ($vf->getArrayvalores(33) as $dat) {
            $opciones = $opciones + array(
                $dat["id_valor_flexible"] . '-' . $dat["valor_flexible_padre_id"]  => $dat["descripcion_valor"]
            );
        }
        $EditaraplicariForm->get('id_dependencia_academica')->setValueOptions($opciones);

        $opciones = array('' => '');
		foreach ($vf->getArrayvalores(34) as $dat) {
            $opciones = $opciones + array(
                $dat["id_valor_flexible"] . '-' . $dat["valor_flexible_padre_id"]  => $dat["descripcion_valor"]
            );
        }
        $EditaraplicariForm->get('id_programa_academico')->setValueOptions($opciones);

        $opciones = array('' => '');
		foreach ($vf->getArrayvalores(38) as $dat) {
            $opciones = $opciones + array(
                $dat["id_valor_flexible"] => $dat["descripcion_valor"]
            );
        }
        $EditaraplicariForm->get('instituciones_coofinanciacion')->setValueOptions($opciones);
    
    $opciones = array('' => '');
    foreach ($Semillero->getSemilleroinv() as $dat) {
         $opciones = $opciones + array(
              $dat["id"] => $dat["nombre"] ." - ". substr($dat["fecha_creacion"], 0, 4)
          );
      }
     $EditaraplicariForm->get('id_semillero')->setValueOptions($opciones);

    
        if($id!=""){
			$info_aplicar = $aplicar->getAplicar($id);
			if($info_aplicar!=""){
                $info_vf = $vf->getvalflexseditar($info_aplicar[0]["id_categoria"]);
                $idPadre_vf = $info_vf[0]["valor_flexible_padre_id"];
                if($idPadre_vf == ""){
                    $idPadre_vf = $info_aplicar[0]["id_categoria"];
                }
                $nombreModalidad = $info_vf[0]["descripcion_valor"];
				$EditaraplicariForm->get('nombre_proy')->setValue($info_aplicar[0]["nombre_proy"]);
				//$EditaraplicariForm->get('nombre_proy')->setValue($info_aplicar[0]["id_categoria"]."-".$idPadre_vf);
                $EditaraplicariForm->get('id_categoria')->setValue($info_aplicar[0]["id_categoria"]."-".$idPadre_vf);
				$EditaraplicariForm->get('id_campo')->setValue($info_aplicar[0]["id_campo"]);
				$EditaraplicariForm->get('id_linea_inv')->setValue($info_aplicar[0]["id_linea_inv"].'-'.$info_aplicar[0]["id_campo"]);
				$EditaraplicariForm->get('id_area_tematica')->setValue($info_aplicar[0]["id_area_tematica"]);
				$EditaraplicariForm->get('recursos_funcion')->setValue($info_aplicar[0]["recursos_funcion"]);
				$EditaraplicariForm->get('recursos_inversion')->setValue($info_aplicar[0]["recursos_inversion"]);
				$EditaraplicariForm->get('total_financia')->setValue($info_aplicar[0]["total_financia"]);
				$EditaraplicariForm->get('total_proy')->setValue($info_aplicar[0]["total_proy"]);
                $EditaraplicariForm->get('id_semillero')->setValue($info_aplicar[0]["id_semillero"]);
				$usuario = $us->getUsuarios($info_aplicar[0]["id_investigador"]);

				$EditaraplicariForm->get('investigador_principal')->setValue($usuario[0]["primer_nombre"]." ".$usuario[0]["segundo_nombre"]." ".$usuario[0]["primer_apellido"]." ".$usuario[0]["segundo_apellido"]);
				$EditaraplicariForm->get('numero_documento')->setValue($usuario[0]["documento"]);
				foreach ($vf->getArrayvalores(25) as $dat) {
		            if($dat["id_valor_flexible"]==$usuario[0]["id_tipo_documento"]){
		            	$EditaraplicariForm->get('tipo_documento')->setValue($dat["descripcion_valor"]);
		            }
		        }
		        foreach ($vf->getArrayvalores(36) as $dat) {
		            if($dat["id_valor_flexible"]==$usuario[0]["id_tipo_vinculacion"]){
		            	$EditaraplicariForm->get('tipo_vinculacion')->setValue($dat["descripcion_valor"]);
		            }
		        }
				$EditaraplicariForm->get('id_unidad_academica')->setValue($info_aplicar[0]["id_unidad_academica"]);
				$EditaraplicariForm->get('id_dependencia_academica')->setValue($info_aplicar[0]["id_dependencia_academica"].'-'.$info_aplicar[0]["id_unidad_academica"]);
				$EditaraplicariForm->get('id_programa_academico')->setValue($info_aplicar[0]["id_programa_academico"].'-'.$info_aplicar[0]["id_dependencia_academica"]);
				$EditaraplicariForm->get('instituciones_coofinanciacion')->setValue($info_aplicar[0]["instituciones_coofinanciacion"]);
				$EditaraplicariForm->get('duracion')->setValue($info_aplicar[0]["duracion"]);
				$EditaraplicariForm->get('periodo')->setValue($info_aplicar[0]["periodo"]);
				$EditaraplicariForm->get('area_tematica')->setValue($info_aplicar[0]["area_tematica"]);
				$EditaraplicariForm->get('resumen_ejecutivo')->setValue($info_aplicar[0]["resumen_ejecutivo"]);
				$EditaraplicariForm->get('descriptores')->setValue($info_aplicar[0]["descriptores"]);
				$EditaraplicariForm->get('antecedentes')->setValue($info_aplicar[0]["antecedentes"]);
				$EditaraplicariForm->get('planteamiento_problema')->setValue($info_aplicar[0]["planteamiento_problema"]);
				$EditaraplicariForm->get('marco_teorico')->setValue($info_aplicar[0]["marco_teorico"]);
				$EditaraplicariForm->get('estado_arte')->setValue($info_aplicar[0]["estado_arte"]);
				$EditaraplicariForm->get('bibliografia')->setValue($info_aplicar[0]["bibliografia"]);
				$EditaraplicariForm->get('compromisos_conocimiento')->setValue($info_aplicar[0]["compromisos_conocimiento"]);
				$EditaraplicariForm->get('metodologia')->setValue($info_aplicar[0]["metodologia"]);
        $EditaraplicariForm->get('momentos_proyecto')->setValue($info_aplicar[0]["momentos_proyecto"]);
				$EditaraplicariForm->get('objetivo_general')->setValue($info_aplicar[0]["objetivo_general"]);
				$EditaraplicariForm->get('semestresano')->setValue($info_aplicar[0]["semestresano"]);

        if($info_aplicar[0]["id_categoria"] == 1 || $info_aplicar[0]["id_categoria"] == 3 || $info_aplicar[0]["id_categoria"] == 6 || $info_aplicar[0]["id_categoria"] == 7){
          $EditaraplicariForm->get('momentos_proyecto')->setAttribute("disabled","disabled");
          $EditaraplicariForm->get('id_semillero')->setAttribute("disabled","disabled");
          $EditaraplicariForm->get('momentos_proyecto')->setAttribute("placeholder","No aplica para la modalidad escogida.");
        }elseif($info_aplicar[0]["id_categoria"] == 2){
          $EditaraplicariForm->get('planteamiento_problema')->setAttribute("disabled","disabled");
          $EditaraplicariForm->get('id_semillero')->setAttribute("disabled","disabled");
          $EditaraplicariForm->get('metodologia')->setAttribute("disabled","disabled");
          $EditaraplicariForm->get('planteamiento_problema')->setAttribute("placeholder","No aplica para la modalidad escogida.");
          $EditaraplicariForm->get('metodologia')->setAttribute("placeholder","No aplica para la modalidad escogida.");
        }elseif($info_aplicar[0]["id_categoria"] == 4 || $info_aplicar[0]["id_categoria"] == 5){
          $EditaraplicariForm->get('total_financia')->setAttribute("disabled","disabled");
          $EditaraplicariForm->get('area_tematica')->setAttribute("disabled","disabled");
          $EditaraplicariForm->get('estado_arte')->setAttribute("disabled","disabled");
          $EditaraplicariForm->get('metodologia')->setAttribute("disabled","disabled");
          $EditaraplicariForm->get('momentos_proyecto')->setAttribute("disabled","disabled");
          $EditaraplicariForm->get('instituciones_coofinanciacion')->setAttribute("disabled","disabled");
          $EditaraplicariForm->get('total_financia')->setAttribute("placeholder","No aplica para la modalidad escogida.");
          $EditaraplicariForm->get('area_tematica')->setAttribute("placeholder","No aplica para la modalidad escogida.");
          $EditaraplicariForm->get('estado_arte')->setAttribute("placeholder","No aplica para la modalidad escogida.");
          $EditaraplicariForm->get('metodologia')->setAttribute("placeholder","No aplica para la modalidad escogida.");
          $EditaraplicariForm->get('momentos_proyecto')->setAttribute("placeholder","No aplica para la modalidad escogida.");
        }
			
      }
		}

		$multi = 0;
		if ($info_aplicar[0]["periodo"] == 'S') {
          if($info_aplicar[0]["duracion"]<=2){
          	$multi = 1;
          }elseif($info_aplicar[0]["duracion"]<=4){
          	$multi = 2;
          }elseif($info_aplicar[0]["duracion"]<=6){
          	$multi = 3;
          }elseif($info_aplicar[0]["duracion"]<=8){
          	$multi = 4;
          }elseif($info_aplicar[0]["duracion"]<=10){
          	$multi = 5;
          }elseif($info_aplicar[0]["duracion"]<=12){
          	$multi = 6;
          }elseif($info_aplicar[0]["duracion"]<=14){
          	$multi = 7;
          }elseif($info_aplicar[0]["duracion"]<=16){
          	$multi = 8;
          }elseif($info_aplicar[0]["duracion"]<=18){
          	$multi = 9;
          }else{
          	$multi = 10;
          }
        } else {
        	if($info_aplicar[0]["duracion"]<=12){
	          	$multi = 1;
	        }elseif($info_aplicar[0]["duracion"]<=24){
          		$multi = 2;
          	}elseif($info_aplicar[0]["duracion"]<=36){
          		$multi = 3;
          	}elseif($info_aplicar[0]["duracion"]<=48){
          		$multi = 4;
          	}elseif($info_aplicar[0]["duracion"]<=60){
          		$multi = 5;
          	}elseif($info_aplicar[0]["duracion"]<=72){
          		$multi = 6;
          	}elseif($info_aplicar[0]["duracion"]<=84){
          		$multi = 7;
          	}elseif($info_aplicar[0]["duracion"]<=96){
          		$multi = 8;
          	}else{
          		$multi = 9;
          	}
        }
        $ex = null;
        $tf = new Tablafin($this->dbAdapter);
        $pf = new Tablafinproy($this->dbAdapter);
        
        foreach ($pf->getTablafin($id) as $existe) {
            $ex = $existe["id_aplicar"];
        }
        if ($ex == null) {
            
            foreach ($tf->getTablafin($info_aplicar[0]["id_convocatoria"]) as $tabla) {
                for ($x = 1; $x <= $multi; $x ++) {
                    $resul = $pf->addTablafin($tabla["id_rubro"], $tabla["id_fuente"], $id, $x);
                }
            }
        }


		$exc = null;
		foreach ($Camposaddproy->getcamposaddproy($id) as $existec) {
            $exc = $existec["id_aplicar"];
        }
        if ($exc == null) {
            foreach ($Camposadd->getCamposadd($info_aplicar[0]["id_convocatoria"]) as $tablac) {
                if ($tablac["obligatorio"] == 'S') {
                    $resul = $Camposaddproy->addCamposaddproy(trim($tablac["titulo"]) . ' (Campo obligatorio)', $id);
                } else {
                    $resul = $Camposaddproy->addCamposaddproy(trim($tablac["titulo"]), $id);
                }
            }
        }

        $excs = null;
        $ffReq = new Requisitos($this->dbAdapter);
        $ccReq = new Requisitosap($this->dbAdapter);
        foreach ($ccReq->getRequisitosap($id) as $existec) {
            $excs1 = $existec["id_aplicar"];
        }

        if ($excs == null) {
            
            foreach ($ffReq->getRequisitos($info_aplicar[0]["id_convocatoria"]) as $tablac) {
                
                $resul = $ccReq->addRequisitosdoc($tablac["descripcion"], $tablac["id_tipo_requisito"], $tablac["observaciones"], $tablac["id_requisito"], $id);
            }
        }

        $excs = null;        
        foreach ($Requisitosapdoc->getRequisitosapdoc($id) as $existec) {
            $excs = $existec["id_aplicar"];
        }
        if ($excs == null) {
            foreach ($Requisitosdoc->getRequisitosdoc($info_aplicar[0]["id_convocatoria"]) as $tablac) {
                $resul = $Requisitosapdoc->addRequisitosdoc($tablac["descripcion"], $tablac["fecha_limite"], $tablac["observaciones"], $tablac["id_requisito_doc"], $id);
            }
        }
		$Tablafinproy = new Tablafinproy($this->dbAdapter);
    $rol = new Roles($this->dbAdapter);
    $rolusuario = new Rolesusuario($this->dbAdapter);
    $rolusuario->verificarRolusuario($identi->id_usuario);
    foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
        $dat["id_rol"];
    }
    
    $editar_aplicar="No";
    $info_convo = $convocatoria->getConvocatoriaid($info_aplicar[0]["id_convocatoria"]);
    $estado_convo = $info_convo[0]["id_estado"];
    if($info_aplicar[0]["id_investigador"]==$identi->id_usuario || $dat["id_rol"]==1){
      if($estado_convo == "B" || $estado_convo == "P"){
          $editar_aplicar="Si";
      }
    }
    $nombreModalidad = $info_aplicar[0]["nombre_modalidad"];
    $EditaraplicariForm->get('nombre_modalidad')->setValue($nombreModalidad);
    
    $area = new Areashv($this->dbAdapter);    
    $formaca = new Formacionacahv($this->dbAdapter);
    if($id2=="1"){
        $pdf = new PdfModel();        
      $pdf->setOption('filename', 'certificado_aplicacion.pdf');
      $pdf->setOption('paperSize', 'letter');
      $pdf->setOption('paperOrientation', 'portrait');
      $valpdf="Si";

      $pdf->setVariables(array(
          'objetivosespecificos' => $Objetivosespecificos->getObjetivosespecificos($id),
          'objetivosmetas' => $Objetivosmetas->getObjetivosmetast(),
          'cronogramaap' => $Cronogramaap->getCronogramah($id),
          'Gruposaplicari' => $Gruposaplicari->getGruposaplicari($id),
          'grupostotal' => $Grupoinvestigacion->getGrupoinvestigacion(),
          'clasificaciongrupo' => $vf->getArrayvalores(21),
          'usuarios' => $us->getArrayusuarios(),
          'tablae' => $tablae->getTablaequipo($id),
          'Coinvestigadores' => $Coinvestigadores->getCoinvestigadoresid($id),
          'Contratacionpersonal' => $Contratacionpersonal->getContratacionpersonalid($id),
          'Camposaddproy' => $Camposaddproy->getCamposaddproy($id),
          'Requisitosapdoc' => $Requisitosapdoc->getRequisitosapdoc($id),
          'Paresevaluadores' => $Paresevaluadores->getaresevaluadoresid($id),
          'Propuestainv' => $Propuestainv->getArchivos($id),
          'Tablafinper' => $Tablafinproy->getArrayfinanciaper($id),
          'Tablafinproy' => $Tablafinproy->getTablafin($id),
          'pr' => $Tablafinproy->caseSql($id),
          'sumfuente' => $Tablafinproy->sumFuente($id),
          'sumrubro' => $Tablafinproy->sumRubro($id),
          'sumtotal' => $Tablafinproy->sumTotal($id),
          'Tablafinrubro' => $Tablafinproy->getArrayfinancia($id),
          'Agregarresponsable' => $Agregarresponsable->getAgregarresponsableByaplicar($id),
          'sesionesformacion' => $Sesionesperiodicasformacion->getSesionesperiodicasformacionByaplicar($id),
          'sesionesestudiantes' => $Sesionesperiodicasestudiantes->getSesionesperiodicasestudiantesByaplicar($id),
          'valflex' => $vf->getValoresf(),
          'id' => $id,
          'id2' => $id2,
          'modalidad' => $idPadre_vf,
          'nombreModalidad' => $nombreModalidad,
          //'modalidad' => $info_aplicar[0]["id_categoria"],
          'editar_aplicar' => $editar_aplicar,
          'datos' => $info_aplicar,
          'convo' => $convocatoria->getConvocatoria(),
          'semilleros' => $Semillero->getSemilleroinv(),
          'area' => $area->getAreashvt(),
          'formaca' => $formaca->getFormacionacahvt(),
          'usuario' => $usuario,
          'recursosinforme' => $Tablafinproy->caseSql2($id),
          'estado_convo' => $estado_convo
      ));
      return $pdf;
    }else{
      $view = new ViewModel ( array (
          'form' => $EditaraplicariForm,
          'titulo' => "Aplicar a la convocatoria interna",
          'url' => $this->getRequest ()->getBaseUrl (),
          'objetivosespecificos' => $Objetivosespecificos->getObjetivosespecificos($id),
          'objetivosmetas' => $Objetivosmetas->getObjetivosmetast(),
          'cronogramaap' => $Cronogramaap->getCronogramah($id),
          'Gruposaplicari' => $Gruposaplicari->getGruposaplicari($id),
          'grupostotal' => $Grupoinvestigacion->getGrupoinvestigacion(),
          'clasificaciongrupo' => $vf->getArrayvalores(21),
          'usuarios' => $us->getArrayusuarios(),
          'tablae' => $tablae->getTablaequipo($id),
          'Coinvestigadores' => $Coinvestigadores->getCoinvestigadoresid($id),
          'Contratacionpersonal' => $Contratacionpersonal->getContratacionpersonalid($id),
          'Camposaddproy' => $Camposaddproy->getCamposaddproy($id),
          'Requisitosapdoc' => $Requisitosapdoc->getRequisitosapdoc($id),
          'Paresevaluadores' => $Paresevaluadores->getaresevaluadoresid($id),
          'Propuestainv' => $Propuestainv->getArchivos($id),
          'Tablafinper' => $Tablafinproy->getArrayfinanciaper($id),
          'Tablafinproy' => $Tablafinproy->getTablafin($id),
          'pr' => $Tablafinproy->caseSql($id),
          'sumfuente' => $Tablafinproy->sumFuente($id),
          'sumrubro' => $Tablafinproy->sumRubro($id),
          'sumtotal' => $Tablafinproy->sumTotal($id),
          'Tablafinrubro' => $Tablafinproy->getArrayfinancia($id),
          'Agregarresponsable' => $Agregarresponsable->getAgregarresponsableByaplicar($id),
          'sesionesformacion' => $Sesionesperiodicasformacion->getSesionesperiodicasformacionByaplicar($id),
          'sesionesestudiantes' => $Sesionesperiodicasestudiantes->getSesionesperiodicasestudiantesByaplicar($id),
          'valflex' => $vf->getValoresf(),
          'id' => $id,
          'id2' => $id2,
          'modalidad' => $idPadre_vf,
          'nombreModalidad' => $nombreModalidad,
          'editar_aplicar' => $editar_aplicar,
          'menu'=>$dat["id_rol"],
          'estado_convo' => $estado_convo
      ) );
      return $view;
    }	
	}
}