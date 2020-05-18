<template>
	<div class="inner-page">
        <header>
            <button class="go-back btn btn-default btn-primary" @click="goBack">
                < Back
            </button>

		    <heading class="mb-6">
                Level2 Connector Types
            </heading>
        </header>

        <div class="chargers-form card mb-4">
            <div class="row">
                <form class="form" action="#">
                    <div class="form-group">
                        <div class="w-1/5 px-8 py-6">
                            <h4>Add New Price</h4>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="w-1/5 px-8 py-6">
                            <label for="min-kwt">Min Kwt</label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <input type="number" id="min-kwt" name="min-kwt" step=".5" class="w-full form-control form-input form-input-bordered" v-model="minKwt">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="w-1/5 px-8 py-6">
                            <label for="max-price">Max kwt</label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <input type="number" id="max-kwt" name="max-kwt" step=".5" class="w-full form-control form-input form-input-bordered" v-model="maxKwt">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="w-1/5 px-8 py-6">
                            <label for="start-time">Start Time</label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <input type="number" id="start-time" name="start-time" step="1" class="w-full form-control form-input form-input-bordered" v-model="startTime">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="w-1/5 px-8 py-6">
                            <label for="end-time">End Time</label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <input type="number" id="end-time" name="end-time" step="1" class="w-full form-control form-input form-input-bordered" v-model="endTime">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="w-1/5 px-8 py-6">
                            <label for="price">Price</label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <input type="number" id="price" name="price" step="1" class="w-full form-control form-input form-input-bordered" v-model="price">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="w-1/5 px-8 py-6">
                            <input type="button" class="w-full btn btn-default btn-primary" @click="save" value="Submit">
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="charger-prices-listing card">
            <div class="row">
                <table cellpadding="0" cellspacing="0" data-testid="resource-table" class="table w-full">
                    <thead>
                        <tr>
                            <th>Charger</th>
                            <th>Connector Type</th>
                            <th>Min Kwt</th>
                            <th>Max Kwt</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Price</th>
                            <th>Remove</th>
                        </tr>
                    </thead>

                    <tbody>
                        <template v-for="charger in chargers">
                            <template v-for="connectorType in charger.connector_types">
                                <template v-if="connectorType.activeInput">
                                    <tr v-for="chargingPrice in connectorType.charging_prices" :class="{ 'removed': chargingPrice.removed }" :key="chargingPrice.id">
                                        <td>{{ charger.name ? charger.name.en : '-' }}</td>
                                        <td>{{ connectorType.name }}</td>
                                        <td class="center">{{ chargingPrice.min_kwt }}</td>
                                        <td class="center">{{ chargingPrice.max_kwt }}</td>
                                        <td class="center">{{ chargingPrice.start_time }}</td>
                                        <td class="center">{{ chargingPrice.end_time }}</td>
                                        <td class="center">{{ chargingPrice.price }}</td>
                                        <td class="center">
                                            <template v-if=" ! chargingPrice.removed">
                                                <button class="btn btn-default btn-danger" @click="removeChargingPrice(chargingPrice)">
                                                    Remove
                                                </button>
                                            </template>
                                            <template v-else>
                                                Removed
                                            </template>
                                        </td>
                                    </tr>
                                </template>
                            </template>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
	</div>
</template>

<script>
    export default {
    	props: [
			'chargers'
		],
        data() {
            return {
                minKwt: '',
                maxKwt: '',
                startTime: '',
                endTime: '',
                price: ''
            };
        },
        methods: {
            save() {
                axios({
	                method: 'post',
	                url: '/nova-vendor/charger-prices/save-level2',
	                data: {
	                    minKwt: this.minKwt,
	                    maxKwt: this.maxKwt,
	                    startTime: this.startTime,
	                    endTime: this.endTime,
                        price: this.price,
                        chargers: this.chargers
	                }
	            }).then(() => window.location.reload());
            },
            goBack() {
                this.$emit('goBack');
            },
            removeChargingPrice(chargingPrice) {
                axios({
	                method: 'post',
	                url: '/nova-vendor/charger-prices/remove-charging-price',
	                data: {
	                    chargingPriceID: chargingPrice.id
	                }
	            }).then(() => {
                    chargingPrice.removed = true;

                    this.$forceUpdate();
                });
            }
        }
    }
</script>

<style lang="scss">
    table tbody {
        tr.removed {
            background-color: #e53e3e !important;

            &:hover {
                background-color: #e53e3e !important;
            }

            td {
                color: white !important;
                background-color: inherit !important;

                &:hover {
                    background-color: inherit !important;
                }
            }
        }

        td.center {
            text-align: center;
        }
    }
</style>
