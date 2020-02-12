<template>
    <div>
        <filters class="page" :class="{ active: activePage == 'filters' }" @goToActionsPage="displayActionsPage"></filters>
        <actions class="page" :class="{ active: activePage == 'actions' }" :chargers="chargers"></actions>
    </div>
</template>

<script>
    import Filters from './Filters';
    import Actions from './Actions';

    export default {
        components: {
            Filters,
            Actions
        },
        data() {
            return {
                chargers: {},
                activePage: 'filters'
            };
        },
        methods: {
            displayActionsPage(chargers) {
                this.chargers = chargers;

                this.displayPage('actions');
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
