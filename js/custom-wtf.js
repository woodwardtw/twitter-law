function categoriesChecked () {
	var catHolder = document.getElementById('cat_list');
		if (document.getElementById('cat_list_tweet')) {
			checkedValues = jQuery('input:checkbox:checked').map(function() {
		    return this.value;
		}).get();
			console.log(checkedValues);
			catHolder.value = checkedValues;
		}
}

