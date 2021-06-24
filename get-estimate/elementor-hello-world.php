<?php
/**
 * Plugin Name: Estimation Plugin
 * Description: Get Estimation Plugin, with redirection and email sending capabilities, also uses cookies for redirection authentication, uses elementor.
 * Version:     1.2.1
 * Author:      Diganta Ray
 * Author URI:  ray.diganta00@gmail.com
 * Elementor tested up to: 3.0.0
 * Elementor Pro tested up to: 3.0.0
 * 
 * 
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action('wpcf7_before_send_mail','est_do');
add_action( 'admin_menu', 'estimation_add_menu_page' );
add_action('wp_ajax_est_get_obj', 'est_get_obj');
add_action('wp_ajax_est_set_obj', 'est_set_obj');
add_action('wp_ajax_est_update_link', 'est_update_link');
add_action('wp_ajax_est_get_update_link', 'est_get_update_link');
add_action('wp_ajax_est_estimate_shortcode_link', 'est_estimate_shortcode_link');
add_action('wp_ajax_est_get_estimate_shortcode_link', 'est_get_estimate_shortcode_link');
add_action('wp_ajax_est_set_cookie', 'est_set_cookie');
add_action('wp_ajax_nopriv_est_set_cookie', 'est_set_cookie');
add_action('wp_ajax_est_update_email', 'est_update_email');
add_action('wp_ajax_est_set_data', 'est_set_data');
add_action('wp_ajax_nopriv_est_set_data', 'est_set_data');
add_action('init', 'est_redirect');
add_shortcode( 'est_frontend_inp', 'est_frontend_inp_render' );
add_shortcode( 'est_output', 'est_output_render' );


function est_set_data() {
    if (isset($_POST['data'])) {
        setcookie('est_data', "", time()-1000, COOKIEPATH, COOKIE_DOMAIN);
        setcookie('est_data', $_POST['data'], time()+1000000000, COOKIEPATH, COOKIE_DOMAIN);
    }
    wp_die();
}

function  est_do($args) {
    if (isset($_COOKIE['est_data'])) {
        $arr = json_decode(stripslashes(stripslashes($_COOKIE['est_data'])), true);
        if ($arr != null) {
            foreach ($_REQUEST as $key => $value) {
                $arr[$key] = $value;
            }
        }
        $arr['form_found'] = true;

    }
    setcookie('est_data', "", time()-1000, COOKIEPATH, COOKIE_DOMAIN);
    setcookie('est_data', json_encode($arr), time()+1000000000, COOKIEPATH, COOKIE_DOMAIN);
    //header("Location: " . get_option("est_estimate_shortcode_link"));
    //echo var_dump($_REQUEST);
}

function est_redirect () {
    $selfurl = "";
    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
        $selfurl = "https://";   
    else  
        $selfurl = "http://";   
    // Append the host(domain name, ip) to the URL.   
    $selfurl.= $_SERVER['HTTP_HOST']; 
    $selfurl.= $_SERVER['REQUEST_URI'];
    if ($selfurl == get_option("est_estimate_shortcode_link")) {
        if (isset($_COOKIE['est_data'])) {
            $arr  = json_decode(stripslashes($_COOKIE['est_data']), true);
            if (isset($arr['form_found'])) {
                $link = get_option('est_link');
                $owner_string = "<table>";
                $os1 = json_decode(get_option("est_step1"), true);
                $os2 = json_decode(get_option("est_step2"), true);
                $os3 = json_decode(get_option("est_step3"), true);
                foreach ($arr as $key => $value) {
                    $key = htmlspecialchars($key);
                    $value = htmlspecialchars($value);
                    $owner_string .= "<tr><td>{$key}</td><td>{$value}</td></tr>";
                }
                if (isset($os1[$arr["1"]])) {
                    $owner_string .= "<tr><td>House Type</td><td>" . $os1[$arr["1"]]["description"] . "</td></tr>";
                }
                if (isset($os2[$arr["2"]])) {
                    $owner_string .= "<tr><td>Product Type</td><td>" . $os2[$arr["2"]]["category"] . "</td></tr>";
                }
                if (isset($os3[$arr["3"]])) {
                    $owner_string .= "<tr><td>Fuel Type</td><td>" . $os3[$arr["3"]]["description"] . "</td></tr>";
                }
                $owner_string .= "</table>";
                $client_string = "<a href='" . $link . "?cid=" . $arr["2"] . "'>Get Your Free Estimate</a>";
                $headers = array('Content-Type: text/html; charset=UTF-8');
                if (isset($arr["email"])) 
                    wp_mail($arr["email"], "Your Free Estimate is Ready", $client_string);
                wp_mail(get_option('est_email'), "New Lead", $owner_string);
                header("Location: " . $link);
                exit();
            }
        }
        if (isset($_COOKIE['est_data'])) {
            $arr  = json_decode(stripslashes($_COOKIE['est_data']), true);
            if ($arr == null) {
                $arr = json_decode(stripslashes(stripslashes($_COOKIE['est_data'])), true);
            }
            if (isset($arr['form_found'])) {
                $link = get_option('est_link');
                header("Location: " . $link);
                exit();
            }
        }
    }
    $link = get_option('est_estimate_shortcode_link');
    if (isset($_GET['cid'])) {
        $explodeurl = explode("/", $selfurl);
        array_pop($explodeurl);
        $selfurl = implode("/", $explodeurl);
        $selfurl .= "/";
        if ($selfurl == get_option('est_link')) {
            $options_structure = json_decode(get_option('est_step2'), true);
            $found = false;
            foreach ($options_structure as $id => $obj) {
                if ($id == $_GET['cid']) {
                    $found = true;
                }
            }
            if (!$found) {
                header("Location: " . $link);
                exit();
            }
        }
    } else if ($selfurl == get_option('est_link')) {
        if (!isset($_COOKIE['est_data'])) {
            header("Location: " . $link);
            exit();
        } else {
            $found = false;
            $options_structure = json_decode(get_option('est_step2'), true);
            foreach ($options_structure as $id => $obj) {
                if ($id == json_decode(stripslashes($_COOKIE['est_data']), true)["2"]) {
                    $found = true;
                }
            }
            if (!$found) {
                header("Location: " . $link);
                exit();
            }
        }
    }
    

// Append the requested resource location to the URL  
    
}

function est_update_email() {
    if (isset($_POST['link'])) {
        $option = get_option('est_email');
        if (!$option) {
            add_option('est_email', $_POST['link']);
        }
        update_option('est_email', $_POST['link']);
    }
    wp_die();
}

function estimation_add_menu_page() {
    add_menu_page( 'Estimation', 'Estimation', 'manage_options', 'estimation-plugin', 'estimation_render_page' );
}

function est_output_render($atts = array(), $content = null) {
    
}

function est_show_shortcode($type, $id) {
    $options_structure = json_decode(get_option("est_step2"), true);
    return $options_structure[$id][$type];
}


function est_frontend_inp_render() {
    
}

function est_get_update_link() {
    echo get_option('est_link');
    wp_die();
}

function est_get_estimate_shortcode_link () {
    echo get_option('est_estimate_shortcode_link');
    wp_die();
}

function est_update_link() {
    if (isset($_POST['link'])) {
        $link = $_POST['link'];
        $options_structure = get_option('est_link');
        if (!$options_structure) {
            add_option('est_link', "");
            $options_structure = get_option('est_link');
        }
        update_option('est_link', $link);
    }
    wp_die();
}

function est_estimate_shortcode_link() {
    if (isset($_POST['link'])) {
        $link = $_POST['link'];
        $options_structure = get_option('est_estimate_shortcode_link');
        if (!$options_structure) {
            add_option('est_estimate_shortcode_link', "");
            $options_structure = get_option('est_estimate_shortcode_link');
        }
        update_option('est_estimate_shortcode_link', $link);
    }
    wp_die();
}

function est_get_obj() {
    if (isset($_POST['type'])) {
        $type = $_POST['type'];
        $options_structure = get_option($type);
        if (!$options_structure) {
            add_option($type, "{}");
            $options_structure = get_option($type);
        }
        echo $options_structure;
    }
    wp_die();
}

function est_set_obj() {
    if (isset($_POST['type']) && isset($_POST['obj'])) {
        $type = $_POST['type'];
        $obj = stripslashes($_POST['obj']);
        $options_structure = get_option($type);
        if (!$options_structure) {
            add_option($type, "{}");
        }
        update_option($type, $obj);
    }
    wp_die();
}

function estimation_render_page() {
    wp_enqueue_media();
?>
<style>
a {
    text-decoration: none;
}
td {
    padding: 10px;
}
</style>
<br>
<table>
    <tr>
        <td>Page Where Get Estimate Shortcode Exists</td>
        <td><input type="text" placeholder="Enter Link" id="est_estimate_shortcode_link" onkeyup="est_estimate_shortcode_link()"></td>
    </tr>
    <tr>
        <td>Page Redirect Link</td>
        <td><input type="text" placeholder="Page Redirect Link" id="est_red_link" onkeyup="est_update_link()"></td>
    </tr>
    <tr>
        <td>Your Email</td>
        <td><input type="text" placeholder="Page Redirect Link" id="est_update_email" onkeyup="est_update_email()" value="<?php if (!get_option("est_email")) echo add_option("est_email"); else echo get_option("est_email"); ?>"></td>
    </tr>
</table>
<br>
<br>
<h2>Step 1(House Type)</h2>
<a href="#" onclick="est_add_step1_row()">Add House Type</a>
<div id="est_step1_config">
    <table>
        <tbody>
            <tr>
                <td></td>
                <td>Type</td>
                <td>Image</td>
                <td>Choose</td>
                <td></td>
            </tr>
        </tbody>
    </table>
</div>
<a href="#" onclick="est_save_step1()">Save Changes</a>
<h2>Step 2</h2>
<a href="#" onclick="est_add_step2_row()">Add Heater Config</a>
<div id="est_step2_config">
    <table>
        <tbody>
            <tr>
                <td></td>
                <td>Category<br>
                    [est_output type="category"]
                </td>
                <td>Price<br>
                    [est_output type="price"]
                </td>
                <td>Per Month<br>
                    [est_output type="perMonth"]
                </td>
                <td>Extra<br>
                    [est_output type="extra"]
                </td>
                <td>Image<br>
                    [est_output type="image"]
                </td>
                <td>Change Image</td>
                <td>Product Image<br>
                    [est_output type="productImage"]
                </td>
                <td>Change Product Image</td>
                <td></td>
            </tr>
        </tbody>
    </table>
</div>
<a href="#" onclick="est_save_step2()">Save Changes</a>
<h2>Step 3 (Fuel Type)</h2>
<a href="#" onclick="est_add_step3_row()">Add Fuel Type</a>
<div id="est_step3_config">
    <table>
        <tbody>
            <tr>
                <td></td>
                <td>Type</td>
                <td>Image</td>
                <td>Choose Image</td>
                <td></td>
            </tr>
        </tbody>
    </table>
</div>
<a href="#" onclick="est_save_step3()">Save Changes</a>
<script>

var js1Obj = {}, js2Obj = {}, js3Obj = {};

function est_update_link() {
    var elem = jQuery("#est_red_link");
    var obj = {'action': 'est_update_link'};
    obj.link = elem.val();
    jQuery.post(ajaxurl, obj, function(response) {

    });
}

function est_update_email() {
    var elem = jQuery("#est_update_email");
    var obj = {'action': 'est_update_email'};
    obj.link = elem.val();
    jQuery.post(ajaxurl, obj, function(response) {

    });
}

function est_estimate_shortcode_link() {
    var elem = jQuery("#est_estimate_shortcode_link");
    var obj = {'action': 'est_estimate_shortcode_link'};
    obj.link = elem.val();
    jQuery.post(ajaxurl, obj, function(response) {

    });
}

var elem = jQuery("#est_estimate_shortcode_link");
var obj = {'action': 'est_get_estimate_shortcode_link'};
jQuery.post(ajaxurl, obj, function(response) {
    elem.val(response);
    elem = jQuery("#est_red_link");
    var obj = {'action': 'est_get_update_link'};
    jQuery.post(ajaxurl, obj, function(response) {
        elem.val(response);
    });
});



function est_get_obj (type, callback) {
    var obj = {'action': 'est_get_obj'};
    obj.type = type;
    jQuery.post(ajaxurl, obj, function(response) {

        var obj2 = JSON.parse(response);
        console.log(response);
        callback(obj2);
    });
}

function est_set_obj (type, callback) {
    var obj = {'action': 'est_set_obj'};
    obj.type = type;
    var refObj = {};
    if (type == 'est_step2') {
        refObj = js2Obj;
    } else if (type == 'est_step1') {
        refObj = js1Obj;
    } else {
        refObj = js3Obj;
    }
    obj.obj = JSON.stringify(refObj);
    jQuery.post(ajaxurl, obj, function(response) {
        console.log(response);
        callback();
    });
}

function uniqueId () {
  const dateString = Date.now().toString(36);
  const randomness = Math.random().toString(36).substr(2);
  return dateString + randomness;
}

function est_delete_step2_row(id) {
    delete js2Obj[id];
    est_set_obj('est_step2', function () {
        alert("Item Deleted");
    });
    est_step2_object_to_table();
}

function est_delete_common_row(id, i) {
    if (i == 1) {
        delete js1Obj[id];
    } else {
        delete js3Obj[id];
    }
    var setstr = 'est_step' + i; 
    est_set_obj(setstr, function () {
        alert("Item Deleted");
    });
    est_common_object_to_table(i);
}

function est_step2_table_to_object() {
    var table = jQuery("#est_step2_config > table > tbody");
    var rows = table.find("tr");
    rows.each(function () {
        if (jQuery(this).data('id') != null) {
            var id = jQuery(this).data('id');
            var index = 0;
            var rowObj = {};
            var tds = jQuery(this).find('td').each(function () {
                if (index == 1) {
                    rowObj.category = jQuery(this).html(); 
                } else if (index == 2) {
                    var td = jQuery(this);
                    var p = 0;
                    rowObj.price = [];
                    td.find('div').each(function () {
                        rowObj.price[p] = jQuery(this).html();
                        p++;
                    }); 
                } else if (index == 3) {
                    var td = jQuery(this);
                    var p = 0;
                    rowObj.perMonth = [];
                    td.find('div').each(function () {
                        rowObj.perMonth[p] = jQuery(this).html();
                        p++;
                    });
                } else if (index == 4) {
                    var td = jQuery(this);
                    var p = 0;
                    rowObj.extra = [];
                    td.find('div').each(function () {
                        rowObj.extra[p] = jQuery(this).html();
                        p++;
                    });
                } else if (index == 5) {
                    rowObj.image = (jQuery(this).find('img').attr('src') == null) ? "": jQuery(this).find('img').attr('src');
                } else if (index == 7) {
                    var td = jQuery(this);
                    var p = 0;
                    rowObj.productImage = [];
                    td.find('div').each(function () {
                        rowObj.productImage[p] = (jQuery(this).find('img').attr('src') == null) ? "": jQuery(this).find('img').attr('src');
                        p++;
                    });
                }
                index++;
            });
            js2Obj[id] = rowObj;
        }
        console.log(js2Obj);
    });
}

function est_common_table_to_object(i) {
    var table = jQuery("#est_step" + i + "_config > table > tbody");
    var rows = table.find("tr");
    rows.each(function () {
        if (jQuery(this).data('id') != null) {
            var id = jQuery(this).data('id');
            var index = 0;
            var rowObj = {};
            var tds = jQuery(this).find('td').each(function () {
                if (index == 1) {
                    rowObj.description = jQuery(this).html(); 
                } else if (index == 2) {
                    rowObj.image = (jQuery(this).find('img').attr('src') == null) ? "": jQuery(this).find('img').attr('src');
                }
                index++;
            });
            if (i == 1) {
                js1Obj[id] = rowObj;
            } else {
                js3Obj[id] = rowObj;
            }
        }
    });
}

function est_step2_object_to_table() {
    var table = jQuery("#est_step2_config > table > tbody");
    table.html("");
    table.html(table.html() + "<tr><td></td><td>Category<br>[est_output type=\"category\"]</td><td>Price<br>[est_output type=\"price\"]</td><td>Per Month<br>[est_output type=\"perMonth\"]</td><td>Extra<br>[est_output type=\"extra\"]</td><td>Image<br>[est_output type=\"image\"]</td><td>Choose Image</td><td>Product Image</td><td>Choose Product Image</td><td></td></tr>");
    var ids = Object.keys(js2Obj);
    for (var i = 0; i< ids.length; i++) {
        var rowObj = js2Obj[ids[i]];
        var imgstr = "";
        if (rowObj.image.length > 0) {
            imgstr = "<img src='" + rowObj.image + "' style='width: 100px;'>";
        }
        var productImg = ["", "", ""];
        productImg[0] = "<img src='" + rowObj.productImage[0] + "' style='width: 100px;'>";
        productImg[1] = "<img src='" + rowObj.productImage[1] + "' style='width: 100px;'>";
        productImg[2] = "<img src='" + rowObj.productImage[2] + "' style='width: 100px;'>";
        str = "<tr data-id='" + ids[i] + "'><td></td>";
        str += "<td contenteditable='true'>" + rowObj.category + "</td>";
        str += "<td><div contenteditable='true'>" + rowObj.price[0] + "</div><div contenteditable='true'>" + rowObj.price[1] + "</div><div contenteditable='true'>" + rowObj.price[2] + "</div></td>";
        str += "<td><div contenteditable='true'>" + rowObj.perMonth[0] + "</div><div contenteditable='true'>" + rowObj.perMonth[1] + "</div><div contenteditable='true'>" + rowObj.perMonth[2] + "</div></td>";
        str += "<td><div contenteditable='true'>" + rowObj.extra[0] + "</div><div contenteditable='true'>" + rowObj.extra[1] + "</div><div contenteditable='true'>" + rowObj.extra[2] + "</div></td>";
        str += "<td>" + imgstr + "</td>";
        str += "<td><a href='#' onclick='est_choose_img(\"" + ids[i]+ "\", 2);'>Choose Image</a></td>";
        str += "<td><div>" + productImg[0] + "</div><div>" + productImg[1] + "</div><div>" + productImg[2] + "</div></td>";
        str += "<td><div><a href='#' onclick='est_choose_product_img(\"" + ids[i]+ "\", 0);'>Choose Product Image</a></div><div><a href='#' onclick='est_choose_product_img(\"" + ids[i]+ "\", 1);'>Choose Product Image</a></div><div><a href='#' onclick='est_choose_product_img(\"" + ids[i]+ "\", 2);'>Choose Product Image</a></div></td>";
        str += "<td><a href='#' onclick=\"est_delete_step2_row('" + ids[i] + "')\">Delete</a></td></tr>";
        table.html(table.html() + str);
    }
}

function est_common_object_to_table(i) {
    var table = jQuery("#est_step" + i + "_config > table > tbody");
    table.html("");
    table.html(table.html() + "<tr><td></td><td>Description</td><td>Image</td><td>Change Image</td><td></td></tr>");
    var ids = (i == 1) ? Object.keys(js1Obj): Object.keys(js3Obj);
     var obj = (i == 1) ? js1Obj: js3Obj;
    for (var j = 0; j< ids.length; j++) {
        var rowObj = obj[ids[j]];
        var imgstr = "";
        if (obj[ids[j]].image.length > 0) {
            imgstr = "<img src='" + rowObj.image + "' style='width: 100px;'>";
        }
        str = "<tr data-id='" + ids[j] + "'><td></td><td contenteditable='true'>" + rowObj.description + "</td><td>" + imgstr + "</td><td><a href='#' onclick='est_choose_img(\"" + ids[j]+ "\", " + i + ");'>Choose Image</a></td><td><a href='#' onclick=\"est_delete_common_row('" + ids[j] + "', " + i +")\">Delete</a></td></tr>";
        table.html(table.html() + str);
    }
}

function est_choose_img (id, i) {
    var frame;
        
    // Create a new media frame
    frame = wp.media({
        title: 'Select or Upload Media Of Your Chosen Persuasion',
        button: {
            text: 'Use this media'
        },
        multiple: false  // Set to true to allow multiple files to be selected
    });
    frame.open();
    frame.on('select', function () {
        var attachment = frame.state().get('selection').first().toJSON();
        
        var image = attachment.url;
        console.log(attachment);
        // We can also pass the url value separately from ajaxurl for front end AJAX implementations
        var table = jQuery("#est_step" + i + "_config > table > tbody");
        table.find("tr").each(function () {
            if (jQuery(this).data('id') == id) {
                var index = 0;
                var rowObj = {};
                var tds = jQuery(this).find('td').each(function () {
                    if (i == 2) {
                        if (index == 5) {
                            jQuery(this).html("<img src='" + image + "' style='width: 100px;'>"); 
                        }
                    } else {
                        if (index == 2) {
                            jQuery(this).html("<img src='" + image + "' style='width: 100px;'>"); 
                        }
                    }
                    index++;
                });
            }
        });
    });
}

function est_choose_product_img (id, i) {
    var frame;
        
    // Create a new media frame
    frame = wp.media({
        title: 'Select or Upload Media Of Your Chosen Persuasion',
        button: {
            text: 'Use this media'
        },
        multiple: false  // Set to true to allow multiple files to be selected
    });
    frame.open();
    frame.on('select', function () {
        var attachment = frame.state().get('selection').first().toJSON();
        
        var image = attachment.url;
        console.log(attachment);
        // We can also pass the url value separately from ajaxurl for front end AJAX implementations
        var table = jQuery("#est_step2_config > table > tbody");
        table.find("tr").each(function () {
            if (jQuery(this).data('id') == id) {
                var index = 0;
                var rowObj = {};
                jQuery(this).find('td').each(function () {
                    if (index == 7) {
                        var k = 0;
                        jQuery(this).find('div').each(function () {
                            if (k == i)
                                jQuery(this).html("<img src='" + image + "' style='width: 100px;'>");
                            k++;
                        }); 
                    }
                    index++;
                });
            }
        });
    });
}

function est_save_step2() {
    est_step2_table_to_object();
    est_set_obj('est_step2', function (){
        alert("Changes Saved");
    });
}
function est_save_step1() {
    est_common_table_to_object(1);
    est_set_obj('est_step1', function (){
        alert("Changes Saved");
    });
}
function est_save_step3() {
    est_common_table_to_object(3);
    est_set_obj('est_step3', function (){
        alert("Changes Saved");
    });
}
function est_add_step2_row() {
    var inner = jQuery("#est_step2_config > table > tbody");
    var uid = uniqueId();
    str = "<tr data-id='" + uid + "'><td></td>";
    str += "<td contenteditable='true'>Edit Name</td>";
    str += "<td><div contenteditable='true'>0</div><div contenteditable='true'>0</div><div contenteditable='true'>0</div></td>";
    str += "<td><div contenteditable='true'>0</div><div contenteditable='true'>0</div><div contenteditable='true'>0</div></td>";
    str += "<td><div contenteditable='true'>0</div><div contenteditable='true'>0</div><div contenteditable='true'>0</div></td>";
    str += "<td></td>";
    str += "<td><a href='#' onclick='est_choose_img(\"" + uid + "\",2)'>Choose Image</a></td>";
    str += "<td><div></div><div></div><div></div></td>";
    str += "<td><div><a href='#' onclick='est_choose_product_img(\"" + uid + "\",0)'>Choose Image</a></div><div><a href='#' onclick='est_choose_product_img(\"" + uid + "\",1)'>Choose Image</a></div><div><a href='#' onclick='est_choose_product_img(\"" + uid + "\",2)'>Choose Image</a></div></td>";
    str += "<td><a href='#' onclick=\"est_delete_step2_row('" + uid + "')\">Delete</a></td></tr>";
    inner.html(inner.html() + str);
    est_step2_table_to_object();
}

function est_add_step1_row() {
    est_add_common_row(1);
}

function est_add_step3_row() {
    est_add_common_row(3);
}

function est_add_common_row(i) {
    var inner = jQuery("#est_step" + i + "_config > table > tbody");
    var uid = uniqueId();
    str = "<tr data-id='" + uid + "'><td></td><td contenteditable='true'>Edit Description</td><td></td><td><a href='#' onclick='est_choose_img(\"" + uid + "\", " + i + ")'>Choose Image</a></td><td><a href='#' onclick=\"est_delete_step2_row('" + uid + "')\">Delete</a></td></tr>";
    inner.html(inner.html() + str);
    est_step2_table_to_object();
}

est_get_obj('est_step2', function (obj) {
    js2Obj = obj;
    est_step2_object_to_table();
});
est_get_obj('est_step1', function (obj) {
    js1Obj = obj;
    est_common_object_to_table(1);
});
est_get_obj('est_step3', function (obj) {
    js3Obj = obj;
    est_common_object_to_table(3);
});
</script>
<?php
}
?>

<?php
/**
 * Main Elementor Hello World Class
 *
 * The init class that runs the Hello World plugin.
 * Intended To make sure that the plugin's minimum requirements are met.
 *
 * You should only modify the constants to match your plugin's needs.
 *
 * Any custom code should go inside Plugin Class in the plugin.php file.
 * @since 1.2.0
 */
final class Elementor_Hello_World {

	/**
	 * Plugin Version
	 *
	 * @since 1.2.1
	 * @var string The plugin version.
	 */
	const VERSION = '1.2.1';

	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.2.0
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '3.0.0';

	/**
	 * Minimum PHP Version
	 *
	 * @since 1.2.0
	 * @var string Minimum PHP version required to run the plugin.
	 */
	const MINIMUM_PHP_VERSION = '7.0';

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		// Load translation
		add_action( 'init', array( $this, 'i18n' ) );

		// Init Plugin
		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}

	/**
	 * Load Textdomain
	 *
	 * Load plugin localization files.
	 * Fired by `init` action hook.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function i18n() {
		load_plugin_textdomain( 'elementor-hello-world' );
	}

	/**
	 * Initialize the plugin
	 *
	 * Validates that Elementor is already loaded.
	 * Checks for basic plugin requirements, if one check fail don't continue,
	 * if all check have passed include the plugin class.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function init() {

		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_missing_main_plugin' ) );
			return;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_elementor_version' ) );
			return;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_php_version' ) );
			return;
		}

		// Once we get here, We have passed all validation checks so we can safely include our plugin
		require_once( 'plugin.php' );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_missing_main_plugin() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'elementor-hello-world' ),
			'<strong>' . esc_html__( 'Elementor Hello World', 'elementor-hello-world' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'elementor-hello-world' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-hello-world' ),
			'<strong>' . esc_html__( 'Elementor Hello World', 'elementor-hello-world' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'elementor-hello-world' ) . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_php_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-hello-world' ),
			'<strong>' . esc_html__( 'Elementor Hello World', 'elementor-hello-world' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'elementor-hello-world' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}
}

// Instantiate Elementor_Hello_World.
new Elementor_Hello_World();
