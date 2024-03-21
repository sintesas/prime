<?php


namespace Application\Editargrupoinv;

use Zend\Form\Form;
use Zend\Form\Element;

class EventosgruForm extends Form 
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
	   

        $this->add(array(
            'name' => 'id_tipoevento',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'id' => 'id_tipoevento',
                'required'=>false,
            ),
            'options' => array(
                'label' => 'Tipo de evento:'
            )
        ));

        $this->add(array(
            'name' => 'id_tipoparticipacion',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'id' => 'id_tipoparticipacion',
                'required'=>false,
            ),
            'options' => array(
                
                'label' => 'Tipo de participación:'
            )
        ));

        //create field
        $this->add(array(
            'name' => 'nombre_evento',
            'attributes' => array(
                'required'=>false,
                'type'  => 'text',
                'maxlength' => 500,
                'style' => 'max-width: 100%;width: 100%;',
            ),
            'options' => array(
                'label' => 'Nombre del evento:',
            ),
        ));

        //create field
        $this->add(array(
            'name' => 'nombre_trabajo',
            'attributes' => array(
                'required'=>false,
                'type'  => 'text',
                'maxlength' => 500,
                'style' => 'max-width: 100%;width: 100%;',
            ),
            'options' => array(
                'label' => 'Nombre del trabajo presentado:',
            ),
        ));

        $this->add(array(
            'name' => 'id_institucion',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'id' => 'id_institucion',
                'required'=>false,
            ),
            'options' => array(
                
                'label' => 'Institución:'
            )
        ));

        //create field
        $this->add(array(
            'name' => 'ciudad_trabajo',
            'attributes' => array(
                'required'=>false,
                'type'  => 'text',
                'maxlength' => 500,
                'style' => 'max-width: 100%;width: 100%;',
            ),
            'options' => array(
                'label' => 'Ciudad donde se realizó el trabajo:',
            ),
        ));

        //create field
        $this->add(array(
            'name' => 'fecha_inicio',
            'attributes' => array(
                'required'=>false,
                'type'  => 'date',
                'maxlength' => 10,
                'style' => 'max-width: 100%;width: 100%;',
            ),
            'options' => array(
                'label' => 'Fecha de inicio:',
                'format' => 'Y-m-d'
            ),
        ));

        //create field
        $this->add(array(
            'name' => 'fecha_fin',
            'attributes' => array(
                'required'=>false,
                'type'  => 'date',
                'maxlength' => 10,
                'style' => 'max-width: 100%;width: 100%;',
            ),
            'options' => array(
                'label' => 'Fecha fin:',
                'format' => 'Y-m-d'
            ),
        ));


        $this->add(array(
            'name' => 'otra_informacion',
            'attributes' => array(
                'maxlength' => 3000,
                'type'  => 'textarea',
                'required'=>false
            ),
            'options' => array(
                'label' => 'Otra información relevante:',
            )
        ));


        $this->add(array(
            'name' => 'id_tipomedio',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'id' => 'id_tipomedio',
                'required'=>false,
            ),
            'options' => array(
                
                'label' => 'Tipo de medio:'
            )
        ));

        //create field
        $this->add(array(
            'name' => 'nombre_trabajo_medio',
            'attributes' => array(
                'required'=>false,
                'type'  => 'text',
                'maxlength' => 500,
                'style' => 'max-width: 100%;width: 100%;',
            ),
            'options' => array(
                'label' => 'Nombre del trabajo:',
            ),
        ));


        $this->add(array(
            'name' => 'filtro_autor',
            'attributes' => array(
                'id' => 'filtro_autor',
                'type' => 'text',
                'placeholder' => 'Filtro',
                'onkeyup' => 'showHint(this.value)'
            ),
            'options' => array(
                'label' => 'Filtro autor: '
            )
        ));

        $this->add(array(
            'name' => 'id_autor',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'id' => 'id_autor'
            ),
            'options' => array(
                
                'label' => 'Autor:'
            )
        ));

        //create field
        $this->add(array(
            'name' => 'medio_divulgacion',
            'attributes' => array(
                'required'=>false,
                'type'  => 'text',
                'maxlength' => 500,
                'style' => 'max-width: 100%;width: 100%;',
            ),
            'options' => array(
                'label' => 'Medio de divulgación:',
            ),
        ));

        $this->add(array(
            'name' => 'id_institucion_medio',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'id' => 'id_institucion_medio',
                'required'=>false,
            ),
            'options' => array(
                
                'label' => 'Institución:'
            )
        ));
        
        //create field
        $this->add(array(
            'name' => 'ciudad_medio',
            'attributes' => array(
                'required'=>false,
                'type'  => 'text',
                'maxlength' => 500,
                'style' => 'max-width: 100%;width: 100%;',
            ),
            'options' => array(
                'label' => 'Ciudad de realización:',
            ),
        ));

        //create field
        $this->add(array(
            'name' => 'fecha_medio',
            'attributes' => array(
                'required'=>false,
                'type'  => 'date',
                'maxlength' => 10,
                'style' => 'max-width: 100%;width: 100%;',
            ),
            'options' => array(
                'label' => 'Fecha:',
                'format' => 'Y-m-d'
            ),
        ));

        $this->add(array(
            'name' => 'descripcion_medio',
            'attributes' => array(
                'maxlength' => 3000,
                'type'  => 'textarea',
                'required'=>false
            ),
            'options' => array(
                'label' => 'Descripción del trabajo:',
            )
        ));

        $this->add(array(
            'name' => 'otra_informacion_medio',
            'attributes' => array(
                'maxlength' => 3000,
                'type'  => 'textarea',
                'required'=>false
            ),
            'options' => array(
                'label' => 'Otra información relevante:',
            )
        ));
        
	    $this->add(array(
	      'name'=>'submit',
		  'attributes'=>array(
            'type'=>'submit',
            'required'=>false,
            'class'=>'btn',
            'value'=>'Agregar',
		  ),
	   ));
    }
}

