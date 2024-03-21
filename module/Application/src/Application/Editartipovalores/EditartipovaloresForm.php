<?php


namespace Application\Editartipovalores;

use Zend\Form\Form;
use Zend\Form\Element;


class EditartipovaloresForm extends Form 
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
                'label' => 'Estado :',
                'value_options' => array(
                    '0' => 'Seleccione estado',
                    'S' => 'Si',
                    'N' => 'No'
                ),
            ),
            'attributes' => array(
                'value' => '0' //set selected to '1'
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

