jQuery("#facebook-group-id-qm").click(function() {
	jQuery(".answer2").slideUp('fast');
	jQuery(".answer3").slideUp('fast');
	jQuery(".answer1").slideDown('slow');
});			

jQuery("#facebook-access-token-qm").click(function() {
	jQuery(".answer1").slideUp('fast');
	jQuery(".answer3").slideUp('fast');
	jQuery(".answer2").slideDown('slow');
});			

jQuery("#number-of-posts-qm").click(function() {
	jQuery(".answer1").slideUp('fast');
	jQuery(".answer2").slideUp('fast');
	jQuery(".answer3").slideDown('slow');
});			

jQuery(".im-done").click(function() {
	jQuery(".answer1").slideUp('fast');
	jQuery(".answer2").slideUp('fast');
	jQuery(".answer3").slideUp('fast');
});