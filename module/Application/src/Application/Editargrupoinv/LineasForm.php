<?php
namespace Application\Editargrupoinv;

use Zend\Form\Form;
use Zend\Form\Element;

class LineasForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   

	   
	   //create field	
        $this->add(array(
            'name' => 'nombre_linea',
            'attributes' => array(
                'type'  => 'text',
				'placeholder'=>'Ingrese el nombre de la línea',
            ),
            'options' => array(
                'label' => 'Nombre Línea :',
            ),
        ));

        $this->add(array(
            'name' => 'objetivo',
            'attributes' => array(
                'type'  => 'textarea',
				'placeholder'=>'Ingrese el objetivo',
				'lenght' => 500,
            ),
            'options' => array(
                'label' => 'Objetivo :',
            ),
        ));

        $this->add(array(
            'name' => 'efectos',
            'attributes' => array(
                'type'  => 'textarea',
				'placeholder'=>'Ingrese los efectos',
				'lenght' => 500,
            ),
            'options' => array(
                'label' => 'Efectos :',
            ),
        ));

        $this->add(array(
            'name' => 'logros',
            'attributes' => array(
                'type'  => 'textarea',
				'placeholder'=>'Ingrese los logros',
				'lenght' => 500,
            ),
            'options' => array(
                'label' => 'Logros :',
            ),
        ));

        //Creacion de campos
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
	   


    }
}



