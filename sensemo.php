<?php
   /*
   Plugin Name: Sensemo
   Plugin URI: http://vtricity.com/sensemo-wordpress-plugin/
   Description: Easily include Sensemo feedback poll shortcodes. Sensemo is the world's first emotional feedback cloud service.
   Version: 1.0.1
   Author: vTricity LLC
   Author URI: http://vtricity.com/about
   License: GPLv2 or later
   */

add_action('admin_menu', 'sensemo_menu');
function sensemo_menu() {
    add_menu_page('Sensemo Settings', 'Sensemo', 'administrator', 'sensemo', 'sensemo_settings_page', 'dashicons-admin-generic');
}
function sensemo_settings_page() {
?> 
       <style>
            .sensemo-shortcode-example{margin:10px 0 0 0;}
            .sensemo-logo{margin:20px 0 5px -8px;}
            .sensemo-logo > a:hover, .sensemo-logo > a:focus, .sensemo-logo > a:visited, .sensemo-logo > a:active {
              border: none;
              outline: none;
            }
            .sensemo-logo > a:hover, .sensemo-logo > a:focus, .sensemo-logo > a:visited, .sensemo-logo > a:active, .sensemo-logo > a img {
              border: none;
              outline: none;
              color: none;
            }
       </style>
        <?php
    $pluginlogo = plugins_url('img/SensemoNameLogo60.png', __FILE__);
?>
       <div class="wrap">
        <div class="sensemo-logo"><a href="https://sensemo.com/studio" target="sensemo_studio"><img src="<?php
    echo $pluginlogo;
?>" height="45px" alt="Sensemo Logo" /></a></div>
        
        <h2>Sensemo | Master Settings</h2>
        
        <div class="sensemo-shortcode-example">Shortcode example: [sensemo]</div>
        
        <form method="post" action="options.php">
            <?php
    settings_fields('sensemo-settings-group');
?>
           <?php
    do_settings_sections('sensemo-settings-group');
?>
           
            <table class="form-table">
                
                <tr valign="top">
                <th scope="row">Master Poll ID</th>
                <td><input type="text" name="sensemo_default_campaign" value="<?php
    echo esc_attr(get_option('sensemo_default_campaign'));
?>" /></td>
                </tr>
                
                <tr valign="top">
                <th scope="row">Master API Token</th>
                <td><input type="text" name="sensemo_api_token" value="<?php
    echo esc_attr(get_option('sensemo_api_token'));
?>" /></td>
                </tr>
                
                 <?php
    $anonymize_options = get_option('sensemo_anonymize');
?>
				<?php
    $anonymize_yes = checked('item1', $anonymize_options['radio1'], false);
    $anonymize_no = checked('item2', $anonymize_options['radio1'], false);
    if(empty($anonymize_yes)&&empty($anonymize_no)){
    	$anonymize_yes = 'checked';
    }
?>
                <tr valign="top">
                <th scope="row">Anonymize Sources</th>
                <td>
                <input type="radio" name="sensemo_anonymize[radio1]" value="item1" <?php
    echo $anonymize_yes;
?> />Yes<br />
                  <input type="radio" name="sensemo_anonymize[radio1]" value="item2" <?php
    echo $anonymize_no;
?> />No<br />    
                
                <tr valign="top">
                <th scope="row"><?php
    submit_button();
?></th>
                <td><a href="https://sensemo.com/studio" target="sensemo_studio">Sensemo Studio</a></td>
                
                </tr>

                </td>
                </tr>
                
            </table>
        
        </form>
        </div>
        <?php
}
add_action('admin_init', 'sensemo_settings');
function sensemo_settings() {
    register_setting('sensemo-settings-group', 'sensemo_default_campaign');
    register_setting('sensemo-settings-group', 'sensemo_api_token');
    register_setting('sensemo-settings-group', 'sensemo_anonymize');
}
function generate_uuid() {
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000, mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));
}
class WPSE_Filter_Storage {
    private $values;
    public function __construct($values) {
        $this->values = $values;
    }
    public function __call($callback, $arguments) {
        if (is_callable($callback))
            return call_user_func($callback, $arguments, $this->values);
        throw new InvalidArgumentException(sprintf('File: %1$s<br>Line %2$d<br>Not callable: %3$s', __FILE__, __LINE__, print_r($callback, TRUE)));
    }
}
function sensemo_shortcode_creation($atts, $content = null) {
    ob_start();
    global $current_user;
    get_currentuserinfo();
    $authorid           = get_the_author_id();
    $dec                = esc_attr(get_option('sensemo_default_campaign'));
    $token              = esc_attr(get_option('sensemo_api_token'));
    $postid             = get_the_ID();
    //$admintestindicator = 'test';
    if ($authorid === $current_user->ID) {
        $isthepostauthor = true;
    } else {
        $isthepostauthor = false;
    }
    if (current_user_can('edit_posts') || current_user_can('edit_others_posts') || current_user_can('edit_pages') || current_user_can('edit_others_pages')) {
        $caneditthepost = true;
    } else {
        $canedithepost = true;
    }
    $a        = shortcode_atts(array(
        'id' => '',
        'context' => '',
        'question' => ''
    ), $atts);
    $ec       = sanitize_text_field(esc_attr($a['id']));
    $question = sanitize_text_field(esc_attr($a['question']));
    $source   = '[sensemo_source]';
    $context  = sanitize_text_field(esc_attr($a['context']));
    if (empty($ec)) {
        if (!empty($a['question'])) {
            $childec = $dec . '-wp_' . $_SERVER['SERVER_NAME'] . '_' . "$postid";
        } else {
            $childec = $dec;
        }
    } else {
        $childec = $ec;
    }
    $childec = str_replace('.', '_', $childec);
    
    if (empty($context)) {
        if (strpos($context, '-') !== false) {
            $context = $_SERVER['SERVER_NAME'] . '|' . $postid ;//. '|' . $admintestindicator;
        }
    }
    
    function filter_sensemo_results_function($args, $vars) {
        $content = $args[0];
        $childec = $vars[0];
        global $current_user;
        get_currentuserinfo();
        $authorid = get_the_author_id();
        if ($authorid === $current_user->ID) {
            $isthepostauthor = true;
        } else {
            $isthepostauthor = false;
        }
        if (current_user_can('edit_posts') || current_user_can('edit_others_posts') || current_user_can('edit_pages') || current_user_can('edit_others_pages')) {
            $caneditthepost = true;
        } else {
            $canedithepost = true;
        }
        $anonymize_options = get_option('sensemo_anonymize');
        if ($anonymize_options['radio1'] !== 'item2') {
            $anonymize = true;
        } else {
            $anonymize = false;
        }
        $reporticon = plugins_url('img/SensemoReportIcon60.png', __FILE__);
        if (is_singular() && is_main_query()) {
            if ($isthepostauthor || $caneditthepost) {
                $content = str_replace('[sensemo_source]', '&source=test', $content);
                $content = str_replace('[sensemo_report]', '<span><a href="https://sensemo.com/studio?ecr=' . $childec . '" target="sensemo_' . $childec . '"><img src="' . $reporticon . '" alt="Sensemo Report Icon" title="Sensemo Report ' . $childec . '" style="max-height:50px;vertical-align:middle;"></a></span>', $content);
            } else {
                if ($anonymize || $current_user->ID < 1) {
                    $randid  = "wp-" . generate_uuid();
                    $content = str_replace('[sensemo_source]', '&source=' . $randid, $content);
                } else {
                    $content = str_replace('[sensemo_source]', '&source=' . $current_user->ID, $content);
                }
                $content = str_replace('[sensemo_report]', '', $content);
            }
        }
        return $content;
    }
    add_filter('the_content', array(
        new WPSE_Filter_Storage(array(
            $childec,
            $context
        )),
        'filter_sensemo_results_function'
    ), 6);
    $icon     = plugins_url('img/SensemoNameLogo60.png', __FILE__);
    $url      = 'https://sensemo.com/api?a=createchildcampaign';
    $response = wp_remote_post($url, array(
        'method' => 'POST',
        'timeout' => 45,
        'redirection' => 5,
        'httpversion' => '1.0',
        'blocking' => true,
        'headers' => array(),
        'body' => array(
            'ec' => $childec,
            'to' => $token,
            'qu' => $question
        ),
        'cookies' => array()
    ));
    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        return "Something went wrong: $error_message";
    } else {
        $response_json = json_decode($response['body'], true);
        if ($response_json['status'] == 'OK') {
            $question = $response_json['question'];
            $ec       = $response_json['ec'];
?>
        <span style="padding:3px;margin:0 2px 0 0;" title="Emotion Poll"><a href="https://sensemo.com/emo?ec=<?php
            echo ($ec);
?><?php
            echo ($source);
?><?php
            if (!empty($context))
                echo ('&context=' . $context);
?>" target="_blank">
            <img src="<?php
            echo $icon;
?>" alt="Sensemo Icon" style="max-height:20px;vertical-align:middle;">
            <span><?php
            echo ($question);
?></span></a>
            [sensemo_report]
        </span>
        <?php
        } else {
?>
      <span style="padding:3px;margin:0 2px 0 0;">
            <img src="<?php
            echo $icon;
?>" alt="Sensemo Icon" style="max-height:20px;vertical-align:middle;">
            <span style="color:#FA0032;"><?php
            echo ($response_json['reason']);
?></span>
          </span>
        <?php
        }
    }
    $result = ob_get_contents();
    ob_end_clean();
    return $result;
}
add_shortcode('sensemo', 'sensemo_shortcode_creation', 1);
?> 