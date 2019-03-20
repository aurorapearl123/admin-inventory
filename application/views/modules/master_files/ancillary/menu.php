<?php 
	/**
	 * File: menu.php
	 * Description: Defines all the menu and submenu of this module
	 * 
	 * Example of Menu
	 * 	$submenu = array();
	 * 	$submenu[] = array('label'=>'Item','url'=>site_url('item'),'icon'=>'icon.png');
	 * $this->menu['Deliveries'] = $submenu;
	 */
	
	$this->menu = array();
	// main menu
	$submenu = array();
    $submenu[] = array('label'=>'Employees','url'=>site_url('apple/show'),'icon'=>'bullet','subitem'=>array());
    $submenu[] = array('label'=>'Custom List','url'=>site_url('apple/show'),'icon'=>'bullet','subitem'=>array());
    $submenu[] = array('label'=>'Batch Update','url'=>site_url('apple/show'),'icon'=>'bullet','subitem'=>array());
    $submenu[] = array('label'=>'Credentials','url'=>site_url('apple/show'),'icon'=>'la-group', 
        'subitem'=> array(
            array('label'=>'Family Members','url'=>site_url('apple/show'),'icon'=>'bullet'),
            array('label'=>'Education Backgrounds','url'=>site_url('apple/show'),'icon'=>'bullet'),
            array('label'=>'Service Eligibilities','url'=>site_url('apple/show'),'icon'=>'bullet'),
            array('label'=>'Work Experiences','url'=>site_url('apple/show'),'icon'=>'bullet'),
            array('label'=>'Volunteer Works','url'=>site_url('apple/show'),'icon'=>'bullet'),
            array('label'=>'Training Programs','url'=>site_url('apple/show'),'icon'=>'bullet'),
            array('label'=>'Special Skills','url'=>site_url('apple/show'),'icon'=>'bullet'),
            array('label'=>'Awards and Recognitions','url'=>site_url('apple/show'),'icon'=>'bullet'),
            array('label'=>'Organizations','url'=>site_url('apple/show'),'icon'=>'bullet')
        )
    );
	    
	$this->menu = $submenu;
?>
