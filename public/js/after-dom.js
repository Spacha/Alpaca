/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!************************************!*\
  !*** ./app/assets/js/after-dom.js ***!
  \************************************/
function fadeInSlogan() {
  var elem = document.querySelector('.slogan');

  if (!!elem && 'classList' in elem) {
    elem.classList.remove('before-fading');
  }
} ////////////////////////////////////////////////////////////////////////////////
// Run after DOM has laoded


fadeInSlogan();
/******/ })()
;