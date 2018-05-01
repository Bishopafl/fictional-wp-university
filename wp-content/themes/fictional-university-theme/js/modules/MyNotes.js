import $ from 'jquery';

class MyNotes {
	// three sections for object oriented javascript. first constructor. second events, third custom methods

	constructor() {
		this.events();
	}

	events() {
		$(".delete-note").on("click", this.deleteNote);
	}

	// Methods will go here
	deleteNote() {
		$.ajax({
			beforeSend: (xhr) => {
				xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
			},
			url: universityData.root_url + '/wp-json/wp/v2/note/91',	// wp url where post json is showing
			type: 'DELETE',	// what type of request you want to send, GET POST DELETE
			success: (response) => {
				console.log('Congrats');
				console.log(response);
			},		// what you want to happen on success usually a function
			error: (response) => {
				console.log('Sorry...');
				console.log(response);
			}		// 
		});
	
	}

}

export default MyNotes;