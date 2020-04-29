<template>
	<div>
        <header>
		    <heading class="mb-6">Fast Connector Types</heading>

            <div class="go-back" @click="goBack">< Back</div>
        </header>

        <div class="chargers-form">
            <div class="row">
                <form class="form card" action="#">
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
