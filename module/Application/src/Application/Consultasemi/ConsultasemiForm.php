<?php


namespace Application\Consultasemi;

use Zend\Form\Form;
use Zend\Form\Element;


class ConsultasemiForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   //create fields
        $this->add(array(
            'name' => 'nombre',
            'attributes' => array(
				'type'  => 'text',
				'placeholder'  => 'Ingrese el nombre del Semillero',
            ),
            'options' => array(
				'label' => 'Filtro nombre del Semillero / Otros procesos de formación:',
            ),
        ));

        $this->add(array(
            'name' => 'codigo',
            'attributes' => array(
				'type'  => 'text',
				'placeholder'  => 'Ingrese el código del semillero',
            ),
            'options' => array(
				'label' => 'Filtro código del Semillero / Otros procesos de formación:',
            ),
        ));

        $this->add(array(
		    'type' => 'Zend\Form\Element\Checkbox',
		    'name' => 'area',
		    'attributes'=> array(
		    	'id'  => 'area'
		    ),
		    'options' => array(
		        'label' => 'Áreas de actuación',
		        'use_hidden_element' => true,
		        'checked_value' => '1',
		        'unchecked_value' => '0'
		    	)
		));

		$this->add(array(
		    'type' => 'Zend\Form\Element\Checkbox',
		    'name' => 'integrantes',
		    'attributes'=> array(
		    	'id'  => 'integrantes'
		    ),
		    'options' => array(
		        'label' => 'Integrantes',
		        'use_hidden_element' => true,
		        'checked_value' => '1',
		        'unchecked_value' => '0'
		    	)
		));

		$this->add(array(
		    'type' => 'Zend\Form\Element\Checkbox',
		    'name' => 'participacion',
		    'attributes'=> array(
		    	'id'  => 'participacion'
		    ),
		    'options' => array(
		        'label' => 'Participación en eventos',
		        'use_hidden_element' => true,
		        'checked_value' => '1',
		        'unchecked_value' => '0'
		    	)
		));

		$this->add(array(
		    'type' => 'Zend\Form\Element\Checkbox',
		    'name' => 'infogeneral',
		    'attributes'=> array(
		    	'id'  => 'infogeneral'
		    ),
		    'options' => array(
		        'label' => 'Información general del semillero',
		        'use_hidden_element' => true,
		        'checked_value' => '1',
		        'unchecked_value' => '0'
		    	)
		));

		$this->add(array(
		    'type' => 'Zend\Form\Element\Checkbox',
		    'name' => 'grupos',
		    'attributes'=> array(
		    	'id'  => 'grupos'
		    ),
		    'options' => array(
		        'label' => 'Grupos de Investigación al que se encuentra artículado el Semillero',
		        'use_hidden_element' => true,
		        'checked_value' => '1',
		        'unchecked_value' => '0'
		    	)
		));

		$this->add(array(
		    'type' => 'Zend\Form\Element\Checkbox',
		    'name' => 'articulos',
		    'attributes'=> array(
		    	'id'  => 'articulos'
		    ),
		    'options' => array(
		        'label' => 'Artículos',
		        'use_hidden_element' => true,
		        'checked_value' => '1',
		        'unchecked_value' => '0'
		    	)
		));

		$this->add(array(
		    'type' => 'Zend\Form\Element\Checkbox',
		    'name' => 'libros',
		    'attributes'=> array(
		    	'id'  => 'libros'
		    ),
		    'options' => array(
		        'label' => 'Líbros',
		        'use_hidden_element' => true,
		        'checked_value' => '1',
		        'unchecked_value' => '0'
		    	)
		));

		$this->add(array(
		    'type' => 'Zend\Form\Element\Checkbox',
		    'name' => 'capitulos',
		    'attributes'=> array(
		    	'id'  => 'capitulos'
		    ),
		    'options' => array(
		        'label' => 'Capítulos de libros',
		        'use_hidden_element' => true,
		        'checked_value' => '1',
		        'unchecked_value' => '0'
		    	)
		));

		$this->add(array(
		    'type' => 'Zend\Form\Element\Checkbox',
		    'name' => 'otras',
		    'attributes'=> array(
		    	'id'  => 'otras'
		    ),
		    'options' => array(
		        'label' => 'Otras producciones de investigación',
		        'use_hidden_element' => true,
		        'checked_value' => '1',
		        'unchecked_value' => '0'
		    	)
		));

		$this->add(array(
		    'type' => 'Zend\Form\Element\Checkbox',
		    'name' => 'otros',
		    'attributes'=> array(
		    	'id'  => 'otros'
		    ),
		    'options' => array(
		        'label' => 'Otros documentos',
		        'use_hidden_element' => true,
		        'checked_value' => '1',
		        'unchecked_value' => '0'
		    	)
		));

		$this->add(array(
		    'type' => 'Zend\Form\Element\Checkbox',
		    'name' => 'reconocimientos',
		    'attributes'=> array(
		    	'id'  => 'reconocimientos'
		    ),
		    'options' => array(
		        'label' => 'Reconocimientos',
		        'use_hidden_element' => true,
		        'checked_value' => '1',
		        'unchecked_value' => '0'
		    	)
		));

		$this->add(array(
		    'type' => 'Zend\Form\Element\Checkbox',
		    'name' => 'proyectosint',
		    'attributes'=> array(
		    	'id'  => 'proyectosint'
		    ),
		    'options' => array(
		        'label' => 'Proyectos de investigación internos',
		        'use_hidden_element' => true,
		        'checked_value' => '1',
		        'unchecked_value' => '0'
		    	)
		));

		$this->add(array(
		    'type' => 'Zend\Form\Element\Checkbox',
		    'name' => 'material',
		    'attributes'=> array(
		    	'id'  => 'material'
		    ),
		    'options' => array(
		        'label' => 'Material colaborativo gratuito',
		        'use_hidden_element' => true,
		        'checked_value' => '1',
		        'unchecked_value' => '0'
		    	)
		));

		$this->add(array(
			    'type' => 'Zend\Form\Element\Checkbox',
			    'name' => 'identificadores',
			    'attributes'=> array(
		    	'id'  => 'identificadores'
		    ),
			    'options' => array(
			        'label' => 'Identificadores de Investigación',
			        'use_hidden_element' => true,
			        'checked_value' => '1',
			        'unchecked_value' => '0'
			    	)
			));

			$this->add(array(
			    'type' => 'Zend\Form\Element\Checkbox',
			    'name' => 'divulgacion',
			    'attributes'=> array(
		    	'id'  => 'divulgacion'
		    ),
			    'options' => array(
			        'label' => 'Divulgación',
			        'use_hidden_element' => true,
			        'checked_value' => '1',
			        'unchecked_value' => '0'
			    	)
			));

			$this->add(array(
			    'type' => 'Zend\Form\Element\Checkbox',
			    'name' => 'formacion',
			    'attributes'=> array(
		    	'id'  => 'formacion'
		    ),
			    'options' => array(
			        'label' => 'Formación',
			        'use_hidden_element' => true,
			        'checked_value' => '1',
			        'unchecked_value' => '0'
			    	)
			));
				
	    $this->add(array(
			'name'=>'submit',
			'attributes'=>array(
				'type'=>'submit',
				'class'=>'btn',
				'value'=>'Filtrar',
		  ),
	   ));
    }
}

