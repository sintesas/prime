<?php


namespace Application\Consultaredes;

use Zend\Form\Form;
use Zend\Form\Element;


class hredinvestigacionForm extends Form 
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
				'placeholder'  => 'Ingrese el nombre de la red',
            ),
            'options' => array(
				'label' => 'Filtro nombre de la red:',
            ),
        ));
		
        $this->add(array(
            'name' => 'codigo',
            'attributes' => array(
				'type'  => 'text',
				'placeholder'  => 'Ingrese el código de la red',
            ),
            'options' => array(
				'label' => 'Filtro código de la red:',
            ),
        ));

                $this->add(array(
		    'type' => 'Zend\Form\Element\Checkbox',
		    'name' => 'equipo',
		    'attributes'=> array(
		    	'id'  => 'equipo'
		    ),
		    'options' => array(
		        'label' => 'Equipo Directivo o Esctuctura Organiz acional',
		        'use_hidden_element' => true,
		        'checked_value' => '1',
		        'unchecked_value' => '0'
		    	)
		));

		$this->add(array(
		    'type' => 'Zend\Form\Element\Checkbox',
		    'name' => 'contacto',
		    'attributes'=> array(
		    	'id'  => 'contacto'
		    ),
		    'options' => array(
		        'label' => 'Contacto',
		        'use_hidden_element' => true,
		        'checked_value' => '1',
		        'unchecked_value' => '0'
		    	)
		));

		$this->add(array(
		    'type' => 'Zend\Form\Element\Checkbox',
		    'name' => 'miembros',
		    'attributes'=> array(
		    	'id'  => 'miembros'
		    ),
		    'options' => array(
		        'label' => 'Miembros de la Red de la UPN',
		        'use_hidden_element' => true,
		        'checked_value' => '1',
		        'unchecked_value' => '0'
		    	)
		));

		$this->add(array(
		    'type' => 'Zend\Form\Element\Checkbox',
		    'name' => 'datos',
		    'attributes'=> array(
		    	'id'  => 'datos'
		    ),
		    'options' => array(
		        'label' => 'Datos generales de la red',
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
		    'name' => 'servicios',
		    'attributes'=> array(
		    	'id'  => 'servicios'
		    ),
		    'options' => array(
		        'label' => 'Servicios',
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
		    'name' => 'proyectos',
		    'attributes'=> array(
		    	'id'  => 'proyectos'
		    ),
		    'options' => array(
		        'label' => 'Proyectos de investigación externos',
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
			'name'=>'submit',
			'attributes'=>array(
				'type'=>'submit',
				'class'=>'btn',
				'value'=>'Filtrar',
		  ),
	   ));
    }
}

