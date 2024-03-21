<?php


namespace Application\Tipovalores;

use Zend\Form\Form;
use Zend\Form\Element;


class TipovaloresForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   //create fields
        $this->add(array(
            'name' => 'descripcion',
            'attributes' => array(
                'type'  => 'text',
'size'=>30,
				'placeholder'=>'Ingrese el nombre del tipo valor',
				'required'=>'required',
            ),
            'options' => array(
                'label' => 'Descripción:',
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'activo',
			'required'=>'required',
            'options' => array(
                'label' => 'Estado :',
                'value_options' => array(
                    '' => 'Seleccione estado',
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
			 'value'=>'Guardar',
		  ),
	   ));
	   


    }
}

