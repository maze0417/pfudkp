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

class guildauction_pageobject extends pageobject {

	private $data = array();

	public function __construct(){
		if (!$this->pm->check('guildbank', PLUGIN_INSTALLED))
			message_die($this->user->lang('guildbank_not_installed'));

		$handler = array(
			'bid'		=> array('process' => 'perform_bid', 'csrf' => true, 'check' => 'u_guildbank_auction'),
			'dkp'		=> array('process' => 'get_availabledkp', 'check' => 'u_guildbank_auction'),
			'pull'		=> array('process' => 'pull_newbids', 'check' => 'u_guildbank_auction'),
		);
		parent::__construct('u_guildbank_auction', $handler, array(), null, '', 'auction');
		$this->process();
	}
	
	public function pull_newbids(){
		$intAuction = $this->url_id;
		
		$arrBids =		$this->pdh->get('guildbank_auction_bids', 'bids_byauction', array($this->url_id));
		if(is_array($arrBids) && count($arrBids)){
			echo max($arrBids);
		} else {
			echo 0;
		}

		exit;
	}
	
	public function get_availabledkp(){
		$intMemberID	= $this->in->get('memberid', 0);
		
		$intVirtualDKP	= $this->pdh->get('guildbank_auction_bids', 'virtual_bid_dkps', array($intMemberID, $this->url_id));
		$intMDKPID		= $this->pdh->get('guildbank_auctions', 'multidkppool', array($this->url_id));
		$intCurrDKP		= $this->pdh->get('points', 'current', array($intMemberID, $intMDKPID, 0, 0, false));
		
		echo $intCurrDKP.'|'.$intVirtualDKP;
		exit;
	}

	public function perform_bid(){
		$intMemberID	= $this->in->get('memberid', 0);
		$intBidValue	= runden($this->in->get('bidvalue', 0.0));
		$intMDKPID		= $this->pdh->get('guildbank_auctions', 'multidkppool', array($this->url_id));
		$intCurrDKP		= $this->pdh->get('points', 'current', array($intMemberID, $intMDKPID, 0, 0, false));
		$intAttendance	= $this->pdh->get('guildbank_auctions', 'raidattendance', array($this->url_id));
		$intVirtualDKP	= $this->pdh->get('guildbank_auction_bids', 'virtual_bid_dkps', array($intMemberID, $this->url_id));
		$intHighestValue= $this->pdh->get('guildbank_auction_bids', 'highest_value', array($this->url_id));
		$this->pdl->log('guildbank', 'Virtual FKP for this char '.$intVirtualDKP);

		// check if the meber has enough DKP
		$bid_allowed	= ($intCurrDKP >= $intBidValue) ? true : false;
		if(!$bid_allowed){
			$this->core->message($this->user->lang('gb_bids_error_dkp'), $this->user->lang('error'), 'orange');
		}
		
		// check if there is another bid acive with a bid
		if($bid_allowed && ($intVirtualDKP > 0)){
			$bid_allowed = (($intCurrDKP - $intVirtualDKP - $intBidValue) < 0) ? false : true;
			if(!$bid_allowed){
				$this->core->message($this->user->lang('gb_bids_error_virtual'), $this->user->lang('error'), 'orange');
			}
		}
		
		// check if value is bigger than the highest one
		$bidsteps		= $this->pdh->get('guildbank_auctions', 'bidsteps', array($this->url_id));
		if($bid_allowed && $intBidValue < ($intHighestValue+$bidsteps)){
			$bid_allowed = false;
			$this->core->message($this->user->lang('gb_bids_error_step'), $this->user->lang('error'), 'orange');
		}
		
		//check if auction is still running
		$intTimeLeft = $this->pdh->get('guildbank_auctions', 'atime_left', array($this->url_id));
		if($bid_allowed && $intTimeLeft == 0){
			$bid_allowed = false;
			$this->core->message($this->user->lang('gb_bids_error_time'), $this->user->lang('error'), 'orange');
		}
		
		
		// now, check if the other requirements are met
		if($bid_allowed){
			if($intAttendance > 0){
				$intItemName	= $this->pdh->get('guildbank_auctions', 'name', array($this->url_id));
				$intItemIDs		= $this->pdh->get('item', 'ids_by_name', array($intItemName));
				$intItems		= $this->pdh->get('raid', 'raidids4memberid_item', array($intMemberID, $intItemIDs));
				$bid_allowed	= ($intAttendance >= count($intItems)) ? true : false;
			}
		}

		// perform the process
		if($bid_allowed && $intMemberID > 0){
			$this->pdh->put('guildbank_auction_bids', 'add', array($this->url_id, $this->time->time, $intMemberID, $intBidValue));
		}
		$this->pdh->process_hook_queue();
		$this->display();
	}

	// shop display
	public function display(){
		require_once($this->root_path.'plugins/guildbank/includes/systems/guildbank.esys.php');

		$bid_list		= $this->pdh->get('guildbank_auction_bids', 'id_list', array($this->url_id));
		$hptt_bids		= $this->get_hptt($systems_guildbank['pages']['hptt_guildbank_bids'], $bid_list, $bid_list, array(), 'bids'.$this->url_id);
		$page_suffix	= '&amp;start='.$this->in->get('start', 0);
		$sort_suffix	= '&amp;sort='.$this->in->get('sort');
		$bids_count		= count($bid_list);
		$footer_bids	= sprintf($this->user->lang('gb_bids_footcount'), $bids_count, $this->user->data['user_rlimit']);

		// data
		$dkppool		= $this->pdh->get('guildbank_auctions', 'multidkppool', array($this->url_id));
		$actual_bid		= $this->pdh->get('guildbank_auction_bids', 'highest_value', array($this->url_id));
		$mainchar		= $this->pdh->get('member', 'mainchar', array($this->user->data['user_id']));
		if($mainchar){
			$points			= $this->pdh->get('points', 'current', array($mainchar, $dkppool, 0, 0, false));
			$intVirtualDKP	= $this->pdh->get('guildbank_auction_bids', 'virtual_bid_dkps', array($mainchar, $this->url_id));
		} else {
			$points = 0;
			$intVirtualDKP = 0;
		}
		
		$bidsteps		= $this->pdh->get('guildbank_auctions', 'bidsteps', array($this->url_id));
		$startvalue		= $this->pdh->get('guildbank_auctions', 'startvalue', array($this->url_id));
		$bidspinner		= ((int)$actual_bid > 0) ? $actual_bid+$bidsteps : $startvalue;
		$intTimeLeft 	= $this->pdh->get('guildbank_auctions', 'atime_left', array($this->url_id));
		$available_points = (($points - $intVirtualDKP) < 0) ? 0 : ($points - $intVirtualDKP);		
		$arrBids		= $this->pdh->get('guildbank_auction_bids', 'bids_byauction', array($this->url_id));
		$bidvalueopt	= ($this->config->get('allow_manualentry',	'guildbank') == 1) ? false : true;

		
		$this->pdh->get('guildbank_auctions', 'counterJS');
		$this->tpl->assign_vars(array(
			'ROUTING_BANKER'	=> $this->routing->build('guildbank'),
			'ERROR_WARNING'		=> (!$this->url_id || !$this->user->is_signedin()) ? true : false,
			'AUCTION_ID'		=> $this->url_id,
			'DD_MYCHARS'		=> (new hdropdown('memberid', array('value' => $mainchar, 'js' => 'onchange="getcurrentdkp(this.value)"', 'options' => $this->pdh->aget('member', 'name', 0, array($this->pdh->get('member', 'connection_id', array($this->user->data['user_id'])))))))->output(),
			'MY_DKPPOINTS'		=> $available_points,
			'DKP_NAME'			=> $this->config->get('dkp_name'),
			'BID_SPINNER'		=> (new hspinner('bidvalue', array('value' => $bidspinner, 'step'=> $bidsteps, 'min' => $bidspinner, 'max' => $available_points, 'onlyinteger' => true, 'readonly' => $bidvalueopt)))->output(),
			'TIMELEFT'			=> $this->pdh->get('guildbank_auctions', 'atime_left_html', array($this->url_id)),
			'BUTTON_DISABLED'	=> ($actual_bid+$bidsteps > $available_points|| $startvalue > $available_points || $intTimeLeft == 0) ? 'disabled="disabled"' : '',
			'NEXT_BID_AMOUNT'	=> $bidspinner,
			'LATEST_BID_ID'		=> ((is_array($arrBids) && count($arrBids)>0) ? max($arrBids): 0),
			'NEW_BID_INFO'		=> sprintf($this->user->lang('gb_new_bid_info'), $this->controller_path.'Guildauction/'.$this->SID.'&auction='.$this->url_id),
			'S_AUCTION_RUNNING' => ($intTimeLeft > 0),
				
			'BIDS_TABLE'		=> $hptt_bids->get_html_table($this->in->get('sort'), $page_suffix, $this->in->get('start', 0), $this->user->data['user_rlimit'], $footer_bids),
			'PAGINATION_BIDS'	=> generate_pagination($this->routing->build('guildauction').$sort_suffix, $bids_count, $this->user->data['user_rlimit'], $this->in->get('start', 0)),
		));

		$this->core->set_vars(array(
			'page_title'		=> $this->user->lang('gb_auction_window'),
			'template_path'		=> $this->pm->get_data('guildbank', 'template_path'),
			'template_file'		=> 'auction.html',
				'page_path'			=> [
						['title'=>$this->user->lang('gb_title_page'), 'url'=> $this->routing->build('guildbank')],
						['title'=>$this->user->lang('gb_auction_title'), 'url'=>' '],
				],
			'display'			=> true,
		));
	}
}
