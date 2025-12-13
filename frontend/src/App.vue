<script setup>
import HelloWorld from './components/HelloWorld.vue'
import Sidebar from './components/Sidebar.vue'
</script>

<template>
  <div class="app">
    <Sidebar />

    <main class="appContent">
      <HelloWorld msg="Vite + Vue" />
    </main>
  </div>
</template>

<style scoped>
.app {
  display: flex;
  min-height: 100vh;
}

.appContent {
  padding: 24px;
}
</style>
