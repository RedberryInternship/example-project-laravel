<template>
	<div class="inner-page">
        <header>
		    <heading class="mb-6">
                Min Max Prices
            </heading>

            <button class="go-back btn btn-default btn-primary" @click="goBack">
                < Back
            </button>
        </header>

        <div class="chargers-form">
            <div class="row">
                <form class="form card" action="#">
                    <div class="form-group">
                        <div class="w-1/5 px-8 py-6">
                            <label for="min-price">Min Price</label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <input type="number" id="min-price" name="min-price" step=".5" class="w-full form-control form-input form-input-bordered" v-model="minPrice">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="w-1/5 px-8 py-6">
                            <label for="max-price">Max Price</label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <input type="number" id="max-price" name="max-price" step=".5" class="w-full form-control form-input form-input-bordered" v-model="maxPrice">
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
	</div>
</template>

<script>
    export default {
    	props: [
			'chargers'
		],
        data() {
            return {
                minPrice: '',
                maxPrice: ''
            };
        },
        methods: {
            save() {
                axios({
	                method: 'post',
	                url: '/nova-vendor/charger-prices/save-min-max',
	                data: {
	                    minPrice: this.minPrice,
	                    maxPrice: this.maxPrice,
	                    chargers: this.chargers
	                }
	            }).then(response => {
	                this.goBack();
	            });
            },
            goBack() {
                this.$emit('goBack');
            }
        }
    }
</script>

<style lang="scss">
    //
</style>
