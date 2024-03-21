<?php


namespace Application\Convocatoria;

use Zend\Form\Form;
use Zend\Form\Element;


class EditartablaeForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   //create fields		
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'activo',
			'required'=>'required',
            'options' => array(
                'label' => 'Activo',
                'value_options' => array(
                    '1' => 'Seleccione',
                    'S' => 'Si',
                    'N' => 'No'
                ),
            ),
            'attributes' => array(
                'value' => '1' //set selected to '1'
            )
        ));
		
	   $this->add(array(
	      'name'=>'submit',
		  'attributes'=>array(
		     'type'=>'submit',
'class'=>'btn',
		     'required'=>'required',
			 'value'=>'Actualizar',
		  ),
	   ));
	   


    }
}

