<?php
 /*
 * Project:		EQdkp-Plus
 * License:		Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:		2011
 * Date:		$Date$
 * -----------------------------------------------------------------------
 * @author		$Author$
 * @copyright	2006-2011 EQdkp-Plus Developer Team
 * @link		http://eqdkp-plus.com
 * @package		eqdkp-plus
 * @version		$Rev$
 * 
 * $Id$
 */

error_reporting(0);
ini_set("display_errors", 0); 
 
define( 'INSTALL_ROOT', dirname( __FILE__ ));
define( 'SETUP_PACKAGE', 'eqdkp_plus.zip' );

$installDir = (strlen($_POST['install_dir']) && valid_folder($_POST['install_dir'])) ? $_POST['install_dir'] : '/';
if(substr($installDir, -1) != "/") $installDir = $installDir.'/';
define( 'INSTALL_DIR', $installDir);

define( 'INSTALL_PATH', INSTALL_ROOT.INSTALL_DIR);
if (!isset($_GET['step'])){
	$content = step1();
} else {
	if (function_exists($_GET['step'])){
		$function = $_GET['step'];
		$content = $function();
	}
}
 
 
 
//Step1: check requirements for unpacking EQdkp Plus 
function step1(){
	$phpcheckdata	= getCheckParams();
		
		if (!do_match_absreq()){
		$content .='<div style="margin-top: 10px; padding: 0pt 0.7em;" class="ui-state-error ui-corner-all"> 
					<p>Your Server does not fulfill the requirements needed to unpack the Installation Package. Please unpack the File "'.SETUP_PACKAGE.'" on your local computer and upload it via FTP. After that, open the file "index.php" of the EQdkp Plus Root-Folder in your Browser.</p>
				</div>';
	}
	
	$content .= '<br/>
		<table style="border-collapse: collapse; width: 100%">
			<thead class="ui-state-default">
			<tr>
				<th width="54%">Name</th>
				<th width="19%">Installed</th>
				<th width="19%">Required</th>
				<th width="6%"></th>
			</tr>
			</thead>
			<tbody>';
	
	$okicon = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAABEhJREFUeNq0VWtoHFUU/u6dndlns83LIGpq1CIqhhYabBLEFsQWSgsi4gN/KJb+9ZeCD1DEX2KhIlX/Cj7QIobWgq0gUquCpS0UVAJtQyLNxmQ3m02yO7NzX547k0w3TdL0jxc+ZriP75zvnHPPZcYYMMaw3jh4mXu0/Bj99hEeJGQJ5wlTRuMsYVZrwMvH0BI42qOjs5Y7tR7xi6O8SMRvSoVDMCgSETjn4I4DKQWsT4wjBMOXhuFtfx4TIgTSm1fysLUUvPCXs5dIvyDSjvZiJzhzYLQhl+yJGIwzgkGlWrYsIa29phU+dAvAZ/eo9RU8e8l5RQgcTrtZJ18oQJJXltyYFq8iI4bUAG2FLkgderX52hGafVjWcLCVb4WBp847B0jmkU2FIlzHQxhEbkSem1bZLT/MYWTIw+bibShXpl+mjRO08u6qEB34w7kDGqOZTC6fy+RhVOx5rHWdRDEs5YLCRWqUkZirVe3+R4/vVGdXhKjp4z04yG9K5xFKXI/5zcZSTlikkpRwouMegkb4Ea1sTxTsveB2yAVMdXZ2uXbjLZEvjW+2laLvM5duJ3KbeKBcngHzMPTjTvE7t4t+DfvIa1coBqEMBJXkrWCZ3I6v+0uUbBOpF5LBr+LpJMl+A7vcjINQLXl+C95/v6O0ak7nOMS8huNlKUyN4cQAWexNGRdCsLhElgycemQy+u4/14dQNxOi5fnW8WSplxSwmANUgdq/085HIaLIpIVdtNJVLL+V5MTAWBKWtcj3T/XCX9SoV3S0x4ZJcSqZRIHBvAkV0iouOye/ujf9NDi5Zqj2XCPPFzRCIo+Ek4Gm1KA01BMFzQZGfSUi70PaoFyGXVfv2jAPu//pRaNGns/EngsZq1wMmpbnamKgMY4zqkAJsmGypssGzSbD8NiWdcmHx7fY6kNjxkTnQluBOobvCgQz7LfEwL+vi58pQmNzYT3xpD6lqBIMBq6sNjI0dh+RGyJXcczV9dz5SkJlmD93Qh1LDNCo1X/VRxuZgJLDqBpiI42SovAZbB+/OyEfGNuKhVoIv6ySopAW9E9HUOU1NP7WX9VH1OiN7bqr/Vj6OGtng51OF0zTJP0m1eOAZ1mUQF03kFUV/bfeF5ZiqLJZ6ECPL7wT7hPn9J8Rd+t74NzPh3KHvc9ZFn3d2W4oujQbXjo6yumCzcoKvWa6FnwsDoUj6ltb/asMWBt8G3/ce8t9nxVZf3uxHa50oX0qwdDEXi93UY/6ToaQY5iuTAMBJsWn4lV5Uo3Yull+cNZ60VzWxXak3nBfYg/w522HzeayyGZzcN24+SoKuh8EqC8uWoXSTJiT8gPxiRnVv3inM43wiQA3M7Cc/F48xPvZc6nd7F42iDa2la5lR2yBLtGiuYJr5oL5Tp02Z9RFmr3MT2VkdNf2bGwgUUPoIXQT2qImEw9LtECoEEr4IRMkT53FjQr+z/GfAAMA1LhhVWn7WtAAAAAASUVORK5CYII%3D";
	$failicon = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAABFJJREFUeNq0Vl1sVEUU/mbv3bu7Ld3CYuNDDZEHXnwwfRCTBfEvkZA0qUCsMeALMTaBxDRiMMYmGH9CH9AEokRplBdRjMYQoTxgfKiKJBb505LQCiIodZWw2+7u3b137tw7nrmzP91ut8QHT/Jl7s6cOf9zzjIpJRhjkOffAaIGYJlAJgdM3AAkgAizCI/Q10rCfYQE4Swhg0CeYttHsmhBoeyaggtvA3ESPpUBrhJMo5N4hggDJKiTuElZRMMXoEsKnM4/pfPX2OChG4sr+GUvcJOMmfhDWb2BrP8EfpDCsmXKDSAI6jcjrKKM1uxtUgRO/C+zlz7eP19BpPYrbwMXfgeEGITDRyFZCu1JoExGFktAyQFsR6/FMlAo6bWtg8KasMC9fXL4mQ/ne1FXcJGEu7yPhO9DLGHAiGphDingnobn1b9d2i+7mscn75Kdav85+frm3XMV1EM01NdN35MsFm9HgvKY3gg8tElz7Xl24Sy+elivp78iHKWYkKKZGRWbdWx49FRDiLyS8xZz3XaYUW1dVXhVkBCNqApXtObJyr7Ok3D4uw0hkm/0pkzBt6KD4sld5Veztbs/0yFSUN/ziQX6bjwOktUjX1mfrilwcqVe6XpReELHF8R8cLBZyJtfasynvVsr+eGh95ILFLJ2f12BXX40CHzNIIixQBX1N5X1nqdxRxp+ihJt6yoMPeRgpoEId9fWFASetyIIZKVShK6cbBH4h170i+tbC9+1QfPl7Ppd1ws7gOGLexSLqTMRxDxisJT1/4XKzgL9QTnBlUyj5oFryjx3HB2iaiKreG+stQJ1NpeXjAxIhnBdeCbsmoKs9CbbhEPh9yAVo6jg4A939kLxVPh9in9AqyjbKFjBbzUFU5nid7GlJuVCwAutoFh+NL5AzB8Gdq6jIsg17hOv4EqBCGV0mAI3887pmoLNx66P5aP+NYsXQgsiUjQL37GaKoX6j03Y9UTTsQk/vGsJF/5SVj508dYXc3vR7IkrsweQ9BCPSP1oRkfqt5/vocbmwKNq8XIlXWUDPfXz4yNht7UklbpVwE+Z4pEPJmYnG3oR0V1/9ncd67YiaRjL9bM3GMI5ICRc12+wOBanIjEiqlVTggO9sjwoBtf7x2Z6T067lxrnwZYUHjyZX/PN4x2HkyZbCauLXrgLHshFc6xGwpKkRYbkiDeY3XmuNHBgylHP3W+cBxS/M7fFjxu/zW+/VeI/ozyNeLeJZCqKpMUQo/YR+H4Ik7pmMqo6tIkl97aRBxkaFc70C+OFHSR8VAlvbtebLL1xlEe7E+yBI6vNbekU22IytIdNUMGKVVovlaVNZT6To2kJcbkoT2w7K94fz8nv6bS08MjsM/SQV0qO+8qzFWtT7P6hVeyxniRLL7ewyoogFTorYec8XP21KM/tvya//nxanqftK+qo5UxuQRQI3E3oItD8hFUNKKFAoIGMv1S/XPRfxf9J/wowAFn7djHyydS0AAAAAElFTkSuQmCC";
	
		foreach($phpcheckdata as $fname=>$fdata){
			if(isset($fdata['adviced_fail'])){
				$passfail_color	= ($fdata['adviced_fail']) ? 'neutral' : (($fdata['passfail']) ? 'positive' : 'negative');
				$passfail_icon	= ($fdata['adviced_fail']) ? 'style/warn.png' : (($fdata['passfail']) ? $okicon : $failicon);
			}else{
				$passfail_color	= ($fdata['passfail']) ? 'positive' : 'negative';
				$passfail_icon	= (($fdata['passfail']) ? $okicon : $failicon);
			}
			$content .= '<tr>
				<td>'.$fdata['name'].'</td>
				<td class="'.$passfail_color.'">'.$fdata['installed'].'</td>
				<td class="positive">'.$fdata['required'].'</td>
				<td><img src="'.$passfail_icon.'" alt="passfail" /></td>
			</tr>';	
		}	
		$content .='</tbody>
				</table>
				<br />
			<table width="100%" border="0" cellspacing="1" cellpadding="2">
			<tr>
				<td align="right"><strong>Select Destination Folder for EQdkp Plus: </strong></td>
				<td><input type="text" name="install_dir" size="25" value="'.INSTALL_DIR.'" class="input" /></td>
			</tr>
		</table>';
				
				
		$button = (do_match_req()) ? 'Next' : 'Check again';
		$next_step = (do_match_req()) ? 'step2' : 'step1';
		
		return array('content' => $content, 'button' => $button, 'next_step' => $next_step);
}

function getCheckParams(){
		return array(
			'php'		=> array(
				'name'			=> 'PHP-Version',
				'required'		=> '5.4.0+',
				'installed'		=> phpversion(),
				'passfail'		=> (phpversion() >= '5.4.0') ? true : false,
			),
			'zLib'		=> array(
				'name'			=> 'Zip-Functions',
				'required'		=> 'Yes',
				'installed'		=> (function_exists('zip_open')) ? 'Yes' : 'No',
				'passfail'		=> (function_exists('zip_open')) ? true : false,
			),
			'safemode'	=> array(
				'name'			=> 'PHP-Safemode',
				'required'		=> 'No',
				'installed'		=> (ini_get('safe_mode') != '1') ? 'No' : 'Yes',
				'passfail'		=> (ini_get('safe_mode') != '1') ? true : false,
			),
			'setupfile'	=> array(
				'name'			=> 'Setup-Package',
				'required'		=> 'Yes',
				'installed'		=> (file_exists( SETUP_PACKAGE )) ? 'Yes' : 'No',
				'passfail'		=> (file_exists( SETUP_PACKAGE )) ? true : false,
				'ignore_asb'	=> true,
			),
			'writable'	=> array(
				'name'			=> 'Directory Writable',
				'required'		=> 'Yes',
				'installed'		=> (checkWritable()) ? 'Yes' : 'No',
				'passfail'		=> (checkWritable()) ? true : false,
				'ignore_asb'	=> true,
			),

		);
}

function do_match_req(){
	$allmatched_req		= true;
	foreach(getCheckParams() as $fname=>$fdata){
		$allmatched_req = ($fdata['passfail'] || $fdata['ignore']) ? $allmatched_req : false;
	}
	return $allmatched_req;
}

function do_match_absreq(){
	$allmatched_req		= true;
	foreach(getCheckParams() as $fname=>$fdata){
		$allmatched_req = ($fdata['passfail'] || $fdata['ignore_asb']) ? $allmatched_req : false;
	}
	return $allmatched_req;
}


function checkWritable(){
  $folder = INSTALL_ROOT.'/tmp/';
  @mkdir($folder,0755);
  @chmod($folder,0755);
  
  if (@file_exists($folder)){
    if (!( $f = @fopen($folder . 'tmp.php', 'wb'))){
      return false;
    }
    
    @fwrite($f,'EQdkp Plus');
    @fclose($f);
    
    if (!@file_exists($folder.'tmp.php') || (@file_get_contents($folder.'tmp.php') != "EQdkp Plus")){
      return false;
    }
    
    @unlink($folder.'tmp.php');
    
    if (@file_exists($folder.'tmp.php')){
      return false;
    }
    
    @rmdir($folder);
    return true;
  } else {
    return false;
  }
}

function valid_folder($path){
	$ignore = array('.', '..', '.svn', 'CVS', 'cache', 'install', 'index.html', '.htaccess', '_images', 'libraries.php');
	if (isset($path)){
		if (!in_array(basename($path), $ignore) && !is_file($path) && !@is_link($path)){
			return true;
		}
	}
	return false;
}

//Step2: unpack EQdkp Plus
function step2(){

$loadingicon = 'data:image/gif;base64,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';

	$content = '<br /><img src="'.$loadingicon.'" style="vertical-align:middle;"/> Unpacking ...';
	
	$js = '$(document).ready(function(){
		$("#submit_button").attr( "disabled", "disabled" );
		$.post("?step=ajax_unpack", { install_dir: "'.INSTALL_DIR.'"},
		   function(data) {
			if (data == "true"){
				$("#submit_button").removeAttr("disabled");
				window.location.href = ".'.INSTALL_DIR.'install/";
			 }
		  });
		});
	';
	
	return array('content' => $content, 'button' => 'Install', 'next_step' => '', 'js'	=> $js);
}

//Ajax Funktion: Unpack
function ajax_unpack(){
	// The call to exctract a path within the zip file
	extractZip(INSTALL_ROOT.'/'.SETUP_PACKAGE);
	echo "true";
	@unlink(INSTALL_ROOT.'/install.php');
	die();
}

/**
* This method unzips a directory within a zip-archive
*
* @author Florian 'x!sign.dll' Wolf
* @license LGPL v2 or later
* @link http://www.xsigndll.de
* @link http://www.clansuite.com
*/

function extractZip( $zipFile = '', $dirFromZip = '' )
{   

	@set_time_limit(0);
	
	$zipDir = INSTALL_PATH.$dirFromZip;
    $zip = zip_open($zipFile);

    if ($zip)
    {
        while ($zip_entry = zip_read($zip))
        {
            $completePath = $zipDir . dirname(zip_entry_name($zip_entry));
            $completeName = $zipDir . zip_entry_name($zip_entry);
            // Walk through path to create non existing directories
            // This won't apply to empty directories ! They are created further below

            if(!file_exists($completePath) && preg_match( '#^' . $dirFromZip .'.*#', dirname(zip_entry_name($zip_entry)) ) )
            {
                $tmp = '';
                foreach(explode('/',$completePath) AS $k)
                {
                    $tmp .= $k.'/';

                    if(!file_exists($tmp) )
                    {
						@mkdir($tmp, 0755);
                    }
                }
            }
           
            if (zip_entry_open($zip, $zip_entry, "r"))
            {
                if( preg_match( '#^' . $dirFromZip .'.*#', dirname(zip_entry_name($zip_entry)) ) )
                {

					if ($fd = @fopen($completeName, 'w+'))
                    {
                        fwrite($fd, zip_entry_read($zip_entry, zip_entry_filesize($zip_entry)));
                        fclose($fd);
                    }
                    else
                    {
                        // We think this was an empty directory
                        @mkdir($completeName, 0755);
                    }
                    zip_entry_close($zip_entry);
                }
            }
        }
        zip_close($zip);
    }
    return true;
}
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
		<link rel="stylesheet" type="text/css" media="screen" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/themes/base/jquery-ui.css" />
		<script type="text/javascript" language="javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
		<script type="text/javascript" language="javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/jquery-ui.min.js"></script>
		<style type="text/css">
			/* EQDKP+ Installer */
html {
	height: 100%;
}

body {
	background: #efefef; /* Old browsers */
	font-size: 14px;
	font-family: Tahoma,Arial,Verdana,sans-serif;
	color: #000000;
	padding:0;
  	margin:0;
	height: 100%;
}

#outerWrapper {
	background: url('background-head.svg') no-repeat scroll center 20px transparent;
	background-size: 100%;
	padding-bottom: 99px;
}

#handler {
	position: relative;
	min-height: 100%;
}

/* Header */
#header {
	font-family:'Trebuchet MS',Arial,sans-serif;
	vertical-align: middle;
	height:120px;
	margin: 0px auto;
	background: linear-gradient(to bottom,#2e78b0 0,#193759 100%);
}
#headerInner {
	background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxNS4xLjAsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+DQo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkViZW5lXzIiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4Ig0KCSB3aWR0aD0iMTM4Mi40cHgiIGhlaWdodD0iMjk1LjVweCIgdmlld0JveD0iMCAwIDEzODIuNCAyOTUuNSIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMTM4Mi40IDI5NS41OyINCgkgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+DQo8ZyBzdHlsZT0ib3BhY2l0eTowLjIyOyI+DQoJPGc+DQoJCTxwYXRoIHN0eWxlPSJmaWxsOiNGRkZGRkY7IiBkPSJNMTM5MC42LDEzYy00NC41LTAuMy0xNDMuOSwzNi45LTI4NS45LDEyMS43Yy01Ni4yLDMzLjYtMTEyLDUwLTE2NS44LDQ4LjkNCgkJCWMtOTAuOS0xLjktMTYwLjMtNTIuNS0yMDYuMi04NmMtOC42LTYuMi0xNS40LTIuNS0yMi41LTcuMmwtMTMuMS04LjljLTM4LjItMjYuNC05Ni02Ni4zLTE5MC43LTcxLjJjLTc2LjItNC0xNjUsMTUuNS0yNjQuMiw1Ny44DQoJCQljLTEyMi40LDUyLjMtMjEwLjIsNzIuOS0yNTQsNTkuN2wtNC40LDIuOWM0Ny45LDE0LjUsMTM2LjctNS44LDI2My45LTYwLjFjOTcuNC00MS42LDE4NC4xLTYwLjcsMjU3LjgtNTYuOA0KCQkJYzkxLDQuNywxNDUuMyw0Mi4yLDE4NC45LDY5LjZsMTMuMSw5YzcsNC43LDE1LDEwLjUsMjMuNiwxNi43YzQ2LjgsMzQuMSwxMTYuMyw3NiwyMTEuNCw3OGM1Ni42LDEuMiwxMTQuNi0xNS44LDE3Mi41LTUwLjMNCgkJCUMxMjUxLDUzLjMsMTM0Ny41LDE2LjIsMTM5MC41LDE2LjVMMTM5MC42LDEzeiIvPg0KCTwvZz4NCgk8Zz4NCgkJPHBhdGggc3R5bGU9ImZpbGw6I0ZGRkZGRjsiIGQ9Ik0xMzkwLjksMTcuOWMtNDQuNC0wLjItMTQzLjYsMzYuNC0yODUuOSwxMTUuM2MtMTAsNS42LTIwLjcsMTAuOS0zMi41LDE2LjINCgkJCWMtNDYuNSwyMC44LTkyLjgsMzAuMy0xMzcuNiwyOC4zYy04Ni41LTMuOS0xNTQuNS01MC0xOTkuNC04MC40Yy0xMC41LTcuMS0xOC40LTMuNi0yNi43LTguN2MtNC45LTMtMTAtNi4yLTE1LjQtOS42DQoJCQljLTI5LjUtMTguNi02Ni4yLTQxLjctMTE3LjctNTUuMmMtOTEtMjMuNy0yMDMuNC04LjMtMzM0LjEsNDUuOEMxMTguNywxMjAuNCwzMy41LDEzOS0xMS42LDEyNC45bC00LjIsMi43DQoJCQlDMzIuNSwxNDIuNywxMjAuOCwxMjQsMjQ2LjUsNzJDMzc0LjUsMTksNDg0LDMuNyw1NzEuOCwyNi42YzUwLjIsMTMuMSw4Ni40LDM1LjksMTE1LjUsNTQuMmM1LjQsMy40LDEwLjYsNi43LDE1LjUsOS42DQoJCQljOC4yLDUsMTcuMywxMS4xLDI3LjcsMTguMmM0NS43LDMxLDExMy43LDY4LjIsMjAzLjYsNzIuMmM0Ny4xLDIuMSw5NS40LTcuNywxNDMuNS0yOS4yYzEyLTUuNCwyMi45LTEwLjcsMzMuMS0xNi40DQoJCQljMTQwLjMtNzcuOSwyMzctMTE0LjMsMjgwLTExNC4xTDEzOTAuOSwxNy45eiIvPg0KCTwvZz4NCgk8Zz4NCgkJPHBhdGggc3R5bGU9ImZpbGw6I0ZGRkZGRjsiIGQ9Ik0xMzkxLjEsMjIuOGMtNDQuMy0wLjEtMTQzLjQsMzUuOC0yODUuOSwxMDguOWMtOS40LDQuOC0xOS41LDkuNC0zMi44LDE0LjkNCgkJCWMtNDUuOCwxOC44LTkxLjUsMjcuMy0xMzUuNywyNS4yYy04NS4yLTQtMTUwLjgtNDUuMy0xOTguOC03NS41Yy0xMi4xLTcuNi0yMS4zLTQuNS0zMC45LTkuN2MtNS42LTMuMS0xMS41LTYuNS0xNy43LTEwLjENCgkJCWMtMjkuOS0xNy4yLTY3LjItMzguNy0xMTctNTAuOGMtNDAuNy05LjktODYuOC0xMi4yLTEzNy02LjdjLTU5LjQsNi41LTEyNSwyNC0xOTQuOCw1MmMtMTIxLjQsNDguNi0yMDYuMiw2NS43LTI1Mi4yLDUwLjhsLTQsMi41DQoJCQljNDguOSwxNS45LDEzNi43LTEuMywyNjAuOC01MWMxMjcuNi01MS4xLDIzNi42LTY2LjIsMzI0LTQ0LjljNDguNiwxMS45LDg1LjMsMzMsMTE0LjksNTBjNi4zLDMuNiwxMi4yLDcsMTcuOCwxMC4xDQoJCQljOS40LDUuMiwxOC42LDIsMzAuNiw5LjZjNDguNywzMC42LDExNS40LDcyLjYsMjAzLjcsNzYuN2M0Ni4yLDIuMiw5My43LTYuNiwxNDEtMjZjMTMuNC01LjUsMjMuNy0xMC4yLDMzLjMtMTUuMQ0KCQkJYzE0MC43LTcyLjIsMjM3LjUtMTA3LjksMjgwLjctMTA3LjlMMTM5MS4xLDIyLjh6Ii8+DQoJPC9nPg0KCTxnPg0KCQk8cGF0aCBzdHlsZT0iZmlsbDojRkZGRkZGOyIgZD0iTTEzOTEuNCwyNy43Yy00NC4yLDAtMTQzLjIsMzUuMy0yODUuOSwxMDIuNWMtMTEuNCw1LjQtMjMsOS44LTMzLDEzLjUNCgkJCWMtNDUuMSwxNi45LTkwLDI0LjQtMTMzLjYsMjIuMmMtODQuMS00LjEtMTUwLjEtNDIuNy0xOTguMy03MC44Yy0xMy03LjYtMjQuMS01LjEtMzQuOS0xMC41Yy02LjQtMy4yLTEzLTYuNy0yMC0xMC40DQoJCQljLTMwLjMtMTUuOS02OC4xLTM1LjctMTE2LjMtNDYuN2MtNjEuNC0xNC0xNjguNS0xNy41LTMyOS42LDQ0LjhjLTEyMCw0Ni40LTIwNC41LDYyLTI1MS4yLDQ2LjRsLTMuOCwyLjINCgkJCWM1MC4yLDE2LjgsMTM0LjksMS42LDI1OS4yLTQ2LjVjMTU4LjUtNjEuMywyNjIuOS01OCwzMjIuNi00NC40YzQ3LDEwLjcsODQuMywzMC4zLDExNC4zLDQ2YzcuMSwzLjcsMTMuNyw3LjIsMjAuMiwxMC40DQoJCQljMTAuNiw1LjMsMjEuMSwyLjQsMzQuNiwxMC4zYzQ4LjksMjguNiwxMTUuOSw2Ny43LDIwMi42LDcxLjljNDUuMywyLjIsOTEuOS01LjUsMTM4LjQtMjIuOWMxMC4yLTMuOCwyMS45LTguMywzMy41LTEzLjgNCgkJCWMxNDEuMS02Ni40LDIzOC4xLTEwMS42LDI4MS4zLTEwMS42TDEzOTEuNCwyNy43eiIvPg0KCTwvZz4NCgk8Zz4NCgkJPHBhdGggc3R5bGU9ImZpbGw6I0ZGRkZGRjsiIGQ9Ik0xMzkxLjYsMzIuNmMtNDQuMSwwLjEtMTQzLDM0LjctMjg1LjksOTYuMWMtMTEsNC43LTIyLjIsOC41LTMzLDEyLjJsLTAuMiwwLjENCgkJCWMtMTQ4LjksNTAuNC0yNTcuNS04LjQtMzI5LjQtNDcuM2MtMTQuNC03LjgtMjYuOS01LjUtMzguOS0xMC45Yy03LjEtMy4yLTE0LjUtNi43LTIyLjMtMTAuNWMtMzIuMy0xNS41LTY5LTMzLTExNS42LTQyLjcNCgkJCWMtMzkuNS04LjMtODUuMi05LjYtMTM1LjgtMy45Yy01OS4xLDYuNy0xMjMuNiwyMi45LTE5MS43LDQ4LjJDMTIwLjMsMTE4LDM2LjEsMTMyLjEtMTEuNSwxMTUuN2wtMy41LDINCgkJCWM1MC43LDE3LjQsMTM1LDMuNywyNTcuNi00MmMxNTguMS01OC45LDI2MS45LTU2LjQsMzIxLjItNDRjNDUuNiw5LjYsODEuOSwyNi45LDExMy44LDQyLjJjNy44LDMuNywxNS4yLDcuMywyMi40LDEwLjUNCgkJCWMxMS45LDUuNCwyNC4yLDMsMzguNSwxMC44QzgxMS45LDEzNSw5MjIuNywxOTQuOSwxMDc2LDE0M2wwLjItMC4xYzExLTMuNywyMi4zLTcuNiwzMy41LTEyLjRjMTQxLjUtNjAuNywyMzguNy05NS4yLDI4MS45LTk1LjQNCgkJCUwxMzkxLjYsMzIuNnoiLz4NCgk8L2c+DQoJPGc+DQoJCTxwYXRoIHN0eWxlPSJmaWxsOiNGRkZGRkY7IiBkPSJNMTM5MS45LDM3LjVjLTQ0LDAuMy0xNDIuNywzNC4yLTI4NS45LDg5LjdjLTkuOSwzLjgtMjAuMiw2LjktMzAuMSw5LjlsLTMuNCwxDQoJCQljLTE0Ni4yLDQ0LjItMjUwLjctOC0zMjctNDYuMmMtMTUuOC03LjktMjkuNi01LjctNDIuOC0xMS4yYy03LjgtMy4yLTE1LjktNi43LTI0LjQtMTAuNGMtMzIuNy0xNC4yLTY5LjktMzAuMy0xMTUuMS0zOQ0KCQkJQzUxMy45LDIyLDQwNi4yLDE1LjEsMjM4LDc1LjJjLTExOC44LDQyLjUtMjAwLjQsNTQuNy0yNDkuNCwzNy41bC0zLjMsMS44YzUxLjMsMTgsMTM1LDUuOCwyNTYtMzcuNQ0KCQkJYzE2Ni01OS40LDI3MS43LTUyLjcsMzE5LjgtNDMuNWM0NC40LDguNSw4MS4xLDI0LjUsMTEzLjUsMzguNWM4LjUsMy43LDE2LjYsNy4yLDI0LjUsMTAuNGMxMy4xLDUuMywyNi43LDMuMSw0Mi41LDExDQoJCQljMzcuMSwxOC42LDgzLjMsNDEuNywxMzguNSw1NC4zYzY1LjEsMTQuOSwxMjksMTIuNCwxOTUuNC03LjdsMy40LTFjMTAtMywyMC40LTYuMiwzMC42LTEwLjFjMTQyLTU1LDIzOS4zLTg4LjksMjgyLjUtODkuMQ0KCQkJTDEzOTEuOSwzNy41eiIvPg0KCTwvZz4NCgk8Zz4NCgkJPHBhdGggc3R5bGU9ImZpbGw6I0ZGRkZGRjsiIGQ9Ik0xMzkyLjIsNDIuNGMtNDMuOSwwLjQtMTQyLjQsMzMuNi0yODYsODMuM2MtOS4zLDMuMi0xOC45LDUuNy0yOC4yLDguMmwtNS42LDEuNQ0KCQkJYy0xNDMuMywzOC4yLTI0OC0xMC4xLTMyNC40LTQ1LjNjLTE3LjMtOC0zMi40LTUuOC00Ni45LTExLjJjLTguNS0zLjEtMTcuMS02LjUtMjYuMi0xMC4xYy0zMy4yLTEzLTcwLjgtMjcuOC0xMTQuOS0zNS40DQoJCQljLTQ4LjctOC40LTE1NS40LTE0LTMyMy4xLDQzLjNjLTExNy40LDQwLjItMTk4LjcsNTAuOS0yNDguNSwzM2wtMywxLjZjNTEuOCwxOC43LDEzNS4xLDcuOSwyNTQuNC0zMw0KCQkJYzE2NS44LTU2LjcsMjcwLjctNTEuMywzMTguNC00M2M0My4zLDcuNSw4MC42LDIyLjEsMTEzLjUsMzUuMWM5LjEsMy42LDE3LjgsNywyNi4zLDEwLjFjMTQuMyw1LjMsMjkuMywzLjEsNDYuNSwxMQ0KCQkJYzM3LjMsMTcuMiw4My44LDM4LjcsMTM4LjQsNTAuNmM2NC4yLDE0LDEyNi45LDEyLjQsMTkxLjgtNC45YzAsMCw1LjUtMS41LDUuNS0xLjVjOS40LTIuNSwxOS4yLTUsMjguNy04LjMNCgkJCUMxMjUxLjYsNzgsMTM0OSw0NC45LDEzOTIuMyw0NC41TDEzOTIuMiw0Mi40eiIvPg0KCTwvZz4NCgk8Zz4NCgkJPHBhdGggc3R5bGU9ImZpbGw6I0ZGRkZGRjsiIGQ9Ik0xMzkyLjQsNDcuM2MtNDMuNywwLjUtMTQyLDMzLTI4Niw3Ni45Yy04LjgsMi43LTE3LjksNC43LTI2LjgsNi43bC03LjIsMS42DQoJCQlDOTMyLjIsMTY0LjksODI3LjMsMTIwLjQsNzUwLjgsODhjLTE4LjctNy45LTM1LjItNS44LTUxLTExYy05LTMtMTguMS02LjItMjcuNy05LjZjLTMzLjctMTEuOS03Mi0yNS41LTExNS0zMi4xDQoJCQljLTgxLjgtMTIuNi0xOTguNywzLTMyMC45LDQyLjhjLTExNiwzNy44LTE5Nyw0Ny4xLTI0Ny42LDI4LjVsLTIuNywxLjRjNTIuNCwxOS4yLDEzNS4xLDkuOSwyNTIuOC0yOC41DQoJCQljMTIxLTM5LjUsMjM2LjYtNTUsMzE3LTQyLjVjNDIuMyw2LjUsODAuMywyMCwxMTMuNywzMS44YzkuNiwzLjQsMTguNyw2LjYsMjcuOCw5LjdjMTUuNiw1LjIsMzIsMyw1MC42LDEwLjkNCgkJCWM3Ny41LDMyLjgsMTgzLjYsNzcuOCwzMjYuNSw0NC45bDcuMS0xLjZjOS0yLDE4LjMtNC4xLDI3LjMtNi44YzE0My4xLTQzLjYsMjQwLjUtNzYuMSwyODMuNy03Ni42TDEzOTIuNCw0Ny4zeiIvPg0KCTwvZz4NCgk8Zz4NCgkJPHBhdGggc3R5bGU9ImZpbGw6I0ZGRkZGRjsiIGQ9Ik0xMzkyLjcsNTIuMmMtNDMuNSwwLjYtMTQxLjYsMzIuNC0yODYuMSw3MC41Yy04LjQsMi4yLTE3LjMsMy45LTI1LjgsNS41bC04LjQsMS42DQoJCQljLTEzNi45LDI2LjctMjQyLTE0LjEtMzE4LjctNDMuOWMtMjAuMy03LjktMzguMy01LjctNTUuNC0xMC44Yy05LjQtMi44LTE4LjgtNS44LTI4LjctOC45Yy0zNi4xLTExLjQtNzMuNC0yMy4zLTExNS42LTI5DQoJCQljLTM2LjUtNC45LTgyLjYtNC40LTEzMy4zLDEuN2MtNTguMiw2LjktMTIwLjYsMjAuNi0xODUuNCw0MC42QzEyMC43LDExNC45LDQwLDEyMi44LTExLjUsMTAzLjVsLTIuNCwxLjMNCgkJCWM1Mi45LDE5LjgsMTM1LjEsMTIsMjUxLjItMjRjMTcyLTUzLjIsMjc4LTQ3LjEsMzE1LjUtNDIuMWM0MS42LDUuNiw3OC42LDE3LjQsMTE0LjUsMjguN2M5LjksMy4yLDE5LjMsNi4xLDI4LjgsOQ0KCQkJYzE2LjksNS4xLDM0LjgsMi44LDU1LDEwLjdjNzcuNSwzMC4xLDE4My42LDcxLjMsMzIyLjYsNDQuMmw4LjQtMS42YzguNy0xLjYsMTcuNi0zLjMsMjYuMy01LjZjMTQzLjgtMzcuOSwyNDEuMS02OS43LDI4NC4zLTcwLjMNCgkJCUwxMzkyLjcsNTIuMnoiLz4NCgk8L2c+DQoJPGc+DQoJCTxwYXRoIHN0eWxlPSJmaWxsOiNGRkZGRkY7IiBkPSJNMTM5Myw1Ny4xYy00My4zLDAuNy0xNDEsMzEuOC0yODYuMiw2NC4yYy04LjIsMS44LTE2LjcsMy4xLTI1LDQuM2MtMy4xLDAuNS02LjQsMC45LTkuNSwxLjQNCgkJCWMtNTkuMSw5LjQtMTE3LjMsOC4zLTE3OC0zLjNjLTUyLjQtMTAtOTcuNS0yNi0xMzcuMy00MC4xYy0yMi03LjgtNDEuNi01LjUtNjAuMS0xMC41Yy05LjYtMi42LTE5LTUuMi0yOS04DQoJCQljLTM3LTEwLjUtNzUuMy0yMS4zLTExNi45LTI2Yy0zNS44LTQuMS04MS43LTMuMS0xMzIuNywzLjFjLTU3LjksNy0xMTkuOCwyMC0xODMuOSwzOC43Yy0xMTMuMiwzMy4xLTE5My42LDM5LjUtMjQ1LjgsMTkuNg0KCQkJbC0yLjEsMS4xYzUzLjQsMjAuNCwxMzUuMSwxNCwyNDkuNy0xOS41YzExOS42LTM0LjksMjM3LTUwLjUsMzE0LjEtNDEuNmM0MS4xLDQuNyw3OS4yLDE1LjUsMTE2LDI1LjljOS42LDIuNywxOS40LDUuNSwyOSw4LjENCgkJCWMxOC4zLDQuOSwzNy45LDIuNiw1OS44LDEwLjRjNDAsMTQuMiw4NS4zLDMwLjIsMTM4LjEsNDAuM2M2MS41LDExLjcsMTIwLjQsMTIuOCwxODAuMywzLjNjMy4xLTAuNSw2LjMtMSw5LjQtMS40DQoJCQljOC40LTEuMiwxNy0yLjUsMjUuNC00LjRjMTQ0LjYtMzIuMiwyNDEuOS02My4zLDI4NC45LTY0TDEzOTMsNTcuMXoiLz4NCgk8L2c+DQoJPGc+DQoJCTxwYXRoIHN0eWxlPSJmaWxsOiNGRkZGRkY7IiBkPSJNMTM5My4zLDYyYy00Mi45LDAuOC0xNDAuMSwzMS4yLTI4Ni4zLDU3LjhjLTgsMS41LTE2LjMsMi40LTI0LjQsMy4zYy0zLjQsMC40LTYuOSwwLjgtMTAuNCwxLjINCgkJCWMtNTcuMyw3LjEtMTE0LjMsNS4yLTE3NC4xLTUuOGMtNTEuOC05LjUtOTcuMS0yNC4xLTEzNy4xLTM3Yy0yNC03LjctNDUuNC01LjMtNjUuNS0xMC4xYy05LjQtMi4yLTE4LjktNC42LTI4LjItNi45DQoJCQlDNjI5LDU0LjgsNTg5LjQsNDQuOSw1NDgsNDFjLTM1LjEtMy4zLTgwLjgtMS44LTEzMi4xLDQuNWMtNTcuNSw3LTExOC45LDE5LjQtMTgyLjQsMzYuOGMtMTExLjksMzAuNy0xOTIsMzUuNi0yNDUsMTUuMWwtMS43LDAuOQ0KCQkJYzI1LjcsOS45LDU4LjMsMTQsOTcuMSwxMi4yYzQxLjktMiw5Mi44LTExLjIsMTUxLTI3LjFDMzU0LjksNTAuNCw0NzEuOCwzNSw1NDcuNSw0Mi4xYzQxLDMuOSw4MC40LDEzLjcsMTE4LjYsMjMuMg0KCQkJYzkuMywyLjMsMTguOCw0LjcsMjguMiw2LjljMTkuOSw0LjgsNDEuMywyLjQsNjUuMiwxMC4xYzQwLjEsMTIuOSw4NS42LDI3LjYsMTM3LjcsMzcuMWM2MC41LDExLjEsMTE4LDEzLDE3NS45LDUuOQ0KCQkJYzMuNC0wLjQsNi45LTAuOCwxMC4zLTEuMmM4LjItMC45LDE2LjYtMS44LDI0LjctMy4zYzE0NS44LTI2LjUsMjQyLjctNTYuOSwyODUuNC01Ny43TDEzOTMuMyw2MnoiLz4NCgk8L2c+DQoJPGc+DQoJCTxwYXRoIHN0eWxlPSJmaWxsOiNGRkZGRkY7IiBkPSJNMTM5My41LDY2LjljLTQyLjIsMC45LTEzOC43LDMwLjYtMjg2LjQsNTEuNWMtNy44LDEuMS0xNiwxLjctMjMuOSwyLjNjLTMuNiwwLjMtNy40LDAuNS0xMS4xLDAuOQ0KCQkJYy0xMjQuNSwxMC45LTIyNS4zLTE4LjYtMzA2LjMtNDIuMmMtMjYuMi03LjctNDkuOC01LjItNzEuNy05LjlsLTI1LjgtNS42QzYyOC4xLDU1LjEsNTg2LjYsNDYsNTQ1LDQyLjkNCgkJCWMtMzQuNC0yLjUtNzkuOS0wLjUtMTMxLjUsNS45Yy01Ni4yLDYuOS0xMTguNywxOS0xODAuOSwzNC45QzEyMiwxMTEuOSw0Mi4yLDExNS40LTExLjUsOTQuM2wtMS40LDAuNw0KCQkJYzI2LjEsMTAuMyw1OS4xLDE0LjgsOTgsMTMuNWM0MS41LTEuNCw5MS41LTkuNSwxNDguNC0yNGMxMjAuOS0zMC45LDIzNy4yLTQ2LjEsMzExLTQwLjdjNDEuMywzLDgyLjcsMTIuMSwxMjIuNywyMC44bDI1LjgsNS42DQoJCQljMjEuOCw0LjYsNDUuMywyLjIsNzEuNSw5LjhjNDAuMiwxMS43LDg1LjcsMjUsMTM2LjksMzRjNTkuMywxMC40LDExNS4yLDEzLjIsMTcwLjksOC4zYzMuNy0wLjMsNy40LTAuNiwxMS0wLjkNCgkJCWM4LTAuNiwxNi4yLTEuMiwyNC4yLTIuM2MxNDcuNS0yMC44LDI0My43LTUwLjUsMjg1LjktNTEuNEwxMzkzLjUsNjYuOXoiLz4NCgk8L2c+DQoJPGc+DQoJCTxwYXRoIHN0eWxlPSJmaWxsOiNGRkZGRkY7IiBkPSJNMTM5My44LDcxLjhjLTMzLDAuOC0xMzAsMjkuNC0yODYuNiw0NS4xYy03LjgsMC44LTE1LjgsMS4xLTIzLjYsMS40Yy0zLjgsMC4xLTcuOCwwLjMtMTEuNywwLjUNCgkJCWMtMTE5LjQsNi4xLTIxOS40LTIwLjMtMjk5LjktNDEuNWMtMjktNy43LTU1LjItNS4yLTc5LjMtOS43bC0yMS00Yy00NC45LTguNS04Ny4yLTE2LjUtMTI5LjctMTguN2MtMzMuNS0xLjctNzguOCwwLjgtMTMwLjksNy4zDQoJCQljLTU1LjUsNi45LTExNy41LDE4LjMtMTc5LjUsMzIuOWMtNTUuNSwxMy4xLTEwNC4zLDIwLjEtMTQ1LjIsMjAuOGMtMzguOCwwLjctNzEuNy00LjMtOTcuOS0xNC43bC0xLDAuNQ0KCQkJYzI2LjUsMTAuNiw1OS44LDE1LjYsOTksMTQuOWM0MS4xLTAuNyw5MC4yLTcuOCwxNDUuOS0yMC45YzEyMC41LTI4LjQsMjM5LjEtNDMuOCwzMDkuNS00MC4yYzQyLjIsMi4yLDg0LjUsMTAuMiwxMjkuMiwxOC43DQoJCQlsMjEuMSw0YzI0LDQuNSw1MC4yLDIsNzkuMSw5LjZjODAuNiwyMS4zLDE4MSw0Ny43LDMwMC45LDQxLjZjMy45LTAuMiw3LjgtMC4zLDExLjYtMC41YzcuOC0wLjMsMTYtMC42LDIzLjgtMS40DQoJCQljMTU2LjUtMTUuNywyNTMuNC00NC4zLDI4Ni4zLTQ1LjFMMTM5My44LDcxLjh6Ii8+DQoJPC9nPg0KCTxnPg0KCQk8cGF0aCBzdHlsZT0iZmlsbDojRkZGRkZGOyIgZD0iTTEzOTQuMSw3Ni43Yy0yOS4yLDAuNy0xMTguOCwyOC43LTI4Ni44LDM4LjhjLTcuNywwLjUtMTUuNywwLjUtMjMuNCwwLjVjLTQsMC04LDAtMTIuMSwwLjENCgkJCWMtMTEzLjQsMS43LTIxMi4xLTIxLjctMjkxLjQtNDAuNmMtMzIuNS03LjctNjItNS40LTg4LjktOS43bC0xMi42LTJjLTQ5LjEtOC05NS41LTE1LjUtMTM5LjctMTYuOGMtMzIuNi0wLjktNzcuNiwyLjEtMTMwLjQsOC44DQoJCQljLTU0LjUsNi45LTExNiwxNy42LTE3OCwzMWMtNTQuMiwxMS43LTEwMi4zLDE3LjctMTQyLjgsMTcuN2MtMTkuOCwwLTM4LjItMS4zLTU0LjgtNGMtMTYuMy0yLjctMzEuMy02LjctNDQuNS0xMi4xbC0wLjcsMC4zDQoJCQljMTMuMyw1LjQsMjguNSw5LjUsNDQuOSwxMi4yYzE2LjcsMi43LDM1LjMsNC4xLDU1LjIsNC4xYzQwLjctMC4xLDg4LjktNi4xLDE0My4zLTE3LjhjNjItMTMuMywxMjMuNS0yNCwxNzcuOS0zMQ0KCQkJYzUyLjYtNi43LDk3LjYtOS43LDEzMC04LjhjNDQuMSwxLjMsOTAuNCw4LjgsMTM5LjUsMTYuOGwxMi42LDJjMjYuOCw0LjMsNTYuMywyLDg4LjcsOS43Yzc5LjQsMTguOSwxNzguMiw0Mi40LDI5Miw0MC42DQoJCQljNC0wLjEsOC4xLTAuMSwxMi4xLTAuMWM3LjgsMCwxNS44LDAsMjMuNi0wLjVjMTY4LTEwLjEsMjU3LjUtMzgsMjg2LjctMzguOEwxMzk0LjEsNzYuN3oiLz4NCgk8L2c+DQoJPGc+DQoJCTxwYXRoIHN0eWxlPSJmaWxsOiNGRkZGRkY7IiBkPSJNMTM5NC40LDgxLjZjLTEwLjUsMC4zLTI1LjMsMzUuNS0zOS44LDM1LjljLTU0LjUsMS42LTEzOC4yLTUuNi0yNDcuMy0zLjUNCgkJCWMtNy43LDAuMi0xNS4zLTAuMS0yMy40LTAuNGMtNC4xLTAuMS04LjItMC4zLTEyLjQtMC40Yy00Ny40LTEtOTYuNS01LjYtMTUwLjItMTQuMmMtNDYuOC03LjQtOTAuOC0xNi45LTEyOS42LTI1LjINCgkJCWMtMzcuMi04LTcxLjItNS44LTEwMS42LTEwLjFjLTI2LjUtMy43LTUzLjgtNy41LTgwLjItMTAuM2MtMjguOS0zLjEtNTIuMy00LjYtNzMuNy00LjhjLTMxLjQtMC4yLTc2LjMsMy4zLTEyOS44LDEwLjINCgkJCWMtNTIuOCw2LjgtMTEzLjgsMTYuOC0xNzYuNywyOWMtMjYuNiw1LjItNTEuOSw5LTc1LjMsMTEuNGMtMjMuMiwyLjQtNDUuMiwzLjUtNjUuMiwzLjJjLTE5LjktMC4zLTM4LjUtMS45LTU1LjMtNC44DQoJCQljLTE2LjctMi45LTMyLTcuMi00NS41LTEyLjdsLTAuNCwwLjJjMTMuNiw1LjYsMjksOS45LDQ1LjcsMTIuOGMxNi44LDIuOSwzNS41LDQuNiw1NS40LDQuOGMyMC4xLDAuMyw0Mi0wLjgsNjUuMy0zLjINCgkJCWMyMy40LTIuNCw0OC43LTYuMyw3NS40LTExLjVDMjkyLjcsNzYsMzUzLjgsNjYsNDA2LjUsNTkuMkM0NjAsNTIuMyw1MDQuOSw0OC44LDUzNi4yLDQ5YzIxLjQsMC4xLDQ0LjgsMS42LDczLjYsNC43DQoJCQljMjYuNCwyLjgsNTMuNyw2LjYsODAuMiwxMC4zYzMwLjQsNC4yLDY0LjMsMi4xLDEwMS41LDEwYzM4LjgsOC4zLDgyLjksMTcuNywxMjkuNiwyNS4yYzUzLjcsOC42LDEwMi45LDEzLjIsMTUwLjMsMTQuMg0KCQkJYzQuMiwwLjEsOC4zLDAuMiwxMi40LDAuNGM3LjcsMC4zLDE1LjcsMC42LDIzLjUsMC40YzEwOS4yLTIuMSwxOTIuOCw1LjEsMjQ3LjMsMy41YzE0LjUtMC40LDI5LjMtMzUuNiwzOS44LTM1LjlMMTM5NC40LDgxLjZ6Ig0KCQkJLz4NCgk8L2c+DQoJPGc+DQoJCTxwYXRoIHN0eWxlPSJmaWxsOiNGRkZGRkY7IiBkPSJNMTM5NC43LDg2LjJjLTMzLjQsMS0xMzguNywyOS4yLTI4Ny40LDI2LjFjLTcuOC0wLjItMTUuNC0wLjctMjMuNS0xLjNjLTQuMS0wLjMtOC4zLTAuNi0xMi41LTAuOA0KCQkJYy05Ny42LTUuNi0xODUuMy0yMi42LTI2Mi42LTM3LjVjLTQzLjktOC41LTg0LjItNi44LTExOS43LTExYy01Mi42LTYuMi0xMDgtMTIuMi0xNTUuNy0xMS4zYy03Mi43LDEuMy0yMDUuOCwyMS44LTMwNC43LDM4LjgNCgkJCWMtNTEuOCw4LjktOTguMSwxMi44LTEzNy44LDExLjZjLTQwLTEuMi03NC4zLTcuNi0xMDEuOS0xOWwtMC43LDAuM2MyNy44LDExLjUsNjIuMywxNy45LDEwMi42LDE5LjENCgkJCWMzOS44LDEuMiw4Ni40LTIuNywxMzguMy0xMS43Yzk4LjgtMTcsMjMxLjgtMzcuNCwzMDQuNC0zOC43QzU4MC44LDUwLDYzNi4xLDU2LDY4OC43LDYyLjJjMzUuNCw0LjIsNzUuNywyLjUsMTE5LjUsMTENCgkJCWM3Ny40LDE1LDE2NS4yLDMxLjksMjYyLjksMzcuNmM0LjIsMC4yLDguNCwwLjUsMTIuNSwwLjhjOC4xLDAuNiwxNS44LDEuMSwyMy42LDEuM2MxNDguOCwzLjEsMjU0LTI1LjEsMjg3LjUtMjYuMUwxMzk0LjcsODYuMnoiDQoJCQkvPg0KCTwvZz4NCgk8Zz4NCgkJPHBhdGggc3R5bGU9ImZpbGw6I0ZGRkZGRjsiIGQ9Ik0xMzk0LjksOTAuOWMtODUuMSwyLjctMTg0LjIsMjYuMS0yODcuNiwxOS44Yy03LjktMC41LTE1LjctMS4zLTI0LTIuMmMtNC0wLjQtOC4xLTAuOC0xMi4yLTEuMg0KCQkJQzk4NS44LDk5LjIsOTA2LDg1LjIsODM1LjcsNzIuOGMtNTYuOC0xMC0xMDQuNi05LTE0Ny44LTEzLjJjLTUwLjMtNC45LTEwOC40LTkuNi0xNTcuNS03LjVDNDY4LDU0LjgsMzU0LjIsNzEuNiwyMzMuOCw4OS41DQoJCQlsLTYuNCwwLjlDMTI0LjUsMTA1LjYsNDQuNCwxMDEuNy0xMC44LDc4LjZsLTEuMSwwLjVjNTUuNiwyMy4yLDEzNi4zLDI3LjMsMjM5LjgsMTEuOWw2LjQtMC45YzEyMC40LTE3LjgsMjM0LjEtMzQuNywyOTYuMi0zNy4zDQoJCQljNDguOC0yLjEsMTA2LjgsMi42LDE1Nyw3LjVjNDMuMSw0LjIsOTAuOSwzLjEsMTQ3LjYsMTMuMWM3MC40LDEyLjQsMTUwLjMsMjYuNSwyMzUuNywzNC41YzQuMSwwLjQsOC4yLDAuOCwxMi4yLDEuMg0KCQkJYzguMywwLjksMTYuMSwxLjcsMjQuMSwyLjJjMTAzLjUsNi4zLDIwMi42LTE3LjEsMjg3LjktMTkuOEwxMzk0LjksOTAuOXoiLz4NCgk8L2c+DQoJPGc+DQoJCTxwYXRoIHN0eWxlPSJmaWxsOiNGRkZGRkY7IiBkPSJNMTM5NS4yLDk1LjVjLTU3LjQsMi0xNTYuNywyNi43LTI4Ny43LDEzLjVjLTguMy0wLjgtMTYuNC0yLTI1LTMuMmwtMTEuNC0xLjYNCgkJCWMtNjQuOS04LjUtMTI1LjctMTguNi0xODQuNS0yOC4zQzgwOS4yLDYzLjMsNzQzLjYsNjIsNjg2LjYsNTcuNmMtNjguMi01LjMtMTE4LjgtNi41LTE1OS4yLTMuN2MtMzksMi43LTk4LjUsMTAuOC0xNjcuNCwyMC4yDQoJCQljLTQzLDUuOS04Ny40LDExLjktMTMzLjcsMTcuNkMxMjQuNSwxMDQuMiw0NC44LDk4LjctMTAuNSw3NS40bC0xLjQsMC43YzU1LjksMjMuNiwxMzYuMiwyOS4xLDIzOC44LDE2LjUNCgkJCWM0Ni4zLTUuNyw5MC44LTExLjgsMTMzLjgtMTcuNmM2OC44LTkuNCwxMjguMi0xNy41LDE2Ny4xLTIwLjFjNDAuMi0yLjgsOTAuNi0xLjYsMTU4LjYsMy43YzU2LjgsNC40LDEyMi4zLDUuNywxOTkuNSwxOC41DQoJCQljNTguOCw5LjcsMTE5LjcsMTkuNywxODQuNywyOC4zbDExLjQsMS42YzguNiwxLjIsMTYuOCwyLjMsMjUuMiwzLjJjMTMxLjMsMTMuMiwyMzAuOS0xMS41LDI4OC40LTEzLjVMMTM5NS4yLDk1LjV6Ii8+DQoJPC9nPg0KCTxnPg0KCQk8cGF0aCBzdHlsZT0iZmlsbDojRkZGRkZGOyIgZD0iTTEzOTUuNCwxMDAuMmMtNTYuOCwyLjEtMTU1LjcsMjUuOC0yODcuOSw3LjJjLTktMS4zLTE4LTIuOC0yNy40LTQuNGwtOS4zLTEuNmwtNDUuOS03LjgNCgkJCWMtMTI4LTIxLjgtMjQ3LjctMzIuNi0zMzkuNi0zOC4xYy02NS4zLTMuOS0xMTkuNS0zLjktMTYxLDAuMWMtMjYuNCwyLjUtNjIuOCw3LjUtMTA0LjksMTMuM2MtNTUuNyw3LjctMTI1LDE3LjItMTk0LjMsMjQNCgkJCWMtMTAwLjksOS44LTE4MC4xLDIuOS0yMzUuNC0yMC43TC0xMiw3M0M0NCw5Ni44LDEyMy45LDEwMy45LDIyNS44LDk0YzY5LjQtNi43LDEzOC44LTE2LjMsMTk0LjUtMjRjNDItNS44LDc4LjMtMTAuOCwxMDQuNi0xMy4zDQoJCQljNDEuMi0zLjksOTUuMS0zLjksMTYwLjEtMC4xYzkxLjUsNS41LDIxMS4xLDE2LjMsMzM5LDM4bDQ1LjksNy44bDkuMiwxLjZjOS41LDEuNiwxOC41LDMuMiwyNy42LDQuNQ0KCQkJYzEzMi42LDE4LjcsMjMxLjgtNSwyODguOS03LjFMMTM5NS40LDEwMC4yeiIvPg0KCTwvZz4NCgk8Zz4NCgkJPHBhdGggc3R5bGU9ImZpbGw6I0ZGRkZGRjsiIGQ9Ik0xMzk1LjcsMTA0LjljLTU2LjQsMi4yLTE1NSwyNC45LTI4Ny45LDAuOWMtMTEuNS0yLjEtMjMuMi00LjUtMzUuNC03bC0xLjUtMC4zDQoJCQljLTE1Mi42LTMxLjUtMjg1LjUtNDAuOC0zODYuNi00NUM2MjAuMyw1MC43LDU2NCw1Miw1MjEuMyw1Ny4yYy0xNy44LDIuMi00MC4yLDUuNS02Ni4xLDkuM2MtNjEuOSw5LjEtMTQ2LjcsMjEuNi0yMzEsMjcuNQ0KCQkJQzEyNCwxMDEsNDUuMiw5Mi42LTkuOCw2OC45bC0yLjIsMWM1NS44LDI0LDEzNS40LDMyLjYsMjM2LjcsMjUuNWM4NC42LTUuOSwxNjkuNS0xOC40LDIzMS41LTI3LjVjMjUuOS0zLjgsNDguMi03LjEsNjYtOS4zDQoJCQljNDIuMy01LjIsOTguMi02LjUsMTYxLjctMy44YzEwMC44LDQuMiwyMzMuMywxMy40LDM4NS42LDQ0LjlsMS41LDAuM2MxMi4zLDIuNSwyNCw0LjksMzUuNiw3YzEzMy41LDI0LjEsMjMyLjcsMS40LDI4OS40LTAuOA0KCQkJTDEzOTUuNywxMDQuOXoiLz4NCgk8L2c+DQoJPGc+DQoJCTxwYXRoIHN0eWxlPSJmaWxsOiNGRkZGRkY7IiBkPSJNMTM5NS45LDEwOS41Yy01Ny4xLDIuMy0xNjguOSwyNC4xLTMyNS4yLTE0LjFDODI4LjcsMzYuMiw2MjIuNCw0My4zLDUxOC4zLDU5DQoJCQljLTExLjIsMS43LTI0LjEsMy44LTM5LjEsNi4yYy0xMjQuNCwyMC4zLTM1Niw1OC00ODguNiwwLjVsLTIuNiwxLjJjMTM0LjMsNTguMiwzNjcuMywyMC4zLDQ5Mi41LTAuMWMxNC45LTIuNCwyNy44LTQuNSwzOS02LjINCgkJCUM2MjIuOSw0NC45LDgyOCwzNy45LDEwNjksOTYuOWMxNjAuNCwzOS4yLDI3NS4yLDE2LjQsMzI3LjMsMTQuM0wxMzk1LjksMTA5LjV6Ii8+DQoJPC9nPg0KPC9nPg0KPGcgc3R5bGU9Im9wYWNpdHk6MC4zODsiPg0KCTxnPg0KCQk8cGF0aCBzdHlsZT0iZmlsbDojRkZGRkZGOyIgZD0iTS03LjIsMTYyLjZMLTcuMiwxNjIuNmMzLjMtMC42LDMzNC45LTYzLjIsNzgzLjgsMzIuOGM3NS43LDE2LjIsMTQyLjUsMjkuNywyMDQuMyw0MS4yDQoJCQljNTkuNCwxMS4xLDExMi41LDIwLjEsMTYyLjIsMjcuNWM0Ni44LDcsODkuOCwxMi40LDEzMS4zLDE2LjZjMzcuOCwzLjgsNzQuMyw2LjYsMTExLjYsOC40bDAuMS0wLjJjLTM3LjItMS45LTczLjctNC42LTExMS40LTguNA0KCQkJYy00MS41LTQuMi04NC40LTkuNi0xMzEuMi0xNi42Yy00OS43LTcuNC0xMDIuOC0xNi40LTE2Mi4yLTI3LjVjLTYxLjgtMTEuNi0xMjguNi0yNS0yMDQuMy00MS4yDQoJCQljLTU0LjItMTEuNi0xMDkuNC0yMS41LTE2NC4yLTI5LjNjLTQ5LjgtNy4yLTEwMC4zLTEyLjgtMTUwLjEtMTYuOGMtODMuOS02LjgtMTY3LjUtOS0yNDguNC02LjdjLTMxLjgsMC45LTYzLjIsMi41LTkzLjUsNC44DQoJCQljLTI0LjIsMS44LTQ3LjcsNC4xLTY5LjcsNi43Yy0zNy42LDQuNS01OC41LDguNS01OC43LDguNUwtNy4yLDE2Mi42eiIvPg0KCTwvZz4NCgk8Zz4NCgkJPHBhdGggc3R5bGU9ImZpbGw6I0ZGRkZGRjsiIGQ9Ik0tNy4xLDE2NS42YzMuMi0wLjgsMzQ2LjgtNjkuOCw3OTIsMjcuMWM3NC44LDE2LjQsMTQwLjQsMzAsMjAwLjYsNDEuNQ0KCQkJYzU4LjgsMTEuMywxMTEuMSwyMC40LDE1OS43LDI3LjhjODkuMiwxMy42LDE2NS44LDIxLjMsMjQwLjgsMjQuM2wwLjItMC4yYy03NC45LTMtMTUxLjQtMTAuNy0yNDAuNS0yNC4zDQoJCQljLTQ4LjYtNy40LTEwMC44LTE2LjUtMTU5LjYtMjcuOEM5MjYsMjIyLjYsODYwLjQsMjA5LDc4NS42LDE5Mi42Yy01Mi45LTExLjUtMTA3LjEtMjEuMi0xNjEuMi0yOC45DQoJCQljLTQ5LjItNy05OS40LTEyLjQtMTQ5LjItMTYuMmMtODQtNi4zLTE2OC40LTgtMjUwLjktNS4xYy0zMi41LDEuMi02NC44LDMuMS05Niw1LjdjLTI1LjEsMi4xLTQ5LjUsNC42LTcyLjYsNy42DQoJCQljLTQxLjksNS4zLTYzLjQsOS44LTYzLjYsOS44TC03LjEsMTY1LjZ6Ii8+DQoJPC9nPg0KCTxnPg0KCQk8cGF0aCBzdHlsZT0iZmlsbDojRkZGRkZGOyIgZD0iTS03LDE2OC42YzMuNC0wLjgsMzY4LjEtNzUuMSw4MDAuMywyMS40YzE0Ny45LDMzLDI2MC41LDU1LjIsMzU0LjQsNjkuOA0KCQkJYzg5LjYsMTMuOSwxNjUuMywyMS41LDIzOC40LDIzLjZsMC4xLTAuM2MtNzIuOS0yLjItMTQ4LjUtOS43LTIzNy45LTIzLjZjLTkzLjktMTQuNi0yMDYuNS0zNi44LTM1NC4zLTY5LjgNCgkJCWMtOTguNC0yMi0yMDEuMy0zNi45LTMwNS44LTQ0LjJjLTg0LTUuOS0xNjkuMi03LjEtMjUzLjQtMy40Yy03NS43LDMuMy0xMzYuNSwxMC4xLTE3NC4yLDE1LjFDMjAsMTYyLjgtNC45LDE2Ny44LTcuOCwxNjguNA0KCQkJTC03LDE2OC42eiIvPg0KCTwvZz4NCgk8Zz4NCgkJPHBhdGggc3R5bGU9ImZpbGw6I0ZGRkZGRjsiIGQ9Ik0tNi45LDE3MS43YzMuNi0wLjksMzgxLjYtODEuOSw4MDguNywxNS43YzE0Ny4zLDMzLjYsMjU4LDU2LDM0OC40LDcwLjQNCgkJCWM4OS40LDE0LjIsMTY0LjUsMjEuNSwyMzYuMiwyMi45bDAuMS0wLjNjLTEzOS44LTIuNy0yOTgtMjgtNTgzLjctOTMuMmMtOTUuOC0yMS45LTE5Ny4yLTM2LjQtMzAxLjYtNDMuMw0KCQkJYy04NC01LjUtMTcwLjEtNi4xLTI1NS44LTEuN2MtNzcuNSw0LTE0MC41LDExLjQtMTc5LjcsMTYuOWMtNDMsNi02OS4zLDExLjQtNzMuNSwxMi40TC02LjksMTcxLjd6Ii8+DQoJPC9nPg0KCTxnPg0KCQk8cGF0aCBzdHlsZT0iZmlsbDojRkZGRkZGOyIgZD0iTS02LjgsMTc0LjdjMy44LTAuOSw0MDIuMy04Ni45LDgxNyw5LjljMjg5LjEsNjcuNSw0MzkuOSw5MS45LDU3Ni4zLDkzLjJsMC4xLTAuNA0KCQkJYy0xMzYtMS4zLTI4Ni41LTI1LjYtNTc1LjItOTMuMWMtOTMuMi0yMS44LTE5My4yLTM2LTI5Ny4zLTQyLjNjLTg0LTUuMS0xNzAuOS01LjEtMjU4LjMsMGMtNzkuMyw0LjYtMTQ0LjUsMTIuNy0xODUuMiwxOC43DQoJCQljLTQ1LjMsNi42LTczLjMsMTIuNS03OC40LDEzLjdMLTYuOCwxNzQuN3oiLz4NCgk8L2c+DQoJPGc+DQoJCTxwYXRoIHN0eWxlPSJmaWxsOiNGRkZGRkY7IiBkPSJNLTYuNywxNzcuN2MxLjctMC40LDE3OS43LTQwLjIsNDIwLTQwLjFjMTQ2LjEsMCwyODIuNSwxNSw0MDUuMyw0NC4zDQoJCQljMTQ3LjksMzUuNCwyNTEuNiw1Ny40LDMzNi4yLDcxLjVjNDUuMiw3LjUsODUuNiwxMi45LDEyMy41LDE2LjRjMzcuNywzLjUsNzMuMSw1LjIsMTA4LjMsNS4xbDAtMC40DQoJCQljLTEzMi43LDAuMS0yNzUuNy0yMy4zLTU2Ni43LTkyLjljLTEyMy4yLTI5LjUtMjYwLTQ0LjQtNDA2LjYtNDQuNWMtNTIuNywwLTEwNi44LDEuOS0xNjAuOCw1LjhjLTQzLjgsMy4xLTg3LjgsNy41LTEzMC42LDEzDQoJCQlDMzksMTY2LjYtNy42LDE3Ny4zLTgsMTc3LjRMLTYuNywxNzcuN3oiLz4NCgk8L2c+DQoJPGc+DQoJCTxwYXRoIHN0eWxlPSJmaWxsOiNGRkZGRkY7IiBkPSJNLTYuNiwxODAuN2MxLjktMC40LDE5OS00NC43LDQ0OC41LTQ0LjJjMTQwLjgsMC4yLDI3MC4zLDE0LjYsMzg1LjEsNDIuNw0KCQkJYzE0OC45LDM2LjUsMjQ3LjcsNTgsMzMwLjEsNzIuMWM0NC43LDcuNiw4NC42LDEzLDEyMiwxNi40YzM3LjUsMy40LDcyLjcsNC45LDEwNy43LDQuNWwtMC4xLTAuNWMtMTQ1LDEuNy0zMDEuOC0yOS45LTU1OC4yLTkyLjcNCgkJCWMtMTE1LjItMjguMi0yNDUuMi00Mi42LTM4Ni42LTQyLjljLTU0LjktMC4xLTExMS43LDItMTY4LjgsNi4yYy00Ni40LDMuNC05My4zLDguMi0xMzkuMiwxNC4zYy03NS43LDEwLTEyOS4yLDIwLjktMTQxLjksMjQNCgkJCUwtNi42LDE4MC43eiIvPg0KCTwvZz4NCgk8Zz4NCgkJPHBhdGggc3R5bGU9ImZpbGw6I0ZGRkZGRjsiIGQ9Ik0tNi41LDE4My43YzIuMS0wLjUsMjE0LjUtNDkuMyw0NzYuNC00OC40YzEzNS40LDAuNCwyNTguMywxNC4zLDM2NS41LDQxLjINCgkJCWMxNDcuOCwzNy4xLDI0NC43LDU4LjgsMzI0LDcyLjZjODguNSwxNS40LDE1OC43LDIxLjYsMjI3LjUsMjAuMWwtMC4yLTAuNmMtMTQxLjUsMy4xLTI5MC0yNy40LTU0OS42LTkyLjYNCgkJCWMtMTA3LjYtMjctMjMxLjEtNDAuOS0zNjcuMi00MS40Yy01Ni45LTAuMi0xMTYuMiwyLTE3Ni40LDYuNWMtNDguOSwzLjctOTguNiw4LjktMTQ3LjYsMTUuNWMtOTYuMSwxMy0xNTMuNSwyNi40LTE1NC4xLDI2LjUNCgkJCUwtNi41LDE4My43eiIvPg0KCTwvZz4NCgk8Zz4NCgkJPHBhdGggc3R5bGU9ImZpbGw6I0ZGRkZGRjsiIGQ9Ik0tNi4zLDE4Ni43YzIuMy0wLjUsMjM0LjQtNTMuOSw1MDMuNi01Mi43YzEzMCwwLjYsMjQ2LjUsMTQsMzQ2LjUsMzkuNw0KCQkJYzE0OC41LDM4LjIsMjQwLjYsNTkuNCwzMTcuOSw3My4yYzg3LjcsMTUuNiwxNTcuMiwyMS42LDIyNS40LDE5LjRsLTAuMy0wLjZjLTEzNy42LDQuNC0yODQuMS0yNi4yLTU0MS4xLTkyLjQNCgkJCUM3NDUuMiwxNDcuNSw2MjgsMTM0LDQ5Ny4zLDEzMy40Yy01OC43LTAuMy0xMjAuNCwyLjEtMTgzLjQsNi45Yy01MS40LDQtMTAzLjgsOS42LTE1NS45LDE2LjhjLTg2LDExLjktMTQ5LjIsMjUtMTY2LjMsMjkuMQ0KCQkJTC02LjMsMTg2Ljd6Ii8+DQoJPC9nPg0KCTxnPg0KCQk8cGF0aCBzdHlsZT0iZmlsbDojRkZGRkZGOyIgZD0iTS02LjIsMTg5LjdjMi41LTAuNiwyNTQuNi01OC43LDUzMC4zLTU3YzEyNC41LDAuOCwyMzUsMTMuNiwzMjguMiwzOC4zDQoJCQljMjU5LjgsNjguNiwzOTguOCw5OC4zLDUzNS4xLDkyLjRsLTAuNS0wLjdjLTEzNSw1LjgtMjczLjQtMjMuOC01MzIuNS05Mi4yYy05My44LTI0LjgtMjA0LjgtMzcuNy0zMzAuMi0zOC41DQoJCQljLTYwLjItMC40LTEyNC4xLDIuMS0xODkuOSw3LjNjLTUzLjcsNC4zLTEwOC44LDEwLjQtMTYzLjksMTguMUM2MC44LDE3My03LjYsMTg5LjEtOC4yLDE4OS4zTC02LjIsMTg5Ljd6Ii8+DQoJPC9nPg0KCTxnPg0KCQk8cGF0aCBzdHlsZT0iZmlsbDojRkZGRkZGOyIgZD0iTS02LjEsMTkyLjhjMi43LTAuNiwyNzAuNi02My42LDU1Ni4zLTYxLjRjMTE5LjIsMC45LDIyMy42LDEzLjMsMzEwLjUsMzYuOQ0KCQkJYzEyMi45LDMzLjMsMjIxLjksNTguNywzMDUuNiw3NC4zYzg1LjksMTYsMTU0LjEsMjEuNSwyMjEuMywxNy45bC0wLjctMC43Yy0xMzIuOCw3LjEtMjYzLjItMjEuMy01MjMuOS05Mi4xDQoJCQljLTg3LjQtMjMuNy0xOTIuNi0zNi4yLTMxMi43LTM3LjJjLTYxLjYtMC41LTEyNy41LDIuMS0xOTUuOSw3LjdjLTU1LjksNC41LTExMy42LDExLjEtMTcxLjgsMTkuNQ0KCQkJQzY3LjMsMTc0LjQtNy41LDE5Mi4xLTguMywxOTIuMkwtNi4xLDE5Mi44eiIvPg0KCTwvZz4NCgk8Zz4NCgkJPHBhdGggc3R5bGU9ImZpbGw6I0ZGRkZGRjsiIGQ9Ik0tNiwxOTUuOGMyLjktMC43LDI5MS4zLTY4LjUsNTgxLjYtNjUuOGMxMTMuOSwxLjEsMjEyLjYsMTMsMjkzLjQsMzUuNg0KCQkJYzEyNC45LDM0LjgsMjE4LjUsNTkuNSwyOTkuNSw3NC45Yzg0LjksMTYuMiwxNTIuNSwyMS41LDIxOS4yLDE3LjJsLTAuOC0wLjhjLTEzMC4xLDguMy0yNTguNS0yMC4zLTUxNS40LTkxLjkNCgkJCWMtODEuNS0yMi43LTE4MS0zNC44LTI5NS44LTM1LjljLTI5MS43LTIuNy01ODEuMiw2NS4zLTU4NCw2NkwtNiwxOTUuOHoiLz4NCgk8L2c+DQoJPGc+DQoJCTxwYXRoIHN0eWxlPSJmaWxsOiNGRkZGRkY7IiBkPSJNLTUuOSwxOTguOGMzLjEtMC43LDMxMi4zLTczLjUsNjA2LjMtNzAuMmMxMDguNiwxLjIsMjAxLjgsMTIuNywyNzcsMzQuMw0KCQkJYzEyNi4zLDM2LjIsMjE0LjgsNjAuMiwyOTMuMyw3NS41YzgzLjksMTYuNCwxNTAuOSwyMS40LDIxNy4yLDE2LjVsLTEuMS0wLjhjLTEzMy43LDEwLTI2My4xLTIxLjgtNTA2LjgtOTEuNw0KCQkJYy03NS44LTIxLjgtMTY5LjktMzMuNC0yNzkuNS0zNC42Yy02My43LTAuNy0xMzMuMiwyLjEtMjA2LjUsOC40Yy02MCw1LjEtMTIyLjksMTIuNi0xODYuOSwyMi4xQzgwLjcsMTc3LjEtNC44LDE5Ny4zLTguNCwxOTguMg0KCQkJTC01LjksMTk4Ljh6Ii8+DQoJPC9nPg0KCTxnPg0KCQk8cGF0aCBzdHlsZT0iZmlsbDojRkZGRkZGOyIgZD0iTS01LjgsMjAxLjhjMy44LTAuOSw5NS43LTIyLjYsMjI3LjMtNDIuNmM2Ni43LTEwLjEsMTMxLjgtMTgsMTkzLjQtMjMuNA0KCQkJYzc1LjItNi42LDE0NS43LTkuNSwyMDkuNy04LjdjMTAzLjUsMS4zLDE5MS40LDEyLjQsMjYxLjIsMzMuMWMxMjQuNCwzNi43LDIxMS4yLDYwLjksMjg3LjIsNzYuMQ0KCQkJYzgyLjksMTYuNSwxNDkuMiwyMS40LDIxNS4yLDE1LjdsLTEuMy0wLjhjLTEzMS40LDExLjMtMjU4LjctMjAuOC00OTguMi05MS41Yy03MC41LTIwLjgtMTU5LjMtMzIuMS0yNjMuOS0zMy40DQoJCQljLTY0LjUtMC44LTEzNS41LDIuMS0yMTEuMiw4LjhjLTYxLjksNS40LTEyNy4yLDEzLjMtMTk0LjEsMjMuNWMtMTMyLDIwLTIyNC4xLDQxLjgtMjI3LjksNDIuN0wtNS44LDIwMS44eiIvPg0KCTwvZz4NCgk8Zz4NCgkJPHBhdGggc3R5bGU9ImZpbGw6I0ZGRkZGRjsiIGQ9Ik0tNS43LDIwNC44YzQuMS0xLDEwMi43LTI0LjMsMjM5LjctNDUuNGM2OS41LTEwLjcsMTM3LTE5LjEsMjAwLjQtMjQuOGM3Ny4zLTYuOSwxNDkuMi0xMCwyMTMuNy05DQoJCQljOTguNCwxLjQsMTgxLjIsMTIuMiwyNDYsMzEuOWMxMjUuMiwzOC4xLDIwNyw2MS42LDI4MSw3Ni43YzgxLjgsMTYuNywxNDcuNiwyMS4zLDIxMy4yLDE1bC0xLjUtMC45DQoJCQljLTEzNi43LDEzLjItMjc1LjEtMjYuMS00ODkuNS05MS40Yy02NS42LTE5LjktMTQ5LjMtMzAuOC0yNDguOS0zMi4yYy0xMTkuMy0xLjctMjU5LjQsOS43LTQxNi42LDM0DQoJCQlDOTQuNCwxNzkuOC00LjQsMjAzLjItOC41LDIwNC4yTC01LjcsMjA0Ljh6Ii8+DQoJPC9nPg0KCTxnPg0KCQk8cGF0aCBzdHlsZT0iZmlsbDojRkZGRkZGOyIgZD0iTS01LjYsMjA3LjhDOS42LDIwNC4yLDM3MSwxMTkuMSw2NzEsMTI0YzkzLjUsMS41LDE3MS40LDExLjksMjMxLjUsMzAuNw0KCQkJYzEwMy42LDMyLjUsMTk1LjgsNjAuOCwyNzQuOCw3Ny4zYzgwLjcsMTYuOSwxNDUuOSwyMS4zLDIxMS4zLDE0LjJsLTEuNy0wLjljLTEzNS4yLDE0LjUtMjY0LjMtMjMuMi00ODAuOS05MS4yDQoJCQlDODQ1LjEsMTM1LDc2Ni4xLDEyNC41LDY3MS4zLDEyM2MtMTIwLjEtMi0yNjMuOCwxMC4xLTQyNy4yLDM1LjdjLTE0MC4yLDIyLTI0MS42LDQ1LjgtMjUyLjcsNDguNEwtNS42LDIwNy44eiIvPg0KCTwvZz4NCgk8Zz4NCgkJPHBhdGggc3R5bGU9ImZpbGw6I0ZGRkZGRjsiIGQ9Ik0tNS41LDIxMC44YzE2LjEtMy44LDM5OC4zLTkzLjksNjk4LjgtODguNWM4OC43LDEuNiwxNjIsMTEuNiwyMTcuNiwyOS42DQoJCQljMjEzLjUsNjkuMiwzNDEsMTA3LjYsNDc3LjksOTEuNGwtMi0wLjljLTEzMy43LDE1LjgtMjYwLjItMjIuMy00NzIuMy05MS4xYy01Ni40LTE4LjMtMTMwLjctMjguNC0yMjAuOC0zMA0KCQkJQzM5MS4yLDExNS45LDcuNSwyMDYuMy04LjYsMjEwLjFMLTUuNSwyMTAuOHoiLz4NCgk8L2c+DQoJPGc+DQoJCTxwYXRoIHN0eWxlPSJmaWxsOiNGRkZGRkY7IiBkPSJNLTUuNCwyMTMuOWMxNy00LDQyMC4zLTk5LjEsNzIwLjUtOTMuMWM4NC4xLDEuNywxNTIuOCwxMS4zLDIwNC4yLDI4LjUNCgkJCWMyMDksNzAsMzMzLjksMTA4LjgsNDY5LjgsOTEuMmwtMi4zLTFjLTEzMi40LDE3LjItMjU2LjItMjEuNC00NjMuNy05MC45Yy01Mi4zLTE3LjUtMTIyLjEtMjcuMy0yMDcuNy0yOQ0KCQkJQzQxMy4yLDExMy42LDguNCwyMDktOC43LDIxMy4xTC01LjQsMjEzLjl6Ii8+DQoJPC9nPg0KCTxnPg0KCQk8cGF0aCBzdHlsZT0iZmlsbDojRkZGRkZGOyIgZD0iTS01LjMsMjE2LjljMTcuOS00LjMsNDQyLjQtMTA0LjQsNzQxLjUtOTcuOGM3OS42LDEuNywxNDQsMTEsMTkxLjUsMjcuNQ0KCQkJYzIwNC4zLDcwLjksMzI2LjUsMTEwLjEsNDYxLjYsOTEuMWwtMi41LTFjLTEzMS40LDE4LjUtMjUyLjQtMjAuNC00NTUtOTAuN2MtNDguMy0xNi44LTExNC0yNi4yLTE5NS4xLTI4DQoJCQljLTEyMC42LTIuNi0yNzMuOSwxMS4xLTQ1NS43LDQxYy0xNTUuMSwyNS41LTI3Ni40LDU0LTI4OS43LDU3LjFMLTUuMywyMTYuOXoiLz4NCgk8L2c+DQoJPGc+DQoJCTxwYXRoIHN0eWxlPSJmaWxsOiNGRkZGRkY7IiBkPSJNLTUuMiwyMTkuOWMxOC44LTQuNSw0NjQuNS0xMDkuNiw3NjEuOS0xMDIuNWM3NS4yLDEuOCwxMzUuNiwxMC43LDE3OS4zLDI2LjQNCgkJCWMxOTkuNSw3MS44LDMxOC45LDExMS40LDQ1My41LDkwLjlsLTIuOC0xYy0xMzAuNSwxOS44LTI0OC43LTE5LjQtNDQ2LjQtOTAuNmMtNDQuNy0xNi4xLTEwNi4zLTI1LjEtMTgzLjEtMjcNCgkJCWMtMTIwLjItMi45LTI3Ni40LDExLjUtNDY0LjEsNDIuOEMxMzYuNiwxODUsMTQuMSwyMTMuNi04LjgsMjE5TC01LjIsMjE5Ljl6Ii8+DQoJPC9nPg0KCTxnPg0KCQk8cGF0aCBzdHlsZT0iZmlsbDojRkZGRkZGOyIgZD0iTS01LDIyMi45YzQ5LjUtMTEuOCw0OTEuOC0xMTQuOCw3ODEuOC0xMDcuM2M3MSwxLjgsMTI3LjQsMTAuNCwxNjcuNywyNS40DQoJCQljMTUxLjYsNTYuNiwyNTAuNyw5MS4zLDM0OS43LDk2LjFjMzIuNSwxLjYsNjMuOC0wLjIsOTUuNi01LjRsLTMuMS0xLjFjLTMwLjUsNS02MC41LDYuNy05MS41LDUuMg0KCQkJYy05Ni45LTQuNy0xOTUuNC0zOS4yLTM0Ni4yLTk1LjZjLTQxLjItMTUuNC05OC45LTI0LjEtMTcxLjctMjZjLTExOS42LTMuMS0yNzguNCwxMS45LTQ3Miw0NC42QzE0NC4zLDE4Ni4xLDE1LjMsMjE2LjItOC45LDIyMg0KCQkJTC01LDIyMi45eiIvPg0KCTwvZz4NCgk8Zz4NCgkJPHBhdGggc3R5bGU9ImZpbGw6I0ZGRkZGRjsiIGQ9Ik0tNC45LDIyNS45YzUxLjgtMTIuMyw1MTQuMS0xMjAuMSw4MDEuMS0xMTJjNjcsMS45LDExOS43LDEwLjEsMTU2LjYsMjQuNQ0KCQkJYzE0Ni45LDU3LjEsMjQzLjIsOTIsMzQxLjMsOTYuNmMzMi42LDEuNSw2NC0wLjQsOTYtNmwtMy4zLTEuMWMtMzAuNiw1LjMtNjAuNiw3LjItOTEuNSw1LjhjLTk1LjYtNC41LTE5NS43LTQwLjktMzM3LjUtOTYNCgkJCWMtMzcuOS0xNC43LTkyLTIzLjItMTYwLjgtMjUuMUM1MDcuMSwxMDQuNCw0MywyMTIuNi04LjksMjI1TC00LjksMjI1Ljl6Ii8+DQoJPC9nPg0KCTxnPg0KCQk8cGF0aCBzdHlsZT0iZmlsbDojRkZGRkZGOyIgZD0iTS00LjgsMjI4LjljNTQuMS0xMi45LDUzNi40LTEyNS40LDgxOS45LTExNi44YzYzLDEuOSwxMTIuMiw5LjgsMTQ2LjEsMjMuNQ0KCQkJYzEzNy45LDU1LjgsMjM1LjYsOTIuNywzMzIuNyw5Ny4xYzMyLjcsMS41LDY0LjItMC43LDk2LjQtNi43bC0zLjYtMS4xYy0zMC43LDUuNy02MC43LDcuOC05MS43LDYuNA0KCQkJYy05NC43LTQuMy0xOTEuNy00MS0zMjguOC05Ni41Yy0zNC45LTE0LjEtODUuNS0yMi4zLTE1MC40LTI0LjJDNTI5LjUsMTAyLDQ1LjMsMjE1LTksMjI4TC00LjgsMjI4Ljl6Ii8+DQoJPC9nPg0KCTxnPg0KCQk8cGF0aCBzdHlsZT0iZmlsbDojRkZGRkZGOyIgZD0iTS00LjcsMjMyYzU2LjQtMTMuNCw1NTguOC0xMzAuOCw4MzguMi0xMjEuN2M1OS4zLDEuOSwxMDUuMSw5LjUsMTM2LjEsMjIuNg0KCQkJYzEzMy4xLDU2LjIsMjI3LjYsOTMuNCwzMjQuMiw5Ny42YzMyLjgsMS40LDY0LjUtMSw5Ni44LTcuM2wtMy45LTEuMWMtMzAuOSw2LjEtNjAuOSw4LjQtOTEuOCw3Yy05NC00LjEtMTg3LjctNDEuMS0zMjAtOTYuOQ0KCQkJYy0zMi0xMy41LTc5LjMtMjEuNC0xNDAuNi0yMy40QzcxOCwxMDUsNTUyLjIsMTIxLjksMzQxLjUsMTU4LjlDMTcyLjIsMTg4LjYsMzAuNCwyMjEuNS05LDIzMC45TC00LjcsMjMyeiIvPg0KCTwvZz4NCgk8Zz4NCgkJPHBhdGggc3R5bGU9ImZpbGw6I0ZGRkZGRjsiIGQ9Ik0tNC42LDIzNWM1OC43LTE0LDU4MS4zLTEzNi4xLDg1NS45LTEyNi42YzU1LjcsMS45LDk4LjMsOS4zLDEyNi42LDIxLjgNCgkJCWMxMjQuMiw1NC45LDIxOS41LDk0LjEsMzE1LjUsOTguMmMzMywxLjQsNjQuOC0xLjIsOTcuNC04bC00LjItMS4xYy0zMSw2LjUtNjEuMiw5LTkyLjEsNy43Yy05My4yLTQtMTg3LjctNDIuOS0zMTEuMS05Ny40DQoJCQljLTI5LjMtMTIuOS03My40LTIwLjUtMTMxLjMtMjIuNWMtMTE0LjgtNC0yODIuNiwxMy41LTQ5OC44LDUxLjlDMTgwLjQsMTg5LjYsMzIuMiwyMjQuMS05LjEsMjMzLjlMLTQuNiwyMzV6Ii8+DQoJPC9nPg0KCTxnPg0KCQk8cGF0aCBzdHlsZT0iZmlsbDojRkZGRkZGOyIgZD0iTS00LjUsMjM4Qzk1LDIxNC4zLDYwNC44LDk2LjcsODY4LjcsMTA2LjVjNTIuMywxLjksOTEuOCw5LDExNy42LDIwLjkNCgkJCWMxMTkuMiw1NS4yLDIxMSw5NC43LDMwNi44LDk4LjdjMzMuMSwxLjQsNjUuMi0xLjUsOTgtOC43bC00LjUtMS4xYy0zMS4yLDYuOS02MS40LDkuNi05Mi4zLDguM2MtOTIuNy0zLjktMTg3LjYtNDQuOC0zMDIuMS05Ny45DQoJCQljLTI2LjgtMTIuNC02OC0xOS43LTEyMi40LTIxLjhjLTExMy4xLTQuMi0yODIuOCwxMy45LTUwNC40LDUzLjhDMTg4LjcsMTkwLjYsMzQsMjI2LjYtOS4yLDIzNi45TC00LjUsMjM4eiIvPg0KCTwvZz4NCgk8Zz4NCgkJPHBhdGggc3R5bGU9ImZpbGw6I0ZGRkZGRjsiIGQ9Ik0tNC4zLDI0MUM5OSwyMTYuNCw2MjcuMiw5NC40LDg4NS42LDEwNC42YzQ4LjksMS45LDg1LjYsOC43LDEwOSwyMC4xDQoJCQljMTEwLjQsNTMuOCwyMDIuNSw5NS4zLDI5OC4yLDk5LjJjMzMuMywxLjQsNjUuNi0xLjcsOTguNy05LjRsLTQuOS0xLjJjLTMxLjMsNy4zLTYxLjYsMTAuMi05Mi42LDguOQ0KCQkJYy05Mi40LTMuOC0xODMuNS00NC45LTI5My05OC4zYy0yNC40LTExLjktNjIuOC0xOS0xMTQuMS0yMUM2MjUsOTIuNiw5NC40LDIxNS4yLTkuMywyMzkuOEwtNC4zLDI0MXoiLz4NCgk8L2c+DQo8L2c+DQo8L3N2Zz4NCg==) no-repeat scroll center 20px transparent;
	background-size: 100%;
    margin: auto;
    min-height: 120px;
}

.logoContainer {
	width: 900px;
	margin: auto;
	min-height: 120px;
}

#logo {
	background-image: url(data:image/png;base64,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%3D%3D);
	height: 100px;
	width:269px;
	margin-left: 15px;
	float: left;
}
#logotext{
	float: right;
	width: 400px;
	margin-top: 32px;
	padding-right: 20px;
	color: white;
	font-size: 30px;
	text-align:right;
}

#languageselect{
	float: right;
	width: 300px;
	margin-top: 3px;
	padding-right: 20px;
	text-align:right;
	color: white;
}

/* Inputs */

select, textarea, input[type="text"], input[type="password"], input[type="datetime"], input[type="datetime-local"], input[type="date"], input[type="month"], input[type="time"], input[type="week"], input[type="number"], input[type="email"], input[type="url"], input[type="search"], input[type="tel"], input[type="color"], .uneditable-input {
    border-radius: 0px;
    color: #555555;
    font-size: 13px;
    line-height: 20px;
    padding: 6px 8px;
    vertical-align: middle;
	background-color: #FFFFFF;
    border: 1px solid #CCCCCC;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset;
    transition: border 0.2s linear 0s, box-shadow 0.2s linear 0s;
	font-family: Tahoma,Arial,Verdana,sans-serif;
	margin: 3px;
}

select:focus, textarea:focus, input:focus {
    border-color: rgba(82, 168, 236, 0.8);
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset, 0 0 8px rgba(82, 168, 236, 0.6);
    outline: 0 none;
}

input[type=radio], input[type=checkbox]{
	background:none;
	border: none;
}

.resizeable{
	resize: both;
}

.input-icon, .input-icon-append {
	white-space: nowrap;
}

.input-icon i {
	border-radius: 4px 0 0 4px;
	background-color: #EEEEEE;
	border: 1px solid #CCCCCC;
	line-height: 20px;
	padding: 4px 8px !important;
    vertical-align: middle;
	font-size: 13px;
	margin-right: -5px;
	height: 20px;
	display: inline-block;
}

.input-icon input {
	border-radius: 0 4px 4px 0;
	margin-left: 0px;
	height: 20px;
}

.input-icon-append i {
	border-radius: 0 4px 4px 0;
	background-color: #EEEEEE;
	border: 1px solid #CCCCCC;
	line-height: 20px;
	padding: 4px 8px !important;
    vertical-align: middle;
	font-size: 13px;
	margin-left: -5px;
	height: 20px;
	display: inline-block;
}

.input-icon-append input {
	border-radius: 4px 0 0 4px;
	margin-right: 0px;
	height: 20px;
}

/* Global */
#installer{
	width:900px;
	overflow: auto;
	margin:0 auto;
	background-color: #fff;
    border-right: 1px solid #ccc;
	border-left: 1px solid #ccc;
	border-bottom: 1px solid #ccc;
    padding: 10px;
    display:flex; 
}
#main{
	font-size: 12px;
	order: 0;
	width: 100%;
}
#steps, #content{
	background-color: #FFFFFF;
	padding:20px;
}
#steps{
	width:230px;
	display:block;
	padding:3px;
	margin-right:5px;
	order: 1;
	margin-left: 100px;
	padding-top: 15px;
}
#footer a, #footer a:hover {
	color: #fff;
	text-decoration: none;
}

#footer {
	background-color: #0e0e0e;
    bottom: 0;
    height: 100px;
    left: 0;
    position: absolute;
    right: 0;
	color: #fff;
	text-align: center;
}

#footer > div {
	padding-top: 30px;
}

/* Progress Bar */
#progressbar{
	margin: 10px 10px 10px 10px;
}
.ui-progressbar {
	position:relative;
}
.install_label {
	position: absolute;
	width: 90%;
	text-align: center;
	line-height: 1.9em;
	left:5%;
	right:5%;
}

/* Steps links */
ul.steps li span{
	display:block;
	cursor: pointer;
	text-decoration:none;
	border:1px solid #FFFFFF;
	color:#000000;
	padding:4px 0px 4px 2px;
}
ul.steps li.notactive span{
	color:grey;
	font-style:italic;
	cursor: auto;
}
ul.steps li.done2 span{
	cursor: pointer;
	color:#000000;
	font-style: normal;
}
ul.steps li.done span {
	color: #008000;
}

ul.steps li span:hover{
	color:#000000;
}
ul.steps{
	margin:14px 0 10px 0;
	padding:0px 20px 0px 6px;
	list-style-type:none;
}
ul.steps li{
	background-repeat: no-repeat;
	margin:10px 0 10px 0;
	padding-left: 30px;
	display: block;
}


ul.steps li.now:before {
	content: '\f0a9';
	font-family: FontAwesome;
	display: inline-block;
	position: absolute;
	margin-left: -30px;
	padding:4px 0px 0px 10px;
	width: 30px;
	font-size: 20px;
}

ul.steps li.notactive:before {
	content: '\f18e';
	color:#707070;
	font-family: FontAwesome;
	display: inline-block;
	position: absolute;
	margin-left: -30px;
	padding:4px 0px 0px 10px;
	width: 30px;
	font-size: 20px;
}
ul.steps li.done:before {
	content: '\f14a';
	color:green;
	font-family: FontAwesome;
	display: inline-block;
	position: absolute;
	margin-left: -30px;
	padding:4px 0px 0px 10px;
	width: 30px;
	font-size: 20px;
}

/* Content */
h1, h2, h3 {
	font-family: 'Trebuchet MS',Arial,sans-serif;
    font-weight: bold;
    margin-bottom: 10px;
    padding-bottom: 5px;
	border-bottom: 1px solid #CCCCCC;
	margin-top: 5px;
}

h1 {
    font-size: 20px;
}

h2 {
	font-size: 18px;
}

h3 {
	font-size: 14px;
	border-bottom: none;
	margin-bottom: 5px;
}

.buttonbar{
	margin: 15px 10px 0px 0px;
	text-align:right;
}

.positive{
	color: green;	
}
.negative{
	color: red;
	font-weight: bold;
}
.neutral{
	color: orange;
	font-weight: bold;
}
th, td { 
	padding: 0.25em 0.5em;
	
}
th{
	font-size: 12px;
	text-align: left;
	font-weight: bold;	
}
td{
	font-size: 13px;
}
.ui-widget-content tr.ui-state-highlight{
	border:0;
}
.subname{
	font-weight: normal;
	font-size: 11px;
	font-style: italic;	
}

.licence{
	height: 250px;
	overflow:auto; 
}

/* Buttons */
.button, input[type="submit"], input[type="button"], input[type="reset"], button{
	display: inline-block;
	zoom: 1; /* zoom and *display = ie7 hack for display:inline-block */
	*display: inline;
	vertical-align: baseline;
	margin: 0 2px;
	outline: none;
	cursor: pointer !important;
	text-align: center;
	text-decoration: none;
	font: 15px/110% Arial, Helvetica, sans-serif;
	padding: .5em 2em .55em;
	text-shadow: 0 1px 1px rgba(0,0,0,.3);
	margin-top: 2px;
}
.button:hover, input[type="submit"]:hover, input[type="button"]:hover , input[type="reset"]:hover, button:hover{
	text-decoration: none;
}
.button:active, input[type="submit"]:active, input[type="button"]:active, input[type="reset"]:active, button:active{
	position: relative;
	top: 1px;
}

.bigrounded {
	-webkit-border-radius: 2em;
	-moz-border-radius: 2em;
	border-radius: 2em;
}
.medium, .button, input[type="submit"], input[type="button"],input[type="reset"], button {
	font-size: 13px;
	padding: .3em 1em .32em;
}
.small {
	font-size: 11px;
}

button[disabled], button[disabled]:hover, input[type=submit][disabled], input[type=button][disabled], input[type=reset][disabled],input[type=submit][disabled]:hover, input[type=button][disabled]:hover, input[type=reset][disabled]:hover {
	color: #999;
	border: solid 1px #b7b7b7;
	background: #fff;
	background: -webkit-gradient(linear, left top, left bottom, from(#fff), to(#ededed));
	background: -moz-linear-gradient(top,  #fff,  #ededed);
	filter:  progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffff', endColorstr='#ededed');
	text-shadow: none;
	box-shadow: none;
	cursor: default;
}

select[disabled], textarea[disabled], input[type="text"][disabled], input[type="password"][disabled], input[type="datetime"][disabled], input[type="datetime-local"][disabled], input[type="date"][disabled], input[type="month"][disabled], input[type="time"][disabled], input[type="week"][disabled], input[type="number"][disabled], input[type="email"][disabled], input[type="url"][disabled], input[type="search"][disabled], input[type="tel"][disabled], input[type="color"][disabled],
select[readonly], textarea[readonly], input[type="text"][readonly], input[type="password"][readonly], input[type="datetime"][readonly], input[type="datetime-local"][readonly], input[type="date"][readonly], input[type="month"][readonly], input[type="time"][readonly], input[type="week"][readonly], input[type="number"][readonly], input[type="email"][readonly], input[type="url"][readonly], input[type="search"][readonly], input[type="tel"][readonly], input[type="color"][readonly]{
	background-color: #EEEEEE;
    cursor: not-allowed;
}

/* color styles 
---------------------------------------------- */

/* black */
.black {
	color: #d7d7d7;
	border: solid 1px #333;
	background: #333;
	background: -webkit-gradient(linear, left top, left bottom, from(#666), to(#000));
	background: -moz-linear-gradient(top,  #666,  #000);
	filter:  progid:DXImageTransform.Microsoft.gradient(startColorstr='#666666', endColorstr='#000000');
}
.black:hover {
	background: #000;
	background: -webkit-gradient(linear, left top, left bottom, from(#444), to(#000));
	background: -moz-linear-gradient(top,  #444,  #000);
	filter:  progid:DXImageTransform.Microsoft.gradient(startColorstr='#444444', endColorstr='#000000');
}
.black:active {
	color: #666;
	background: -webkit-gradient(linear, left top, left bottom, from(#000), to(#444));
	background: -moz-linear-gradient(top,  #000,  #444);
	filter:  progid:DXImageTransform.Microsoft.gradient(startColorstr='#000000', endColorstr='#666666');
}

/* gray */
.gray {
	color: #e9e9e9;
	border: solid 1px #555;
	background: #6e6e6e;
	background: -webkit-gradient(linear, left top, left bottom, from(#888), to(#575757));
	background: -moz-linear-gradient(top,  #888,  #575757);
	filter:  progid:DXImageTransform.Microsoft.gradient(startColorstr='#888888', endColorstr='#575757');
}
.gray:hover {
	background: #616161;
	background: -webkit-gradient(linear, left top, left bottom, from(#757575), to(#4b4b4b));
	background: -moz-linear-gradient(top,  #757575,  #4b4b4b);
	filter:  progid:DXImageTransform.Microsoft.gradient(startColorstr='#757575', endColorstr='#4b4b4b');
}
.gray:active {
	color: #afafaf;
	background: -webkit-gradient(linear, left top, left bottom, from(#575757), to(#888));
	background: -moz-linear-gradient(top,  #575757,  #888);
	filter:  progid:DXImageTransform.Microsoft.gradient(startColorstr='#575757', endColorstr='#888888');
}

/* white */
.white {
	color: #606060;
	border: solid 1px #b7b7b7;
	background: #fff;
	background: -webkit-gradient(linear, left top, left bottom, from(#fff), to(#ededed));
	background: -moz-linear-gradient(top,  #fff,  #ededed);
	filter:  progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffff', endColorstr='#ededed');
}
.white:hover {
	background: #ededed;
	background: -webkit-gradient(linear, left top, left bottom, from(#fff), to(#dcdcdc));
	background: -moz-linear-gradient(top,  #fff,  #dcdcdc);
	filter:  progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffff', endColorstr='#dcdcdc');
}
.white:active {
	color: #999;
	background: -webkit-gradient(linear, left top, left bottom, from(#ededed), to(#fff));
	background: -moz-linear-gradient(top,  #ededed,  #fff);
	filter:  progid:DXImageTransform.Microsoft.gradient(startColorstr='#ededed', endColorstr='#ffffff');
}

/* orange */
.orange {
	color: #fef4e9;
	border: solid 1px #da7c0c;
	background: #f78d1d;
	background: -webkit-gradient(linear, left top, left bottom, from(#faa51a), to(#f47a20));
	background: -moz-linear-gradient(top,  #faa51a,  #f47a20);
	filter:  progid:DXImageTransform.Microsoft.gradient(startColorstr='#faa51a', endColorstr='#f47a20');
}
.orange:hover {
	background: #f47c20;
	background: -webkit-gradient(linear, left top, left bottom, from(#f88e11), to(#f06015));
	background: -moz-linear-gradient(top,  #f88e11,  #f06015);
	filter:  progid:DXImageTransform.Microsoft.gradient(startColorstr='#f88e11', endColorstr='#f06015');
}
.orange:active {
	color: #fcd3a5;
	background: -webkit-gradient(linear, left top, left bottom, from(#f47a20), to(#faa51a));
	background: -moz-linear-gradient(top,  #f47a20,  #faa51a);
	filter:  progid:DXImageTransform.Microsoft.gradient(startColorstr='#f47a20', endColorstr='#faa51a');
}

/* red */
.red {
	color: #faddde;
	border: solid 1px #980c10;
	background: #d81b21;
	background: -webkit-gradient(linear, left top, left bottom, from(#ed1c24), to(#aa1317));
	background: -moz-linear-gradient(top,  #ed1c24,  #aa1317);
	filter:  progid:DXImageTransform.Microsoft.gradient(startColorstr='#ed1c24', endColorstr='#aa1317');
}
.red:hover {
	background: #b61318 !important;
	background: -webkit-gradient(linear, left top, left bottom, from(#c9151b), to(#a11115))!important;
	background: -moz-linear-gradient(top,  #c9151b,  #a11115) !important;
	filter:  progid:DXImageTransform.Microsoft.gradient(startColorstr='#c9151b', endColorstr='#a11115') !important;
}
.red:active {
	color: #de898c;
	background: -webkit-gradient(linear, left top, left bottom, from(#aa1317), to(#ed1c24));
	background: -moz-linear-gradient(top,  #aa1317,  #ed1c24);
	filter:  progid:DXImageTransform.Microsoft.gradient(startColorstr='#aa1317', endColorstr='#ed1c24');
}

/* blue */
a.blue, a.blue:hover, a.button, a.button:hover {
	color: #d9eef7;
	line-height: 15px;
}

.blue, .button, input[type="submit"], input[type="button"], input[type="reset"], button {
	color: #fff;
	border: solid 1px #1a5188;
	background: #1a5188;
	padding: 0.7em 1.5em;
}
.blue:hover, .button:hover, input[type="submit"]:hover, input[type="button"]:hover, input[type="reset"]:hover, button:not(.ui-widget):hover {
	color: #fff;
	background: #2e78b0;
}
.blue:active, .button:hover, input[type="submit"]:active, input[type="button"]:active, input[type="reset"]:active, button:not(.ui-widget):active {
	color: #fff;
}

/* green */
.green {
	color: #e8f0de;
	border: solid 1px #538312;
	background: #64991e;
	background: -webkit-gradient(linear, left top, left bottom, from(#7db72f), to(#4e7d0e));
	background: -moz-linear-gradient(top,  #7db72f,  #4e7d0e);
	filter:  progid:DXImageTransform.Microsoft.gradient(startColorstr='#7db72f', endColorstr='#4e7d0e');
}
.green:hover {
	background: #538018;
	background: -webkit-gradient(linear, left top, left bottom, from(#6b9d28), to(#436b0c));
	background: -moz-linear-gradient(top,  #6b9d28,  #436b0c);
	filter:  progid:DXImageTransform.Microsoft.gradient(startColorstr='#6b9d28', endColorstr='#436b0c');
}
.green:active {
	color: #a9c08c;
	background: -webkit-gradient(linear, left top, left bottom, from(#4e7d0e), to(#7db72f));
	background: -moz-linear-gradient(top,  #4e7d0e,  #7db72f);
	filter:  progid:DXImageTransform.Microsoft.gradient(startColorstr='#4e7d0e', endColorstr='#7db72f');
}

/* general: information boxes  NEW */
.infobox {
    background-color: #F5F5F5;
    border-left: 3px solid #E3E3E3;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05) inset;
    margin-bottom: 10px;
    min-height: 40px;
    padding: 19px;
}

.infobox-large {
    border-radius: 0px;
    padding: 10px;
}

.infobox-blue {
	background-color: #D9EDF7;
    color: #3A87AD;
    border-color: #3A87AD;
}

.infobox-red {
	background-color: #F2DEDE;
    color: #B94A48;
    border-color: #B94A48;
}

.infobox-green {
	background-color: #DFF0D8;
    color: #468847;
    border-color: #468847;
}

.infobox-orange {
	background-color: #ffa500;
	color: #fff;
	border-color: #fff;
}

/* Tables */
#installer table, .debug table {
	border-collapse:collapse;
}
#installer th, .debug th{
	font-weight: bold;
	background-color: #e8e8e8;
	white-space:nowrap;
	text-align:left;
	font-family:'Trebuchet MS',Arial,sans-serif;
	background-color: #efefef;
	border-bottom: 2px solid #4e7fa8 !important;
}

#installer table.no-borders td {
	border: none !important;
}

#installer th, #installer td, .debug th, .debug td {
    line-height: 20px;
    padding: 6px;
    text-align: left;
    vertical-align: top;
	border-bottom: 1px solid #DDDDDD;
}

#installer .row1, .debug .row1, .colorswitch tr:nth-child(even),.colorswitch>td:nth-child(even), .colorswitch > div:nth-child(even){
	background-color: #F9F9F9;
}

#installer .row2,.debug .row2,.helpline, .colorswitch  tr:nth-child(odd),.colorswitch>td:nth-child(odd), .colorswitch > div:nth-child(odd){

}

#installer th.footer {
	font-style:italic;
	font-weight: normal;
	text-align: right;
	background-color: #F5F5F5 !important;
	border-bottom: none; 
}

#installer th a {
	text-decoration: none;
	color: #000;
	font-family:'Trebuchet MS',Arial,sans-serif;
}

#installer th a:hover {
	color: #999;
}

/* Progressbar */
.ui-widget.ui-widget-content {
    border: 1px solid #c5c5c5;
}

.ui-corner-all, .ui-corner-bottom, .ui-corner-right, .ui-corner-br {
    border-bottom-right-radius: 3px;
}
.ui-corner-all, .ui-corner-bottom, .ui-corner-left, .ui-corner-bl {
    border-bottom-left-radius: 3px;
}
.ui-corner-all, .ui-corner-top, .ui-corner-right, .ui-corner-tr {
    border-top-right-radius: 3px;
}
.ui-corner-all, .ui-corner-top, .ui-corner-left, .ui-corner-tl {
    border-top-left-radius: 3px;
}
.ui-widget-content {
    border: 1px solid #dddddd;
    background: #ffffff;
    color: #333333;
}
.ui-widget {
    font-family: Arial,Helvetica,sans-serif;
    font-size: 1em;
}
.ui-progressbar {
    height: 2em;
    text-align: left;
    overflow: hidden;
}

.ui-progressbar .ui-progressbar-value {
    margin: -1px;
    height: 100%;
}

.ui-widget-header {
    border: 1px solid #dddddd;
    background: #e9e9e9;
    color: #333333;
    font-weight: bold;
}

</style>
		
		<script type="text/javascript">
			//<![CDATA[
		$(function() {

			$("#progressbar").progressbar({
				value: 0,
			});
		});
		
		<?php if (isset($content['js'])) echo $content['js']; ?>
			//]]>
		</script>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Installation</title>
	</head>

	<body>
		<div id="handler">
			<form action="?step=<?php echo $content['next_step']; ?>" method="post" id="form_install">
			<div id="outerWrapper">
				<div id="header">
					<div id="headerInner">
						<div class="logoContainer">
							<div id="logo"></div>
							<div id="logotext">ZIP-Installer</div>
						</div>
					</div>
				</div>
				<div id="installer">
					<div id="main">
						<div id="content">

							<h1 class="hicon home">Unpacking Installation Files</h1>
							<?php echo $content['content']; ?>
							<div class="buttonbar">

								<input id="submit_button" type="submit" class="" name="next" value="<?php echo $content['button']; ?>" />
							</div>
						</div>
					</div>
				</div>
				<div id="footer">
					EQDKP Plus  © 2006 - <?php echo date('Y', time()); ?> by EQDKP Plus Development-Team
				</div>
			</div>
			</form>
		</div>
	</body>
</html>
