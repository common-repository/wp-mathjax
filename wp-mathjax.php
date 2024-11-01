<?php
/*
Plugin Name: WP MathJax
Plugin URI: http://www.yelinkyaw.com
Description: MathJax for Wordpress
Author: Ye Lin Kyaw
Version: 1.0.2
Author URI: http://www.yelinkyaw.com
*/

// Add MathJax to Header
add_action('wp_head', 'mathjax');

//Add MathJax to Footer
$delay = get_option('wp_mathjax_delayStartupUntil');
if($delay=='1')
{
	add_action('wp_footer', 'mathjax_foot');
}

// MathJax Header
function mathjax()
{
	// CDN MathJax Javascript
	$mathjax_url = 'http://cdn.mathjax.org/mathjax/latest/MathJax.js';
	
	// Get MathJax Config
	$config = get_option('wp_mathjax_config');
	
	// Get Delay
	$delay = get_option('wp_mathjax_delayStartupUntil');
	if($delay=='1')
	{
		$config=$config."&delayStartupUntil=configured";
	}
	
	echo "<script type=\"text/javascript\" src=\"$mathjax_url?config=$config\"></script>\n";
}

// MathJax Footer
function mathjax_foot()
{
	echo "<script type=\"text/javascript\">\nMathJax.Hub.Configured()\n</script>\n";
}

// Add Admin Panel
add_action('admin_menu', 'wp_mathjax_option');

// Admin Panel
function wp_mathjax_option()
{
	add_options_page('WP MathJax', 'WP MathJax', 'administrator', 'wp-mathjax.php', 'admin_options');
}

function admin_options()
{
	if(isset($_POST['Submit']))
	{
		update_option('wp_mathjax_local', $_POST['wp_mathjax_local']);
		update_option('wp_mathjax_config', $_POST['wp_mathjax_config']);
		update_option('wp_mathjax_delayStartupUntil', $_POST['wp_mathjax_delayStartupUntil']);
	}
	
	//Initialization
	if (get_option('wp_mathjax_config') =='')
	{
		update_option('wp_mathjax_local', 0);
		update_option('wp_mathjax_config', 'TeX-AMS-MML_HTMLorMML');
		update_option('wp_mathjax_delayStartupUntil', '1');
	}
	
	//Show Admin UI
	admin_ui();
}
?>
<?php
function admin_ui()
{
?>
<h2>WP MathJax Settings</h2>
<form method="post" action="options-general.php?page=wp-mathjax.php">
	<table>
		<tr>
			<td colspan="2"><h3>MathJax Configuration</h3></td>
		</tr>
		<?php
		$config_dir = plugin_dir_path(__FILE__).'MathJax/config/';
		$files = scandir($config_dir);
		foreach($files as $file)
		{
			if(is_file($config_dir.$file))
			{
				$path_parts = pathinfo($config_dir.$file);
				$config_file = $path_parts['filename'];
				$checked = '';
				if(get_option('wp_mathjax_config')==$config_file)
				{
					$checked = 'checked="checked"';
				}
				echo "<tr><td>$config_file:</td><td><input type=\"radio\" name=\"wp_mathjax_config\" value=\"$config_file\" $checked/></td></tr>";
			}
		}
		?>
		<tr>
			<td colspan="2"><h3>MathJax Javascript Delay Startup</h3></td>
		</tr>
		<tr>
			<td>delayStartupUntil:</td><td><input type="checkbox" name="wp_mathjax_delayStartupUntil" value="1" <?php if(get_option('wp_mathjax_delayStartupUntil')=='1') echo 'checked="checked"'; ?>/></td>
		</tr>
	</table>
	<input type="submit" name="Submit" value="Save Settings" />		
</form>
<?php
}
?>
