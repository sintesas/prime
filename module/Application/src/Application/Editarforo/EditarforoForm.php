<?php


namespace Application\Editarforo;

use Zend\Form\Form;
use Zend\Form\Element;

class EditarforoForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   

	   
	   //create field	
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'estado',
			'required'=>'required',
            'options' => array(
                'label' => 'Estado',
                'value_options' => array(
                    '' => 'Seleccione',
                    'H' => 'Habilitado',
                    'D' => 'Deshabilitado'
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
		     'required'=>'required',
'class'=>'btn',
			 'value'=>'Actualizar',
		  ),
	   ));
	   


    }
}

