<?php


namespace Application\Convocatoria;

use Zend\Form\Form;
use Zend\Form\Element;

class ConsulconvocatoriaForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   //create field <input type="number" min="0" inputmode="numeric" pattern="[0-9]*" title="Non-negative integral number">
        $this->add(array(
            'name' => 'id_convocatoria',
            'attributes' => array(
                'type'  => 'text',
				'placeholder'=>'Ingrese el ID de la convocatoria',
                'onkeypress' => 'funtionRegex(event)',
                'maxlength' => 9,
            ),
            'options' => array(
                'label' => 'ID Convocatoria :',
            ),
        ));

        $this->add(array(
            'name' => 'titulo',
            'attributes' => array(
                'type'  => 'text',
				'placeholder'=>'Ingrese el título de la convocatoria',
            ),
            'options' => array(
                'label' => 'Título Convocatoria :',
            ),
        ));

        $this->add(array(
 'name' => 'fecha_apertura',
 'attributes' => array(
            'type' => 'Date',
		    'placeholder'=>'YYYY-MM-DD',
           ),
            'options' => array(
            'label' => 'Fecha Apertura:'
            )
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

