<?php


namespace Application\Solicitudes;

use Zend\Form\Form;
use Zend\Form\Element;


	
class SolicitudesForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   //create fields  
        $this->add(array(
            'name' => 'justificacion',
            'attributes' => array(
                'type'  => 'textarea',
				'placeholder'=>'Ingrese la justificación',
				'lenght' => 500,
            ),
            'options' => array(
                'label' => 'Justificación :',
            ),
        ));

        $this->add(array(
            'name' => 'codigo_proy',
            'attributes' => array(
                'type'  => 'text',
				'placeholder'=>'Ingrese el código del proyecto',
				'lenght' => 500,
            ),
            'options' => array(
                'label' => 'Código del Proyecto :',
            ),
        ));

        $file = new Element\File('image-file');
        $file->setLabel('Seleccione un archivo')
             ->setAttribute('id', 'image-file');
        $this->add($file);

        $this->add(array(
            'type' => 'date',
            'name' => 'nueva_fecha',
			'style' => 'background-color:#333333;',
            'options' => array(
                'label' => 'Fecha :'	
            )
        ));
		
	   $this->add(array(
	      'name'=>'submit',
		  'attributes'=>array(
		     'type'=>'submit',
		     'required'=>'required',
			 'value'=>'Guardar',
		  ),
	   ));

    }
}

