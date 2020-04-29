<template>
	<div>
        <header>
		    <heading class="mb-6">Level 2 Connector Types</heading>

            <div class="go-back" @click="goBack">< Back</div>
        </header>

        <div class="chargers-form">
            <div class="row">
                <form class="form card" action="#">
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
    header {
        width: 100%;
        display: flex;
        text-align: center;
        align-items: center;
        justify-content: space-between;

        .go-back {
            cursor: pointer;
            color: #4099de;
        }
    }

    .form-group {
        display: flex;

        > div {
            display: flex;
            align-items: center;
        }
    }
</style>
