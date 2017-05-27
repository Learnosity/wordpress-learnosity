# Learnosity plugin for Wordpress

=== Work in progress === 

This Plugin is currently undergoing some rework and cleanup to update it to allow the latest functionality and become fully supported.

TODO:
- Add support for Items API version (Config level)
- Remove support for Questions API version
- Remove Course_id setting in admin
- Add support for authenticated users
- Add support for Activity Template Id works
- Add support for submit practice
- Support for session id

Future:
- Support for reporting


=== Learnosity API ===
Tags: learnosity, api, assessment, education
Requires at least: 3.5.1
License: Copyright 2014, Learnosity

The Learnosity API plugin allows you to embed Learnosity items on your site using WordPress shortcodes.

== Installation ==
After activating the plugin, visit Settings > Learnosity API Plugin and add your Learnosity consumer key and consumer secret (course ID is optional). Once you\'ve saved these settings, you\'ll be able to start embedding Learnosity items on your site by using shortcodes.

You can embed a set of items fairly simply by adding the following shortcode, with your own items:

[lrn-items items=\"Demo5, Demo6, Demo7\"]

You can also pass additional attributes to your [lrn-items] shortcode, like so:

[lrn-items items=\"Demo5, Demo6, Demo7\" name=\"My Awesome Activity\" sessionid=\"1234\" activityid=\"activity_123\"]

The \'items\' attribute is the only required attribute, the others are defaulted to the following:
- activityid: (randomly generated)
- autorender: true
- apiversion: v2
- courseid: (randomly generated if you haven\'t set it on the settings page)
- name: My Activity
- rendersubmit: false
- renderingtype: inline
- sessionid: (randomly generated)
- state: initial
- studentid: (randomly generated)
- type: local_practice

Setting \'autorender\' to \'false\' will allow you to place the item elements wherever you please, e.g:

[lrn-items items=\"Demo5, Demo6, Demo7\" autorender=\"false\"]
< Some content separating the embed >
[lrn-item ref=\"Demo5\"]
< Another separator >
[lrn-item ref=\"Demo6\"]
< More separation >
[lrn-item ref=\"Demo7\"]

You can also manually embed a submit button this way:

[lrn-items items=\"Demo6\" autorender=\"false\" rendersubmit=\"true\"]
[lrn-item ref=\"Demo6\"]
[lrn-submit]