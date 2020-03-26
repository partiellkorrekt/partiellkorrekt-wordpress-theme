import hljs from 'highlight.js'
import 'bootstrap/js/src/collapse'
import './matchHeight'
import clipboard from 'clipboard-polyfill'

//@ts-ignore
const { baseUrl } = frontendJS_parcel

for (const el of document.getElementsByTagName('pre')) {
  if (el.hasAttribute('data-language-mode')) {
    ;(el.firstChild as HTMLElement).setAttribute(
      'class',
      el.getAttribute('data-language-mode')
    )
    hljs.highlightBlock(el.firstChild)
  }
}

jQuery($ => {
  $('.code-block').each(function() {
    const block = $(this)
    block.find('.js-copy').click(() => {
      const code = block.find('code').get(0).textContent
      if (code) {
        clipboard.writeText(code)
      }
    })
  })
})
