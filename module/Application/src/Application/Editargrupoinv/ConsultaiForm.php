<?php


namespace Application\Editargrupoinv;

use Zend\Form\Form;
use Zend\Form\Element;

class ConsultaiForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   //create field
        $this->add(array(
            'name' => 'nombre',
            'attributes' => array(
                'type'  => 'text'
            ),
            'options' => array(
                'label' => 'Nombre:',
            ),
        ));

        $this->add(array(
            'name' => 'apellido',
            'attributes' => array(
                'type'  => 'text'
            ),
            'options' => array(
                'label' => 'Apellido:',
            ),
        ));

        $this->add(array(
            'name' => 'documento',
            'attributes' => array(
                'type'  => 'text'
            ),
            'options' => array(
                'label' => 'Documento:',
            ),
        ));
		
        $this->add(array(
            'name' => 'grupo',
            'attributes' => array(
                'type'  => 'text'
            ),
            'options' => array(
                'label' => 'Grupo de nvestigacion:',
            ),
        ));

        $this->add(array(
            'name' => 'proyecto',
            'attributes' => array(
                'type'  => 'text'
            ),
            'options' => array(
                'label' => 'Proyecto de investigación:',
            ),
        ));	

        $this->add(array(
            'name' => 'id_grupo_inv',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'id' => 'id_grupo_inv',
            ),
            'options' => array(
                'label' => 'Grupo de investigación:')
        )); 

        $this->add(array(
	      'name'=>'submit',
		  'attributes'=>array(
		     'type'=>'submit',
		     'required'=>'required',
'class'=>'btn',
			 'value'=>'Filtrar',
		  ),
	   ));
	   


    }
}

