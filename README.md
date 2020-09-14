# Learnosity plugin for WordPress

## Learnosity API
Tags: learnosity, api, assessment, education
Requires at least: 3.5.1
License: Copyright (c) 2017 Learnosity under the MIT License

The Learnosity plugin allows you to embed Learnosity items on your site using WordPress shortcodes.

## Current Work in progress

This Plugin is currently undergoing some rework and cleanup to update it to allow the latest functionality and become fully supported.

TODO:
- [ ] Add validation check and error handling when invalid shortcodes settings - eg no items, or no activityid
- [ ] Only load primer when there are shortcodes on the page
- [ ] Support for session id
- [ ] Test multiple activities on the one page
- [ ] Are multiple submit buttons supported?
- [ ] Support for more reporting


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
- studentid: student_[WordPress user id].  Eg: student_123.  Note: student_0 is used when not authenticated.
- type: (as configured in settings - default of submit_practice but can be defaulted to local_practice)
- onsubmit_redirect_url: on submit, redirect to this url (sessionid and studentid generated will be added at the end automatically as lrnsid and lrnusid url parameters)

#### Basic Assessment Example
Using the `lrn-assess` shortcode lets you include an Assessment style activity.

```
[lrn-assess activitytemplateid="DemoActivityWordpress"]
```

Note you can use either the activitytemplateid to load from the Author site/Item Bank, or you can specify individual items.

Supported url parameters for WordPress page:
- lrnactid (activitytemplateid)
- lrnactname (name) 

```
http://wordpress.learnosity.com/2017/11/21/assessment-url/?lrnactid=DemoActivityWordpress&lrnactname=DemoActivityName
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

For both assess and inline rendering types, it is supported to pass readyListener JS code as content of 
Items API plugin shortcode. If multiple instances of Items API are on page (multiple shortcodes) please use 
correct index for "learnosityCollection". Example below refers to the first (index 0) instance: 

```
[lrn-assess activitytemplateid="TexttoSpeech_Testing_Activity"]
<pre>
var assessApp = window.learnosityCollection[0].assessApp();
assessApp.on("test:start", function() {
    myFunction();
});
</pre>
[/lrn-assess]
```


### Reports API Usage
A subset of reports are currently available in Learnosity plugin.

Valid JSON for Reports API init options could be passed as content of Reports API plugin shortcode. 

```
[lrn-report]
<pre>
{
	"id": "item-scores-by-tag-by-user-report",
	"type": "item-scores-by-tag-by-user",
	"items_tags_live_dataset_reference": "content-hierarchy-items-dataset-00001",
	"session_items_live_dataset_reference": "content-hierarchy-sessions-dataset-00001",
	"users": [{
		"id": "user_20180417a_00001",
		"name": "Student Name 1"
	}, {
		"id": "user_20180417a_00002",
		"name": "Student Name 2"
	}, {
		"id": "user_20180417a_00003",
		"name": "Student Name 3"
	}],
	"row_tag_type": "ch_proficiency_strand",
	"column_tag_types": ["ch_topic", "ch_subtopic", "ch_curriculum_code"],
	"item_tags": [{
		"type": "ch_title",
		"name": "content_hierarchy_001"
	}]
}
</pre>
[/lrn-report]
```


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

Supported url parameters for WordPress page:
- lrnusid (single value for users). Only works if the current WordPress user is not logged in.
```
http://wordpress.learnosity.com/2017/11/07/report/?lrnsid=0c2c3a66-5719-4f5f-b8c9-1492aa3c7cfa&lrnusid=student_123
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

Supported url parameters for WordPress page:
- lrnsid (session_id)
- lrnusid (user_id). Only works if the current WordPress user is not logged in.
```
http://wordpress.learnosity.com/2017/11/07/report/?lrnsid=0c2c3a66-5719-4f5f-b8c9-1492aa3c7cfa&lrnusid=student_123
```

### Author API Usage
Valid JSON for Author API init options could be passed as content of lrn-author shortcode. 

```
[lrn-author]
<pre>
{
	"mode": "item_list",
	"user": {
		"id": "www-site",
		"firstname": "WWW Firstname",
		"lastname": "WWW Lastname",
		"email": "www@learnosity.com"
	}
}
</pre>
[/lrn-author]
```

### RTL (Right-to-Left) Mode (Arabic and Hebrew Language Support) Usage
Provide rtl=true within associated shortcode to enable RTL.

Example to enable RTL for Author API shortcode:
```
[lrn-author rtl=true]
<pre>
{
	"mode": "item_list",
	"config": {
		"label_bundle": {
			"searchStatus": "حالة",
			"searchTags": "علامات",
			"searchTitle": "العنوان",
			"searchTitlePlaceholder": "البحث بالعنوان",
			"searchTitleReferenceContent": "عنوان ‫/‬ مرحع ‫/‬ محتوي",
			"searchTitleReferenceDescription": "عنوان / مرجع / وصف"
		}
	},
	"user": {
		"id": "www-site",
		"firstname": "WWW Firstname",
		"lastname": "WWW Lastname",
		"email": "www@learnosity.com"
	}
}
</pre>
[/lrn-author]
```

RTL mode is supported for:
- [Items API (inline, assess)](https://help.learnosity.com/hc/en-us/articles/360002588377-Configuring-Items-API-to-Initialize-in-RTL-Right-to-Left-Mode-Arabic-and-Hebrew-Language-Support-)
- [Author API](https://help.learnosity.com/hc/en-us/articles/360000858898-Configuring-Author-API-to-Initialize-in-RTL-Right-to-Left-Mode-Arabic-and-Hebrew-Language-Support-)

Please refer to the linked articles above (hyper-links) to read full instructions on RTL mode for particular API.

## Release History
### Version 1.9.1 - 14th Sep 2020
- [BUGFIX] Correct version on plugin settings page and documentation on how to use RTL has been added

### Version 1.9.0 - 11th Sep 2020
- [FEATURE] Support rtl flag (so set text direction to right to left) in lrn-items, lrn-author and lrn-assess shortcode

### Version 1.8.0 - 27th Dec 2019
- [BUGFIX] Signatures don't match error for lrn-author shortcode
- [FEATURE] Support Author API valid JSON passed as a content in lrn-author shortcode

### Version 1.7.0 - 23rd Dec 2019
- [FEATURE] Support readyListener JS code for Items API if passed as content of plugin shortcode

### Version 1.6.0 - 20th Apr 2018
- [BUGFIX] Signature mismatch issue for lrn-items fixed
- [FEATURE] Support valid JSON for Reports API init options if passed as content of plugin shortcode

### Version 1.5.0 - 29th Dec 2017
- Add support for lrnuid url parameter for lrn-assess and lrn-report

### Version 1.4.0 - 18th Dec 2017
- Add support for lrnactname as url parameter of WordPress page for lrn-assess

### Version 1.3.0 - 21st Nov 2017
- Add support for lrnactid as url parameter of WordPress page for lrn-assess

### Version 1.2.0 - 14th Nov 2017
- Add support for lrnsid as url parameter of WordPress page for Session Detail By Item report
- Add support for onsubmit_redirect_url parameter for lrn-assess shortcode 

### Version 1.1.1 - 6th Oct 2017
- Minor cleanups to settings page

### Version 1.1.0 - 11th Sep 2017
- Add support for Reports API with the sessions-list and session-detail-by-item reports

### Version 1.0.0 - 2nd Jun 2017
- Initial release of the wordpress-learnosity plugin which makes it easy to add Learnosity into a Wordpress Site

### Pre v1 - 2014
- The base version of this was created in 2014
