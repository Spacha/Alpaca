////////////////////////////////////////////////////////////////////////////////
// 								GLOBAL
////////////////////////////////////////////////////////////////////////////////

/**
 * Toggle hamburger menu.
 * @todo add event listeners for the menu links to close the menu!
 * 
 * @param  {[MouseEvent]} evt
 * @return {void}
 */
window.toggleHamMenu = evt => {
	evt.preventDefault();
	var menu = document.querySelector('.ham-menu');

	if (menu.classList.contains('ham-expanded')) {
		menu.classList.remove('ham-expanded');
	} else {
		menu.classList.add('ham-expanded');
	}
}

/**
 * Highlight a checkbox's parent element when the value is true.
 * 
 * @param  {[MouseEvent]} evt
 * @return {void}
 */
window.highlightWarning = (e) => {
	if (e.srcElement.checked) {
		e.srcElement.parentNode.classList.add("form-warning")
	} else {
		e.srcElement.parentNode.classList.remove("form-warning")
	}
}