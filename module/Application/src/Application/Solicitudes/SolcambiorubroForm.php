<?php


namespace Application\Solicitudes;

use Zend\Form\Form;
use Zend\Form\Element;



class SolcambiorubroForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   //create fields  
		$this->add(array(
            'name' => 'justificacion',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Justificacion:',
            ),
        ));
		
	   $this->add(array(
	      'name'=>'submit',
		  'attributes'=>array(
		     'type'=>'submit',
		     'required'=>'required',
			 'value'=>'Enviar',
		  ),
	   ));
    }
}
