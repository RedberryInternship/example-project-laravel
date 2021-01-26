<template>
  <form>
    <input 
      type="text" 
      class="find-charger-input" 
      placeholder="Finde charger by code..." 
      v-model="chargerCode" 
      @keydown="filterSearchKeys" 
    />

    <input 
      type="submit" 
      value="Find" 
      class="find-charger-button"
      @click="filterChargersOnClick"
    />
  </form>
</template>

<script>
import validation from '../helpers/validation';

const { shouldPrevent, isAlreadyFilled } = validation;

export default {
  data() {
    return {
      chargerCode: ''
    }
  },
  props:[ 'chargers' ],
  methods: {
    filterSearchKeys( e ) {
      if( shouldPrevent( e ) || isAlreadyFilled( this.chargerCode, e))
      {
        e.preventDefault();
      }
    },
    filterChargersOnClick( e ) {
      e.preventDefault();
      this.filterChargers();
    },
    filterChargers() {
      this.$emit( 'filter-chargers', this.chargerCode );
    }
  },
}

</script>

<style scoped>
  .find-charger-input{
    width: 30%;
    padding: .5em;
    border: 1px solid #7e8ea1;
    border-radius: 3px;
  }

  .find-charger-button{
    border: 1px solid grey;
    padding: .5em 2em;
    border-radius: 3px;
    background-color: #7e8ea1;
    color: white;
  }
</style>