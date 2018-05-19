import $ from 'jquery';

class Like {
	constructor() {
		this.events();
	}

	// event handlers
	events() {
		$(".like-box").on("click", this.ourClickDispatcher.bind(this));
	}

	// methods that we create
	ourClickDispatcher(e) {
		var currentLikeBox = $(e.target).closest(".like-box"); // find closest ancestor of parent element

		if (currentLikeBox.data('exists') == 'yes') {
			this.deleteLike();
		} else {
			this.createLike();
		}
	}

	createLike() {
		alert("create test message");
	}

	deleteLike() {
		alert("delete test message");
	}
}

export default Like;