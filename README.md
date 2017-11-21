# Learnosity plugin for Wordpress

## Work in progress

This Plugin is currently undergoing some rework and cleanup to update it to allow the latest functionality and become fully supported.

TODO:
- [x] Add support for Items API version (Config level)
- [x] Remove Course_id setting in admin
- [x] Remove support for Questions API version
- [x] Add support for authenticated users
- [x] Add support for submit practice - N/a - already worked
- [x] Add support for changing default from local_practice to submit_practice - and make that default
- [x] Add support for Activity Template Id
- [ ] Add validation check and error handling when invalid shortcodes settings - eg no items, or no activityid.
- [ ] Only load primer when there are shortcodes on the page
- [ ] Support for session id
- [ ] Test multiple activities on the one page
- [ ] Are multiple submit buttons supported?
- [X] Support for basic reporting

Future:
- [ ] Support for more reporting



## Learnosity API
Tags: learnosity, api, assessment, education
Requires at least: 3.5.1
License: Copyright (c) 2017 Learnosity under the MIT License

The Learnosity API plugin allows you to embed Learnosity items on your site using WordPress shortcodes.

### Installation
After activating the plugin, visit Settings > Learnosity API Plugin and add your Learnosity consumer key and consumer secret (course ID is optional). Once you\'ve saved these settings, you\'ll be able to start embedding Learnosity items on your site by using shortcodes.

### Usage

#### Basic Inline Example
You can embed a set of items fairly simply by adding the following shortcode, with your own items:

```
[lrn-items items="Demo5, Demo6, Demo7"]
```

You can also pass additional attributes to your [lrn-items] shortcode, like so:

```
[lrn-items items="Demo5, Demo6, Demo7" name="My Awesome Activity" sessionid="1234" activityid="activity_123"]
```

The 'items' attribute is the only required attribute, the others are defaulted to the following:
- activityid: (randomly generated) if not supplied.   It is recommended to set this for easier reporting.
- autorender: true
- name: My Activity
- rendersubmit: false
- sessionid: (randomly generated)
- state: initial
- studentid: student_[wordpress user id].  Eg: student_123.  Note: student_0 is used when not authenticated.
- type: (as configured in settings - default of submit_practice but can be defaulted to local_practice)
- onsubmit_redirect_url: on submit, redirect to this url (sessionid generated will be added to the end as lrnsid url parameter)

#### Basic Assessment Example
Using the `lrn-assess` shortcode lets you include an Assessment style activity.

```
[lrn-assess activitytemplateid="DemoActivityWordpress"]
```

Note you can use either the activitytemplateid to load from the Author site/Item Bank, or you can specify individual items.

The activitytemplateid could be also passed as url parameter (lrnactid) for WordPress landing page:
```
http://wordpress.vg.learnosity.com/2017/11/21/assessment-url/?lrnactid=ACTIVITY_3_DEMO
```


#### Advanced Inline Usage

Setting `autorender="false"` will allow you to place the item elements wherever you please, e.g:

```
[lrn-items items="Demo5, Demo6, Demo7" autorender="false"]
```
< Some content separating the embed >
```
[lrn-item ref="Demo5"]
```
< Another separator >
```
[lrn-item ref="Demo6"]
```
< More separation >
```
[lrn-item ref="Demo7"]
```

You can also manually embed a submit button this way:

```
[lrn-items items="Demo6" autorender="false" rendersubmit="true"]
[lrn-item ref="Demo6"]
[lrn-submit]
```


### Reports API Usage
A subset of reports are currently available for us in the wordpress plugin.


### Sessions List report

Parameters
- type: sessions-list
- limit: number of sessions to show - default 10, max 100
- users: list of user_ids seperated by commas.
- activities: list of activity_ids seperated by commas.
- display_user: true | false.  detault is true
- display_activity: true | false.  detault is true


```
[lrn-report type="sessions-list"]
```

This shows the list of session for the current user with the default of the last 5.

```
[lrn-report type="sessions-list" limit="10"]
```

Show a report for multiple users and display the activities
```
[lrn-report type="sessions-list" limit="10" users="1,2,3" display_activity="true"]
```

Show a report for a single activity id.  Note: activity id's are generated automatically if not supplied when first doing the activity with the [lrn-assess] or [lrn-items] shortcode.
```
[lrn-report type="sessions-list" limit="10" activities="c98ee0e5-b50d-40b8-9d30-aa21c2a0d712"]
```

### Session Detail By Item report

Show a full session detail report:

Parameters
- type: session-detail-by-item
- session_id: (required) session_id to load
- user_id: uses current user_id if not specified
- show_correct_answers: true | false.  detault is true

```
[lrn-report type="session-detail-by-item" session_id="17b9bb6e-16f8-4808-aebd-42a246a1158e"]
```

The session_id could be also passed as url parameter (lrnsid) for WordPress landing page:
```
http://wordpress.vg.learnosity.com/2017/11/07/report/?lrnsid=0c2c3a66-5719-4f5f-b8c9-1492aa3c7cfa
```


## Release History
### Version 1.2.1 - 21th Nov 2017
- Add support for lrnactid as url parameter of WordPress landing page for lrn-assess

### Version 1.2.0 - 14th Nov 2017
- Add support for lrnsid as url parameter of WordPress landing page for Session Detail By Item report
- Add support for onsubmit_redirect_url parameter for lrn-assess shortcode 

### Version 1.1.1 - 6th Oct 2017
- Minor cleanups to settings page

### Version 1.1.0 - 11th Sep 2017
- Add support for Reports API with the sessions-list and session-detail-by-item reports

### Version 1.0.0 - 2nd Jun 2017
- Initial release of the wordpress-learnosity plugin which makes it easy to add Learnosity into a Wordpress Site

### Pre v1 - 2014
- The base version of this was created in 2014



