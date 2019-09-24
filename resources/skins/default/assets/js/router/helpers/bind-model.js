/**
 * Binding the model to a route.
 * @source https://markus.oberlehner.net/blog/route-model-binding-with-vue-and-vuex/
 */
export default function bindModel(model) {
    return route => ({
        model: model.find(route.params.id)
    })
}
