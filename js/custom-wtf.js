function categoriesChecked () {
	if (document.getElementById('cat_list')){
		var catHolder = document.getElementById('cat_list');
	}
		if (document.getElementById('cat_list_tweet')) {
			checkedValues = jQuery('input:checkbox:checked').map(function() {
		    return this.value;
		}).get();
			console.log(checkedValues);
			catHolder.value = checkedValues;
		}
}




function twitterLinks (){
	var url = WPURLS.siteurl + '/?p=';
	jQuery(".wtf-tweet-holder").click(function() {
		window.location = url + jQuery(this).data('link');
		console.log('click = ' + jQuery(this).data('link'));
		});
	}

twitterLinks();
