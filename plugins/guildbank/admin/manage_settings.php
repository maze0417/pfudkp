<?php
/*	Project:	EQdkp-Plus
 *	Package:	Guildbanker Plugin
 *	Link:		http://eqdkp-plus.eu
 *
 *	Copyright (C) 2006-2016 EQdkp-Plus Developer Team
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU Affero General Public License as published
 *	by the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU Affero General Public License for more details.
 *
 *	You should have received a copy of the GNU Affero General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

// EQdkp required files/vars
define('EQDKP_INC', true);
define('IN_ADMIN', true);
define('PLUGIN', 'guildbank');

$eqdkp_root_path = './../../../';
include_once('./../includes/common.php');

class guildbankSettings extends page_generic {

	/**
	* Constructor
	*/
	public function __construct(){
		// plugin installed?
		if (!$this->pm->check('guildbank', PLUGIN_INSTALLED))
			message_die($this->user->lang('guildbank_not_installed'));

		$handler = array(
			'save' => array('process' => 'save', 'csrf' => true, 'check' => 'a_guildbank_settings'),
		);
		parent::__construct('a_guildbank_settings', $handler);

		$this->process();
	}

	private $arrData = false;

	public function save(){
		$objForm				= register('form', array('gb_settings'));
		$objForm->langPrefix	= 'gb_';
		$objForm->validate		= true;
		$objForm->add_fieldsets($this->fields());
		$arrValues				= $objForm->return_values();

		if($objForm->error){
			$this->arrData		= $arrValues;
		}else{
			// update configuration
			$this->config->set($arrValues, '', 'guildbank');
			// Success message
			$messages[]			= $this->user->lang('gb_saved');
			$this->display($messages);
		}
	}

	private function fields(){
		$arrFields = array(
			'banker_display' => array(
				'show_money' => array(
					'type'		=> 'radio',
				),
				'merge_bankers' => array(
					'type'		=> 'radio',
				),
			),
			'itemshop' => array(
				'use_autoadjust' => array(
					'type'		=> 'radio',
				),
				'default_event' => array(
					'type'		=> 'dropdown',
					'options'	=> $this->pdh->aget('event', 'name', 0, array($this->pdh->get('event', 'id_list'))),
				),
			),
			'auctions' => array(
				'allow_manualentry' => array(
					'type'		=> 'radio',
				),
			)
		);
		
		$arrMultidkpPools = $this->pdh->get('multidkp', 'id_list');
		
		foreach($arrMultidkpPools as $intMultiDKPID){
			$arrEvents = $this->pdh->aget('event', 'name', 0, array($this->pdh->get('multidkp', 'event_ids', array($intMultiDKPID))));
			
			$arrFields['auctions']['default_event_'.$intMultiDKPID] = array(
					'type'		=> 'dropdown',
					'options'	=> $arrEvents,
					'lang'		=> $this->user->lang('gb_f_default_event').': '.$this->pdh->get('multidkp', 'name', array($intMultiDKPID))
			);
			
		}
		return $arrFields;
	}

	public function display($messages=array()){
		// -- Messages ------------------------------------------------------------
		if ($messages){
			foreach($messages as $name)
				$this->core->message($name, $this->user->lang('guildbank'), 'green');
		}

		// get the saved data
		$arrValues		= $this->config->get_config('guildbank');
		if ($this->arrData !== false) $arrValues = $this->arrData;

		// -- Template ------------------------------------------------------------
		// initialize form class
		$objForm				= register('form', array('gb_settings'));
		$objForm->reset_fields();
		$objForm->lang_prefix	= 'gb_';
		$objForm->validate		= true;
		$objForm->use_fieldsets	= true;
		$objForm->add_fieldsets($this->fields());

		// Output the form, pass values in
		$objForm->output($arrValues);

		$this->core->set_vars(array(
			'page_title'	=> $this->user->lang('guildbank').' '.$this->user->lang('settings'),
			'template_path'	=> $this->pm->get_data('guildbank', 'template_path'),
			'template_file'	=> 'admin/manage_settings.html',
				'page_path'			=> [
						['title'=>$this->user->lang('menu_admin_panel'), 'url'=>$this->root_path.'admin/'.$this->SID],
						['title'=>$this->user->lang('guildbank').': '.$this->user->lang('settings'), 'url'=>' '],
				],
			'display'		=> true
	  ));
	}

}
registry::register('guildbankSettings');
