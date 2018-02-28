// Object oriented JavaScript!!!! Lets get it!

import $ from 'jquery';  // includes all you need for jQuery!

class Search {
	// 1. Constructor is where we describe/initiate our object
	constructor() {
		this.addSearchHTML(); // order matters for this method, because elements won't exist in the dom
		this.resultsDiv		= $("#search-overlay__results");
		this.openButton		= $(".js-search-trigger");
		this.closeButton	= $(".search-overlay__close");
		this.searchOverlay 	= $(".search-overlay");
		this.searchField	= $("#search-term");
		this.events();
		this.isOverlayOpen	= false;
		this.isSpinnerVisible = false;
		this.previousValue;
		this.typingTimer;

	}
	// 2. Events  - connects dots between object and actions it can perform
	events() {
		this.openButton.on("click", this.openOverlay.bind(this));
		this.closeButton.on("click", this.closeOverlay.bind(this));
		$(document).on("keydown", this.keyPressDispatcher.bind(this));
		this.searchField.on("keyup", this.typingLogic.bind(this));
	}

	// 3. methods (function, action...)
	typingLogic() {
		if (this.searchField.val() != this.previousValue) {
			clearTimeout(this.typingTimer);

			if (this.searchField.val()) {
				if (!this.isSpinnerVisible) {
					this.resultsDiv.html('<div class="spinner-loader"></div>');
					this.isSpinnerVisible = true;
				}
				this.typingTimer = setTimeout(this.getResults.bind(this), 750);
			} else {
				this.resultsDiv.html('');
				this.isSpinnerVisible = false;
			}
		}
		this.previousValue = this.searchField.val();
	}

	getResults() {
		// within when - can include json requests at same time and waits until they all complete until then method runs | then - annonymous function for what happens after everything is run
		// automatically maps a with one and b with two
		// $.when(a, b).then((one, two) => ); 
		$.when(
			$.getJSON(universityData.root_url + '/wp-json/wp/v2/posts?search=' + this.searchField.val()), 
			$.getJSON(universityData.root_url + '/wp-json/wp/v2/pages?search=' + this.searchField.val())
			).then((posts, pages) => {
			// back tick next to number one allows for template literal html entries in javascipt.  ${} in template literal tells javascript should be evaluated as real javascript code
			var combinedResults = posts[0].concat(pages[0]);
			this.resultsDiv.html(`
				<h2 class="search-overlay__section-title">General Information</h2>
				${combinedResults.length ? '<ul class="link-list min-list">' : '<p>No general information matches that search</p>'}
					${combinedResults.map(item => `<li><a href="${item.link}">${item.title.rendered}</a></li>`).join('')} 
				
				${combinedResults.length ? '</ul>' : ''}
			`); 
			this.isSpinnerVisible = false;
		}, () => {
			this.resultsDiv.html('<p>Unexpected error; please try again.</p>');
		}); 
	}

	openOverlay() {
		this.searchOverlay.addClass("search-overlay--active");
		$("body").addClass("body-no-scroll");
		this.searchField.val('');
		setTimeout(() => this.searchField.focus(), 301); // inline functions are written like this
		console.log("open method just ran");
		this.isOverlayOpen = true;
	}

	closeOverlay() {
		this.searchOverlay.removeClass("search-overlay--active");
		$("body").removeClass("body-no-scroll");
		console.log("close method just ran");
		this.isOverlayOpen = false;
	}

	keyPressDispatcher(e) {
		if (e.keyCode == 83 && !this.isOverlayOpen && !$("input, textarea").is(':focus')) {
			this.openOverlay();
		}
		if (e.keyCode == 27 && this.isOverlayOpen) {
			this.closeOverlay();
		}
	}

	addSearchHTML() {
		$("body").append(`
			<div class="search-overlay">
			    <div class="search-overlay__top">
			    	<div class="container">
			        	<i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
			        	<input type="text" class="search-term" placeholder="What are you looking for?" id="search-term">
			        	<i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
			      	</div>
			    </div>
			    <div class="container">
			      <div id="search-overlay__results"></div>
			    </div>
			</div>
		`);
	}



}

export default Search;


/*

this keyword allows reference of properties/methods


*/