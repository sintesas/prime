<?php


namespace Application\Editarusuario;

use Zend\Form\Form;
use Zend\Form\Element;

class PruebaForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   

	   
	   //create field
        $this->add(array(
            'name' => 'tipo_formacion',
            'attributes' => array(
                'type'  => 'text',
				'placeholder'=>'Ingrese el tipo de formacion',
            ),
            'options' => array(
                'label' => 'Tipo de Formacion :',
            ),
        ));
		
        $this->add(array(
            'name' => 'nombre_formacion',
            'attributes' => array(
                'type'  => 'text',
				'placeholder'=>'Ingrese el nombre de la formacion',
            ),
            'options' => array(
                'label' => 'Nombre Formacion :',
            ),
        ));
		
        $this->add(array(
            'name' => 'titulo_formacion',
            'attributes' => array(
                'type'  => 'text',
				'placeholder'=>'Ingrese el titulo de la formacion',
            ),
            'options' => array(
                'label' => 'Titulo Formacion :',
            ),
        ));
		
        $this->add(array(
            'name' => 'institucion',
            'attributes' => array(
                'type'  => 'text',
				'placeholder'=>'Ingrese la institucion',
            ),
            'options' => array(
                'label' => 'Institucion :',
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
                'type'  => 'text',
				'placeholder'=>'Ingrese las horas',
            ),
            'options' => array(
                'label' => 'Horas :',
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

