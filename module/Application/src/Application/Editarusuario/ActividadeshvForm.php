<?php


namespace Application\Editarusuario;

use Zend\Form\Form;
use Zend\Form\Element;

class ActividadeshvForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   //create field
        $this->add(array(
            'name' => 'tipo',
            'attributes' => array(
                'type'  => 'text',
                'maxlength' => 120,
            ),
            'options' => array(
                'label' => 'Tipo actividad Convocatoria/Proyecto/Modalidad:',
            ),
        ));
	   
        $this->add(array(
            'name' => 'descripcion',
            'attributes' => array(
                'type'  => 'text',
                'maxlength' => 500,
            ),
            'options' => array(
                'label' => 'Titulo de la actividad evaluada:',
            ),
        ));

        $this->add(array(
            'name' => 'documento_vinculacion',
            'attributes' => array(
                'type'  => 'text',
                'maxlength' => 2000,
            ),
            'options' => array(
                'label' => 'Documento de vinculación para evaluación:',
            ),
        ));

        $this->add(array(
            'name' => 'valor',
            'attributes' => array(
                'type' => 'Number'
            ),
            'options' => array(
                'label' => 'Valor pagado por la evaluación:'
            )
        ));

        $this->add(array(
            'name' => 'dedicacion',
            'attributes' => array(
                'type'  => 'number'
            ),
            'options' => array(
                'label' => 'Horas empleadas en la actividad de evaluación:',
            ),
        ));

        $this->add(array(
            'name' => 'fecha',
            'attributes' => array(
            'type' => 'Date',
            'required'=>'required',
		    'placeholder'=>'YYYY-MM-DD',
           ),
            'options' => array(
            'label' => 'Fecha:'
            )
        ));
		
		$this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'tema',
            'options' => array(
                'label' => 'Tema/campo/área de actuación de la evaluación: ',
            ),
            'attributes' => array(
                'value' => '',
                'id' => 'tema'
            )
        )); // set selected to '1'

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'instituciones',
            'options' => array(
                'label' => 'Institución: ',
            ),
            'attributes' => array(
                'value' => '',
                'id' => 'instituciones'
            )
        )); // set selected to '1'

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