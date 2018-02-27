// Object oriented JavaScript!!!! Lets get it!

import $ from 'jquery';  // includes all you need for jQuery!

class Search {
	// 1. Constructor is where we describe/initiate our object
	constructor() {
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
				this.typingTimer = setTimeout(this.getResults.bind(this), 2000);
			} else {
				this.resultsDiv.html('');
				this.isSpinnerVisible = false;
			}
		}
		this.previousValue = this.searchField.val();
	}

	getResults() {
		$.getJSON('http://localhost/wordpress_playground/wordpress/wp-json/wp/v2/posts?search=' + this.searchField.val(), posts => {
			this.resultsDiv.html(`
				<h2 class="search-overlay__section-title">General Information</h2>
				<ul class="link-list min-list">
					<li>
						<a href="${posts[0].link}">${posts[0].title.rendered}</a>
					</li>
				</ul>
			`); // back tick next to number one allows for template literal html entries in javascipt.  ${} in template literal tells javascript should be evaluated as real javascript code
		});
	}

	openOverlay() {
		this.searchOverlay.addClass("search-overlay--active");
		$("body").addClass("body-no-scroll");
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



}

export default Search;


/*

this keyword allows reference of properties/methods


*/