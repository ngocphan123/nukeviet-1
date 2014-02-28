<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if( ! defined( 'NV_IS_MOD_PAGE' ) ) die( 'Stop!!!' );

$contents = '';

if( $id )
{
	$cache_file = NV_LANG_DATA . '_' . $module_name . '_' . $module_info['template'] . '_' . $id . '_' . NV_CACHE_PREFIX . '.cache';
	// Cache tung giao dien
	
	if( ( $cache = nv_get_cache( $cache_file ) ) != false )
	{
		$cache = unserialize( $cache );
		$module_info['layout_funcs'][$op_file] = !empty( $cache['layout_func'] ) ? $cache['layout_func'] : $module_info['layout_funcs'][$op_file];
		$page_title = $mod_title = $cache['page_title'];
		$key_words = $cache['keywords'];
		$contents = $cache['contents'];
		
	}
	else
	{
		$cache = array();

		$sql = 'SELECT id,title,alias,description,bodytext,keywords,image,add_time,edit_time,layout_func FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE status=1 AND id=' . $id;
		$query = $db->query( $sql );
		$row = $query->fetch();
		if( !empty( $row['image'] ) && ! nv_is_url( $row['image'] ) )
		{
			$row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $row['image'];
		}
		$row['add_time'] = nv_date( 'H:i T l, d/m/Y', $row['add_time'] );
		$row['edit_time'] = nv_date( 'H:i T l, d/m/Y', $row['edit_time'] );
		$contents = $cache['contents'] = nv_page_main( $row, $ab_links );
		$cache['bodytext'] = strip_tags( $row['bodytext'] );
		$cache['bodytext'] = nv_clean60( $cache['bodytext'], 300 );
		$cache['layout_func'] = $row['layout_func'];
		$module_info['layout_funcs'][$op_file] = !empty( $cache['layout_func'] ) ? $cache['layout_func'] : $module_info['layout_funcs'][$op_file];
		
		$page_title = $mod_title = $cache['page_title'] = $row['title'];

		if( ! empty( $row['keywords'] ) )
		{
			$key_words = $cache['keywords'] = $row['keywords'];
		}
		else
		{
			$key_words = nv_get_keywords( $row['bodytext'] );

			if( empty( $key_words ) )
			{
				$key_words = nv_unhtmlspecialchars( $row['title'] );
				$key_words = strip_punctuation( $key_words );
				$key_words = trim( $key_words );
				$key_words = nv_strtolower( $key_words );
				$key_words = preg_replace( '/[ ]+/', ',', $key_words );
			}

			$cache['keywords'] = $key_words;

			$stmt = $db->prepare( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET keywords= :keywords WHERE id =' . $id );
			$stmt->bindParam( ':keywords', $keywords, PDO::PARAM_STR, strlen( $keywords ) );
			$stmt->execute();

		}

		$cache['alias'] = $row['alias'];

		//Dung cho Block
		$cache = serialize( $cache );
		nv_set_cache( $cache_file, $cache );
	}
}
else
{
	$page_title = $module_info['custom_title'];
	$key_words = $module_info['keywords'];
	$mod_title = isset( $lang_module['main_title'] ) ? $lang_module['main_title'] : $module_info['custom_title'];
}

if( ! defined( 'SHADOWBOX' ) )
{
	$my_head .= "<link type=\"text/css\" rel=\"Stylesheet\" href=\"" . NV_BASE_SITEURL . "js/shadowbox/shadowbox.css\" />\n";
	$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/shadowbox/shadowbox.js\"></script>\n";
	$my_head .= "<script type=\"text/javascript\">Shadowbox.init({ handleOversize: \"drag\" });</script>";
	define( 'SHADOWBOX', true );
}
include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';

?>