function fadeInSlogan()
{
	var elem = document.querySelector('.slogan');

	if (!!elem && 'classList' in elem) {
		elem.classList.remove('before-fading');
	}
}

////////////////////////////////////////////////////////////////////////////////

// Run after DOM has laoded
fadeInSlogan();