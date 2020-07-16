<template>
  <div class="chargers">
    <Charger 
      :charger="charger" 
      v-for="charger in chargers" 
      :key="charger.id" 
      :terminals="terminals" 
    />
  </div>
</template>

<script>
import Charger from './Charger';

export default {
  components:{
    Charger,
  },
  props:[ "chargers", "terminals" ]
}
</script>

<style scoped>
 .chargers{
   margin-top: 1em;
 }
</style>