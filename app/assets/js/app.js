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
window.highlightWarning = evt => {
    if (evt.srcElement.checked) {
        evt.srcElement.parentNode.classList.add("form-warning");
    } else {
        evt.srcElement.parentNode.classList.remove("form-warning");
    }
}

/**
 * Capture the tab key to an input rather than blurring.
 *
 * @see https://stackoverflow.com/a/32523641
 * @param  {[KeyboardEvent]} evt
 * @return {void}
 */
window.captureTab = evt => {
    // handle tab press
    if(evt.keyCode === 9){
        evt.preventDefault();
        let el = evt.srcElement;
        let v = el.value, s = el.selectionStart, e = el.selectionEnd;
        el.value = v.substring(0, s)+'\t'+v.substring(e);
        el.selectionStart = el.selectionEnd = s + 1;
        return false;
    }
}