<script>
	window.learnosityCollection = window.learnosityCollection || [];
	window.learnosityCollection.push(LearnosityItems.init(<?php echo $signed_request; ?>, { errorListener: function(e) { console.log(e); } }));
</script>