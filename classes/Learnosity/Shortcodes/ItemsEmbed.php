<?php

namespace Learnosity\Shortcodes;

require_once __DIR__ . '/../../../vendor/learnosity-utils/uuid.php';
require_once __DIR__ . '/../../../vendor/learnosity-utils/RequestHelper.php';
require_once __DIR__ . '/../../../vendor/learnosity-utils/UrlHelper.php';

class ItemsEmbed
{

    private $config;
    private $security;
    private $sessionId;

    private $student_prefix;

    public function __construct($options, $mode)
    {
        $this->student_prefix = get_option('lrn_student_prefix', 'student_');

        $this->security = array(
            'consumer_key' => get_option('lrn_consumer_key'),
            'domain' => $_SERVER['SERVER_NAME'],
            'timestamp' => gmdate('Ymd-Hi')
        );

        $this->sessionId = \UUID::generateUuid();

        //Handling URL parameters
        $lrnactid = \UrlHelper::get_url_parameter('lrnactid','');

        $defaults = array(
            'activityid' => \UUID::generateUuid(),
            'activitytemplateid' => $lrnactid,
            'autorender' => true,
            'name' => 'My Activity',
            'rendersubmit' => false,
            'sessionid' => $this->sessionId,
            'state' => 'initial',
            'studentid' => $this->student_prefix . get_current_user_id(),
            'type' => get_option('lrn_default_type', 'submit_practice')
        );
        $options = $this->parse_options($options);
        $this->config = array_merge($defaults, $options);

        //Force ther rendering type based based on mode called
        // lrn-items:inline or lrn-assess:assess
        $this->config['renderingtype'] = $mode;

    }

    public function render()
    {
        ob_start();
        $this->render_init_js($this->config);

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
            if (count($url_parsed['query']) > 0) {
                // adding learnosity parameter with "&" prefix
                // using html_entity_decode as last & is converted to "&amp;"
                if (substr(html_entity_decode($url_parsed['query']), -1) != '&') {
                    $url_parsed['query'] .= '&lrnsid=' . $this->sessionId;
                } else {
                    $url_parsed['query'] .= 'lrnsid=' . $this->sessionId;
                }
            } else {
                // adding just learnosity parameter as the only parameter
                $url_parsed['query'] = 'lrnsid=' . $this->sessionId;
            }

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
        if ($this->config['activitytemplateid']) {
            $request['activity_template_id'] = $this->config['activitytemplateid'];
        }


        $request_helper = new \RequestHelper(
            'items',
            $this->security,
            get_option('lrn_consumer_secret'),
            $request
        );
        $signed_request = $request_helper->generateRequest();
        if (isset($this->config['activitytemplateid'])) {
            $signed_request = json_decode($signed_request, true);
            $ati = $this->config['activitytemplateid'];
            $signed_request['request']['activity_template_id'] = $ati;
            $signed_request = json_encode($signed_request);
        }
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
        $signed_request = $this->generate_signed_request($this->config);
        include(__DIR__ . '/../../../templates/init-items-js.php');
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
