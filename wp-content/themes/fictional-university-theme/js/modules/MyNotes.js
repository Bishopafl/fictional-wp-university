import $ from 'jquery';

class MyNotes {
	// three sections for object oriented javascript. first constructor. second events, third custom methods

	constructor() {
		this.events();
	}

	events() {
		$(".delete-note").on("click", this.deleteNote);
		$(".edit-note").on("click", this.editNote.bind(this));
		$(".update-note").on("click", this.updateNote.bind(this));
	}

	// CRUD Methods will go here

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
			url: universityData.root_url + '/wp-json/wp/v2/note/' + thisNote.data('id'),	// wp url where post json is showing when you use the data attribute in javascript, you don't need to specify data-id
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
			},		// what you want to happen on success usually a function
			error: (response) => {
				console.log('Sorry...');
				console.log(response);
			}		// 
		});	
	}

}

export default MyNotes;