<?php


namespace Application\Mensajeadministrador;

use Zend\Form\Form;
use Zend\Form\Element;



class MensajeadministradorForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   //create fields
        $this->add(array(
            'name' => 'asunto',
            'attributes' => array(
                'type'  => 'text',
'required'=>'required',
				'placeholder'=>'Ingrese el asunto del mensaje',
            ),
            'options' => array(
                'label' => 'Asunto:',
            ),
        ));
         
        $this->add(array(
            'name' => 'mensaje',
            'attributes' => array(
                'type'  => 'textarea',
'required'=>'required',
				'placeholder'=>'Ingrese el mensaje',
				'lenght' => 500,
            ),
            'options' => array(
                'label' => 'Mensaje:',
            ),
        ));
		
	   $this->add(array(
	      'name'=>'submit',
		  'attributes'=>array(
		     'type'=>'submit',
		     'required'=>'required',
			 'value'=>'enviar',
		  ),
	   ));
	   


    }
}

