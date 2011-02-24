<?php
/*
Plugin Name: Readability Verifier
Plugin URI: http://readability.com
Description: A simple plugin that allows you to start tracking your Readability reader contributions.
Author: Arc90
Version: 1.0
Author URI: http://arc90.com
*/

/*  Copyright 2011  

    Have a comment or question?
       https://readability.com/contact/
    
    Code adapted from Webmaster Tools Verification by James Pegram

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

add_action('admin_menu', 'rdb_pub_add_admin_page');
add_action('rdb_pub_update_verification', 'rdb_pub_save_update');
add_action('wp_head', 'rdb_pub_add_meta',99);

function rdb_pub_add_admin_page() {
    add_management_page('Readability Verifier', 'Readability Verifier', 'manage_options', 'rdb-pub-tools', 'rdb_pub_verify_domain');
}

function rdb_pub_save_update() {
	$rdb_verification_code = str_replace('<meta name=\"readability-verification\" content=\"','',$_POST['rdb_verification_code']);
	$rdb_verification_code = str_replace('\"/>','',$rdb_verification_code);
	update_option( 'readability_publisher[verification_code]' , $rdb_verification_code );
}

function rdb_pub_verify_domain() {
?>
<div class="tool-box">
	<form method="post">
	<input type="hidden" name="action" value="update" />
	 <?php wp_nonce_field('webmaster_tools'); ?>
	<h3 class="title"><?php _e('Readability Verifier') ?></h3> 
<?php if ($_POST['action'] == 'update') { ?>
		<p style="color:green;font-weight:600">Verification Code saved! Now go <a href="http://readability.com">back to Readability</a> and verify your domain!</p>
		<p>(NOTE: If your domain will not validate, please make sure any caching tools, like WP SuperCache, are temporarily disabled.)</p>
<?php	do_action( 'rdb_pub_update_verification' ); 
	}
?>
		<table class="form-table">
			<tr valign='top'>
			<th scope='row'>Readability Publisher Code</th>
				<td>
					<input size='50' name='rdb_verification_code' type='text' value="<?php echo get_option('readability_publisher[verification_code]'); ?>" />
				</td>
			</tr>
			<tr>
				<td style="vertical-align:top;width:5em;">
					<label for='rdb_verification_code'>Example:</label>
				</td>
				<td><code>&lt;meta name="readability-verification" content="<strong><font color="red">hxhepkhH9AZse7WdfqutAXTjLam3ERvdqzdMHdYX</font></strong>"/&gt;</code>
					<p>(The code is the text inside the &#147;content&#148; attribute of the meta tag you received from Readability).</p>
				</td>
			</tr>
		</table>
	<p class="submit"><input type="submit" class="button-primary" value="Save Changes" /></p>
	</form>
	</div>
	<?php
}

function rdb_pub_add_meta() {
if (get_option('readability_publisher[verification_code]') != '') { ?>
<meta name="readability-verification" content="<?php echo get_option('readability_publisher[verification_code]'); ?>" />
<?php
}
}
