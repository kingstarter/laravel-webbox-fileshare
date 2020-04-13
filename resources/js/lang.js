/**
 * Load language files in js
 */
import Lang from "lang.js"

const messages = {}
const locales = [ 'en', 'de' ]
locales.forEach(locale => {
  Object.assign(messages, { [locale]: require('../lang/'+locale+'/messages.php') })
})

console.log(messages)

const Language = new Lang({
  messages: messages,
  locale: window.config && window.config.defaultLocale ? window.config.defaultLocale : 'en',
  fallback: 'en'
})

export default Language
