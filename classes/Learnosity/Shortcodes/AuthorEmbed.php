<?php

namespace Learnosity\Shortcodes;

require_once __DIR__ . '/../../../vendor/learnosity-utils/RequestHelper.php';

class AuthorEmbed
{
    private $config;
    private $security;

    public function __construct($options, $content)
    {
        $this->security = array(
            'consumer_key' => get_option('lrn_consumer_key'),
            'domain' => $_SERVER['SERVER_NAME'],
            'timestamp' => gmdate('Ymd-Hi')
        );

        $defaults = array(
            'mode' => 'item_list',
            'user' => [
                'id' => 'www-site',
                'firstname' => 'WWW Firstname',
                'lastname' => 'WWW Lastname',
                'email' => 'www@learnosity.com'
            ]
        );

        //supporting $content to be passed inside short code
        //[lrn-author]<pre>{JSON}</pre>[/lrn-author]
        //using preformatted text <pre> to avoid replacing " for “
        if ($content != '') {
            $content = json_decode(sanitize_text_field($content), TRUE);
            if (is_null($content)) {
                $this->render_error("Invalid JSON for Learnosity Author API provided.");
            } else {
                $this->config = $content;
            }
        } else {
            $this->config = array_merge($defaults, $options);
        }
    }

    public function render()
    {
        ob_start();
        $this->render_init_js();
        $this->render_author();
        return ob_get_clean();
    }

    private function generate_signed_request()
    {
        $request = $this->config;
        $request_helper = new \RequestHelper(
            'author',
            $this->security,
            get_option('lrn_consumer_secret'),
            $request
        );
        $signed_request = $request_helper->generateRequest();
        return $signed_request;
    }

    private function render_init_js()
    {
        $signed_request = $this->generate_signed_request($this->config);
        include(__DIR__ . '/../../../templates/init-author-js.php');
    }

    private function render_author()
    {
        include(__DIR__ . '/../../../templates/author.php');
    }

    private function render_error($msg)
    {
        include(__DIR__ . '/../../../templates/author_error.php');
    }
}