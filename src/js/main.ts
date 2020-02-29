import hljs from 'highlight.js';
import "bootstrap/js/src/collapse"
import './matchHeight'

//@ts-ignore
const { baseUrl } = frontendJS_parcel

for (const el of document.getElementsByTagName('pre')) {
  if (el.hasAttribute('data-language-mode')) {
    (el.firstChild as HTMLElement).setAttribute('class', el.getAttribute('data-language-mode'))
    hljs.highlightBlock(el.firstChild);
  }
}
