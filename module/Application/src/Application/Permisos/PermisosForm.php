<?php


namespace Application\Permisos;

use Zend\Form\Form;
use Zend\Form\Element;
use Application\Controller\PermisosController;
use Application\Modelo\Entity\Permisos;
use Application\Permisos\PermisosForm;

class PermisosForm extends Form 
{
    function __construct($name = null)
    {
		parent::__construct( $name = null);

		parent::setAttribute('method', 'post');
		parent::setAttribute('action ', 'usuario');

		$this->add(array(
		    'name' => 'id_modulo',
		    'type'  => 'Zend\Form\Element\Select',
		    'attributes' => array(
		    	'id' => 'id_modulo',
				'required' => 'required',
				'onchange' => 'myFunction2();'
		    ),
		    'options' => array(
		        'label' => 'Módulo:'
		    ),
		));

		$this->add(array(
		    'name' => 'id_submodulo',
		    'type'  => 'Zend\Form\Element\Select',
		    'attributes' => array(
				'id' => 'id_submodulo',
				'required' => 'required',
				'onchange' => 'myFunction3();'
		    ),
		    'options' => array(
		        'label' => 'Submódulo:'
		    ),
		));

		$this->add(array(
		    'name' => 'id_formulario',
		    'type'  => 'Zend\Form\Element\Select',
		    'attributes' => array(
				'id' => 'id_formulario',
				'required' => 'required',
		    ),
		    'options' => array(
		        'label' => 'Formulario:'
		    ),
		));

	   $this->add(array(
	      'name'=>'submit',
		  'attributes'=>array(
		     'type'=>'submit',
		     'required'=>'required',
			 'class'=>'btn1',
			 'value'=>'Agregar',
		  ),
	   ));
        
 
    }
}

