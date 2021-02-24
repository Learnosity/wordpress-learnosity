signed_requests.forEach((signed_request, index) => {
    window.learnosityCollection = window.learnosityCollection || [];
    window.learnosityCollection.push(LearnosityItems.init(JSON.parse(signed_request), {
        readyListener: new Function(ready_listeners[index]),
        errorListener: function (e) {
            console.log(e);
        }
    }));
});
