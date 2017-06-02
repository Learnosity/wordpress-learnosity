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

Future:
- [ ] Support for reporting


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

```[lrn-items items="Demo5, Demo6, Demo7"]```

You can also pass additional attributes to your [lrn-items] shortcode, like so:

```[lrn-items items="Demo5, Demo6, Demo7" name="My Awesome Activity" sessionid="1234" activityid="activity_123"]```

The 'items' attribute is the only required attribute, the others are defaulted to the following:
- activityid: (randomly generated)
- autorender: true
- name: My Activity
- rendersubmit: false
- sessionid: (randomly generated)
- state: initial
- studentid: student_[wordpress user id].  Eg: student_123.  Note: student_0 is used when not authenticated.
- type: (as configured in settings - default of submit_practice but can be defaulted to local_practice)

#### Basic Assessment Example
Using the `lrn-assess` shortcode lets you include an Assessment style activity.

```[lrn-assess activitytemplateid="DemoActivityWordpress"]```

Note you can use either the activitytemplateid to load from the Author site/Item Bank, or you can specify individual items.


#### Advanced Inline Usage

Setting `autorender="false"` will allow you to place the item elements wherever you please, e.g:

```[lrn-items items="Demo5, Demo6, Demo7" autorender="false"]```

< Some content separating the embed >

```[lrn-item ref="Demo5"]```

< Another separator >

```[lrn-item ref="Demo6"]```

< More separation >

```[lrn-item ref="Demo7"]```

You can also manually embed a submit button this way:

```
[lrn-items items="Demo6" autorender="false" rendersubmit="true"]
[lrn-item ref="Demo6"]
[lrn-submit]
```

The End



