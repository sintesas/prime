<?php


namespace Application\Editarredinv;

use Zend\Form\Form;
use Zend\Form\Element;

class GruposredForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   //create field
        $this->add(array(
            'name' => 'codigo',
            'attributes' => array(
                'type'  => 'text',
                'maxlength' => 50
            ),
            'options' => array(
                'label' => 'Filtro código del grupo:',
            ),
        ));

        $this->add(array(
            'name' => 'nombre',
            'attributes' => array(
                 'type'  => 'text'
            ),
            'options' => array(
                'label' => 'Filtro nombre del grupo',
            )
        ));

        $this->add(array(
            'name' => 'filtro_autor',
            'attributes' => array(
                'id' => 'filtro_autor',
                'type' => 'Text',
                'placeholder' => 'Filtrar',
                'onkeyup' => 'showHint(this.value)'
            ),
            'options' => array(
                'label' => 'Filtro líder del grupo:'
            )
        ));
        
	    $this->add(array(
	      'name'=>'submit',
		  'attributes'=>array(
            'type'=>'submit',
            'required'=>'required',
            'class'=>'btn',
            'value'=>'Buscar',
		  ),
	   ));

        //Creacion de campos
        //create field
        $file = new Element\File('image-file');
        $file->setLabel('Seleccione un archivo')->setAttribute('id', 'image-file');
        $this->add($file);

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

