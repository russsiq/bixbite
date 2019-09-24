export default function auth({ next, router }) {
  if (!localStorage.getItem('jwt')) {
    return router.push({
        name: 'login'
    })
  }

  return next()
}

/*router.beforeEach((to, from, next) => {
  if(to.matched.some(record => record.meta.requiresAuth)) {
    if (store.getters.isLoggedIn) {
      next()
      return
    }
    next('/login') 
  } else {
    next() 
  }
})*/