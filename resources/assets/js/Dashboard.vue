/** Primary component and template for the entire application.
  * Includes the main navigation and Vue router outlet.
*/
<template>
  <v-app standalone>
    <nav-bar></nav-bar>
    <main>
      <!-- Primary router outlet -->
      <router-view></router-view>
    </main>
    <v-footer class="default pa-3 mt-5">
      <v-spacer></v-spacer>
      <div>© {{ new Date().getFullYear() }}</div>
    </v-footer>    
  </v-app>
</template>

<script>
  import NavBar from './_ui/Nav-bar';

  export default {

    components: {
      'nav-bar': NavBar
    },

    created () {
      // For debug
      if(this.$store.state.debug) console.log("Dashboard Created");
      // Set authenticated user
      this.$store.commit('setAuthUser');
    }

  }
</script>
