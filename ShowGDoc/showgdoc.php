<?php
/*
Plugin Name: showgdoc
Plugin URI: http://www.orestad-linux.se/
Description: Show data from a google spreadsheet document
Author: Tord Johansson Munk
Version: 0.1
*/
function ShowData($user,$pass,$spread,$colname,$collink, $colended){
	#Get the Google spreadsheet helper class
	include_once("Google_Spreadsheet.php");
	$ss = new Google_Spreadsheet($user,$pass);
	$ss -> useSpreadsheet($spread);
	$text = $ss -> getrows();
	#output the colums that has a link as a link and if they have an matching ended colum do not print
	foreach($text as $entry){
		if($entry[$collink] == true && $entry[$colended] == false){
		echo "<a href='$entry[$collink]' target='_blank'>";
		echo $entry[$colname];
		echo "</a>";
		echo "<br>"; 
		}
		elseif($entry[$collink] == false && $entry[$colended] == false){
			echo $entry[$colname];
			echo "<br>";
		}
	}
}

#The user settings to the widget
function control($options) {
	#Get the options array
	$options = get_option('widget_showgdoc');

    ?>
    <label>Widget title<input name="widget_title" type="text" value="<?php echo $options['widget_title'] ?>" /></label>
    <label>Gmail<input name="username" type="text" value="<?php echo $options['user'] ?>"/></label>
	<label>Password<input name="password" type="password" value="<?php echo $options['pass'] ?>"/></label>
	<label>Spreadsheet<input name="spreadsheet" type="text" value="<?php echo $options['spread'] ?>"/></label>
	<label>Colum name<input name="colname" type="text" value="<?php echo $options['colname'] ?>"/></label>
	<label>Colum link<input name="collink" type="text" value="<?php echo $options['collink'] ?>"/></label>
	<label>Colum ended<input name="colended" type="text" value="<?php echo $options['colended'] ?>"/></label>
	<?php
	#Get the use input to the options array
	$options['widget_title'] = attribute_escape($_POST['widget_title']);
	$options['user'] = attribute_escape($_POST['username']);
    $options['pass'] = attribute_escape($_POST['password']);
    $options['spread'] = attribute_escape($_POST['spreadsheet']);
    $options['colname'] = attribute_escape($_POST['colname']);
    $options['collink'] = attribute_escape($_POST['collink']);
    $options['colended'] = attribute_escape($_POST['colended']);
    #Update the option array for the widget
    update_option('widget_showgdoc',$options);
}

function widget_showgdoc($args) {
	extract($args);
	#Get users options
	$options = get_option('widget_showgdoc');
	echo $before_widget;
	echo $before_title;
	echo $options['widget_title'];
	echo $after_title;
	#show the data from ShowData function with the selected user options
	ShowData($options['user'],$options['pass'],$options['spread'],$options['colname'],$options['collink'],$options['colended']);
	echo $after_widget;
}
   
function showgdoc_init(){
	#register the widget control and the sidebar plugin
	register_widget_control('showgdoc','control');
	register_sidebar_widget(__('showgdoc'), 'widget_showgdoc');
}

add_action("plugins_loaded", "showgdoc_init");
?>
