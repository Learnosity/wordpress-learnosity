<?php

namespace Learnosity\Shortcodes;

require_once __DIR__ . '/../../../vendor/learnosity-utils/uuid.php';
require_once __DIR__ . '/../../../vendor/learnosity-utils/RequestHelper.php';
require_once __DIR__ . '/../../../vendor/learnosity-utils/UrlHelper.php';


class ReportEmbed
{

    // public static $script_has_been_added = false;

    private $config;
    private $security;

    private $report_id;

    private $student_prefix;

    private $contentProvided = false;

    private $supported_reports = array(
        'sessions-list',
        'session-detail-by-item');

    public function __construct($options, $content)
    {
        $this->report_id = \UUID::generateUuid();
        $this->student_prefix = get_option('lrn_student_prefix', 'student_');

        $this->security = array(
            'consumer_key' => get_option('lrn_consumer_key'),
            'domain' => $_SERVER['SERVER_NAME'],
            'timestamp' => gmdate('Ymd-Hi')
        );

        //Handling URL parameters
        $lrnsid = \UrlHelper::get_url_parameter('lrnsid', '');
        $lrnuid = \UrlHelper::get_url_parameter('lrnuid', '');

        // get_current_user_id() - (int) The current user's ID, or 0 if no user is logged in
        $lrnRepUserId = get_current_user_id();
        if ($lrnRepUserId == 0 && $lrnuid != '') {
            // get only id without prefix as prefix will be added later
            $lrnRepUserId = str_replace($this->student_prefix, "", $lrnuid);
        }

        $defaults = array(
            'id' => $this->report_id,
            'type' => '',

            //settings for sessions-list
            'limit' => 10,

            'display_user' => 'true',
            'display_activity' => 'true',

            'users' => $lrnRepUserId,
            'activities' => '',

            //settings for session-detail-by-item
            'user_id' => $lrnRepUserId,
            'session_id' => $lrnsid,
            'show_correct_answers' => 'true',
        );

        //supporting JSON to be passed inside short code: [lrn-report] <pre>{...}]}</pre>[/lrn-report]
        //using preformatted text <pre> to avoid replacing " for “
        if ($options == '' && $content != '') {
            // set up a flag for compatibility
            $this->contentProvided = true;
            //clean out <pre>, </pre> stuff
            $content = str_replace(["<pre>", "</pre>"], ["", ""], $content);
            $content = json_decode(sanitize_text_field($content), TRUE);

            if (is_null($content)) {
                $this->render_error("Invalid JSON for Learnosity Reports API provided.");
            } else {
                $content["id"] = $this->report_id;
                $this->config = $content;
            }
        } else {
            $this->config = array_merge($defaults, $options);
        }
    }

    public function render()
    {
        ob_start();

        //Check this is a supported report
        if (!$this->contentProvided && !in_array($this->config['type'], $this->supported_reports)) {
            $this->render_error("Unsupported report type: {$this->config['type']}");
        } else {
                $this->render_init_js($this->config);
                $this->render_report($this->report_id);
        }
        return ob_get_clean();
    }

    private function get_user_name($user_id)
    {
        $user_info = get_userdata($user_id);
        $username = $user_info->user_login;
        $first_name = $user_info->first_name;
        $last_name = $user_info->last_name;
        return "$first_name $last_name ($username)";
    }

    // Takes a comma seperated list of users and returns an array for reports
    private function get_users_array($users_list)
    {
        $user_array = array();
        foreach (explode(',', $users_list) as $key => $value) {
            array_push($user_array,
                array(
                    'id' => $this->student_prefix . $value,
                    'name' => $this->get_user_name($value)
                )
            );
        }
        return $user_array;
    }

    // Takes a comma seperated list of users and returns an array for reports
    private function get_activities_array($activities_list)
    {
        $act_array = array();

        foreach (explode(',', $activities_list) as $key => $value) {
            array_push($act_array,
                array(
                    'id' => $value,
                )
            );
        }
        return $act_array;
    }


    private function generate_signed_request()
    {
        //Setup report Array
        $report = array(
            'id' => $this->config['id'],
            'type' => $this->config['type']);

        //Handle different type of reports
        switch ($this->config['type']) {
            case "sessions-list":
                $report['limit'] = (int)$this->config['limit'];
                $report['display_user'] = $this->parse_boolean($this->config['display_user']);
                $report['display_activity'] = $this->parse_boolean($this->config['display_activity']);

                //support string (default via $attrs) and array (via $content) as input parameters
                if (gettype($this->config['users']) == "string" && $this->config['users'] != "") {
                    $report['users'] = $this->get_users_array($this->config['users']);
                } elseif (gettype($this->config['users']) == "array") {
                    $report['users'] = $this->config['users'];
                }

                //support string (default via $attrs) and array (via $content) as input parameters
                if (gettype($this->config['activities']) == "string" && $this->config['activities'] != "") {
                    $report['activities'] = $this->get_activities_array($this->config['activities']);
                } elseif (gettype($this->config['activities']) == "array") {
                    $report['activities'] = $this->config['activities'];
                }

                break;
            case "session-detail-by-item":
                $report['session_id'] = $this->config['session_id'];
                $report['user_id'] = $this->student_prefix . $this->config['user_id'];
                break;
            default:
                //grab all the parameters from $content JSON
                if ($this->contentProvided) {
                    $report = $this->config;
                }
                break;
        }

        //Build correct array for single report
        $request = array('reports' => array($report));

        $request_helper = new \RequestHelper(
            'reports',
            $this->security,
            get_option('lrn_consumer_secret'),
            $request
        );
        $signed_request = $request_helper->generateRequest();
        return $signed_request;
    }

    private function parse_boolean($str_val)
    {
        return $str_val === 'true' ||
            ($str_val !== 'false' && intval($str_val) > 0);
    }


    private function render_init_js()
    {
        $signed_request = $this->generate_signed_request($this->config);
        include(__DIR__ . '/../../../templates/init-reports-js.php');
    }

    private function render_report($report_id)
    {
        include(__DIR__ . '/../../../templates/report.php');
    }

    private function render_error($msg)
    {
        include(__DIR__ . '/../../../templates/report_error.php');
    }

}
