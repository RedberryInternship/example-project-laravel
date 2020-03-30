<template>
    <div>
    	<heading class="mb-6">Filter Chargers</heading>

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
                    Submit
                </button>
            </div>
        </div>

        <div class="chargers" v-if="chargers.length">
            <heading class="mb-6">Chargers</heading>

            <div class="chargers-header">
                <div class="mb-6">
                    <input type="checkbox" @change="toggleAllConnectorTypes">
                    <span class="toggle-checkboxes-text">Toggle Checkboxes</span>
                </div>

                <div class="mb-6">
                    <button class="btn btn-default btn-primary" @click="continueWithSelectedChargerTypes">
                        Continue with selected charger Types
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
	            activeGroup: 0
	        };
	    },
	    mounted() {
	        this.getGroups();
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
	            this.changeConnectorTypesActiveStatus(this.chargers);
	        },
	        changeConnectorTypesActiveStatus(chargers, val = undefined) {
	            chargers.forEach(charger => {
	                charger.connector_types.forEach(connectorType => {
	                    connectorType.activeInput = val != undefined ? val : ! connectorType.activeInput;
	                });
	            });
	        },
	        continueWithSelectedChargerTypes() {
                let chosenChargers = this.getChosenChargers();

	            this.$emit('goToActionsPage', chosenChargers);
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
        justify-content: space-between;

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
