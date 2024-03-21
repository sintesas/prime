<?php


namespace Application\Editarsemilleroinv;

use Zend\Form\Form;
use Zend\Form\Element;

class ProyectosintsemiForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');

	   //create field	
     	$this->add(array(
         'name' => 'nombre_proy',
         'attributes' => array(
             'type'  => 'text',
			'placeholder'=>'Nombre',
         ),
         'options' => array(
             'label' => 'Nombre proyecto:',
         ),
     	));

     	$this->add(array(
         'name' => 'codigo_proy',
         'attributes' => array(
             'type'  => 'text',
			'placeholder'=>'Código',
         ),
         'options' => array(
             'label' => 'Código proyecto:',
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
			 'value'=>'Filtrar',
		  ),
	   ));

	   $this->add(array(
	      'name'=>'cargar',
		  'attributes'=>array(
		     'type'=>'submit',
		     'required'=>'required',
			 'class'=>'btn',
			 'value'=>'Cargar',
		  ),
	   ));
	   
    }
}

