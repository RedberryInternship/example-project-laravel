Nova.booting((Vue, router, store) => {
    router.addRoutes([
        {
            name: 'charger-terminals',
            path: '/charger-terminals',
            component: require('./components/Tool'),
        },
    ])
})
