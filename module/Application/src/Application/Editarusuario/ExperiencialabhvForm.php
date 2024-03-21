<?php


namespace Application\Editarusuario;

use Zend\Form\Form;
use Zend\Form\Element;

class ExperiencialabhvForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   //create field
        $this->add(array(
            'name' => 'empresa',
            'attributes' => array(
                'type'  => 'text',
				'placeholder'=>'Si no cuenta con experiencia laboral indíquelo aquí',
                'maxlength' => 500,
            ),
            'options' => array(
                'label' => 'Institución/Empresa :',
            ),
        ));
		
        $this->add(array(
            'name' => 'tipo_vinculacion',
            'attributes' => array(
                'type'  => 'text',
				'placeholder'=>'Ingrese el tipo de vinculación',
                'maxlength' => 500,
            ),
            'options' => array(
                'label' => 'Tipo Vinculación :',
            ),
        ));
		
        $this->add(array(
            'name' => 'dedicacion_horaria',
            'attributes' => array(
                'type'  => 'text',
				'placeholder'=>'Ingrese la dedicación en horas',
            ),
            'options' => array(
                'label' => 'Dedicación Horaria :',
            ),
        ));

        $this->add(
            array(
                'name' => 'fecha_inicio',
                'attributes' => array(
                'type' => 'date',
                'placeholder'=>'YYYY-MM-DD',
            ),
            'options' => array(
                'label' => 'Fecha de inicio:'
            )
        ));

        $this->add(array(
            'name' => 'fecha_fin',
            'attributes' => array(
                'type' => 'date',
                'placeholder'=>'YYYY-MM-DD',
            ),
            'options' => array(
                'label' => 'Fecha finalización:'
            )
        ));
	   
        $this->add(array(
            'name' => 'periodo_vinculacion',
            'attributes' => array(
                'type'  => 'text',
				'placeholder'=>'Ingrese el período de vinculación',
            ),
            'options' => array(
                'label' => 'Período vinculación :',
            ),
        ));
	   
	   
        $this->add(array(
            'name' => 'cargo',
            'attributes' => array(
                'type'  => 'text',
				'placeholder'=>'Ingrese el cargo',
                'maxlength' => 300,
            ),
            'options' => array(
                'label' => 'Cargo/Puesto de Trabajo :',
            ),
        ));
	   
        $this->add(array(
            'name' => 'descripcion_actividades',
            'attributes' => array(
                'type'  => 'textarea',
				'placeholder'=>'Ingrese las actividades',
				'maxlength' => 500,
            ),
            'options' => array(
                'label' => 'Descripción de las actividades realizadas:',
            ),
        ));
		
        $this->add(array(
            'name' => 'otra_info',
            'attributes' => array(
                'type'  => 'textarea',
				'placeholder'=>'Ingrese otra información',
				'maxlength' => 500,
            ),
            'options' => array(
                'label' => 'Otra información relevante:',
            ),
        ));

        $this->add(array(
            'name' => 'horas',
            'attributes' => array(
                'type'  => 'text',
				'placeholder'=>'Ingrese las horas',
            ),
            'options' => array(
                'label' => 'Horas :',
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

