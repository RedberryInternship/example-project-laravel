<template>
  <div>
    <div class="charger-wrapper" :class="{ 'full' : selected, 'blured' : ! charger.terminal_title }">
      <div class="charger">
        <div class="charger-info">
          <p class="id"> <span>ID:</span> {{ charger.id }} </p>
          <p class="code"> <span>Code:</span> {{ charger.code }} </p>
          <p class="location"> <span>Location:</span> {{ charger.location }} </p>
          <p> <span>Terminal:</span> {{ !! charger.terminal_title ? charger.terminal_title : 'X' }} </p>
        </div>
      
        <div class="edit-btn-wrapper">
          <button class="charger-edit-btn" @click="toggleChargerTab">{{ selected ? "Close" : "Edit" }}</button>
        </div>
      </div>
      <div class="edit">
        <form>
          <div class="left">
            <select name="terminals" @change="updateTerminalId">
              <option disabled selected>Choose Terminal</option>
              <option :value="terminal.id" v-for="terminal in terminals" :key="terminal.id">{{ terminal.title }}</option>
            </select>
            <input 
              type="text" 
              placeholder="Report info..." 
              class="report" 
              :value="updateChargerTerminal.report"
              @change="updateReport" 
            />
          </div>
          <div class="right">
            <input 
              type="submit" 
              value="Save" 
              class="save" 
              :class="{ 'active' : shouldSave }" 
              @click="save" 
            />
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: [ "charger", "terminals" ],
  data() {
    return {
      selected: false,
      shouldSave: false,
      updateChargerTerminal: {
        charger_id: this.charger.id,
        terminal_id: this.charger.terminal_id,
        report: this.charger.terminal_report,
      },
    };
  },
  methods: {
    toggleChargerTab() {
      this.selected = ! this.selected;
    },
    activateSaveButton() {
      if( ! this.shouldSave )
      {
        this.shouldSave = true;
      }
    },
    updateTerminalId(e) {
      this.updateChargerTerminal.terminal_id = e.target.value;
      this.activateSaveButton();
    },
    updateReport(e) {
      this.updateChargerTerminal.report = e.target.value;
      this.activateSaveButton();
    },
    save(e) {
      e.preventDefault();
      console.log([ 'body', this.updateChargerTerminal ]);
      fetch( '/nova-vendor/charger-terminals/save', {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        },
        body: JSON.stringify( this.updateChargerTerminal ),
      })
      .then( response => response.json())
      .then( data => {
        if( data.success )
        {
          this.setTerminalName();
          this.toggleChargerTab();
        }
      })
      .catch( err => console.log );
    },
    setTerminalName() {
      const terminalId = this.updateChargerTerminal.terminal_id;
      const terminalName = this.getTerminalName( terminalId );
      this.charger.terminal_title = terminalName;
    },
    getTerminalName( id ) {
      return this.terminals.find( el => el.id == id ).title;
    },
  },
};
</script>

<style scoped>
  .charger-wrapper{
    background-color: #3f4857;
    border-radius: 10px;
    color: white;
    margin-top: .8em;
    height: 3em;
    overflow: hidden;
    transition-duration: .4s;
  }
  .full{
    height: 9em;
  }

  .blured {
    background-color: #607D8B;
  }

 .charger {
    display: flex;
    justify-content: space-between;
    height: 3em;
 }

 .charger-info {
    display: flex;
    align-items: center;
 }

 .edit-btn-wrapper{
  display: flex;
  align-items: center;
 }

 .charger-edit-btn {
    margin-right: 2em;
    padding: .5em 1em;
    background-color: white;
    border-radius: 3px;
 }

 .charger-info p {
  margin-left: 2em;
 }
 
 .charger-info p.id {
  width: 3em;
 }
 
 .charger-info p.code {
  width: 6em;
 }
 
 .charger-info p.location {
   width: 30em;
 }

 .charger-info p span {
  font-weight: bold;
 }

 .edit form {
  margin-left: 2em;
  margin-top: 2em;
  display: flex;
  justify-content: space-between;
 }

 .edit form select {
  width: 10em;
  border-radius: 5px;
  background-color: #7e8ea1;
  color: white;
  padding: .15em .4em;
  margin-right: 1em;
 }

 .edit form input.report {
  width: 33em;
  border-radius: 5px;
  padding: .3em .5em;
  outline: none;
 }

 .edit form input.save {
  margin-right: 2em;
  padding: .5em 1em;
  color: white;
  border: 1px solid transparent;
  transition-duration: .3s;
  border-radius: 3px;
  background-color: darkgray;
 }
 .edit form input.active {
  background-color: #7e8ea1;
 }

 .edit form input.active:hover {
  border: 1px solid white;
  cursor: pointer;
 }
</style>