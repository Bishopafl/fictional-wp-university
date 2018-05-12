import $ from 'jquery';

class MyNotes {
	// three sections for object oriented javascript. first constructor. second events, third custom methods
	// creating CRUD information with Javascript to save into the WP REST api

	constructor() {
		this.events();
	}

	events() {
		$("#my-notes").on("click", ".delete-note", this.deleteNote);
		$("#my-notes").on("click", ".edit-note", this.editNote.bind(this));
		$("#my-notes").on("click", ".update-note", this.updateNote.bind(this));
		$(".submit-note").on("click", this.createNote.bind(this));
	}

	// CRUD Methods will go here

	createNote(e) {
		
		var ourNewPost = {
			// very specific property names
			'title': $(".new-note-title").val(),
			'content': $(".new-note-body").val(),
			'status': 'publish' // makes notes automatically published
		}

		$.ajax({
			beforeSend: (xhr) => {
				xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
			},
			url: universityData.root_url + '/wp-json/wp/v2/note/',	// wp url where post json is showing when you use the data attribute in javascript, you don't need to specify data-id when creating post
			type: 'POST',	// what type of request you want to send, GET POST DELETE
			data: ourNewPost,
			success: (response) => {
				$(".new-note-title, .new-note-body").val(''); // empties the title and body sections
				$(`
					<li data-id="${response.id}">
						<input readonly class="note-title-field" value ="${response.title.raw}" type="text">
						<span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i>Edit</span>
						<span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i>Delete</span>
						<textarea readonly class="note-body-field">${response.content.raw}</textarea>
						<span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true"></i>Save</span>
					</li>
				 `).prependTo("#my-notes").hide().slideDown(); // adds li element to my-notes id element // backticks for javascript template literal
				console.log('Congrats');
				console.log(response);
			},		// what you want to happen on success usually a function
			error: (response) => {
				if (response.responseText == "You have reached your note limit.") {
					$(".note-limit-message").addClass("active");
				}
				console.log('Sorry...');
				console.log(response);
			}		// 
		});	
	}


	updateNote(e) {
		var thisNote = $(e.target).parents("li");
		var ourUpdatedPost = {
			// very specific property names
			'title': thisNote.find(".note-title-field").val(),
			'content': thisNote.find(".note-body-field").val()
		}

		$.ajax({
			beforeSend: (xhr) => {
				xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
			},
			url: universityData.root_url + '/wp-json/wp/v2/note/' + thisNote.data('id'),	// wp url where post json is showing when you use the data attribute in javascript, thisNote.data('id') grabs the wp id for individual posts
			type: 'POST',	// what type of request you want to send, GET POST DELETE
			data: ourUpdatedPost,
			success: (response) => {
				this.makeNoteReadOnly(thisNote);
				console.log('Congrats');
				console.log(response);
			},		// what you want to happen on success usually a function
			error: (response) => {
				console.log('Sorry...');
				console.log(response);
			}		// 
		});	
	}

	editNote(e) {
		var thisNote = $(e.target).parents("li");

		if (thisNote.data("state") == "editable") {
			this.makeNoteReadOnly(thisNote);
		} else {
			this.makeNoteEditable(thisNote);
		}
	}

	makeNoteEditable(thisNote) {
		thisNote.find(".edit-note").html('<i class="fa fa-times" aria-hidden="true"></i>Cancel');
		// makes text fields readonly and makes note fields flash that you can edit them
		thisNote.find(".note-title-field, .note-body-field").removeAttr("readonly").addClass("note-active-field");
		// adds class to blue save button makes it visible
		thisNote.find(".update-note").addClass("update-note--visible");
		thisNote.data("state", "editable");
	}

	makeNoteReadOnly(thisNote) {
		thisNote.find(".edit-note").html('<i class="fa fa-pencil" aria-hidden="true"></i>Edit');
		// makes text fields readonly and makes note fields flash that you can edit them
		thisNote.find(".note-title-field, .note-body-field").attr("readonly", "readonly").removeClass("note-active-field");
		// adds class to blue save button makes it invisible
		thisNote.find(".update-note").removeClass("update-note--visible");
		thisNote.data("state", "cancel");
	}


	deleteNote(e) {
		var thisNote = $(e.target).parents("li");
		$.ajax({
			beforeSend: (xhr) => {
				xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
			},
			url: universityData.root_url + '/wp-json/wp/v2/note/' + thisNote.data('id'),	// wp url where post json is showing when you use the data attribute in javascript, you don't need to specify data-id
			type: 'DELETE',	// what type of request you want to send, GET POST DELETE
			success: (response) => {
				thisNote.slideUp();	// removes element with a slide up 
				console.log('Congrats');
				console.log(response);
				if (response.userNoteCount < 5) {
					$(".note-limit-message").removeClass(".active");
				}
			},		// what you want to happen on success usually a function
			error: (response) => {
				console.log('Sorry...');
				console.log(response);
			}		// 
		});	
	}

}

export default MyNotes;