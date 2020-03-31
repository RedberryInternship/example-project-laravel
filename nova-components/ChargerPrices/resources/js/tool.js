Nova.booting((Vue, router, store) => {
    router.addRoutes([
        {
            name: 'charger-prices',
            path: '/charger-prices',
            component: require('./components/Tool'),
        },
    ])
})
