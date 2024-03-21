<?php


namespace Application\Convocatoria;

use Zend\Form\Form;
use Zend\Form\Element;

class TablaequipoForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   

	   
	   //create field	
	$this->add(array(
            'name' => 'usuario',
            'attributes' => array(
                'type'  => 'text',
		'placeholder'=>'Ingrese el usuario',
		'id'=>'usuario'
			
            ),
            'options' => array(
                'label' => 'Filtro Usuario:',
		'placeholder'=>'Ingrese el usuario',

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

