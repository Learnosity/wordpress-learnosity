<?php

namespace Learnosity\Shortcodes;

require_once __DIR__ . '/../../../vendor/learnosity-utils/uuid.php';
require_once __DIR__ . '/../../../vendor/learnosity-utils/RequestHelper.php';
require_once __DIR__ . '/../../../vendor/learnosity-utils/UrlHelper.php';

class ItemsEmbed
{
    private $config;
    private $security;
    private $signed_requests;

    private $student_prefix;

    public function __construct($options, $mode, $content, &$signed_requests, &$ready_listeners)
    {
        $this->student_prefix = get_option('lrn_student_prefix', 'student_');

        $this->security = array(
            'consumer_key' => get_option('lrn_consumer_key'),
            'domain' => $_SERVER['SERVER_NAME'],
            'timestamp' => gmdate('Ymd-Hi')
        );

        //Handling URL parameters
        $lrnactid = \UrlHelper::get_url_parameter('lrnactid', '');
        $lrnactname = \UrlHelper::get_url_parameter('lrnactname', 'My Activity');

        $defaults = array(
            'activityid' => \UUID::generateUuid(),
            'activitytemplateid' => $lrnactid,
            'autorender' => true,
            'name' => $lrnactname,
            'rendersubmit' => false,
            'sessionid' => \UUID::generateUuid(),
            'state' => 'initial',
            'studentid' => $this->student_prefix . get_current_user_id(),
            'type' => get_option('lrn_default_type', 'submit_practice')
        );
        $options = $this->parse_options($options);

        //supporting $content to be passed inside short code
        //[lrn-assess]<pre>JavaScript code</pre>[/lrn-assess]
        $this->ready_listeners =& $ready_listeners;
        $this->ready_listeners[] = $content != '' ? sanitize_text_field($content) : '';

        $this->config = array_merge($defaults, $options);
        //Force their rendering type based based on mode called
        // lrn-items:inline or lrn-assess:assess
        $this->config['renderingtype'] = $mode;

        $this->signed_requests =& $signed_requests;
    }

    public function render()
    {
        ob_start();

        $this->render_init_js();

        if ($this->config['renderingtype'] == 'inline') {
            //In Inline mode
            if ($this->config['autorender']) {
                $this->render_items(
                    $this->items_attr_to_array($this->config['items']),
                    $this->config['rendersubmit']
                );
            }
        } else {
            //We are in Assess mode.
            if ($this->config['autorender']) {
                $this->render_assess(
                    $this->items_attr_to_array($this->config['items']),
                    $this->config['rendersubmit']
                );
            }
        }
        return ob_get_clean();
    }

    private function generate_signed_request()
    {
        $request = array(
            'user_id' => $this->config['studentid'],
            'rendering_type' => $this->config['renderingtype'],
            'name' => $this->config['name'],
            'state' => $this->config['state'],
            'activity_id' => $this->config['activityid'],
            'session_id' => $this->config['sessionid'],
            'type' => $this->config['type'],
            'config' => array(
                'renderSubmitButton' => $this->config['rendersubmit'],
            )
        );

        // do not overwrite the value set up for activity_template_id (no else clause)
        // config.configuration.onsubmit_redirect_url
        if (isset($this->config['onsubmit_redirect_url']) AND !empty($this->config['onsubmit_redirect_url'])) {
            $onsubmit_redirect_url = $this->config['onsubmit_redirect_url'];
            // parsing URL properly (to respect anchor if exists)
            $url_parsed = parse_url($onsubmit_redirect_url);
            // if there are parameters in query part (all coming after ? in request)
            if ($url_parsed['query'] != '') {
                // adding learnosity parameter with "&" prefix
                // using html_entity_decode as last & is converted to "&amp;"
                if (substr(html_entity_decode($url_parsed['query']), -1) != '&') {
                    $url_parsed['query'] .= '&lrnsid=' . $this->config['sessionid'];
                } else {
                    $url_parsed['query'] .= 'lrnsid=' . $this->config['sessionid'];
                }
            } else {
                // adding just learnosity parameter as the only parameter
                $url_parsed['query'] = 'lrnsid=' . $this->config['sessionid'];
            }
            // adding student_id for detailed report for non-logged users
            $url_parsed['query'] .= '&lrnuid=' . $this->config['studentid'];

            // building new URL with new 'lrnsid' parameter added
            // TODO
            // http://php.net/manual/fa/function.http-build-url.php
            // right now no support for user/password/port/anchor
            $request['config']['configuration']['onsubmit_redirect_url'] = html_entity_decode(
                $url_parsed["scheme"] . '://' .
                $url_parsed["host"] .
                $url_parsed["path"] . '?' .
                $url_parsed["query"]);
        }

        // If items defined then add them to the request
        if ($this->config['items']) {
            $request['items'] = $this->items_attr_to_array($this->config['items']);
        }

        // If activitytemplateid then add it to the request
        if (isset($this->config['activitytemplateid']) AND !empty($this->config['activitytemplateid'])) {
            $request['activity_template_id'] = $this->config['activitytemplateid'];
        }

        $request_helper = new \RequestHelper(
            'items',
            $this->security,
            get_option('lrn_consumer_secret'),
            $request
        );
        $signed_request = $request_helper->generateRequest();
        return $signed_request;
    }

    private function items_attr_to_array($items_string)
    {
        $items_string = preg_replace('/\s+/', '', $items_string);
        return explode(',', $items_string);
    }

    private function parse_boolean($str_val)
    {
        return $str_val === 'true' ||
            ($str_val !== 'false' && intval($str_val) > 0);
    }

    private function parse_options($options)
    {
        $booleanOptions = ['autorender', 'rendersubmit'];
        foreach ($booleanOptions as $i => $option) {
            if (isset($options[$option])) {
                $str_val = $options[$option];
                $options[$option] = $this->parse_boolean($str_val);
            }
        }
        return $options;
    }

    private function render_init_js()
    {
        $this->signed_requests[] = $this->generate_signed_request($this->config);
        wp_enqueue_script(
            'init-items',
            plugin_dir_url(__FILE__) . 'js/init-items.js',
            array('learnosity-items'),
            null,
            true
        );
        wp_localize_script('init-items', 'signed_requests', $this->signed_requests);
        wp_localize_script('init-items', 'ready_listeners', $this->ready_listeners);
    }

    private function render_items($references, $should_render_submit)
    {
        include(__DIR__ . '/../../../templates/items.php');
    }

    private function render_assess()
    {
        include(__DIR__ . '/../../../templates/assess.php');
    }

}
