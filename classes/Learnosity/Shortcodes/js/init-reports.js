signed_requests.forEach(signed_request => {
    window.learnosityCollection = window.learnosityCollection || [];
    window.learnosityCollection.push(LearnosityReports.init(JSON.parse(signed_request), {
        errorListener: function (e) {
            console.log(e);
        }
    }));
});
