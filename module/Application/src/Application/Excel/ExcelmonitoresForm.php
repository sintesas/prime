<?php


namespace Application\Excel;

use Zend\Form\Form;
use Zend\Form\Element;

class ExcelmonitoresForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   //create field
        $this->add(array(
            'name' => 'nombre_grupo',
            'attributes' => array(
                'type'  => 'text',
				'placeholder'=>'nombre grupo',
            ),
            'options' => array(
                'label' => 'Nombre Grupo :',
            ),
        ));

			
	   $this->add(array(
	      'name'=>'submit',
		  'attributes'=>array(
		     'type'=>'submit',
		     'required'=>'required',
'class'=>'btn',
			 'value'=>'Filtrar',
		  ),
	   ));
	   


    }
}

