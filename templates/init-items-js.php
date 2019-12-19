<script>
    // WP can add "defer" flag to <script> inside "wp_enqueue_script" function
    // it might lead to the error that "LearnosityItems" below in undefined
    // to protect against it, we will use "load" global event to secure that all JS assets are loaded before calling any JS code
    window.addEventListener("load", function () {
        window.learnosityCollection = window.learnosityCollection || [];
        window.learnosityCollection.push(LearnosityItems.init(<?php echo $signed_request; ?>, {
            readyListener: function () {
                console.log("Items API is ready!");
                <?php echo $this->readyListenerJSCode; ?>
            },
            errorListener: function (e) {
                console.log(e);
            }
        }));
    });
</script>