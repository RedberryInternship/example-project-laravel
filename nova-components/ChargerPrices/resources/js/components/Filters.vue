<template>
    <div>
    	<heading class="mb-6">Filter Chargers By Groups</heading>

        <div class="charger-filters">
            <div class="charger-filter charger-filter-input groups">
                <select class="form-control form-select" v-model="activeGroup">
                    <option value="0" selected>All Chargers</option>
                    <option v-for="(group, index) in groups" :value="group.id" :key="index">
                        {{ group.name }}
                    </option>
                </select>
            </div>

            <div class="charger-filter submit">
                <button class="btn btn-default btn-primary" @click="filterChargers">
                    Filter
                </button>
            </div>
        </div>

        <div class="chargers" v-if="chargers.length">
            <heading class="mb-6">Charger Types</heading>

            <div class="chargers-header">
                <div class="w-1/3 text-left">
                    <input type="checkbox" id="toggle-all-connector-types" @change="toggleAllConnectorTypes" :checked="this.activeCheckboxes['all'] ? true : false">
                    <label for="toggle-all-connector-types" class="toggle-checkboxes-text">Toggle Checkboxes</label>
                </div>

                <div class="w-1/3 text-left">
                    <input type="checkbox" id="toggle-lvl2-connector-types" @change="toggleLvl2ConnectorTypes" :checked="this.activeCheckboxes['type 2'] ? true : false">
                    <label for="toggle-lvl2-connector-types" class="toggle-checkboxes-text">Toggle Level2 Connectors</label>
                </div>

                <div class="w-1/3 text-left">
                    <input type="checkbox" id="toggle-fast-connector-types" @change="toggleFastConnectorTypes" :checked="this.activeCheckboxes['combo 2'] ? true : false">
                    <label for="toggle-fast-connector-types" class="toggle-checkboxes-text">Toggle Fast Connectors</label>
                </div>

                <!-- <div class="w-1/4 text-left">
                    <button class="btn btn-default btn-primary" @click="clearConnectorTypes">
                        Clear
                    </button>
                </div> -->
            </div>

            <heading class="mb-6">Actions</heading>

            <div class="chargers-header">
                <div class="w-1/3 text-left">
                    <button class="btn btn-default btn-primary" @click="goToPage('level2')">
                        Level2 Connector Prices
                    </button>
                </div>

                <div class="w-1/3 text-left">
                    <button class="btn btn-default btn-primary" @click="goToPage('fast')">
                        Fast Connector Prices
                    </button>
                </div>

                <div class="w-1/3 text-left">
                    <button class="btn btn-default btn-primary" @click="goToPage('min-max')">
                        Min/Max Prices
                    </button>
                </div>
            </div>

            <div class="charger-items">
                <div class="charger-item" v-for="(charger, index) in chargers" :key="index">
                    <h3>{{ charger.name.en }}</h3>

                    <div class="connector-types">
                        <div class="connector-type" v-for="(connectorType, connectorTypeIndex) in charger.connector_types" :key="connectorTypeIndex">
                            <input
                                type="checkbox"
                                v-model="connectorType.activeInput"
                                :id="'connector-type-' + charger.id + '-' + connectorTypeIndex">
                            <label :for="'connector-type-' + charger.id + '-' + connectorTypeIndex">
                                {{ connectorType.name }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
	export default {
	    data() {
	        return {
	            groups: {},
	            chargers: {},
                activeGroup: 0,
                activeCheckboxes: {
                    'all': false,
                    'type 2': false,
                    'combo 2': false,
                    'chademo': false,
                }
	        };
	    },
	    mounted() {
            this.getGroups();
            this.filterChargers();
	    },
	    methods: {
	        getGroups() {
	            axios
	                .get('/nova-vendor/charger-prices/charger-groups')
	                .then(response => {
	                    this.groups = response.data.groups;
	                });
	        },
	        filterChargers() {
	            axios({
	                method: 'get',
	                url: '/nova-vendor/charger-prices/chargers',
	                params: {
	                    group: this.activeGroup
	                }
	            }).then(response => {
	                this.changeConnectorTypesActiveStatus(response.data.chargers, false);

	                this.chargers = response.data.chargers;
	            });
	        },
	        toggleAllConnectorTypes() {
                this.activeCheckboxes['all']     = ! this.activeCheckboxes.all;
                this.activeCheckboxes['type 2']  = this.activeCheckboxes.all;
                this.activeCheckboxes['combo 2'] = this.activeCheckboxes.all;
                this.activeCheckboxes['chademo'] = this.activeCheckboxes.all;

	            this.changeConnectorTypesActiveStatus(this.chargers, this.activeCheckboxes['all']);
            },
            toggleLvl2ConnectorTypes() {
                this.activeCheckboxes['all']     = false;
                this.activeCheckboxes['combo 2'] = false;
                this.activeCheckboxes['chademo'] = false;
                this.activeCheckboxes['type 2']  = ! this.activeCheckboxes['type 2'];

	            this.changeConnectorTypesActiveStatus(this.chargers, undefined, ['type 2']);
            },
            toggleFastConnectorTypes() {
                this.activeCheckboxes['all']     = false;
                this.activeCheckboxes['type 2']  = false;
                this.activeCheckboxes['combo 2'] = ! this.activeCheckboxes['combo 2'];
                this.activeCheckboxes['chademo'] = ! this.activeCheckboxes['chademo'];

	            this.changeConnectorTypesActiveStatus(this.chargers, undefined, ['combo 2', 'chademo']);
            },
            clearConnectorTypes() {
                this.activeCheckboxes['all']     = false;
                this.activeCheckboxes['combo 2'] = false;
                this.activeCheckboxes['chademo'] = false;
                this.activeCheckboxes['type 2']  = false;

	            this.changeConnectorTypesActiveStatus(this.chargers, false);
	        },
	        changeConnectorTypesActiveStatus(chargers, val = undefined, connectorTypes = []) {
	            chargers.forEach(charger => {
	                charger.connector_types.forEach(connectorType => {
                        // if (connectorTypes.length && ! connectorTypes.includes(connectorType.name.toLowerCase()))
                        //     return;

	                    connectorType.activeInput = val != undefined ? val : this.activeCheckboxes[connectorType.name.toLowerCase()];
	                });
	            });
	        },
	        goToPage(page) {
                let chosenChargers = this.getChosenChargers();

	            this.$emit('goTo', { 'page': page, 'chargers': chosenChargers });
            },
            getChosenChargers() {
                return this.chargers.filter(charger => {
                    let hasChosenConnector = false;

                    charger.connector_types.forEach(connector => {
                        if ( ! hasChosenConnector) {
                            hasChosenConnector = connector.activeInput;
                        }
                    });

                    return hasChosenConnector;
                });
            }
	    }
	}
</script>

<style lang="scss">
    .charger-filters {
        width: 100%;
        display: flex;
        margin-bottom: 3rem;
        justify-content: space-between;

        .charger-filter {
            select,
            button {
                width: 100%;
            }

            &.charger-filter-input {
                min-width: 30%;
            }
        }
    }

    .chargers-header {
        width: 100%;
        display: flex;
        text-align: center;
        align-items: center;
        margin-bottom: 3rem;
        justify-content: flex-start;

        .toggle-checkboxes-text {
            top: 1px;
            margin-left: .5rem;
            position: relative;
        }
    }

    .charger-items {
        margin-bottom: 2rem;

        .charger-item {
            background: white;
            margin-bottom: 1rem;
            padding: 1rem 1rem 0;

            .connector-types {
                width: 100%;
                display: flex;
                flex-wrap: wrap;
                flex-direction: row;

                .connector-type {
                    flex: 45%;
                    margin-right: 10%;
                    padding: 1rem 1rem 1rem 0;

                    input {
                        margin-right: 1rem;
                    }

                    label {
                        cursor: pointer;
                    }

                    &:nth-child(2n) {
                        margin-right: 0;
                    }
                }
            }
        }
    }
</style>
