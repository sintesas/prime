<?php


namespace Application\Gestionarsolicitudes;

use Zend\Form\Form;
use Zend\Form\Element;



class GestionarsolicitudesForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   //create fields  

        $this->add(array(
            'name' => 'codigo',
            'attributes' => array(
				'type'  => 'text',
	'placeholder'  => 'Ingrese el código del proyecto',
            ),
            'options' => array(
				'label' => 'Filtro Código Proyecto :',
            ),
        ));

        $this->add(array(
 'name' => 'fecha_sol',
 'attributes' => array(
            'type' => 'Date',

		    'placeholder'=>'YYYY-MM-DD',
           ),
            'options' => array(
            'label' => 'Fecha de Solicitud:'
            )
        ));
		
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'id_estado2',
            'options' => array(
                'label' => 'Estado',
                'value_options' => array(
                    '0' => 'Seleccione',
                    '1' => 'En Revision',
                    '2' => 'Aprobado',
					'3' => 'Denegado'
                ),
            ),
            'attributes' => array(
                'value' => '0' //set selected to '1'
            )
        ));

	   $this->add(array(
	      'name'=>'filtrar',
		  'attributes'=>array(
		     'type'=>'submit',
'class'=>'btn',
		     'required'=>'required',
			 'value'=>'Buscar',
		  ),
	   ));
		
	   $this->add(array(
	      'name'=>'submit',
		  'attributes'=>array(
		     'type'=>'submit',
'class'=>'btn',
		     'required'=>'required',
			 'value'=>'Cambiar',
		  ),
	   ));

    }
}

