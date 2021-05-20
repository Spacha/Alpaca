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
window.toggleHamMenu = function(evt)
{
	evt.preventDefault();
	var menu = document.querySelector('.ham-menu');

	if (menu.classList.contains('ham-expanded')) {
		menu.classList.remove('ham-expanded');
	} else {
		menu.classList.add('ham-expanded');
	}
}
