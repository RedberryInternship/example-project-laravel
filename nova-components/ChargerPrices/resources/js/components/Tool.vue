<template>
    <div>
        <filters class="page" :class="{ active: activePage == 'filters' }" @goTo="goToPage"></filters>
        <min-max class="page" :class="{ active: activePage == 'min-max' }" @goBack="displayPage('filters')" :chargers="chargers"></min-max>
        <level2 class="page" :class="{ active: activePage == 'level2' }" @goBack="displayPage('filters')" :chargers="chargers"></level2>
        <fast class="page" :class="{ active: activePage == 'fast' }" @goBack="displayPage('filters')" :chargers="chargers"></fast>
    </div>
</template>

<script>
    import Filters from './Filters';
    import MinMax from './MinMax';
    import Level2 from './Level2';
    import Fast from './Fast';

    export default {
        components: {
            Filters,
            MinMax,
            Level2,
            Fast
        },
        data() {
            return {
                chargers: {},
                activePage: 'filters'
            };
        },
        methods: {
            goToPage(data) {
                this.chargers = data.chargers;

                this.displayPage(data.page);
            },
            displayPage(page) {
                this.activePage = page;
            }
        }
    }
</script>

<style lang="scss">
    .page {
        display: none;

        &.active {
            display: block;
        }
    }
</style>
