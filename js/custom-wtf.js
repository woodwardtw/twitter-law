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
	 var url = WPURLS.siteurl;
	if (document.getElementsByClassName('wtf-tweet-holder')){
		var currentTweets = document.getElementsByClassName('wtf-tweet-holder');
	}
	for (var i = currentTweets.length - 1; i >= 0; i--) {	
		 var postID = currentTweets[i].dataset.link;
		jQuery(currentTweets[i]).click(function () {
	    	window.location = url + '?tweet=' + postID;
		});
	}
}

twitterLinks();