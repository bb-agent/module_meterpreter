<? 
/*
    Copyright (C) 2013-2014 xtr4nge [_AT_] gmail.com

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
?>
<?
include "../../../login_check.php";
include "../../../config/config.php";
include "../_info_.php";
include "../../../functions.php";

// Checking POST & GET variables...
if ($regex == 1) {
    regex_standard($_GET["service"], "../msg.php", $regex_extra);
    regex_standard($_GET["action"], "../msg.php", $regex_extra);
    regex_standard($_GET["page"], "../msg.php", $regex_extra);
    regex_standard($io_action, "../msg.php", $regex_extra);
    regex_standard($_GET["install"], "../msg.php", $regex_extra);
}

$service = $_GET['service'];
$action = $_GET['action'];
$page = $_GET['page'];
$install = $_GET['install'];

if($service != "") {
    
    if ($action == "start") {
        
        // COPY LOG
        if ( 0 < filesize( $mod_logs ) ) {
            $exec = "$bin_cp $mod_logs $mod_logs_history/".gmdate("Ymd-H-i-s").".log";
            //exec("$bin_danger \"$exec\"" ); //DEPRECATED
            exec_blackbulb($exec);
            
            $exec = "$bin_echo '' > $mod_logs";
            //exec("$bin_danger \"$exec\"" ); //DEPRECATED
            exec_blackbulb($exec);
        }
			
		$exec = "$bin_php meterpreter.php > /dev/null &";
        //exec($exec);
		//exec("$bin_danger \"$exec\"" );
        exec_blackbulb($exec);
		
    } else if($action == "stop") {
        // STOP MODULE
		$exec = "ps aux|grep 'meterpreter\.php'|awk '{print $2}'";
		exec($exec, $output);
        //$exec = "$bin_killall $mod_name";
		$exec = "$bin_kill " . $output[0];
        //exec("$bin_danger \"$exec\"" ); //DEPRECATED
        exec_blackbulb($exec);
        
        // COPY LOG
        if ( 0 < filesize( $mod_logs ) ) {
            $exec = "$bin_cp $mod_logs $mod_logs_history/".gmdate("Ymd-H-i-s").".log";
            //exec("$bin_danger \"$exec\"" ); //DEPRECATED
            exec_blackbulb($exec);
            
            $exec = "$bin_echo '' > $mod_logs";
            //exec("$bin_danger \"$exec\"" ); //DEPRECATED
            exec_blackbulb($exec);
        }

    }

}

if ($install == "install_$mod_name") {

    $exec = "chmod 755 install.sh";
    //exec("$bin_danger \"$exec\"" ); //DEPRECATED
    exec_blackbulb($exec);

    $exec = "$bin_sudo ./install.sh > $log_path/install.txt &";
    //exec("$bin_danger \"$exec\"" ); //DEPRECATED
    exec_blackbulb($exec);

    header('Location: ../../install.php?module='.$mod_name);
    exit;
}

if ($page == "status") {
    header('Location: ../../../action.php');
} else {
    header('Location: ../../action.php?page='.$mod_name);
}

?>
