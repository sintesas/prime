<?php


namespace Application\Agregarvalflex;

use Zend\Form\Form;
use Zend\Form\Element;


class AgregarvalflexForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   //create fields
        $this->add(array(
            'name' => 'descripcion_valor',
            'attributes' => array(
                'type'  => 'text',
				'required'=>'required',
				'placeholder'=>'Ingrese el nombre del valor flexible',
            ),
            'options' => array(
                'label' => 'Descripción Valor:',
            ),
        ));

        $this->add(array(
            'name' => 'sigla_valor',
            'attributes' => array(
                'type'  => 'text',
				'placeholder'=>'Ingrese la sigla del valor flexible',
            ),
            'options' => array(
                'label' => 'Sigla Valor:',
            ),
        ));
		
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
			 'value'=>'Agregar',
		  ),
	   ));
	   


    }
}
