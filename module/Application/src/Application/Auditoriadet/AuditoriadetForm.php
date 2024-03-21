<?php


namespace Application\Auditoriadet;

use Zend\Form\Form;
use Zend\Form\Element;



class AuditoriadetForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   //create fields
        $this->add(array(
 'name' => 'fecha_ingreso',
 'attributes' => array(
            'type' => 'Date',
		    'placeholder'=>'YYYY-MM-DD',
           ),
            'options' => array(
            'label' => 'Fecha Ingreso:'
            )
        ));

        $this->add(array(
 'name' => 'fecha_salida',
 'attributes' => array(
            'type' => 'Date',
		    'placeholder'=>'YYYY-MM-DD',
           ),
            'options' => array(
            'label' => 'Fecha Salida:'
            )
        ));
		
	   $this->add(array(
	      'name'=>'submit',
		  'attributes'=>array(
		     'type'=>'submit',
		     'required'=>'required',
			 'value'=>'Buscar',
			 'class'=>'btn',
		  ),
	   ));

        $this->add(array(
            'name' => 'usuario',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Filtro Usuario:',
            ),
        ));
	   


    }
}

