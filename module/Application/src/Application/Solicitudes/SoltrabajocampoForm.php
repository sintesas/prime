<?php


namespace Application\Solicitudes;

use Zend\Form\Form;
use Zend\Form\Element;



class SoltrabajocampoForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   //create fields  
        $this->add(array(
            'type' => 'Date',
            'name' => 'fecha_inicio',
            'options' => array(
                'label' => 'Fecha Inicio :'
            )
        ));

        $this->add(array(
            'type' => 'Date',
            'name' => 'fecha_fin',
            'options' => array(
                'label' => 'Fecha Fin :'
            )
        ));
		
		$this->add(array(
            'name' => 'lugar',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Lugar:',
            ),
        ));
		
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

