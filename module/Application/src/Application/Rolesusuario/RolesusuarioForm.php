<?php


namespace Application\Rolesusuario;

use Zend\Form\Form;
use Zend\Form\Element;
use Application\Controller\PermisosController;

class RolesusuarioForm extends Form 
{
    function __construct($name = null)
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   //create fields
	$this->add(array(
            'name' => 'usuario',
            'attributes' => array(
                'type'  => 'text',
		'placeholder'=>'Ingrese el usuario',

		
            ),
            'options' => array(
                'label' => 'Filtro Usuario:',
            ),
        ));
		
		$this->add(array(
            'name' => 'nombre',
            'attributes' => array(
                'type'  => 'text',
		'placeholder'=>'Ingrese el nombre',

            ),
            'options' => array(
                'label' => 'Filtro Nombre:',
            ),
        ));
		
		$this->add(array(
            'name' => 'apellido',
            'attributes' => array(
                'type'  => 'text',
		'placeholder'=>'Ingrese el apellido',

            ),
            'options' => array(
                'label' => 'Filtro Apellido:',
            ),
        ));

        $this->add(array(
            'name' => 'documento',
            'attributes' => array(
                'type'  => 'Number',
		'placeholder'=>'Ingrese el documento',

            ),
            'options' => array(
                'label' => 'Filtro Documento:',
            ),
        ));

	   $this->add(array(
	      'name'=>'submit',
		  'attributes'=>array(
		     'type'=>'submit',
		     'required'=>'required',
			 'class'=>'btn',
			 'value'=>'Buscar',
		  ),
	   ));
        
 
    }
}

