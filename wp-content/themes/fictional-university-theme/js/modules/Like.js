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
			this.deleteLike(currentLikeBox);
		} else {
			this.createLike(currentLikeBox);
		}
	}

	createLike(currentLikeBox) {
		$.ajax({
			beforeSend: (xhr) => {
				xhr.setRequestHeader('X-WP-Nonce', universityData.nonce); // javascript won't create post due to this
			},
			url: universityData.root_url + '/wp-json/university/v1/manageLike',
			type: 'POST', // type of http request
			data: {'professorId' : currentLikeBox.data('professor')},
			success: (response) => {
				console.log(response)
			},
			error: (response) => {
				console.log(response)
			}
		});
	}

	deleteLike() {
		$.ajax({
			url: universityData.root_url + '/wp-json/university/v1/manageLike',
			type: 'DELETE', // type of http request
			success: (response) => {
				console.log(response)
			},
			error: (response) => {
				console.log(response)
			}
		});
	}
}

export default Like;