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
		alert('you have clicked delete');
	}

}

export default MyNotes;