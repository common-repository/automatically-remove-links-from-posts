<?php
/*
Plugin Name: Automatically Remove Links From Posts
Plugin URI: http://rubensargsyan.com/wordpress-plugin-automatically-remove-links-from-posts/
Description: Automatically remove links from posts when they are published and keep the anchor text in tact.
Version: 1.0
Author: Ruben Sargsyan, Morris Bryant, Ricardo Braga
Author URI: http://rubensargsyan.com/
*/

/*  Copyright 2011 Ruben Sargsyan (email: info@rubensargsyan.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, see <http://www.gnu.org/licenses/>.
*/


function remove_links_from_post($post){
    $post_content = stripslashes($post["post_content"]);

    if(!preg_match_all("/(<a.*>)(.*)(<\/a>)/ismU",$post_content,$outbound_links,PREG_SET_ORDER)){
        return $post;
    }

    foreach($outbound_links as $key => $value){
        preg_match("/href\s*=\s*[\'|\"]\s*(.*)\s*[\'|\"]/i",$value[1],$href);

        if((substr($href[1],0,7)!="http://" && substr($href[1],0,8)!="https://") || substr($href[1],0,strlen(get_bloginfo("url")))==get_bloginfo("url")){
            unset($outbound_links[$key]);
        }else{
            $post_content = str_replace($outbound_links[$key][0],$outbound_links[$key][2],$post_content);
        }
    }

    $post["post_content"] = addslashes($post_content);

    return $post;
}

add_filter("wp_insert_post_data", "remove_links_from_post");
?>