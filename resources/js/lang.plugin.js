/**
 * Lang.js as vue plugin
 */
import Lang from './lang'

const plugin = {}
plugin.install = function (Vue, options) {
  /**
   * Global trans method
   */
  Vue.prototype.$trans = key => {
    return Lang.get(key)
  }

  /**
   * v-trans directive
   */
  Vue.directive('trans', {
    bind(el, binding) {
      el.innerText = Lang.get(binding.value) + (binding.modifiers.colon ? ':' : '')
    }
  })
}

export default plugin
