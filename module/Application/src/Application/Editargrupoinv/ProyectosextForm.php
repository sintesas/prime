<?php


namespace Application\Editargrupoinv;

use Zend\Form\Form;
use Zend\Form\Element;

class proyectosextForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');

       //create field
        $file = new Element\File('image-file');
        $file->setLabel('Seleccione un archivo')
             ->setAttribute('id', 'image-file');
        $this->add($file);
	   
	   //create field
        $this->add(array(
            'name' => 'codigo_proyecto',
            'attributes' => array(
                'type'  => 'text'
            ),
            'options' => array(
                'label' => 'Código del proyecto:'
            ),
        ));

        $this->add(array(
            'name' => 'nombre_proyecto',
            'attributes' => array(
                'type'  => 'text',
                 'style' => 'max-width: 100%;width: 100%;'
            ),
            'options' => array(
                'label' => 'Nombre del proyecto:'
            ),
        ));
	   
        $this->add(array(
            'name' => 'tipo_proyecto',
            'attributes' => array(
                'type'  => 'text'
            ),
            'options' => array(
                'label' => 'Tipo de proyecto:',
            ),
        ));
	   
        $this->add(array(
 'name' => 'fecha_inicio',
 'attributes' => array(
            'type' => 'Date',
'required'=>'required',
		    'placeholder'=>'YYYY-MM-DD',
           ),
            'options' => array(
            'label' => 'Fecha inicio:'
            )
        ));
	   
        $this->add(array(
 'name' => 'fecha_fin',
 'attributes' => array(
            'type' => 'Date',
'required'=>'required',
		    'placeholder'=>'YYYY-MM-DD',
           ),
            'options' => array(
            'label' => 'Fecha fin'
            )
        ));
	   
        $this->add(array(
            'name' => 'resumen_ejecutivo',
            'attributes' => array(
                'type'  => 'textarea',
				'lenght' => 500,
            ),
            'options' => array(
                'label' => 'Resumen ejecutivo:',
            ),
        ));
	   
        $this->add(array(
            'name' => 'objetivo_general',
            'attributes' => array(
                'type'  => 'textarea',
				'lenght' => 500,
            ),
            'options' => array(
                'label' => 'Objetivo general:',
            ),
        ));
	   
        $this->add(array(
            'name' => 'productos_derivados',
            'attributes' => array(
                'type'  => 'textarea',
				'lenght' => 500,
            ),
            'options' => array(
                'label' => 'Productos derivados:'
            ),
        ));
			
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