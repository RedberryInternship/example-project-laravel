<template>
	<div class="inner-page">
        <header>
		    <heading class="mb-6">
                Fast Connector Types
            </heading>

            <button class="go-back btn btn-default btn-primary" @click="goBack">
                < Back
            </button>
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
                            <label for="start-minutes">Start Minutes</label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <input type="number" id="start-minutes" name="start-minutes" step="1" class="w-full form-control form-input form-input-bordered" v-model="startMinutes">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="w-1/5 px-8 py-6">
                            <label for="end-minutes">End Minutes</label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <input type="number" id="end-minutes" name="end-minutes" step="1" class="w-full form-control form-input form-input-bordered" v-model="endMinutes">
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
                            <th>Start Minutes</th>
                            <th>End Minutes</th>
                            <th>Price</th>
                            <th>Remove</th>
                        </tr>
                    </thead>

                    <tbody>
                        <template v-for="charger in chargers">
                            <template v-for="connectorType in charger.connector_types">
                                <template v-if="connectorType.activeInput">
                                    <tr v-for="fastChargingPrice in connectorType.fast_charging_prices" :class="{ 'removed': fastChargingPrice.removed }" :key="fastChargingPrice.id">
                                        <td>{{ charger.name.en }}</td>
                                        <td>{{ connectorType.name }}</td>
                                        <td class="center">{{ fastChargingPrice.min_kwt }}</td>
                                        <td class="center">{{ fastChargingPrice.max_kwt }}</td>
                                        <td class="center">{{ fastChargingPrice.start_time }}</td>
                                        <td class="center">{{ fastChargingPrice.end_time }}</td>
                                        <td class="center">{{ fastChargingPrice.price }}</td>
                                        <td class="center">
                                            <template v-if=" ! fastChargingPrice.removed">
                                                <button class="btn btn-default btn-danger" @click="removeFastChargingPrice(fastChargingPrice)">
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
                startMinutes: '',
                endMinutes: '',
                price: ''
            };
        },
        methods: {
            save() {
                axios({
	                method: 'post',
	                url: '/nova-vendor/charger-prices/save-fast',
	                data: {
	                    startMinutes: this.startMinutes,
	                    endMinutes: this.endMinutes,
                        price: this.price,
                        chargers: this.chargers
	                }
	            }).then(() => this.goBack());
            },
            goBack() {
                this.$emit('goBack');
            },
            removeFastChargingPrice(fastChargingPrice) {
                axios({
	                method: 'post',
	                url: '/nova-vendor/charger-prices/remove-fast-charging-price',
	                data: {
	                    fastChargingPriceID: fastChargingPrice.id
	                }
	            }).then(() => {
                    fastChargingPrice.removed = true;

                    this.$forceUpdate();
                });
            }
        }
    }
</script>

<style lang="scss">
    //
</style>
