<?php


namespace Application\Editarusuario;

use Zend\Form\Form;
use Zend\Form\Element;

class FormacionacahvForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
//create field
        $this->add(array(
            'name' => 'nombre_formacion',
            'attributes' => array(
                'type'  => 'text',
                'placeholder'=>'Ingrese el nombre de la formación',
                'maxlength' => 500,
            ),
            'options' => array(
                'label' => 'Nombre Formación :',
            ),
        ));
        
        $this->add(array(
            'name' => 'titulo_formacion',
            'attributes' => array(
                'type'  => 'text',
                'placeholder'=>'Ingrese el título de la formación',
                'maxlength' => 500,
            ),
            'options' => array(
                'label' => 'Título Obtenido :',
            ),
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'institucion',
            'options' => array(
                'label' => 'Institución: ',
            ),
            'attributes' => array(
                'value' => '',
                'id' => 'institucion'
            )
        )); // set selected to '1'
       
       $this->add(array(
            'name' => 'pais',
            'attributes' => array(
                'type'  => 'text'
            ),
            'options' => array(
                'label' => 'País:',
            ),
        ));

       $this->add(array(
            'name' => 'ciudad',
            'attributes' => array(
                'type'  => 'text'
            ),
            'options' => array(
                'label' => 'Ciudad:',
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
            'label' => 'Fecha Inicio:'
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
            'label' => 'Fecha Fin:'
            )
        ));

        
        $this->add(array(
 'name' => 'fecha_grado',
 'attributes' => array(
            'type' => 'Date',
'required'=>'required',
            'placeholder'=>'YYYY-MM-DD',
           ),
            'options' => array(
            'label' => 'Fecha Grado:'
            )
        ));
       
        $this->add(array(
            'name' => 'horas',
            'attributes' => array(
                'type'  => 'number',
                'placeholder'=>'Ingrese las horas',
            ),
            'options' => array(
                'label' => 'Número de Horas :',
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

