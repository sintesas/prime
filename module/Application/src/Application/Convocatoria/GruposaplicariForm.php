<?php


namespace Application\Convocatoria;

use Zend\Form\Form;
use Zend\Form\Element;

class GruposaplicariForm extends Form 
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
    }
}

