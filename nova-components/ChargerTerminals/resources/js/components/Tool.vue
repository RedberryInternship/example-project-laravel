<template>
    <div>
        <Header/>
        <Search :chargers="chargers" @filter-chargers="filterChargers" />
        <Chargers :chargers="filteredChargers" :terminals="terminals" />
    </div>
</template>

<script>

import Header from './Header';
import Search from './Search';
import Chargers from './Chargers';

export default {
    components:{
        Header,
        Search,
        Chargers,
    },
    methods:{
    getChargers() {
        fetch( '/nova-vendor/charger-terminals/chargers' )
            .then( response => response.json())
            .then( data => {
                this.chargers = data.map(el => {
                    const location = JSON.parse( el.location ).ka;
                    return {
                        ...el,
                        location: location && location.length > 39 ? location.substring(0, 39) + '...' : location,
                    }
                });
                this.filteredChargers = this.chargers;
            })
            .catch( err => console.log( err ));
    },
    filterChargers( chargerCode ) {
        this.filteredChargers = this.chargers.filter( el => el.code.includes( chargerCode ) );
    },
    getTerminals() {
        fetch( '/nova-vendor/charger-terminals/terminals' )
            .then( response => response.json())
            .then( data => {
                this.terminals = data;
            })
            .catch( err => console.log );
    }
  },
    mounted() {
        this.getChargers();
        this.getTerminals();
    },
    data() {
        return {
            chargers: [],
            filteredChargers: [],
            terminals: [],
        }
    }
}
</script>