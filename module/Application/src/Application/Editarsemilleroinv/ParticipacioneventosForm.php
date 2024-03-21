<?php
namespace Application\Editarsemilleroinv;

use Zend\Form\Form;
use Zend\Form\Element;

class ParticipacioneventosForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   //create field
		$file = new Element\File('image-file');
		$file->setLabel('Seleccione un archivo')->setAttribute('id', 'image-file');
		$this->add($file);
	
		$this->add(array(
		  'name'=>'submit',
		  'attributes'=>array(
		     'type'=>'submit',
		     'required'=>'required',
			 'class'=>'btn',
			 'value'=>'Agregar',
		  ),
		));

		$this->add(array(
		    'name' => 'nombre',
		    'attributes' => array(
		        'required'=>'required',
		        'type'  => 'text',
		        'maxlength' => 500
		    ),
		    'options' => array(
		        'label' => 'Nombre del evento:',
		    ),
		));

		$this->add(array(
		    'name' => 'lugar',
		    'attributes' => array(
		        'required'=>'required',
		        'type'  => 'text',
		        'maxlength' => 500
		    ),
		    'options' => array(
		        'label' => 'Lugar del evento:',
		    ),
		));

		$today = date("Y-m-d");
        $this->add(array(
            'name' => 'fecha',
            'attributes' => array(
                'type'  => 'date',
                'max' => $today
            ),
            'options' => array(
                'label' => 'Fecha del evento:'
            ),
        ));
    }
}

