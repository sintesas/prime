<?php


namespace Application\Convocatoria;

use Zend\Form\Form;
use Zend\Form\Element;

class RequisitosapdocForm extends Form 
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
                'label' => 'Estado :',
                'value_options' => array(
                    '' => 'Seleccione',
                    'C' => 'Cumple',
                    'N' => 'No Cumple',
                ),
            ),
            'attributes' => array(
                'value' => '1' //set selected to '1'
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'visible',
			'required'=>'required',
            'options' => array(
                'label' => 'Visible :',
                'value_options' => array(
                    '' => 'Seleccione',
                    'S' => 'Si',
                    'N' => 'No',
                ),
            ),
            'attributes' => array(
                'value' => '1' //set selected to '1'
            )
        ));

        $file = new Element\File('image-file');
        $file->setLabel('Seleccione un archivo')
             ->setAttribute('id', 'image-file');
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
	   


    }
}

