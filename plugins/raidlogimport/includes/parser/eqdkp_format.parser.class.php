<?php
/*	Project:	EQdkp-Plus
 *	Package:	RaidLogImport Plugin
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


if(!defined('EQDKP_INC'))
{
	header('HTTP/1.0 Not Found');
	exit;
}

if(!class_exists('eqdkp_format')) {
	class eqdkp_format extends rli_parser {
		
		public static $name = 'MLDKP 1.1 / EQdkp Plugin';
		
		public static function check($xml) {
			$back[1] = true;
			if(!isset($xml->start)) {
				$back[1] = false;
				$back[2][] = 'start';
			} else {
				if(!is_numeric((string)$xml->start[0]) && !(stristr($xml->start, ':'))) {
					$back[1] = false;
					$back[2][] = 'start in format: MM/DD/YY HH:MM:SS';
				}
			}
			if(!isset($xml->end)) {
				$back[1] = false;
				$back[2][] = 'end';
			} else {
				if(!is_numeric((string)$xml->end[0]) && !(stristr($xml->end, ':'))) {
					$back[1] = false;
					$back[2][] = 'end in format: MM/DD/YY HH:MM:SS';
				}
			}
			if(!isset($xml->BossKills)) {
				$back[1] = false;
				$back[2][] = 'BossKills';
			} else {
				foreach($xml->BossKills->children() as $bosskill) {
					if($bosskill) {
						if(!isset($bosskill->name)) {
							$back[1] = false;
							$back[2][] = 'BossKills->name';
						}
						if(!isset($bosskill->time)) {
							$back[1] = false;
							$back[2][] = 'BossKills->time';
						}
					}
				}
			}
			if(!isset($xml->Loot)) {
				$back[1] = false;
				$back[2][] = 'Loot';
			} else {
				foreach($xml->Loot->children() as $loot) {
					if($loot) {
						if(!isset($loot->ItemName)) {
							$back[1] = false;
							$back[2][] = 'Loot->ItemName';
						}
						if(!isset($loot->Player)) {
							$back[1] = false;
							$back[2][] = 'Loot->Player';
						}
						if(!isset($loot->Time)) {
							$back[1] = false;
							$back[2] = 'Loot->Time';
						}
					}
				}
			}
			if(!isset($xml->PlayerInfos)) {
				$back[1] = false;
				$back[2][] = 'PlayerInfos';
			} else {
				foreach($xml->PlayerInfos->children() as $mem) {
					if(!isset($mem->name)) {
						$back[1] = false;
						$back[2][] = 'PlayerInfos->name';
					}
				}
			}
			if(!isset($xml->Join)) {
				$back[1] = false;
				$back[2][] = 'Join';
			} else {
				foreach($xml->Join->children() as $join) {
					if(!isset($join->player)) {
						$back[1] = false;
						$back[2][] = 'Join->player';
					}
					if(!isset($join->time)) {
						$back[1] = false;
						$back[2][] = 'Join->time';
					}
				}
			}
			if(!isset($xml->Leave)) {
				$back[1] = false;
				$back[2][] = 'Leave';
			} else {
				foreach($xml->Leave->children() as $leave) {
					if(!isset($leave->player)) {
						$back[1] = false;
						$back[2][] = 'Leave->player';
					}
					if(!isset($leave->time)) {
						$back[1] = false;
						$back[2][] = 'Leave->time';
					}
				}
			}
			return $back;
		}
		
		public static function parse($xml) {
			$data = array();
			
			$data['zones'][] = array(trim($xml->zone), self::convertTime($xml->start), self::convertTime($xml->end), trim($xml->difficulty));
			foreach ($xml->BossKills->children() as $bosskill) {
				$data['bosses'][] = array(trim($bosskill->name), self::convertTime($bosskill->time));
			}
			foreach($xml->Loot->children() as $loot) {
				$player = (trim($loot->Player));
				$cost = (array_key_exists('Costs', $loot)) ? (int) $loot->Costs : (int) $loot->Note;
				$itemid = trim($loot->ItemID);
				$data['items'][] = array(trim($loot->ItemName), $player, $cost, $itemid, self::convertTime($loot->Time));
			}
			foreach($xml->PlayerInfos->children() as $xmember) {
				$data['members'][] = array(trim($xmember->name), trim($xmember->class), trim($xmember->race), trim($xmember->level), trim($xmember->note));
			}
			foreach ($xml->Join->children() as $joiner) {
				$data['times'][] = array(trim($joiner->player), self::convertTime($joiner->time), 'join');
			}
			foreach ($xml->Leave->children() as $leaver) {
				$data['times'][] = array(trim($leaver->player), self::convertTime($leaver->time), 'leave');
			}
			
			return $data;
		}
		
		private static function convertTime($str){
			if(stristr($str, ':'))	return strtotime($str);
			if(is_numeric((string)$str)) return intval((string)$str);
		}
	}
}
?>