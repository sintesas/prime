<?php


namespace Application\Editargrupoinv;

use Zend\Form\Form;
use Zend\Form\Element;

class SemillerosForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   

	   
	   //create field	
        $this->add(array(
            'name' => 'descripcion',
            'attributes' => array(
                'type'  => 'textarea',
				'placeholder'=>'Ingrese la info del semillero',
				'lenght' => 500,
            ),
            'options' => array(
                'label' => 'Info Semillero :',
            ),
        ));

			
	   $this->add(array(
	      'name'=>'submit',
		  'attributes'=>array(
		     'type'=>'submit',
		     'required'=>'required',
'class'=>'btn',
			 'value'=>'Agregar',
		  ),
	   ));
	   


    }
}

